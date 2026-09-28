<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jemaah;
use App\Models\Package;
use App\Models\Registration;
use Illuminate\Http\Request;

class DocumentVisaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->toString();
        $packageId = $request->integer('package_id');
        $pic = $request->string('pic')->trim()->toString();

        $registrations = Registration::query()
            ->with(['jemaah', 'package', 'items'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('jemaah', function ($jemaahQuery) use ($search) {
                    $jemaahQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('passport_number', 'like', "%{$search}%");
                });
            })
            ->when($packageId, fn ($query) => $query->where('package_id', $packageId))
            ->when($pic !== '', function ($query) use ($pic) {
                $pic === '__unassigned'
                    ? $query->whereNull('pic_name')
                    : $query->where('pic_name', $pic);
            })
            ->whereHas('package')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $packages = Registration::query()
            ->with('package:id,name,departure_date')
            ->whereHas('package')
            ->get()
            ->pluck('package')
            ->unique('id')
            ->sortBy('departure_date')
            ->values();

        $packageOptions = Package::query()
            ->orderBy('departure_date')
            ->orderBy('name')
            ->get(['id', 'name', 'departure_date']);

        $alertCount = Registration::query()
            ->with(['jemaah', 'package'])
            ->whereHas('package')
            ->get()
            ->filter(fn (Registration $registration) => $this->needsPassportRenewal($registration))
            ->count();

        return view('admin.documents.index', compact(
            'registrations',
            'packages',
            'search',
            'packageId',
            'pic',
            'alertCount',
            'packageOptions'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'package_id' => ['required', 'exists:packages,id'],
            'jemaah_id' => ['nullable', 'exists:jemaahs,id'],
            'new_name' => ['required_without:jemaah_id', 'nullable', 'string', 'max:255'],
            'new_gender' => ['nullable', 'string', 'in:'.implode(',', array_keys(Jemaah::GENDERS))],
            'new_passport_number' => ['nullable', 'string', 'max:50', 'unique:jemaahs,passport_number'],
            'new_passport_expiry_date' => ['nullable', 'date'],
            'new_birth_date' => ['nullable', 'date'],
            'new_address' => ['nullable', 'string', 'max:1000'],
            'pic_name' => ['nullable', 'string', 'max:255'],
        ]);

        if (! empty($data['jemaah_id'])) {
            $jemaah = Jemaah::findOrFail($data['jemaah_id']);
        } else {
            $jemaah = Jemaah::create([
                'name' => $data['new_name'],
                'gender' => $data['new_gender'] ?? null,
                'passport_number' => $data['new_passport_number'] ?? null,
                'passport_expiry_date' => $data['new_passport_expiry_date'] ?? null,
                'birth_date' => $data['new_birth_date'] ?? null,
                'address' => $data['new_address'] ?? null,
            ]);
        }

        $registration = Registration::firstOrCreate(
            ['jemaah_id' => $jemaah->id, 'package_id' => $data['package_id']],
            ['status' => 'active', 'pic_name' => $data['pic_name'] ?? null]
        );

        if ($registration->wasRecentlyCreated === false && ! empty($data['pic_name'])) {
            $registration->update(['pic_name' => $data['pic_name']]);
        }

        return redirect()->route('admin.documents.index', ['package_id' => $data['package_id']])
            ->with('status', $registration->wasRecentlyCreated
                ? 'Jemaah berhasil ditambahkan ke modul Dokumen & Visa.'
                : 'Jemaah sudah terdaftar di package tersebut.');
    }

    public function updateChecklist(Request $request, Registration $registration, string $type)
    {
        abort_unless(array_key_exists($type, Registration::DOCUMENT_TYPES), 404);

        $data = $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', array_keys(Registration::STATUSES))],
        ]);

        $registration->items()->updateOrCreate(
            ['type' => $type],
            ['status' => $data['status']]
        );

        return back()->with('status', 'Status dokumen berhasil diperbarui.');
    }

    public function bulkUpdateChecklist(Request $request)
    {
        $data = $request->validate([
            'registration_ids' => ['required', 'array', 'min:1'],
            'registration_ids.*' => ['integer'],
            'type' => ['required', 'string', 'in:'.implode(',', array_keys(Registration::DOCUMENT_TYPES))],
            'status' => ['required', 'string', 'in:'.implode(',', array_keys(Registration::STATUSES))],
        ]);

        $registrationIds = Registration::whereIn('id', $data['registration_ids'])->pluck('id');
        $count = 0;

        foreach ($registrationIds as $registrationId) {
            $count += Registration::find($registrationId)->items()->updateOrCreate(
                ['type' => $data['type']],
                ['status' => $data['status']]
            ) ? 1 : 0;
        }

        return back()->with('status', "Status {$data['type']} berhasil diperbarui untuk {$count} jemaah.");
    }

    public function updateVisaStatus(Request $request, Registration $registration)
    {
        $data = $request->validate([
            'visa_status' => ['required', 'string', 'in:'.implode(',', array_keys(Registration::VISA_STATUSES))],
        ]);

        $registration->update(['visa_status' => $data['visa_status']]);

        $legacyStatus = match ($data['visa_status']) {
            'completed' => 'completed',
            'not_started' => 'missing',
            default => 'in_progress',
        };

        $registration->items()->where('type', 'visa')->update(['status' => $legacyStatus]);

        return back()->with('status', 'Status visa berhasil diperbarui.');
    }

    public function updatePassportExpiry(Request $request, Jemaah $jemaah)
    {
        $data = $request->validate([
            'passport_expiry_date' => ['nullable', 'date'],
        ]);

        $jemaah->update($data);

        return back()->with('status', 'Masa berlaku paspor berhasil diperbarui.');
    }

    private function needsPassportRenewal(Registration $registration): bool
    {
        $expiryDate = $registration->jemaah?->passport_expiry_date;
        $departureDate = $registration->package?->departure_date;

        return $expiryDate && $departureDate
            && $expiryDate->lt($departureDate->copy()->subMonths(6));
    }
}

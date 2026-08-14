<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jemaah;
use App\Models\Package;
use App\Models\Registration;
use App\Models\RegistrationItem;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PackageJemaahController extends Controller
{
    /**
     * Display the jemaah roster for a package (departure batch).
     */
    public function index(Request $request, Package $package)
    {
        $search = $request->string('search')->toString();
        $pic = $request->string('pic')->toString();

        $registrations = $package->registrations()
            ->with(['jemaah', 'items'])
            ->withCount([
                'items as completed_items_count' => fn ($q) => $q->where('status', 'completed'),
                'items as problem_items_count' => fn ($q) => $q->where('status', 'problem'),
            ])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('jemaah', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('passport_number', 'like', "%{$search}%");
                });
            })
            ->when($pic !== '', function ($query) use ($pic) {
                $pic === '__unassigned'
                    ? $query->whereNull('pic_name')
                    : $query->where('pic_name', $pic);
            })
            ->orderByRaw('pic_name is null, pic_name')
            ->orderByDesc('problem_items_count')
            ->orderBy('completed_items_count')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $summary = RegistrationItem::query()
            ->whereHas('registration', fn ($q) => $q->where('package_id', $package->id))
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $picOptions = $package->registrations()
            ->whereNotNull('pic_name')
            ->distinct()
            ->orderBy('pic_name')
            ->pluck('pic_name');

        return view('admin.packages.jemaah.index', compact('package', 'registrations', 'search', 'summary', 'pic', 'picOptions'));
    }

    /**
     * Register a jemaah (existing or new) to this package's departure.
     */
    public function store(Request $request, Package $package)
    {
        $data = $request->validate([
            'jemaah_id' => ['nullable', 'exists:jemaahs,id'],
            'new_name' => ['required_without:jemaah_id', 'nullable', 'string', 'max:255'],
            'new_gender' => ['nullable', 'string', 'in:'.implode(',', array_keys(Jemaah::GENDERS))],
            'new_passport_number' => ['nullable', 'string', 'max:50', 'unique:jemaahs,passport_number'],
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
                'birth_date' => $data['new_birth_date'] ?? null,
                'address' => $data['new_address'] ?? null,
            ]);
        }

        $alreadyRegistered = Registration::where('jemaah_id', $jemaah->id)
            ->where('package_id', $package->id)
            ->exists();

        Registration::firstOrCreate(
            ['jemaah_id' => $jemaah->id, 'package_id' => $package->id],
            ['status' => 'active', 'pic_name' => $data['pic_name'] ?? null]
        );

        return redirect()->route('admin.packages.jemaah.index', $package)
            ->with('status', $alreadyRegistered
                ? 'Jemaah sudah terdaftar di keberangkatan ini.'
                : 'Jemaah berhasil ditambahkan ke keberangkatan ini.');
    }

    /**
     * Assign/change which PIC (person in charge) handles this jemaah's registration.
     */
    public function updatePic(Request $request, Package $package, Registration $registration)
    {
        abort_unless($registration->package_id === $package->id, 404);

        $data = $request->validate([
            'pic_name' => ['nullable', 'string', 'max:255'],
        ]);

        $registration->update(['pic_name' => $data['pic_name'] ?: null]);

        return redirect()->route('admin.packages.jemaah.index', array_merge(['package' => $package], $request->only('search', 'pic')))
            ->with('status', 'PIC berhasil diperbarui.');
    }

    /**
     * Remove a registration (e.g. wrongly added) from this package.
     */
    public function destroy(Package $package, Registration $registration)
    {
        abort_unless($registration->package_id === $package->id, 404);

        $registration->delete();

        return redirect()->route('admin.packages.jemaah.index', $package)
            ->with('status', 'Jemaah berhasil dihapus dari keberangkatan ini.');
    }

    /**
     * Assign the same PIC to multiple selected registrations at once.
     */
    public function bulkUpdatePic(Request $request, Package $package)
    {
        $data = $request->validate([
            'registration_ids' => ['required', 'array', 'min:1'],
            'registration_ids.*' => ['integer'],
            'pic_name' => ['required', 'string', 'max:255'],
        ]);

        $count = Registration::where('package_id', $package->id)
            ->whereIn('id', $data['registration_ids'])
            ->update(['pic_name' => $data['pic_name']]);

        return redirect()->route('admin.packages.jemaah.index', array_merge(['package' => $package], $request->only('search', 'pic')))
            ->with('status', "PIC berhasil diterapkan ke {$count} jemaah.");
    }

    /**
     * Remove multiple selected registrations from this package at once.
     */
    public function bulkDestroy(Request $request, Package $package)
    {
        $data = $request->validate([
            'registration_ids' => ['required', 'array', 'min:1'],
            'registration_ids.*' => ['integer'],
        ]);

        $count = Registration::where('package_id', $package->id)
            ->whereIn('id', $data['registration_ids'])
            ->delete();

        return redirect()->route('admin.packages.jemaah.index', array_merge(['package' => $package], $request->only('search', 'pic')))
            ->with('status', "{$count} jemaah berhasil dihapus dari keberangkatan ini.");
    }

    /**
     * Set one checklist item's status for multiple selected registrations at once.
     */
    public function bulkUpdateStatus(Request $request, Package $package)
    {
        $data = $request->validate([
            'registration_ids' => ['required', 'array', 'min:1'],
            'registration_ids.*' => ['integer'],
            'type' => ['required', 'string', 'in:'.implode(',', array_keys(Registration::ITEM_TYPES))],
            'status' => ['required', 'string', 'in:'.implode(',', array_keys(Registration::STATUSES))],
        ]);

        $registrationIds = Registration::where('package_id', $package->id)
            ->whereIn('id', $data['registration_ids'])
            ->pluck('id');

        $count = RegistrationItem::whereIn('registration_id', $registrationIds)
            ->where('type', $data['type'])
            ->update(['status' => $data['status']]);

        $label = Registration::ITEM_TYPES[$data['type']];
        $statusLabel = Registration::STATUSES[$data['status']];

        return redirect()->route('admin.packages.jemaah.index', array_merge(['package' => $package], $request->only('search', 'pic')))
            ->with('status', "Status {$label} berhasil diubah jadi \"{$statusLabel}\" untuk {$count} jemaah.");
    }

    /**
     * Export the current roster for this package to .xlsx, matching the import template shape.
     */
    public function export(Package $package)
    {
        $registrations = $package->registrations()->with(['jemaah', 'items'])->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(
            array_merge(['Jemaah', 'No. Paspor'], array_values(Registration::ITEM_TYPES)),
            null,
            'A1'
        );

        foreach ($registrations as $i => $registration) {
            $checklist = collect($registration->checklist)->keyBy('key');

            $row = array_merge(
                [$registration->jemaah->name, $registration->jemaah->passport_number],
                collect(array_keys(Registration::ITEM_TYPES))
                    ->map(fn ($type) => Registration::STATUSES[$checklist[$type]['status']])
                    ->all()
            );

            $sheet->fromArray($row, null, 'A'.($i + 2));
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'jemaah-'.$package->slug.'-'.now()->format('Ymd-His').'.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}

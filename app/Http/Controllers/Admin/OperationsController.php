<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DepartureRoom;
use App\Models\Package;
use App\Models\Registration;
use App\Models\RoomAssignment;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OperationsController extends Controller
{
    public const GROUPS = ['A', 'B', 'C'];

    public const ROOM_TYPES = [
        'quad' => ['label' => 'Quad', 'capacity' => 4],
        'triple' => ['label' => 'Triple', 'capacity' => 3],
        'double' => ['label' => 'Double', 'capacity' => 2],
    ];

    public function index(Request $request)
    {
        $packageId = $request->integer('package_id');
        $packages = Package::query()->orderBy('departure_date')->orderBy('name')->get(['id', 'name', 'departure_date']);
        $selectedPackage = $packages->firstWhere('id', $packageId) ?? $packages->first();

        $registrations = $selectedPackage
            ? $selectedPackage->registrations()->with(['jemaah', 'roomAssignment.room'])->orderBy('manifest_group')->orderBy('id')->get()
            : collect();

        $rooms = $selectedPackage
            ? DepartureRoom::where('package_id', $selectedPackage->id)->with(['registrations.jemaah'])->withCount('registrations')->orderBy('city')->orderBy('room_number')->get()
            : collect();

        return view('admin.operations.index', [
            'packages' => $packages,
            'selectedPackage' => $selectedPackage,
            'registrations' => $registrations,
            'rooms' => $rooms,
            'groups' => self::GROUPS,
            'roomTypes' => self::ROOM_TYPES,
        ]);
    }

    public function updateGroup(Request $request, Registration $registration)
    {
        $data = $request->validate([
            'manifest_group' => ['nullable', 'string', 'in:'.implode(',', self::GROUPS)],
        ]);

        $registration->update(['manifest_group' => $data['manifest_group'] ?: null]);

        return back()->with('status', 'Grup manifes berhasil diperbarui.');
    }

    public function bulkUpdateGroup(Request $request)
    {
        $data = $request->validate([
            'registration_ids' => ['required', 'array', 'min:1'],
            'registration_ids.*' => ['integer'],
            'manifest_group' => ['nullable', 'string', 'in:'.implode(',', self::GROUPS)],
        ]);

        $count = Registration::whereIn('id', $data['registration_ids'])
            ->update(['manifest_group' => $data['manifest_group'] ?: null]);

        return back()->with('status', "Grup manifest berhasil diterapkan ke {$count} jemaah.");
    }

    public function storeRoom(Request $request, Package $package)
    {
        $data = $request->validate([
            'city' => ['required', 'string', 'in:makkah,madinah'],
            'room_number' => ['required', 'string', 'max:30'],
            'room_type' => ['required', 'string', 'in:'.implode(',', array_keys(self::ROOM_TYPES))],
        ]);

        DepartureRoom::create([
            'package_id' => $package->id,
            'city' => $data['city'],
            'room_number' => $data['room_number'],
            'room_type' => $data['room_type'],
            'capacity' => self::ROOM_TYPES[$data['room_type']]['capacity'],
        ]);

        return back()->with('status', 'Kamar berhasil ditambahkan.');
    }

    public function assignRoom(Request $request, Registration $registration)
    {
        $data = $request->validate([
            'room_id' => ['nullable', 'exists:departure_rooms,id'],
        ]);

        if (empty($data['room_id'])) {
            $registration->roomAssignment()->delete();
            return back()->with('status', 'Jemaah dikeluarkan dari kamar.');
        }

        $room = DepartureRoom::withCount('registrations')->findOrFail($data['room_id']);
        abort_unless($room->package_id === $registration->package_id, 422);

        $existingAssignment = $registration->roomAssignment;
        if (! $existingAssignment && $room->registrations_count >= $room->capacity) {
            return back()->with('error', 'Kamar sudah penuh.');
        }

        RoomAssignment::updateOrCreate(
            ['registration_id' => $registration->id],
            ['departure_room_id' => $room->id]
        );

        return back()->with('status', 'Pembagian kamar berhasil diperbarui.');
    }

    public function bulkAssignRoom(Request $request)
    {
        $data = $request->validate([
            'registration_ids' => ['required', 'array', 'min:1'],
            'registration_ids.*' => ['integer'],
            'room_id' => ['nullable', 'exists:departure_rooms,id'],
        ]);

        $registrations = Registration::whereIn('id', $data['registration_ids'])->get();
        if (empty($data['room_id'])) {
            RoomAssignment::whereIn('registration_id', $registrations->pluck('id'))->delete();
            return back()->with('status', 'Jemaah terpilih dikeluarkan dari kamar.');
        }

        $room = DepartureRoom::withCount('registrations')->findOrFail($data['room_id']);
        abort_unless($registrations->every(fn (Registration $registration) => $registration->package_id === $room->package_id), 422);

        $selectedIds = $registrations->pluck('id');
        $alreadyInRoom = RoomAssignment::where('departure_room_id', $room->id)
            ->whereNotIn('registration_id', $selectedIds)
            ->count();

        if ($alreadyInRoom + $registrations->count() > $room->capacity) {
            return back()->with('error', 'Jumlah jemaah melebihi kapasitas kamar.');
        }

        foreach ($registrations as $registration) {
            RoomAssignment::updateOrCreate(
                ['registration_id' => $registration->id],
                ['departure_room_id' => $room->id]
            );
        }

        return back()->with('status', "{$registrations->count()} jemaah berhasil dimasukkan ke kamar.");
    }

    public function manifest(Request $request): StreamedResponse
    {
        $package = Package::findOrFail($request->integer('package_id'));
        $group = $request->string('group')->toString();

        $registrations = $package->registrations()
            ->with('jemaah')
            ->when($group !== '', fn ($query) => $query->where('manifest_group', $group))
            ->orderBy('manifest_group')
            ->orderBy('id')
            ->get();

        $filename = 'manifest-'.$package->slug.($group ? '-grup-'.$group : '').'-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($registrations, $package, $group) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Manifest', $package->name]);
            fputcsv($handle, ['Tanggal Keberangkatan', optional($package->departure_date)->format('d/m/Y')]);
            fputcsv($handle, ['Grup', $group ?: 'Semua']);
            fputcsv($handle, []);
            fputcsv($handle, ['No', 'Grup', 'Nama Jemaah', 'No. Paspor']);
            foreach ($registrations as $index => $registration) {
                fputcsv($handle, [$index + 1, $registration->manifest_group ?: '-', $registration->jemaah->name, $registration->jemaah->passport_number ?: '-']);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}

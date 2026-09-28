<x-admin-layout :title="__('Operasional & Manifest')">
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">{{ __('Operasional & Manifest') }}</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">{{ __('Persiapan keberangkatan dan handling bandara') }}</p>
    </x-slot>

    <div class="space-y-5" x-data="operationsBoard()">
        <details open class="content-card bg-blue-50/70 dark:bg-blue-900/15 rounded-2xl border border-blue-100 dark:border-blue-900/50 animate-fade-in-up group">
            <summary class="flex items-center justify-between gap-3 px-5 py-4 cursor-pointer list-none">
                <span class="flex items-center gap-2.5 text-sm font-extrabold text-blue-900 dark:text-blue-200">
                    <span class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center"><i data-lucide="circle-help" class="w-4 h-4"></i></span>
                    {{ __('Panduan Cepat Operasional') }}
                </span>
                <i data-lucide="chevron-down" class="w-4 h-4 text-blue-500 transition-transform group-open:rotate-180"></i>
            </summary>
            <div class="px-5 pb-5 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-3">
                @foreach ([
                    ['1', 'Pilih Package', 'Tentukan keberangkatan yang sedang disiapkan.'],
                    ['2', 'Atur Grup', 'Pilih Grup A, B, atau C pada tabel manifest.'],
                    ['3', 'Generate Manifest', 'Unduh daftar nama dan paspor untuk maskapai/imigrasi.'],
                    ['4', 'Tambah Kamar', 'Buat kamar Mekkah atau Madinah dengan tipe yang sesuai.'],
                    ['5', 'Seret Jemaah', 'Tarik nama ke kamar. Kapasitas kamar akan divalidasi otomatis.'],
                ] as [$number, $title, $description])
                    <div class="flex items-start gap-3 bg-white/70 dark:bg-slate-800/70 rounded-xl p-3 border border-blue-100/80 dark:border-slate-700">
                        <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-[11px] font-extrabold flex items-center justify-center shrink-0">{{ $number }}</span>
                        <div><p class="text-xs font-extrabold text-slate-800 dark:text-white">{{ __($title) }}</p><p class="text-[11px] leading-relaxed text-slate-500 dark:text-slate-400 mt-1">{{ __($description) }}</p></div>
                    </div>
                @endforeach
            </div>
        </details>

        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 animate-fade-in-up">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">{{ __('Keberangkatan aktif') }}</p>
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $selectedPackage?->name ?? __('Belum ada package') }}</h3>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <form method="GET" action="{{ route('admin.operations.index') }}">
                    <select name="package_id" onchange="this.form.submit()" class="w-full sm:w-72 px-4 py-2.5 text-sm rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-800 dark:text-white font-semibold">
                        @forelse ($packages as $package)
                            <option value="{{ $package->id }}" @selected($selectedPackage?->id === $package->id)>{{ $package->name }} · {{ $package->departure_date->format('d/m/Y') }}</option>
                        @empty
                            <option>{{ __('Belum ada package') }}</option>
                        @endforelse
                    </select>
                </form>
                @if ($selectedPackage)
                    <form method="GET" action="{{ route('admin.operations.manifest') }}" class="flex gap-2">
                        <input type="hidden" name="package_id" value="{{ $selectedPackage->id }}">
                        <select name="group" class="w-32 px-3 py-2.5 text-sm rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-800 dark:text-white font-semibold">
                            <option value="">{{ __('Semua Grup') }}</option>
                            @foreach ($groups as $group)<option value="{{ $group }}">{{ __('Grup') }} {{ $group }}</option>@endforeach
                        </select>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-lg shadow-blue-500/20 transition" title="{{ __('Download manifest untuk maskapai dan imigrasi') }}">
                            <i data-lucide="file-down" class="w-4 h-4"></i>{{ __('Generate Manifest') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if ($selectedPackage)
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 animate-fade-in-up">
                <div class="content-card bg-white dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700 p-4"><p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">{{ __('Total Jemaah') }}</p><p class="text-2xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $registrations->count() }}</p></div>
                @foreach ($groups as $group)
                    <div class="content-card bg-white dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700 p-4"><p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">{{ __('Grup') }} {{ $group }}</p><p class="text-2xl font-extrabold text-blue-600 dark:text-blue-400 mt-1">{{ $registrations->where('manifest_group', $group)->count() }}</p></div>
                @endforeach
            </div>

            <section class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden animate-fade-in-up">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div><h3 class="font-extrabold text-slate-900 dark:text-white">{{ __('Manifest Jemaah') }}</h3><p class="text-xs text-slate-500 mt-1">{{ __('Tentukan grup sebelum manifest dibuat.') }}</p></div>
                    <span class="text-xs font-semibold text-slate-400">{{ $selectedPackage->departure_date->translatedFormat('d F Y') }}</span>
                </div>
                <div x-show="selectedIds.length > 0" x-cloak class="flex flex-col xl:flex-row xl:items-center gap-3 px-5 py-3 bg-blue-50 dark:bg-blue-900/20 border-b border-blue-200 dark:border-blue-800">
                    <p class="text-sm font-bold text-blue-700 dark:text-blue-300 xl:mr-auto"><span x-text="selectedIds.length"></span> {{ __('jemaah dipilih') }}</p>
                    <form method="POST" action="{{ route('admin.operations.group.bulk-update') }}" class="flex flex-col sm:flex-row gap-2">
                        @csrf @method('PATCH')
                        <select name="manifest_group" required class="!w-full sm:!w-36 !py-2 text-xs font-bold rounded-lg"><option value="">{{ __('Pilih Grup') }}</option>@foreach ($groups as $group)<option value="{{ $group }}">{{ __('Grup') }} {{ $group }}</option>@endforeach</select>
                        <template x-for="id in selectedIds" :key="'group-' + id"><input type="hidden" name="registration_ids[]" :value="id"></template>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold"><i data-lucide="users-round" class="w-3.5 h-3.5"></i>{{ __('Set Grup') }}</button>
                    </form>
                    <form method="POST" action="{{ route('admin.operations.room.bulk-assign') }}" class="flex flex-col sm:flex-row gap-2">
                        @csrf @method('PATCH')
                        <select name="room_id" required class="!w-full sm:!w-56 !py-2 text-xs font-bold rounded-lg"><option value="">{{ __('Pilih Kamar') }}</option>@foreach ($rooms as $room)<option value="{{ $room->id }}">{{ ucfirst($room->city) }} · {{ $room->room_number }} ({{ $room->registrations_count }}/{{ $room->capacity }})</option>@endforeach</select>
                        <template x-for="id in selectedIds" :key="'room-' + id"><input type="hidden" name="registration_ids[]" :value="id"></template>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold"><i data-lucide="bed-double" class="w-3.5 h-3.5"></i>{{ __('Set Kamar') }}</button>
                    </form>
                    <button type="button" @click="selectedIds = []" class="text-xs font-bold text-blue-700 dark:text-blue-300 hover:underline">{{ __('Batal') }}</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[700px]">
                        <thead><tr class="bg-slate-50/80 dark:bg-slate-900/50 text-[11px] uppercase tracking-wider text-slate-400"><th class="px-2 py-3 text-left"><input type="checkbox" :checked="allSelected" @change="toggleSelectAll($event.target.checked)" class="!w-4 !h-4 rounded"></th><th class="px-3 py-3 text-left">No</th><th class="px-3 py-3 text-left">{{ __('Jemaah') }}</th><th class="px-3 py-3 text-left">{{ __('No. Paspor') }}</th><th class="px-3 py-3 text-left">{{ __('Grup') }}</th><th class="px-5 py-3 text-right">{{ __('Kamar') }}</th></tr></thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                            @forelse ($registrations as $index => $registration)
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/30">
                                    <td class="px-2 py-3"><input type="checkbox" value="{{ $registration->id }}" x-model.number="selectedIds" class="!w-4 !h-4 rounded"></td>
                                    <td class="px-5 py-3 text-slate-400">{{ $index + 1 }}</td>
                                    <td class="px-3 py-3"><a href="{{ route('admin.jemaah.show', $registration->jemaah) }}" class="font-bold text-slate-800 dark:text-white hover:text-blue-600">{{ $registration->jemaah->name }}</a></td>
                                    <td class="px-3 py-3 font-mono text-xs text-slate-500">{{ $registration->jemaah->passport_number ?: '—' }}</td>
                                    <td class="px-3 py-3"><form method="POST" action="{{ route('admin.operations.group.update', $registration) }}" class="flex items-center gap-2">@csrf @method('PATCH')<select name="manifest_group" onchange="this.form.submit()" class="!w-28 !py-1.5 text-xs font-bold rounded-lg"><option value="">{{ __('Belum') }}</option>@foreach ($groups as $group)<option value="{{ $group }}" @selected($registration->manifest_group === $group)>{{ __('Grup') }} {{ $group }}</option>@endforeach</select></form></td>
                                    <td class="px-5 py-3 text-right">
                                        <form method="POST" action="{{ route('admin.operations.room.assign', $registration) }}">
                                            @csrf
                                            @method('PATCH')
                                            <select name="room_id" onchange="this.form.submit()" class="!w-44 !py-1.5 !px-2 text-xs font-semibold rounded-lg" title="{{ __('Pilih kamar jemaah') }}">
                                                <option value="">{{ __('Belum dibagi') }}</option>
                                                @foreach ($rooms as $room)
                                                    <option value="{{ $room->id }}" @selected($registration->roomAssignment?->departure_room_id === $room->id)>{{ ucfirst($room->city) }} · {{ $room->room_number }} ({{ $room->registrations_count }}/{{ $room->capacity }})</option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-slate-400">{{ __('Belum ada jemaah pada package ini.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="space-y-4 animate-fade-in-up">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3"><div><h3 class="font-extrabold text-slate-900 dark:text-white">{{ __('Room List') }}</h3><p class="text-xs text-slate-500 mt-1">{{ __('Seret jemaah ke kamar. Kapasitas mengikuti tipe kamar.') }}</p></div><button type="button" @click="showRoomModal = true" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-700 dark:bg-white dark:hover:bg-slate-200 dark:text-slate-900 text-white text-sm font-bold transition" title="{{ __('Tambah kamar baru') }}"><i data-lucide="plus" class="w-4 h-4"></i>{{ __('Tambah Kamar') }}</button></div>
                @foreach (['makkah' => 'Mekkah', 'madinah' => 'Madinah'] as $city => $cityLabel)
                    <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5">
                        <div class="flex items-center gap-2 mb-4"><span class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 flex items-center justify-center"><i data-lucide="hotel" class="w-4 h-4"></i></span><h4 class="font-extrabold text-slate-900 dark:text-white">{{ $cityLabel }}</h4></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
                            @forelse ($rooms->where('city', $city) as $room)
                                <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/30 p-3 min-h-36" @dragover.prevent @drop="drop({{ $room->id }})">
                                    <div class="flex items-center justify-between gap-2 mb-3"><div><p class="font-extrabold text-slate-800 dark:text-white">{{ __('Kamar') }} {{ $room->room_number }}</p><p class="text-[11px] text-slate-500">{{ $roomTypes[$room->room_type]['label'] }} · {{ $room->registrations_count }}/{{ $room->capacity }}</p></div><i data-lucide="grip" class="w-4 h-4 text-slate-400"></i></div>
                                    <div class="space-y-1.5">
                                        @foreach ($room->registrations as $roomRegistration)
                                            <div draggable="true" @dragstart="draggingId = {{ $roomRegistration->id }}" class="flex items-center gap-2 px-2.5 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-200 cursor-grab"><i data-lucide="grip-vertical" class="w-3.5 h-3.5 text-slate-400"></i>{{ $roomRegistration->jemaah->name }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="md:col-span-2 xl:col-span-3 text-sm text-slate-400 py-5">{{ __('Belum ada kamar. Tambahkan kamar untuk mulai membagi jemaah.') }}</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach

                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 p-5" @dragover.prevent @drop="drop(null)"><div class="flex items-center justify-between gap-3 mb-3"><h4 class="font-bold text-slate-700 dark:text-slate-300">{{ __('Belum Dibagi Kamar') }}</h4><span class="text-[11px] font-semibold text-slate-400">{{ $registrations->whereNull('roomAssignment')->count() }} {{ __('jemaah') }}</span></div><div class="flex flex-wrap gap-2">@forelse ($registrations->whereNull('roomAssignment') as $registration)<div draggable="true" @dragstart="draggingId = {{ $registration->id }}" class="flex items-center gap-2 px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold cursor-grab"><i data-lucide="grip-vertical" class="w-3.5 h-3.5 text-slate-400"></i>{{ $registration->jemaah->name }}</div>@empty<span class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold">{{ __('Semua jemaah sudah memiliki kamar.') }}</span>@endforelse</div></div>
            </section>
        @else
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-12 text-center text-sm text-slate-400">{{ __('Belum ada package untuk operasional.') }}</div>
        @endif

        <div x-show="showRoomModal" x-cloak x-transition.opacity class="fixed inset-0 z-[90] bg-slate-900/60 backdrop-blur-sm"></div>
        <div x-show="showRoomModal" x-cloak class="fixed inset-0 z-[91] flex items-center justify-center p-4"><div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6" @click.outside="showRoomModal = false"><h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Tambah Kamar') }}</h3><form method="POST" action="{{ route('admin.operations.rooms.store', $selectedPackage ?? 0) }}" class="space-y-3">@csrf<div class="form-group"><label>{{ __('Kota') }}</label><select name="city" required><option value="makkah">{{ __('Mekkah') }}</option><option value="madinah">{{ __('Madinah') }}</option></select></div><div class="form-group"><label>{{ __('Nomor Kamar') }}</label><input type="text" name="room_number" required placeholder="Contoh: 101"></div><div class="form-group"><label>{{ __('Tipe Kamar') }}</label><select name="room_type" required>@foreach ($roomTypes as $key => $roomType)<option value="{{ $key }}">{{ $roomType['label'] }} ({{ $roomType['capacity'] }} orang)</option>@endforeach</select></div><div class="flex gap-3 pt-3"><button type="button" @click="showRoomModal = false" class="flex-1 px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-700 text-sm font-bold">{{ __('Batal') }}</button><button type="submit" class="flex-1 px-4 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold">{{ __('Simpan') }}</button></div></form></div></div>
    </div>

    @push('scripts')
        <script>
            function operationsBoard() {
                return {
                    draggingId: null,
                    showRoomModal: false,
                    selectedIds: [],
                    allRegistrationIds: @js($registrations->pluck('id')),
                    get allSelected() { return this.allRegistrationIds.length > 0 && this.selectedIds.length === this.allRegistrationIds.length; },
                    toggleSelectAll(checked) { this.selectedIds = checked ? [...this.allRegistrationIds] : []; },
                    drop(roomId) {
                        if (!this.draggingId) return;
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `{{ url('admin/operations/registrations') }}/${this.draggingId}/room`;
                        form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="PATCH"><input type="hidden" name="room_id" value="${roomId || ''}">`;
                        document.body.appendChild(form);
                        form.submit();
                    }
                };
            }
        </script>
    @endpush
</x-admin-layout>

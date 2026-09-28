<x-admin-layout :title="__('Dokumen & Perlengkapan')">
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">{{ __('Dokumen & Perlengkapan') }}</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">{{ __('Administrasi keberangkatan oleh tim Logistik / Bagian Dokumen') }}</p>
    </x-slot>

    <div class="space-y-4" x-data="{
            showAddModal: false, addTab: 'existing', selectedIds: [],
            allRegistrationIds: @js($registrations->pluck('id')),
            get allSelected() { return this.allRegistrationIds.length > 0 && this.selectedIds.length === this.allRegistrationIds.length; },
            toggleSelectAll(checked) { this.selectedIds = checked ? [...this.allRegistrationIds] : []; }
        }">
        <div class="flex flex-col gap-3 animate-fade-in-up">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">{{ __('Daftar Dokumen Keberangkatan') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Semua dokumen dan perlengkapan keberangkatan dikelola dari modul ini.') }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold px-3 py-1.5 rounded-lg">
                        <i data-lucide="users" class="w-3.5 h-3.5"></i>
                        {{ $registrations->total() }} {{ __('jemaah') }}
                    </span>
                    @if ($alertCount > 0)
                        <span class="inline-flex items-center gap-1.5 bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 text-xs font-bold px-3 py-1.5 rounded-lg">
                            <i data-lucide="triangle-alert" class="w-3.5 h-3.5"></i>
                            {{ $alertCount }} {{ __('perlu perhatian') }}
                        </span>
                    @endif
                    <button type="button" @click="showAddModal = true" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 rounded-xl font-bold text-sm text-white transition shadow-lg shadow-blue-500/20">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        {{ __('Tambah Jemaah') }}
                    </button>
                </div>
            </div>
        </div>

        @if ($alertCount > 0)
            <div class="flex items-start gap-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl px-4 py-3 animate-fade-in-up">
                <i data-lucide="triangle-alert" class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5 shrink-0"></i>
                <div>
                    <p class="text-sm font-bold text-amber-800 dark:text-amber-300">{{ __('Peringatan masa berlaku paspor') }}</p>
                    <p class="text-xs text-amber-700 dark:text-amber-400 mt-0.5">{{ __('Ada jemaah dengan masa berlaku paspor kurang dari 6 bulan sebelum tanggal keberangkatan.') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 animate-fade-in-up">
            @foreach ([
                'missing' => ['Belum lengkap', 'slate', 'file-question'],
                'in_progress' => ['Sedang diproses', 'blue', 'loader-circle'],
                'completed' => ['Selesai', 'emerald', 'file-check-2'],
            ] as $statusKey => [$label, $color, $icon])
                <div class="content-card bg-white dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700 px-4 py-3">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __($label) }}</p>
                        <i data-lucide="{{ $icon }}" class="w-4 h-4 text-{{ $color }}-500"></i>
                    </div>
                    <p class="text-xl font-extrabold text-slate-800 dark:text-white mt-1">
                        {{ \App\Models\RegistrationItem::whereIn('type', array_keys(\App\Models\Registration::DOCUMENT_TYPES))->where('status', $statusKey)->count() }}
                    </p>
                </div>
            @endforeach
        </div>

        <form method="GET" action="{{ route('admin.documents.index') }}" class="flex flex-col lg:flex-row gap-3 animate-fade-in-up">
            <div class="flex-1">
                @include('admin.partials._search', ['action' => route('admin.documents.index'), 'value' => $search, 'placeholder' => __('Cari nama atau nomor paspor...')])
            </div>
            <select name="package_id" onchange="this.form.submit()" class="w-full lg:w-64 px-4 py-2.5 text-sm rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-blue-500 focus:ring-blue-500 font-semibold">
                <option value="">{{ __('Semua Keberangkatan') }}</option>
                @foreach ($packages as $package)
                    <option value="{{ $package->id }}" @selected((int) $packageId === $package->id)>{{ $package->name }} · {{ $package->departure_date->format('d/m/Y') }}</option>
                @endforeach
            </select>
        </form>

        <form method="POST" action="{{ route('admin.documents.checklist.bulk-update') }}" x-show="selectedIds.length > 0" x-cloak class="flex flex-col lg:flex-row lg:items-center gap-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl px-4 py-3 animate-fade-in-up">
            @csrf
            @method('PATCH')
            <div class="flex items-center gap-2 text-sm font-bold text-blue-700 dark:text-blue-300 lg:mr-auto">
                <i data-lucide="check-square" class="w-4 h-4"></i>
                <span><span x-text="selectedIds.length"></span> {{ __('jemaah dipilih') }}</span>
            </div>
            <select name="type" required class="w-full lg:w-48 !py-2 text-xs font-bold rounded-lg">
                <option value="">{{ __('Pilih checklist') }}</option>
                @foreach (\App\Models\Registration::DOCUMENT_TYPES as $type => $label)
                    <option value="{{ $type }}">{{ $label }}</option>
                @endforeach
            </select>
            <select name="status" required class="w-full lg:w-40 !py-2 text-xs font-bold rounded-lg">
                @foreach (\App\Models\Registration::STATUSES as $statusKey => $statusLabel)
                    <option value="{{ $statusKey }}">{{ $statusLabel }}</option>
                @endforeach
            </select>
            <template x-for="id in selectedIds" :key="id">
                <input type="hidden" name="registration_ids[]" :value="id">
            </template>
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-xs font-bold text-white transition">
                <i data-lucide="check-check" class="w-4 h-4"></i>
                {{ __('Terapkan') }}
            </button>
            <button type="button" @click="selectedIds = []" class="inline-flex items-center justify-center px-3 py-2 rounded-lg text-xs font-bold text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition">{{ __('Batal') }}</button>
        </form>

        <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden animate-fade-in-up">
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[920px] table-fixed">
                    <thead>
                        <tr class="text-left text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 bg-slate-50/80 dark:bg-slate-900/50">
                                <th class="px-2 py-3 w-10"><input type="checkbox" :checked="allSelected" @change="toggleSelectAll($event.target.checked)" class="!w-4 !h-4 rounded"></th>
                                <th class="px-4 py-3 w-[22%]">{{ __('Jemaah / Package') }}</th>
                                <th class="px-3 py-3 w-[15%]">{{ __('Paspor Berlaku') }}</th>
                                <th class="px-3 py-3 w-[53%]">{{ __('Kelengkapan') }}</th>
                                <th class="px-3 py-3 w-[7%] text-right">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @forelse ($registrations as $registration)
                            @php
                                $threshold = $registration->package->departure_date->copy()->subMonths(6);
                                $passportExpiry = $registration->jemaah->passport_expiry_date;
                                $passportAlert = $passportExpiry && $passportExpiry->lt($threshold);
                            @endphp
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/30 transition align-top">
                                <td class="px-2 py-3.5"><input type="checkbox" value="{{ $registration->id }}" x-model.number="selectedIds" class="!w-4 !h-4 rounded"></td>
                                <td class="px-4 py-3.5">
                                    <a href="{{ route('admin.jemaah.show', $registration->jemaah) }}" class="font-semibold text-slate-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition">{{ $registration->jemaah->name }}</a>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $registration->jemaah->passport_number ?? __('Belum ada nomor paspor') }}</p>
                                    <a href="{{ route('admin.documents.index', ['package_id' => $registration->package->id]) }}" class="inline-flex items-center gap-1 text-[11px] font-semibold text-blue-600 dark:text-blue-400 hover:underline mt-1">
                                        <i data-lucide="package" class="w-3 h-3"></i>
                                        {{ $registration->package->name }} · {{ $registration->package->departure_date->format('d/m/Y') }}
                                    </a>
                                </td>
                                <td class="px-3 py-3.5">
                                    <form method="POST" action="{{ route('admin.documents.passport-expiry.update', $registration->jemaah) }}" class="space-y-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="date" name="passport_expiry_date" value="{{ optional($passportExpiry)->format('Y-m-d') }}" onchange="this.form.submit()" class="!w-36 !py-1.5 !px-2 text-xs font-semibold rounded-lg {{ $passportAlert ? '!border-amber-400 !text-amber-700 dark:!text-amber-300' : '' }}">
                                        @if ($passportAlert)
                                            <span class="flex items-center gap-1 text-[10px] font-bold text-amber-600 dark:text-amber-400"><i data-lucide="triangle-alert" class="w-3 h-3"></i>{{ __('Kurang 6 bulan') }}</span>
                                        @elseif (!$passportExpiry)
                                            <span class="text-[10px] font-semibold text-slate-400">{{ __('Belum diisi') }}</span>
                                        @endif
                                    </form>
                                </td>
                                <td class="px-3 py-3.5">
                                    <div class="grid grid-cols-2 xl:grid-cols-3 gap-2">
                                            @foreach (\App\Models\Registration::DOCUMENT_TYPES as $type => $label)
                                            @php $item = $registration->items->firstWhere('type', $type); @endphp
                                                <form method="POST" action="{{ route('admin.documents.checklist.update', [$registration, $type]) }}" class="min-w-0 rounded-lg border border-slate-100 dark:border-slate-700/80 bg-slate-50/60 dark:bg-slate-900/30 p-1.5">
                                                @csrf
                                                @method('PATCH')
                                                    <label class="block truncate text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500 px-0.5" title="{{ $label }}">{{ $label }}</label>
                                                    <select name="status" onchange="this.form.submit()" class="!w-full !py-1.5 !px-1.5 text-[10px] font-bold rounded-md !border-0 cursor-pointer
                                                    @switch($item?->status)
                                                        @case('completed') bg-emerald-50 text-emerald-600 dark:!bg-emerald-900/30 dark:!text-emerald-400 @break
                                                        @case('in_progress') bg-blue-50 text-blue-600 dark:!bg-blue-900/30 dark:!text-blue-400 @break
                                                        @case('problem') bg-red-50 text-red-600 dark:!bg-red-900/30 dark:!text-red-400 @break
                                                        @default bg-slate-100 text-slate-500 dark:!bg-slate-700 dark:!text-slate-400
                                                    @endswitch">
                                                    @foreach (\App\Models\Registration::STATUSES as $statusKey => $statusLabel)
                                                        <option value="{{ $statusKey }}" @selected(($item?->status ?? 'missing') === $statusKey)>{{ $statusLabel }}</option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-3 py-3.5 text-right">
                                    <a href="{{ route('admin.jemaah.show', $registration->jemaah) }}" class="inline-flex p-2 rounded-lg bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:text-blue-600 transition" title="{{ __('Detail Jemaah') }}">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-16 text-center text-sm text-slate-400">{{ __('Belum ada data jemaah pada keberangkatan.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($registrations->hasPages())
                <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-700">{{ $registrations->links() }}</div>
            @endif
        </div>

        <div x-show="showAddModal" x-cloak x-transition.opacity class="fixed inset-0 z-[90] bg-slate-900/60 backdrop-blur-sm"></div>
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[91] flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-lg w-full p-6" @click.outside="showAddModal = false">
                <div class="flex items-start justify-between gap-4 mb-5">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Tambah Jemaah ke Package') }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Pilih jemaah, keberangkatan, dan PIC dari satu tempat.') }}</p>
                    </div>
                    <button type="button" @click="showAddModal = false" class="p-2 rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700" title="{{ __('Tutup') }}"><i data-lucide="x" class="w-4 h-4"></i></button>
                </div>

                <form method="POST" action="{{ route('admin.documents.store') }}" x-data="documentJemaahAutocomplete()" class="space-y-4">
                    @csrf
                    <div class="form-group">
                        <label>{{ __('Package / Keberangkatan') }}</label>
                        <select name="package_id" required>
                            <option value="">{{ __('Pilih package') }}</option>
                            @foreach ($packageOptions as $packageOption)
                                <option value="{{ $packageOption->id }}">{{ $packageOption->name }} · {{ $packageOption->departure_date->format('d/m/Y') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2 bg-slate-100 dark:bg-slate-900 rounded-xl p-1">
                        <button type="button" @click="addTab = 'existing'" class="flex-1 py-2 rounded-lg text-xs font-bold transition" :class="addTab === 'existing' ? 'bg-white dark:bg-slate-700 text-slate-800 dark:text-white shadow' : 'text-slate-500'">{{ __('Jemaah Terdaftar') }}</button>
                        <button type="button" @click="addTab = 'new'" class="flex-1 py-2 rounded-lg text-xs font-bold transition" :class="addTab === 'new' ? 'bg-white dark:bg-slate-700 text-slate-800 dark:text-white shadow' : 'text-slate-500'">{{ __('Jemaah Baru') }}</button>
                    </div>

                    <div x-show="addTab === 'existing'" class="space-y-2 relative">
                        <label class="form-group"><span>{{ __('Cari Nama / Paspor') }}</span></label>
                        <input type="text" x-model="query" @input.debounce.300ms="search()" autocomplete="off" placeholder="{{ __('Ketik minimal 2 karakter...') }}">
                        <input type="hidden" name="jemaah_id" x-model="selectedId">
                        <div x-show="query.length >= 2 && results.length > 0" class="border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden divide-y divide-slate-100 dark:divide-slate-700 max-h-44 overflow-y-auto">
                            <template x-for="item in results" :key="item.id">
                                <button type="button" @click="select(item)" class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition"><span class="font-semibold text-slate-800 dark:text-white" x-text="item.name"></span> <span class="text-xs text-slate-400" x-text="item.passport_number || 'Tanpa paspor'"></span></button>
                            </template>
                        </div>
                        <p x-show="selectedId" class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold" x-text="'Terpilih: ' + selectedName"></p>
                    </div>

                    <div x-show="addTab === 'new'" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="form-group sm:col-span-2"><label>{{ __('Nama Lengkap') }}</label><input type="text" name="new_name" placeholder="{{ __('Nama jemaah') }}"></div>
                        <div class="form-group"><label>{{ __('Jenis Kelamin') }}</label><select name="new_gender"><option value="">{{ __('Pilih') }}</option>@foreach (\App\Models\Jemaah::GENDERS as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach</select></div>
                        <div class="form-group"><label>{{ __('Nomor Paspor') }}</label><input type="text" name="new_passport_number" placeholder="{{ __('Opsional') }}"></div>
                        <div class="form-group"><label>{{ __('Paspor Berlaku Sampai') }}</label><input type="date" name="new_passport_expiry_date"></div>
                        <div class="form-group"><label>{{ __('Tanggal Lahir') }}</label><input type="date" name="new_birth_date"></div>
                    </div>

                    <div class="form-group"><label>{{ __('PIC / Penanggung Jawab') }}</label><input type="text" name="pic_name" placeholder="{{ __('Contoh: Ahmad') }}"></div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="showAddModal = false" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 transition">{{ __('Batal') }}</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition">{{ __('Simpan Jemaah') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function documentJemaahAutocomplete() {
                return {
                    query: '', results: [], selectedId: '', selectedName: '',
                    search() {
                        if (this.query.trim().length < 2) { this.results = []; return; }
                        fetch(`{{ route('admin.jemaah.search') }}?q=${encodeURIComponent(this.query)}`).then(response => response.json()).then(data => { this.results = data; });
                    },
                    select(item) { this.selectedId = item.id; this.selectedName = item.name; this.query = item.name; this.results = []; },
                };
            }
        </script>
    @endpush
</x-admin-layout>

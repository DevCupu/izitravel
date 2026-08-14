<x-admin-layout :title="__('Jemaah - ' . $package->name)">
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">
            {{ $package->name }}
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">
            {{ __('Keberangkatan') }} {{ $package->departure_date->translatedFormat('d M Y') }}
        </p>
    </x-slot>

    <div class="space-y-4" x-data="{
            showAddModal: false, showImportModal: false, addTab: 'existing', showEditModal: false, editingJemaah: null,
            showBulkPicModal: false, showBulkStatusModal: false,
            selectedIds: [],
            allRegistrationIds: @js($registrations->pluck('id')),
            get allSelected() { return this.allRegistrationIds.length > 0 && this.selectedIds.length === this.allRegistrationIds.length; },
            toggleSelectAll(checked) { this.selectedIds = checked ? [...this.allRegistrationIds] : []; },
        }">
        <!-- Header actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 animate-fade-in-up">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold px-3 py-1.5 rounded-lg">
                    <i data-lucide="users" class="w-3.5 h-3.5"></i>
                    {{ $registrations->total() }} jemaah
                </span>
            </div>
            <div class="flex flex-wrap gap-2 sm:items-center">
                <a href="{{ route('admin.packages.jemaah.export', $package) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 active:scale-[0.97] rounded-xl font-bold text-sm text-slate-600 dark:text-slate-300 transition-all duration-150">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    {{ __('Export Excel') }}
                </a>
                <button type="button" @click="showImportModal = true" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 active:scale-[0.97] rounded-xl font-bold text-sm text-slate-600 dark:text-slate-300 transition-all duration-150">
                    <i data-lucide="upload" class="w-4 h-4"></i>
                    {{ __('Import Excel') }}
                </button>
                <button type="button" @click="showAddModal = true" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.97] rounded-xl font-bold text-sm text-white transition-all duration-150 shadow-lg shadow-blue-500/20">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    {{ __('Tambah Jemaah') }}
                </button>
            </div>
        </div>

        <!-- Progress summary -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 animate-fade-in-up">
            @foreach (\App\Models\Registration::STATUSES as $key => $label)
                <div class="content-card bg-white dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700 px-4 py-3">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ $label }}</p>
                    <p class="text-xl font-extrabold text-slate-800 dark:text-white mt-1">{{ $summary[$key] ?? 0 }}</p>
                </div>
            @endforeach
        </div>

        <div class="flex flex-col sm:flex-row gap-3 animate-fade-in-up">
            <div class="flex-1">
                @include('admin.partials._search', ['action' => route('admin.packages.jemaah.index', $package), 'value' => $search, 'placeholder' => __('Cari nama/paspor...')])
            </div>
            <form method="GET" action="{{ route('admin.packages.jemaah.index', $package) }}" class="shrink-0">
                <input type="hidden" name="search" value="{{ $search }}">
                <select name="pic" onchange="this.form.submit()" class="w-full sm:w-56 px-4 py-2.5 text-sm rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:border-blue-500 focus:ring-blue-500 font-semibold">
                    <option value="">{{ __('Semua PIC') }}</option>
                    <option value="__unassigned" @selected($pic === '__unassigned')>{{ __('Belum Ditentukan') }}</option>
                    @foreach ($picOptions as $picName)
                        <option value="{{ $picName }}" @selected($pic === $picName)>{{ $picName }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Bulk action bar -->
        <div x-show="selectedIds.length > 0" x-cloak
             class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl px-4 py-3 animate-fade-in-up">
            <p class="text-sm font-bold text-blue-700 dark:text-blue-300">
                <span x-text="selectedIds.length"></span> {{ __('jemaah dipilih') }}
            </p>
            <div class="flex items-center gap-2">
                <button type="button" @click="showBulkPicModal = true"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-blue-200 dark:border-blue-700 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-lg font-bold text-xs text-blue-700 dark:text-blue-300 transition">
                    <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                    {{ __('Set PIC') }}
                </button>
                <button type="button" @click="showBulkStatusModal = true"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-blue-200 dark:border-blue-700 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-lg font-bold text-xs text-blue-700 dark:text-blue-300 transition">
                    <i data-lucide="list-checks" class="w-3.5 h-3.5"></i>
                    {{ __('Ubah Status') }}
                </button>
                <button type="button"
                        @click="$dispatch('confirm-delete', { form: $refs.bulkDeleteForm, message: selectedIds.length + ' {{ __('jemaah akan dihapus dari keberangkatan ini. Lanjutkan?') }}' })"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-red-200 dark:border-red-800 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg font-bold text-xs text-red-600 dark:text-red-400 transition">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    {{ __('Hapus Terpilih') }}
                </button>
                <button type="button" @click="selectedIds = []"
                        class="inline-flex items-center gap-2 px-3 py-2 rounded-lg font-bold text-xs text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition">
                    {{ __('Batal') }}
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.packages.jemaah.bulk-destroy', $package) }}" x-ref="bulkDeleteForm" class="hidden">
            @csrf
            @method('DELETE')
            <input type="hidden" name="search" value="{{ $search }}">
            <input type="hidden" name="pic" value="{{ $pic }}">
            <template x-for="id in selectedIds" :key="id">
                <input type="hidden" name="registration_ids[]" :value="id">
            </template>
        </form>

        <!-- Table -->
        <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden animate-fade-in-up stagger-2">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 bg-slate-50/80 dark:bg-slate-900/50">
                            <th class="!px-2 !py-3 w-8">
                                <input type="checkbox" :checked="allSelected" @change="toggleSelectAll($event.target.checked)" class="!w-4 !h-4 rounded">
                            </th>
                            <th class="!px-3 !py-3">{{ __('Jemaah') }}</th>
                            <th class="!px-2 !py-3">{{ __('PIC') }}</th>
                            <th class="!px-2 !py-3">{{ __('Kelengkapan') }}</th>
                            @foreach (\App\Models\Registration::ITEM_TYPES as $label)
                                <th class="!px-1.5 !py-3 text-center">{{ $label }}</th>
                            @endforeach
                            <th class="!px-2 !py-3 text-right">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @forelse ($registrations as $registration)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/30 transition group">
                                <td class="!px-2 !py-3.5">
                                    <input type="checkbox" value="{{ $registration->id }}" x-model.number="selectedIds" class="!w-4 !h-4 rounded">
                                </td>
                                <td class="!px-3 !py-3.5">
                                    <a href="{{ route('admin.jemaah.show', $registration->jemaah) }}" class="font-semibold text-slate-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition">{{ $registration->jemaah->name }}</a>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $registration->jemaah->passport_number ?? '—' }}</p>
                                </td>
                                <td class="!px-2 !py-3">
                                    <form method="POST" action="{{ route('admin.packages.jemaah.pic.update', [$package, $registration]) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="search" value="{{ $search }}">
                                        <input type="hidden" name="pic" value="{{ $pic }}">
                                        <input type="text" name="pic_name" value="{{ $registration->pic_name }}"
                                               onblur="this.value !== this.defaultValue && this.form.submit()"
                                               placeholder="{{ __('—') }}" list="pic-options"
                                               class="!w-20 !py-1.5 !px-2 text-xs font-semibold rounded-lg">
                                    </form>
                                </td>
                                <td class="!px-2 !py-3">
                                    @php
                                        $completenessColor = $registration->problem_items_count > 0
                                            ? 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400'
                                            : ($registration->completed_items_count === 7
                                                ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400'
                                                : ($registration->completed_items_count <= 3
                                                    ? 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400'
                                                    : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400'));
                                    @endphp
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-md {{ $completenessColor }}">
                                        @if ($registration->problem_items_count > 0)
                                            <i data-lucide="alert-triangle" class="w-3 h-3"></i>
                                        @endif
                                        {{ $registration->completed_items_count }}/7
                                    </span>
                                </td>
                                @foreach ($registration->checklist as $item)
                                    <td class="!px-1.5 !py-3 text-center" @if ($item['model']?->note) title="{{ $item['model']->note }}" @endif>
                                        <form method="POST" action="{{ route('admin.registrations.items.update', [$registration, $item['key']]) }}" class="inline-flex items-center justify-center gap-1">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()"
                                                    class="!w-auto !py-1 !px-1 text-[11px] font-bold rounded-md !border-0 cursor-pointer focus:!ring-1
                                                        @switch($item['status'])
                                                            @case('completed') bg-emerald-50 text-emerald-600 dark:!bg-emerald-900/30 dark:!text-emerald-400 @break
                                                            @case('in_progress') bg-blue-50 text-blue-600 dark:!bg-blue-900/30 dark:!text-blue-400 @break
                                                            @case('problem') bg-red-50 text-red-600 dark:!bg-red-900/30 dark:!text-red-400 @break
                                                            @default bg-slate-100 text-slate-500 dark:!bg-slate-700 dark:!text-slate-400
                                                        @endswitch
                                                    ">
                                                @foreach (\App\Models\Registration::STATUSES as $statusKey => $statusLabel)
                                                    <option value="{{ $statusKey }}" @selected($item['status'] === $statusKey)>{{ $statusLabel }}</option>
                                                @endforeach
                                            </select>
                                            @if ($item['model']?->note)
                                                <i data-lucide="message-square-text" class="w-3 h-3 text-slate-400 shrink-0"></i>
                                            @endif
                                        </form>
                                    </td>
                                @endforeach
                                <td class="!px-2 !py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.jemaah.show', $registration->jemaah) }}"
                                           class="shrink-0 p-2 rounded-lg bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 hover:border-emerald-200 dark:hover:border-emerald-800 hover:text-emerald-600 dark:hover:text-emerald-400 transition"
                                           title="{{ __('Lihat Detail Jemaah') }}">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <button type="button"
                                                @click="editingJemaah = {
                                                        id: {{ $registration->jemaah->id }},
                                                        name: @js($registration->jemaah->name),
                                                        gender: @js($registration->jemaah->gender),
                                                        passport_number: @js($registration->jemaah->passport_number),
                                                        birth_date: @js(optional($registration->jemaah->birth_date)->format('Y-m-d')),
                                                        address: @js($registration->jemaah->address),
                                                    }; showEditModal = true"
                                                class="shrink-0 p-2 rounded-lg bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:border-blue-200 dark:hover:border-blue-800 hover:text-blue-600 dark:hover:text-blue-400 transition"
                                                title="{{ __('Edit Data Jemaah') }}">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </button>
                                        <form method="POST" action="{{ route('admin.packages.jemaah.destroy', [$package, $registration]) }}" class="delete-form shrink-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    @click="$dispatch('confirm-delete', { form: $el.closest('form'), message: 'Hapus &quot;{{ $registration->jemaah->name }}&quot; dari keberangkatan ini?' })"
                                                    class="shrink-0 p-2 rounded-lg bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:bg-red-50 dark:hover:bg-red-900/30 hover:border-red-200 dark:hover:border-red-800 hover:text-red-600 dark:hover:text-red-400 transition"
                                                    title="{{ __('Hapus') }}">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                            <i data-lucide="users" class="w-7 h-7 text-slate-300 dark:text-slate-500"></i>
                                        </div>
                                        <p class="text-sm text-slate-400 dark:text-slate-500">{{ __('Belum ada jemaah di keberangkatan ini.') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <datalist id="pic-options">
                    @foreach ($picOptions as $picName)
                        <option value="{{ $picName }}"></option>
                    @endforeach
                </datalist>
            </div>

            @if ($registrations->hasPages())
                <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $registrations->links() }}
                </div>
            @endif
        </div>

        <!-- ═══════ Tambah Jemaah Modal ═══════ -->
        <div x-show="showAddModal" x-cloak x-transition.opacity class="fixed inset-0 z-[90] bg-slate-900/60 backdrop-blur-sm"></div>
        <div x-show="showAddModal" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="fixed inset-0 z-[91] flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6" @click.outside="showAddModal = false">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Tambah Jemaah') }}</h3>

                <div class="flex gap-2 mb-4 bg-slate-100 dark:bg-slate-900 rounded-xl p-1">
                    <button type="button" @click="addTab = 'existing'" class="flex-1 py-2 rounded-lg text-xs font-bold transition" :class="addTab === 'existing' ? 'bg-white dark:bg-slate-700 text-slate-800 dark:text-white shadow' : 'text-slate-500'">{{ __('Pilih Jemaah') }}</button>
                    <button type="button" @click="addTab = 'new'" class="flex-1 py-2 rounded-lg text-xs font-bold transition" :class="addTab === 'new' ? 'bg-white dark:bg-slate-700 text-slate-800 dark:text-white shadow' : 'text-slate-500'">{{ __('Jemaah Baru') }}</button>
                </div>

                <form method="POST" action="{{ route('admin.packages.jemaah.store', $package) }}" x-data="jemaahAutocomplete()">
                    @csrf

                    <div x-show="addTab === 'existing'" class="space-y-2 relative">
                        <label class="form-group"><span>{{ __('Cari Nama/Paspor') }}</span></label>
                        <input type="text" x-model="query" @input.debounce.300ms="search()" autocomplete="off" placeholder="{{ __('Ketik nama atau nomor paspor...') }}">
                        <input type="hidden" name="jemaah_id" x-model="selectedId">
                        <div x-show="query.length >= 2 && results.length > 0" class="border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden divide-y divide-slate-100 dark:divide-slate-700 max-h-48 overflow-y-auto">
                            <template x-for="item in results" :key="item.id">
                                <button type="button" @click="select(item)" class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition" :class="selectedId == item.id ? 'bg-blue-50 dark:bg-blue-900/30' : ''">
                                    <span class="font-semibold text-slate-800 dark:text-white" x-text="item.name"></span>
                                    <span class="text-xs text-slate-400 ml-1" x-text="item.passport_number"></span>
                                </button>
                            </template>
                        </div>
                        <p x-show="selectedId" class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold" x-text="selectedName ? 'Terpilih: ' + selectedName : ''"></p>
                    </div>

                    <div x-show="addTab === 'new'" class="space-y-3">
                        <div class="form-group">
                            <label>{{ __('Nama Lengkap') }}</label>
                            <input type="text" name="new_name" placeholder="{{ __('Nama lengkap jemaah') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('Jenis Kelamin') }}</label>
                            <select name="new_gender">
                                <option value="">{{ __('Pilih') }}</option>
                                @foreach (\App\Models\Jemaah::GENDERS as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Nomor Paspor') }}</label>
                            <input type="text" name="new_passport_number" placeholder="{{ __('Opsional') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('Tanggal Lahir') }}</label>
                            <input type="date" name="new_birth_date">
                        </div>
                        <div class="form-group">
                            <label>{{ __('Alamat') }}</label>
                            <textarea name="new_address" rows="2" placeholder="{{ __('Opsional') }}"></textarea>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label>{{ __('PIC (Penanggung Jawab)') }}</label>
                        <input type="text" name="pic_name" list="pic-options" placeholder="{{ __('Opsional, misal: Ahmad') }}">
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" @click="showAddModal = false" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition">{{ __('Batal') }}</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition">{{ __('Tambah') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ═══════ Edit Jemaah Modal ═══════ -->
        <div x-show="showEditModal" x-cloak x-transition.opacity class="fixed inset-0 z-[90] bg-slate-900/60 backdrop-blur-sm"></div>
        <div x-show="showEditModal" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="fixed inset-0 z-[91] flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6" @click.outside="showEditModal = false">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Edit Data Jemaah') }}</h3>

                <template x-if="editingJemaah">
                    <form method="POST" :action="'{{ url('admin/jemaah') }}/' + editingJemaah.id" class="space-y-3">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label>{{ __('Nama Lengkap') }}</label>
                            <input type="text" name="name" x-model="editingJemaah.name" required>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Jenis Kelamin') }}</label>
                            <select name="gender" x-model="editingJemaah.gender">
                                <option value="">{{ __('Pilih') }}</option>
                                @foreach (\App\Models\Jemaah::GENDERS as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Nomor Paspor') }}</label>
                            <input type="text" name="passport_number" x-model="editingJemaah.passport_number" placeholder="{{ __('Opsional') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('Tanggal Lahir') }}</label>
                            <input type="date" name="birth_date" x-model="editingJemaah.birth_date">
                        </div>
                        <div class="form-group">
                            <label>{{ __('Alamat') }}</label>
                            <textarea name="address" x-model="editingJemaah.address" rows="2" placeholder="{{ __('Opsional') }}"></textarea>
                        </div>

                        <div class="flex gap-3 mt-6">
                            <button type="button" @click="showEditModal = false" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition">{{ __('Batal') }}</button>
                            <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition">{{ __('Simpan') }}</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>

        <!-- ═══════ Bulk Set PIC Modal ═══════ -->
        <div x-show="showBulkPicModal" x-cloak x-transition.opacity class="fixed inset-0 z-[90] bg-slate-900/60 backdrop-blur-sm"></div>
        <div x-show="showBulkPicModal" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="fixed inset-0 z-[91] flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6" @click.outside="showBulkPicModal = false">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ __('Set PIC') }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                    {{ __('Terapkan ke') }} <span x-text="selectedIds.length"></span> {{ __('jemaah yang dipilih.') }}
                </p>

                <form method="POST" action="{{ route('admin.packages.jemaah.bulk-pic', $package) }}">
                    @csrf
                    <input type="hidden" name="search" value="{{ $search }}">
                    <input type="hidden" name="pic" value="{{ $pic }}">
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="registration_ids[]" :value="id">
                    </template>

                    <div class="form-group">
                        <label>{{ __('PIC (Penanggung Jawab)') }}</label>
                        <input type="text" name="pic_name" list="pic-options" placeholder="{{ __('misal: Ahmad') }}" required>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" @click="showBulkPicModal = false" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition">{{ __('Batal') }}</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition">{{ __('Terapkan') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ═══════ Bulk Ubah Status Modal ═══════ -->
        <div x-show="showBulkStatusModal" x-cloak x-transition.opacity class="fixed inset-0 z-[90] bg-slate-900/60 backdrop-blur-sm"></div>
        <div x-show="showBulkStatusModal" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="fixed inset-0 z-[91] flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6" @click.outside="showBulkStatusModal = false">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ __('Ubah Status') }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                    {{ __('Terapkan ke') }} <span x-text="selectedIds.length"></span> {{ __('jemaah yang dipilih.') }}
                </p>

                <form method="POST" action="{{ route('admin.packages.jemaah.bulk-status', $package) }}" class="space-y-3">
                    @csrf
                    <input type="hidden" name="search" value="{{ $search }}">
                    <input type="hidden" name="pic" value="{{ $pic }}">
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="registration_ids[]" :value="id">
                    </template>

                    <div class="form-group">
                        <label>{{ __('Item Checklist') }}</label>
                        <select name="type" required>
                            @foreach (\App\Models\Registration::ITEM_TYPES as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Status Baru') }}</label>
                        <select name="status" required>
                            @foreach (\App\Models\Registration::STATUSES as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" @click="showBulkStatusModal = false" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition">{{ __('Batal') }}</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition">{{ __('Terapkan') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ═══════ Import Excel Modal ═══════ -->
        <div x-show="showImportModal" x-cloak x-transition.opacity class="fixed inset-0 z-[90] bg-slate-900/60 backdrop-blur-sm"></div>
        <div x-show="showImportModal" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="fixed inset-0 z-[91] flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6" @click.outside="showImportModal = false">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">{{ __('Import Excel') }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">{{ __('Kolom: Jemaah, No. Paspor, Paspor, Vaksin, KTP, KK, Perlengkapan, Visa, Tiket. Data akan ditampilkan dulu untuk dikonfirmasi sebelum disimpan.') }}</p>
                <a href="{{ route('admin.packages.jemaah.import.template', $package) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline mb-4">
                    <i data-lucide="file-down" class="w-3.5 h-3.5"></i>
                    {{ __('Unduh Template Excel') }}
                </a>
                <form method="POST" action="{{ route('admin.packages.jemaah.import.preview', $package) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="upload-zone p-6 text-center">
                        <i data-lucide="file-spreadsheet" class="w-8 h-8 text-slate-400 mx-auto mb-2"></i>
                        <input type="file" name="file" accept=".xlsx" required class="text-xs">
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button type="button" @click="showImportModal = false" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition">{{ __('Batal') }}</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition">{{ __('Upload & Preview') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function jemaahAutocomplete() {
                return {
                    query: '',
                    results: [],
                    selectedId: '',
                    selectedName: '',
                    search() {
                        if (this.query.trim().length < 2) {
                            this.results = [];
                            return;
                        }
                        fetch(`{{ route('admin.jemaah.search') }}?q=${encodeURIComponent(this.query)}`)
                            .then(res => res.json())
                            .then(data => { this.results = data; });
                    },
                    select(item) {
                        this.selectedId = item.id;
                        this.selectedName = item.name;
                        this.results = [];
                        this.query = item.name;
                    },
                };
            }
        </script>
    @endpush
</x-admin-layout>

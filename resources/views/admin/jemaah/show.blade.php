<x-admin-layout :title="__('Detail - ' . $jemaah->name)">
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">
            {{ $jemaah->name }}
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">
            {{ $jemaah->passport_number ?? __('Belum ada nomor paspor') }}
        </p>
    </x-slot>

    <div class="space-y-4" x-data="{ showEditModal: false }">
        <!-- Profile card -->
        <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 animate-fade-in-up">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center font-bold text-white text-xl shadow-lg shadow-blue-500/20 shrink-0">
                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($jemaah->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $jemaah->name }}</h3>
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            @if ($jemaah->gender)
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                                    <i data-lucide="{{ $jemaah->gender === 'male' ? 'mars' : 'venus' }}" class="w-3 h-3"></i>
                                    {{ \App\Models\Jemaah::GENDERS[$jemaah->gender] }}
                                </span>
                            @endif
                            @if ($jemaah->birth_date)
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                                    <i data-lucide="cake" class="w-3 h-3"></i>
                                    {{ $jemaah->birth_date->translatedFormat('d F Y') }}
                                </span>
                            @endif
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-md bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                                <i data-lucide="scan-search" class="w-3 h-3"></i>
                                {{ $jemaah->passport_number ?? __('Belum ada paspor') }}
                            </span>
                        </div>
                        @if ($jemaah->address)
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 flex items-start gap-1.5 max-w-md">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 mt-0.5 shrink-0"></i>
                                {{ $jemaah->address }}
                            </p>
                        @endif
                    </div>
                </div>
                <button type="button" @click="showEditModal = true"
                        class="shrink-0 inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 active:scale-[0.97] rounded-xl font-bold text-sm text-slate-600 dark:text-slate-300 transition-all duration-150">
                    <i data-lucide="pencil" class="w-4 h-4"></i>
                    {{ __('Edit Data') }}
                </button>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 animate-fade-in-up">
            <div class="content-card bg-white dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700 px-4 py-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Total Keberangkatan') }}</p>
                <p class="text-xl font-extrabold text-slate-800 dark:text-white mt-1">{{ $jemaah->registrations->count() }}</p>
            </div>
            <div class="content-card bg-white dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700 px-4 py-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Siap Berangkat') }}</p>
                <p class="text-xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{{ $jemaah->registrations->where('overall_status', 'ready')->count() }}</p>
            </div>
            <div class="content-card bg-white dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700 px-4 py-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Perlu Tindak Lanjut') }}</p>
                <p class="text-xl font-extrabold text-red-600 dark:text-red-400 mt-1">{{ $jemaah->registrations->where('overall_status', 'attention')->count() }}</p>
            </div>
            <div class="content-card bg-white dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700 px-4 py-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Sedang Berjalan') }}</p>
                <p class="text-xl font-extrabold text-blue-600 dark:text-blue-400 mt-1">{{ $jemaah->registrations->whereIn('overall_status', ['not_started', 'in_progress'])->count() }}</p>
            </div>
        </div>

        <!-- Registrations -->
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider px-1">{{ __('Riwayat Keberangkatan') }}</h3>

            @forelse ($jemaah->registrations as $registration)
                @php
                    $package = $registration->package;
                    $overallMeta = [
                        'not_started' => 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400',
                        'in_progress' => 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
                        'ready' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400',
                        'attention' => 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400',
                    ][$registration->overall_status];
                @endphp
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5 animate-fade-in-up">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100 dark:border-slate-700">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                                <i data-lucide="package" class="w-5 h-5"></i>
                            </span>
                            <div>
                                <a href="{{ route('admin.packages.jemaah.index', $package) }}" class="font-bold text-slate-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition">
                                    {{ $package->name }}
                                </a>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Keberangkatan') }} {{ $package->departure_date->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-3 py-1.5 rounded-full {{ $overallMeta }}">
                                {{ \App\Models\Registration::OVERALL_STATUSES[$registration->overall_status] }}
                            </span>
                            @if ($registration->pic_name)
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1.5 rounded-md bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">
                                    <i data-lucide="user-check" class="w-3 h-3"></i>
                                    {{ $registration->pic_name }}
                                </span>
                            @endif
                            <form method="POST" action="{{ route('admin.packages.jemaah.destroy', [$package, $registration]) }}" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        @click="$dispatch('confirm-delete', { form: $el.closest('form'), message: 'Hapus dari keberangkatan &quot;{{ $package->name }}&quot;?' })"
                                        class="p-2 rounded-lg text-slate-400 dark:text-slate-500 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 transition"
                                        title="{{ __('Hapus dari keberangkatan ini') }}">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-2 mt-4">
                        @foreach ($registration->checklist as $item)
                            <div class="flex flex-col gap-1" @if ($item['model']?->note) title="{{ $item['model']->note }}" @endif>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ $item['label'] }}</span>
                                <form method="POST" action="{{ route('admin.registrations.items.update', [$registration, $item['key']]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()"
                                            class="!w-full !py-1.5 !px-2 text-[11px] font-bold rounded-md !border-0 cursor-pointer focus:!ring-1
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
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-10 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="package-x" class="w-7 h-7 text-slate-300 dark:text-slate-500"></i>
                    </div>
                    <p class="text-sm text-slate-400 dark:text-slate-500">{{ __('Jemaah ini belum terdaftar di keberangkatan manapun.') }}</p>
                </div>
            @endforelse
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

                <form method="POST" action="{{ route('admin.jemaah.update', $jemaah) }}" class="space-y-3">
                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label>{{ __('Nama Lengkap') }}</label>
                        <input type="text" name="name" value="{{ old('name', $jemaah->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Jenis Kelamin') }}</label>
                        <select name="gender">
                            <option value="">{{ __('Pilih') }}</option>
                            @foreach (\App\Models\Jemaah::GENDERS as $key => $label)
                                <option value="{{ $key }}" @selected(old('gender', $jemaah->gender) === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Nomor Paspor') }}</label>
                        <input type="text" name="passport_number" value="{{ old('passport_number', $jemaah->passport_number) }}" placeholder="{{ __('Opsional') }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Tanggal Lahir') }}</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', optional($jemaah->birth_date)->format('Y-m-d')) }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Alamat') }}</label>
                        <textarea name="address" rows="2" placeholder="{{ __('Opsional') }}">{{ old('address', $jemaah->address) }}</textarea>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" @click="showEditModal = false" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition">{{ __('Batal') }}</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition">{{ __('Simpan') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>

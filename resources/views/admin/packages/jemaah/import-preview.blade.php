<x-admin-layout :title="__('Preview Import - ' . $package->name)">
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">
            {{ __('Preview Import Excel') }}
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">
            {{ $package->name }}
        </p>
    </x-slot>

    <div class="space-y-4" x-data="{ showDetail: false }">
        <!-- Summary -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 animate-fade-in-up">
            <div class="content-card bg-white dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700 px-4 py-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">{{ __('Baris Ditemukan') }}</p>
                <p class="text-xl font-extrabold text-slate-800 dark:text-white mt-1">{{ $counts['total'] }}</p>
            </div>
            <div class="content-card bg-white dark:bg-slate-800 rounded-xl border border-emerald-100 dark:border-emerald-900/50 px-4 py-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-500">{{ __('Berhasil Dicocokkan') }}</p>
                <p class="text-xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{{ $counts['committable'] }}</p>
            </div>
            <div class="content-card bg-white dark:bg-slate-800 rounded-xl border border-amber-100 dark:border-amber-900/50 px-4 py-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-amber-500">{{ __('Jemaah Baru') }}</p>
                <p class="text-xl font-extrabold text-amber-600 dark:text-amber-400 mt-1">{{ $counts['new_jemaah'] }}</p>
            </div>
            <div class="content-card bg-white dark:bg-slate-800 rounded-xl border border-rose-100 dark:border-rose-900/50 px-4 py-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-rose-500">{{ __('Dilewati') }}</p>
                <p class="text-xl font-extrabold text-rose-600 dark:text-rose-400 mt-1">{{ $counts['duplicate'] + $counts['invalid'] }}</p>
            </div>
        </div>

        <button type="button" @click="showDetail = !showDetail" class="text-sm font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 transition inline-flex items-center gap-1">
            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="showDetail ? 'rotate-180' : ''"></i>
            {{ __('Lihat detail per baris') }}
        </button>

        <div x-show="showDetail" x-cloak class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 bg-slate-50/80 dark:bg-slate-900/50">
                            <th class="px-5 py-3">{{ __('Jemaah') }}</th>
                            <th class="px-5 py-3">{{ __('No. Paspor') }}</th>
                            <th class="px-5 py-3">{{ __('Keterangan') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @foreach ($rows as $row)
                            @php
                                $labels = [
                                    'new_jemaah' => ['Jemaah baru (akan dibuat)', 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400'],
                                    'matched_new_registration' => ['Cocok, registrasi baru', 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400'],
                                    'matched_existing_registration' => ['Cocok, status diperbarui', 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400'],
                                    'duplicate_in_file' => ['Duplikat, dilewati', 'bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400'],
                                    'invalid_passport' => ['Paspor kosong, dilewati', 'bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400'],
                                ];
                                [$label, $color] = $labels[$row['classification']];
                            @endphp
                            <tr>
                                <td class="px-5 py-3 font-semibold text-slate-800 dark:text-white">{{ $row['name'] ?: '—' }}</td>
                                <td class="px-5 py-3 text-slate-500 dark:text-slate-400">{{ $row['passport'] ?: '—' }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-md {{ $color }}">{{ $label }}</span>
                                    @if ($row['warning'])
                                        <span class="text-[11px] text-amber-500 ml-1" title="{{ __('Ada nilai status yang tidak dikenali, dianggap Belum.') }}">
                                            <i data-lucide="alert-circle" class="w-3 h-3 inline"></i>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.packages.jemaah.import.confirm', $package) }}" class="flex gap-3">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <a href="{{ route('admin.packages.jemaah.index', $package) }}" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition">{{ __('Batalkan') }}</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition shadow-lg shadow-blue-500/20">
                {{ __('Import :count Data', ['count' => $counts['committable']]) }}
            </button>
        </form>
    </div>
</x-admin-layout>

<x-admin-layout :title="__('Lead Tracking')">
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">
                {{ __('Lead Tracking') }}
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">
                {{ __('Pantau semua kunjungan izitravel.id/chat dari Media Ads.') }}
            </p>
        </div>
    </x-slot>

    <div class="space-y-4 animate-fade-in">
        {{-- Statistik hari ini --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Lead Hari Ini</p>
                <p class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1 tabular-nums">{{ $statsToday['total'] }}</p>
            </div>
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Campaign Terdeteksi</p>
                <p class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1 tabular-nums">{{ $statsToday['per_campaign']->count() }}</p>
            </div>
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">CS Menerima Lead</p>
                <p class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1 tabular-nums">{{ $statsToday['per_cs']->sum('chat_logs_count') }}</p>
            </div>
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Diarahkan ke CS Utama</p>
                <p class="text-3xl font-extrabold text-amber-500 mt-1 tabular-nums">{{ $statsToday['unassigned'] }}</p>
            </div>
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Hasil Filter</p>
                <p class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1 tabular-nums">{{ $totalFiltered }}</p>
            </div>
        </div>

        {{-- Distribusi per campaign hari ini --}}
        @if ($statsToday['per_campaign']->isNotEmpty())
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3">Distribusi Lead Hari Ini per Campaign</p>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                    @foreach ($statsToday['per_campaign'] as $camp)
                        <div class="rounded-xl bg-slate-50 dark:bg-slate-700/40 border border-slate-100 dark:border-slate-600 p-3">
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate" title="{{ $camp->name }}">{{ $camp->name }}</p>
                            <p class="text-xl font-extrabold text-indigo-600 dark:text-indigo-400 tabular-nums">{{ $camp->chat_logs_count }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Filter form --}}
        <form method="GET" action="{{ route('admin.chat-logs.index') }}" class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            <div class="form-group sm:col-span-1">
                <label for="campaign_id">{{ __('Campaign') }}</label>
                <select id="campaign_id" name="campaign_id">
                    <option value="">Semua Campaign</option>
                    @foreach ($campaigns as $campaign)
                        <option value="{{ $campaign->id }}" @selected($campaignId === $campaign->id)>{{ $campaign->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group sm:col-span-1">
                <label for="cs_id">{{ __('CS') }}</label>
                <select id="cs_id" name="cs_id">
                    <option value="">Semua CS</option>
                    @foreach ($csList as $cs)
                        <option value="{{ $cs->id }}" @selected($csId === $cs->id)>{{ $cs->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group sm:col-span-1">
                <label for="from">{{ __('Dari Tanggal') }}</label>
                <input type="date" id="from" name="from" value="{{ $dateFrom }}">
            </div>
            <div class="form-group sm:col-span-1">
                <label for="to">{{ __('Sampai Tanggal') }}</label>
                <input type="date" id="to" name="to" value="{{ $dateTo }}">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.97] rounded-xl font-bold text-xs text-white transition-all duration-150 shadow-lg shadow-blue-500/20">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    {{ __('Filter') }}
                </button>
                <a href="{{ route('admin.chat-logs.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    Reset
                </a>
            </div>
        </form>

        {{-- Tabel lead --}}
        <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 bg-slate-50/80 dark:bg-slate-900/50 table-header-bg border-b border-slate-100 dark:border-slate-700/50">
                            <th class="px-6 py-4">{{ __('Waktu') }}</th>
                            <th class="px-6 py-4">{{ __('Campaign') }}</th>
                            <th class="px-6 py-4">{{ __('UTM') }}</th>
                            <th class="px-6 py-4">{{ __('CS') }}</th>
                            <th class="px-6 py-4">{{ __('IP') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 dark:text-white text-xs">{{ $log->created_at->format('d M Y') }}</div>
                                    <div class="text-[11px] text-slate-400 font-semibold tabular-nums">{{ $log->created_at->format('H:i:s') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($log->campaign)
                                        <span class="inline-flex items-center gap-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                            {{ $log->campaign->name }}
                                        </span>
                                    @elseif ($log->utm_campaign)
                                        <span class="inline-flex items-center gap-1 bg-slate-50 dark:bg-slate-700/30 text-slate-500 dark:text-slate-400 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                            {{ $log->utm_campaign }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 dark:text-slate-600 text-xs italic">{{ __('Langsung (tanpa campaign)') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @if ($log->utm_source) <span class="font-mono text-[10px] font-semibold text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-900 px-1.5 py-0.5 rounded">src:{{ $log->utm_source }}</span> @endif
                                        @if ($log->utm_medium) <span class="font-mono text-[10px] font-semibold text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-900 px-1.5 py-0.5 rounded">med:{{ $log->utm_medium }}</span> @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($log->cs)
                                        <span class="inline-flex items-center gap-1.5">
                                            <span class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-[10px] font-extrabold">
                                                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($log->cs->name, 0, 1)) }}
                                            </span>
                                            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $log->cs->name }}</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                            CS Utama
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-mono text-[11px] text-slate-400 dark:text-slate-500">{{ $log->ip_address ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-900 flex items-center justify-center mx-auto mb-3 text-slate-400">
                                        <i data-lucide="inbox" class="w-6 h-6"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-500 dark:text-slate-400">{{ __('Belum ada lead pada filter ini.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($logs->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50/30 dark:bg-slate-900/10">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
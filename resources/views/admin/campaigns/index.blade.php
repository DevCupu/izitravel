<x-admin-layout :title="__('Campaign')">
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4 w-full">
            <div><h2 class="text-lg font-bold text-slate-900 dark:text-white">Daftar Campaign</h2><p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">Kelola campaign, ads, template pesan, dan URL tracking Meta Ads.</p></div>
            <a href="{{ route('admin.campaigns.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-xl font-bold text-xs text-white transition shadow-lg shadow-blue-500/20"><i data-lucide="plus" class="w-4 h-4"></i>Tambah Campaign</a>
        </div>
    </x-slot>

    <div class="space-y-4 animate-fade-in">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5 flex items-center gap-4"><div class="w-11 h-11 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-500 flex items-center justify-center"><i data-lucide="megaphone" class="w-5 h-5"></i></div><div><p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Total Campaign</p><p class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ $totalCampaigns }}</p></div></div>
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5 flex items-center gap-4"><div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center"><i data-lucide="radar" class="w-5 h-5"></i></div><div><p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Lead Hari Ini</p><p class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ $leadsToday }}</p></div></div>
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5 flex items-center gap-4"><div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center"><i data-lucide="database" class="w-5 h-5"></i></div><div><p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Total Lead</p><p class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ number_format($totalLeads, 0, ',', '.') }}</p></div></div>
        </div>

        <div class="flex justify-start sm:justify-end">@include('admin.partials._search', ['action' => route('admin.campaigns.index'), 'value' => $search, 'placeholder' => 'Cari campaign atau ads...'])</div>

        <div class="space-y-3">
            @forelse ($campaigns as $campaign)
                <article class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden">
                    <div class="px-5 sm:px-6 py-4 flex items-center gap-4 flex-wrap">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap"><h3 class="text-sm font-extrabold text-slate-900 dark:text-white">{{ $campaign->name }}</h3><span class="text-[9px] font-bold px-2 py-0.5 rounded-md uppercase {{ $campaign->is_active ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-slate-100 dark:bg-slate-700 text-slate-400' }}">{{ $campaign->is_active ? 'Aktif' : 'Nonaktif' }}</span></div>
                            <p class="font-mono text-[11px] font-semibold text-indigo-500 dark:text-indigo-400 mt-1">utm_campaign={{ $campaign->utm_campaign }}</p>
                        </div>
                        <a href="{{ route('admin.chat-logs.index', ['campaign_id' => $campaign->id]) }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-extrabold hover:underline"><i data-lucide="radar" class="w-4 h-4"></i>{{ $campaign->chat_logs_count }} lead</a>
                        <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-bold hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition"><i data-lucide="settings-2" class="w-4 h-4"></i>Kelola Ads</a>
                        <form method="POST" action="{{ route('admin.campaigns.destroy', $campaign) }}">@csrf @method('DELETE')<button type="button" @click="$dispatch('confirm-delete', { form: $el.closest('form'), message: 'Hapus campaign &quot;{{ $campaign->name }}&quot; beserta ads-nya? Lead lama tetap tersimpan.' })" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition" title="Hapus campaign"><i data-lucide="trash-2" class="w-4 h-4"></i></button></form>
                    </div>

                    <div class="border-t border-slate-100 dark:border-slate-700/60 bg-slate-50/60 dark:bg-slate-900/30">
                        @forelse ($campaign->ads as $ad)
                            @php
                                $trackingUrl = url('/chat?' . http_build_query([
                                    'utm_source' => 'meta',
                                    'utm_medium' => 'paid_social',
                                    'utm_campaign' => $campaign->utm_campaign,
                                    'utm_content' => $ad->utm_content,
                                ]));
                            @endphp
                            <div class="px-5 sm:px-6 py-3 flex items-center gap-3 border-b last:border-b-0 border-slate-100 dark:border-slate-700/50" x-data="{ copied: false }">
                                <i data-lucide="badge-ad" class="w-4 h-4 text-indigo-400 shrink-0"></i>
                                <div class="min-w-0 flex-1"><div class="flex items-center gap-2"><p class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate">{{ $ad->name }}</p>@unless ($ad->is_active)<span class="text-[9px] font-bold text-slate-400 uppercase">Nonaktif</span>@endunless</div><code class="block font-mono text-[10px] text-slate-400 truncate mt-0.5" title="{{ $trackingUrl }}">{{ $trackingUrl }}</code></div>
                                <a href="{{ route('admin.chat-logs.index', ['campaign_id' => $campaign->id, 'campaign_ad_id' => $ad->id]) }}" class="text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:underline whitespace-nowrap">{{ $ad->chat_logs_count }} lead</a>
                                <button type="button" data-url="{{ $trackingUrl }}" @click="copyTrackingUrl($el.dataset.url).then(() => { copied = true; setTimeout(() => copied = false, 1600) })" class="p-2 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-white dark:hover:bg-slate-800 transition" :title="copied ? 'Tersalin' : 'Salin link'"><i data-lucide="copy" class="w-4 h-4" x-show="!copied"></i><i data-lucide="check" class="w-4 h-4 text-emerald-500" x-show="copied" x-cloak></i></button>
                                <a href="{{ $trackingUrl }}" target="_blank" rel="noopener" class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-white dark:hover:bg-slate-800 transition" title="Buka link"><i data-lucide="external-link" class="w-4 h-4"></i></a>
                            </div>
                        @empty
                            <div class="px-6 py-4 flex items-center justify-between gap-4"><p class="text-xs text-slate-400">Belum ada ads.</p><a href="{{ route('admin.campaigns.ads.create', $campaign) }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">Tambah ads</a></div>
                        @endforelse
                    </div>
                </article>
            @empty
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 py-12 text-center"><i data-lucide="megaphone" class="w-7 h-7 text-slate-400 mx-auto mb-3"></i><p class="text-sm font-bold text-slate-500 dark:text-slate-400">Belum ada campaign.</p></div>
            @endforelse
        </div>

        @if ($campaigns->hasPages())<div>{{ $campaigns->links() }}</div>@endif
    </div>

    <script>
        window.copyTrackingUrl = function (url) {
            if (navigator.clipboard && (window.isSecureContext || location.hostname === 'localhost')) return navigator.clipboard.writeText(url);
            return new Promise(function (resolve) {
                var input = document.createElement('textarea');
                input.value = url;
                input.setAttribute('readonly', '');
                input.style.position = 'fixed';
                input.style.opacity = '0';
                document.body.appendChild(input);
                input.select();
                try { document.execCommand('copy'); } catch (e) {}
                document.body.removeChild(input);
                resolve();
            });
        };
    </script>
</x-admin-layout>

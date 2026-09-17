<x-admin-layout :title="__('Ubah Campaign')">
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.campaigns.index') }}" class="p-1.5 rounded-lg text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">
                    {{ __('Ubah Campaign') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">
                    {{ __('Perbarui detail kampanye iklan.') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl space-y-5 animate-fade-in-up">
        <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.campaigns.update', $campaign->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                @include('admin.campaigns._form')

                <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.97] rounded-xl font-bold text-sm text-white transition-all duration-150 shadow-lg shadow-blue-500/20">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        {{ __('Simpan Perubahan') }}
                    </button>
                    <a href="{{ route('admin.campaigns.index') }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        {{ __('Batal') }}
                    </a>
                </div>
            </form>
        </div>

        <section class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-5 flex items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-700">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Ads dalam campaign</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Setiap ads memiliki link tracking dan template WhatsApp sendiri.</p>
                </div>
                <a href="{{ route('admin.campaigns.ads.create', $campaign) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.97] rounded-xl font-bold text-xs text-white transition">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Tambah Ads
                </a>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse ($campaign->ads as $ad)
                    @php
                        $trackingUrl = url('/chat?' . http_build_query([
                            'utm_source' => 'meta',
                            'utm_medium' => 'paid_social',
                            'utm_campaign' => $campaign->utm_campaign,
                            'utm_content' => $ad->utm_content,
                        ]));
                    @endphp
                    <div class="px-6 py-4" x-data="{ copied: false }">
                        <div class="flex items-start gap-4 flex-wrap">
                            <div class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-500 flex items-center justify-center shrink-0">
                                <i data-lucide="badge-ad" class="w-4 h-4"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $ad->name }}</p>
                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-md uppercase {{ $ad->is_active ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-slate-100 dark:bg-slate-700 text-slate-400' }}">
                                        {{ $ad->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                    <a href="{{ route('admin.chat-logs.index', ['campaign_id' => $campaign->id, 'campaign_ad_id' => $ad->id]) }}" class="text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:underline">{{ $ad->chat_logs_count }} lead</a>
                                </div>
                                <p class="font-mono text-[11px] text-indigo-500 dark:text-indigo-400 mt-1">utm_content={{ $ad->utm_content }}</p>
                                <code class="block font-mono text-[10px] text-slate-400 mt-2 truncate" title="{{ $trackingUrl }}">{{ $trackingUrl }}</code>
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                <button type="button" data-url="{{ $trackingUrl }}" @click="copyTrackingUrl($el.dataset.url).then(() => { copied = true; setTimeout(() => copied = false, 1600) })" class="p-2 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition" :title="copied ? 'Tersalin' : 'Salin link'">
                                    <i data-lucide="copy" class="w-4 h-4" x-show="!copied"></i>
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-500" x-show="copied" x-cloak></i>
                                </button>
                                <a href="{{ $trackingUrl }}" target="_blank" rel="noopener" class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition" title="Buka link">
                                    <i data-lucide="external-link" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('admin.campaigns.ads.edit', [$campaign, $ad]) }}" class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition" title="Edit ads">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.campaigns.ads.destroy', [$campaign, $ad]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="$dispatch('confirm-delete', { form: $el.closest('form'), message: 'Hapus ads &quot;{{ $ad->name }}&quot;? Lead lama tetap tersimpan.' })" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition" title="Hapus ads">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm font-bold text-slate-500 dark:text-slate-400">Belum ada ads dalam campaign ini.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    <script>
        window.copyTrackingUrl = function (url) {
            if (navigator.clipboard && (window.isSecureContext || location.hostname === 'localhost')) {
                return navigator.clipboard.writeText(url);
            }
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

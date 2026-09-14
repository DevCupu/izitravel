<x-admin-layout :title="__('Campaign')">
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4 w-full">
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">
                    {{ __('Daftar Campaign') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">
                    {{ __('Kelola kampanye Meta Ads dan URL tracking-nya.') }}
                </p>
            </div>
            <a href="{{ route('admin.campaigns.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 active:scale-[0.97] rounded-xl font-bold text-xs text-white transition-all duration-150 shadow-lg shadow-blue-500/20">
                <i data-lucide="plus" class="w-4 h-4"></i>
                {{ __('Tambah Campaign') }}
            </a>
        </div>
    </x-slot>

    <div class="space-y-4 animate-fade-in">

        {{-- Ringkasan --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-500 flex items-center justify-center shrink-0">
                    <i data-lucide="megaphone" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Total Campaign</p>
                    <p class="text-2xl font-extrabold text-slate-900 dark:text-white tabular-nums">{{ $totalCampaigns }}</p>
                </div>
            </div>
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center shrink-0">
                    <i data-lucide="radar" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Lead Hari Ini</p>
                    <p class="text-2xl font-extrabold text-slate-900 dark:text-white tabular-nums">{{ $leadsToday }}</p>
                </div>
            </div>
            <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-5 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center shrink-0">
                    <i data-lucide="database" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Total Lead</p>
                    <p class="text-2xl font-extrabold text-slate-900 dark:text-white tabular-nums">{{ number_format($totalLeads, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="flex justify-start sm:justify-end">
            @include('admin.partials._search', ['action' => route('admin.campaigns.index'), 'value' => $search, 'placeholder' => __('Cari nama atau utm_campaign...')])
        </div>

        {{-- Daftar campaign dalam bentuk kartu --}}
        <div class="space-y-3">
            @forelse ($campaigns as $c)
                @php
                    $trackingUrl = url('/chat?utm_source=meta&utm_medium=paid_social&utm_campaign=' . $c->utm_campaign);
                @endphp
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden">
                    {{-- Badan kartu --}}
                    <div class="px-5 sm:px-6 py-4 flex items-center gap-4 flex-wrap">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-sm font-extrabold text-slate-900 dark:text-white truncate">{{ $c->name }}</h3>
                                @if ($c->is_active)
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                        <i data-lucide="circle-dot" class="w-3 h-3"></i>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-slate-50 dark:bg-slate-700/30 text-slate-400 dark:text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                        Nonaktif
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 flex items-center gap-1.5">
                                <i data-lucide="tag" class="w-3 h-3"></i>
                                <span class="font-mono font-semibold text-indigo-500 dark:text-indigo-400">{{ $c->utm_campaign }}</span>
                            </p>
                        </div>

                        <a href="{{ route('admin.chat-logs.index', ['campaign_id' => $c->id]) }}"
                           class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition group" title="Lihat lead campaign ini">
                            <i data-lucide="radar" class="w-4 h-4 text-blue-500"></i>
                            <span class="text-xs font-extrabold text-blue-600 dark:text-blue-400 tabular-nums group-hover:underline">{{ $c->chat_logs_count }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-500/80 dark:text-blue-400/70">Lead</span>
                        </a>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.campaigns.edit', $c->id) }}" class="p-2 rounded-lg text-slate-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition" title="Edit">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <form x-data method="POST" action="{{ route('admin.campaigns.destroy', $c->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" @click="$dispatch('confirm-delete', { form: $el.closest('form'), message: 'Apakah Anda yakin ingin menghapus campaign ' + @json($c->name) + '? Lead lama tetap tersimpan.' })"
                                        class="p-2 rounded-lg text-slate-400 dark:text-slate-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50/50 dark:hover:bg-red-900/10 transition" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Bar URL tracking --}}
                    <div class="px-5 sm:px-6 py-3 bg-slate-50/70 dark:bg-slate-900/40 border-t border-slate-100 dark:border-slate-700/60">
                        <div class="flex items-end gap-3">
                            <div class="min-w-0 flex-1">
                                <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1 flex items-center gap-1.5">
                                    <i data-lucide="link" class="w-3 h-3"></i>
                                    {{ __('URL Tracking') }}
                                </p>
                                <code class="block max-w-full font-mono text-[11px] text-slate-500 dark:text-slate-400 truncate" title="{{ $trackingUrl }}">{{ $trackingUrl }}</code>
                            </div>
                            <div x-data="{ copied: false }" class="flex items-center gap-1.5 shrink-0">
                                <button type="button"
                                        data-url="{{ $trackingUrl }}"
                                        @click="copyTrackingUrl($el.dataset.url).then(() => { copied = true; setTimeout(() => copied = false, 1600); })"
                                        :class="copied ? 'bg-emerald-50 dark:bg-emerald-900/30 border-emerald-200 dark:border-emerald-800/60 text-emerald-600 dark:text-emerald-400' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:border-blue-300 dark:hover:border-blue-500/50 hover:text-blue-600 dark:hover:text-blue-400'"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border text-[10px] font-bold transition-all duration-150 active:scale-[0.96]" title="Salin URL tracking">
                                    <i data-lucide="copy" class="w-3 h-3" x-show="!copied"></i>
                                    <i data-lucide="check" class="w-3 h-3" x-show="copied" x-cloak></i>
                                    <span x-text="copied ? 'Tersalin' : 'Salin'"></span>
                                </button>
                                <a href="{{ $trackingUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-indigo-300 dark:hover:border-indigo-500/50 transition-all duration-150 text-[10px] font-bold" title="Buka URL tracking">
                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                    {{ __('Buka') }}
                                </button>
                            </div>
                        </div>
                    </div>
                            <div x-data="{ copied: false }" class="flex items-center gap-1.5 shrink-0">
                                <button type="button"
                                        data-url="{{ $trackingUrl }}"
                                        @click="copyTrackingUrl($el.dataset.url).then(() => { copied = true; setTimeout(() => copied = false, 1600); })"
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition inline-flex items-center gap-1.5" title="Salin URL">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                                    <span class="text-[10px] font-bold" x-text="copied ? 'Tersalin' : 'Salin'"></span>
                                </button>
                                <a href="{{ $trackingUrl }}" target="_blank" rel="noopener" class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition inline-flex items-center gap-1.5" title="Buka URL">
                                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                    <span class="text-[10px] font-bold hidden sm:inline">Buka</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="content-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 py-12 text-center">
                    <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-900 flex items-center justify-center mx-auto mb-3 text-slate-400">
                        <i data-lucide="megaphone" class="w-6 h-6"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-500 dark:text-slate-400">{{ __('Belum ada campaign.') }}</p>
                    <a href="{{ route('admin.campaigns.create') }}" class="inline-flex items-center gap-1 text-xs text-blue-600 dark:text-blue-400 font-bold mt-2 hover:underline">
                        {{ __('Tambah Campaign Pertama') }}
                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>
            @endforelse
        </div>

        @if ($campaigns->hasPages())
            <div>{{ $campaigns->links() }}</div>
        @endif
    </div>

    <script>
        window.copyTrackingUrl = function (url) {
            if (navigator.clipboard && (window.isSecureContext || location.hostname === 'localhost')) {
                return navigator.clipboard.writeText(url);
            }
            return new Promise(function (resolve) {
                var ta = document.createElement('textarea');
                ta.value = url;
                ta.setAttribute('readonly', '');
                ta.style.position = 'fixed';
                ta.style.opacity = '0';
                document.body.appendChild(ta);
                ta.select();
                try { document.execCommand('copy'); } catch (e) {}
                document.body.removeChild(ta);
                resolve();
            });
        };
    </script>
</x-admin-layout>
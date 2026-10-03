<x-admin-layout :title="__('Pengaturan Sistem & Layer Server')">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-tight">
                        {{ __('Pengaturan Sistem & Layer Server') }}
                    </h2>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                        System Core
                    </span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 hidden sm:block">
                    {{ __('Kelola konfigurasi mendalam server, mode pemeliharaan, keamanan sistem, SMTP, WhatsApp Gateway, database, dan log sistem.') }}
                </p>
            </div>
            
            <!-- Quick Link to Web Content Settings & Fast Action -->
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.settings.index') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 transition">
                    <i data-lucide="layout" class="w-3.5 h-3.5 text-blue-500"></i>
                    <span>Pengaturan Web (Konten)</span>
                </a>

                <form method="POST" action="{{ route('admin.system.clear-cache') }}" class="inline">
                    @csrf
                    <input type="hidden" name="type" value="all">
                    <button type="submit" 
                            onclick="return confirm('Bersihkan seluruh cache sistem (App, View, Route, Config)?')"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white shadow-sm shadow-blue-500/20 transition">
                        <i data-lucide="zap" class="w-3.5 h-3.5"></i>
                        <span>Bersihkan Cache</span>
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <!-- Top Server Diagnostics Overview Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        <!-- PHP Version -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 p-3.5 shadow-sm">
            <div class="flex items-center justify-between text-slate-400 dark:text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">PHP Version</span>
                <i data-lucide="code" class="w-4 h-4 text-indigo-500"></i>
            </div>
            <p class="text-base font-extrabold text-slate-900 dark:text-white tabular-nums">{{ $diagnostics['php_version'] }}</p>
            <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 truncate">{{ $diagnostics['server_software'] }}</p>
        </div>

        <!-- Laravel Version -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 p-3.5 shadow-sm">
            <div class="flex items-center justify-between text-slate-400 dark:text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Framework</span>
                <i data-lucide="box" class="w-4 h-4 text-red-500"></i>
            </div>
            <p class="text-base font-extrabold text-slate-900 dark:text-white tabular-nums">Laravel {{ $diagnostics['laravel_version'] }}</p>
            <p class="text-[10px] {{ $diagnostics['app_env'] === 'production' ? 'text-emerald-500 font-bold' : 'text-amber-500 font-bold' }} mt-0.5 uppercase tracking-wide">
                {{ $diagnostics['app_env'] }}
            </p>
        </div>

        <!-- Database Engine -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 p-3.5 shadow-sm">
            <div class="flex items-center justify-between text-slate-400 dark:text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Database</span>
                <i data-lucide="database" class="w-4 h-4 text-cyan-500"></i>
            </div>
            <p class="text-base font-extrabold text-slate-900 dark:text-white tabular-nums">{{ $diagnostics['db_size_mb'] }}</p>
            <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">{{ $diagnostics['total_tables'] }} Tabel ({{ strtoupper($diagnostics['db_connection']) }})</p>
        </div>

        <!-- Memory Usage -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 p-3.5 shadow-sm">
            <div class="flex items-center justify-between text-slate-400 dark:text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Memory RAM</span>
                <i data-lucide="activity" class="w-4 h-4 text-emerald-500"></i>
            </div>
            <p class="text-base font-extrabold text-slate-900 dark:text-white tabular-nums">{{ $diagnostics['memory_usage'] }}</p>
            <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Limit: {{ $diagnostics['memory_limit'] }}</p>
        </div>

        <!-- Maintenance Mode Status -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 p-3.5 shadow-sm">
            <div class="flex items-center justify-between text-slate-400 dark:text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Maintenance</span>
                <i data-lucide="shield-alert" class="w-4 h-4 {{ ($settings['maintenance_mode_enabled'] ?? '0') === '1' ? 'text-amber-500' : 'text-slate-400' }}"></i>
            </div>
            <div class="flex items-center gap-1.5 mt-1">
                <span class="w-2 h-2 rounded-full {{ ($settings['maintenance_mode_enabled'] ?? '0') === '1' ? 'bg-amber-500 animate-ping' : 'bg-emerald-500' }}"></span>
                <p class="text-xs font-black {{ ($settings['maintenance_mode_enabled'] ?? '0') === '1' ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                    {{ ($settings['maintenance_mode_enabled'] ?? '0') === '1' ? 'AKTIF (ON)' : 'NORMAL (OFF)' }}
                </p>
            </div>
            <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">
                {{ ($settings['registration_open'] ?? '1') === '1' ? 'Regis Buka' : 'Regis Tutup' }}
            </p>
        </div>

        <!-- Disk Storage -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 p-3.5 shadow-sm">
            <div class="flex items-center justify-between text-slate-400 dark:text-slate-500 mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider">Storage Disk</span>
                <i data-lucide="hard-drive" class="w-4 h-4 text-purple-500"></i>
            </div>
            <p class="text-base font-extrabold text-slate-900 dark:text-white tabular-nums">{{ $diagnostics['disk_free'] }} Free</p>
            <div class="w-full bg-slate-100 dark:bg-slate-700 h-1.5 rounded-full overflow-hidden mt-1.5">
                <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full" style="width: {{ $diagnostics['disk_percent'] }}%"></div>
            </div>
        </div>
    </div>

    <!-- Flash Status Notifications -->
    @if (session('status'))
        <div class="mb-6 rounded-2xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 p-4 text-emerald-800 dark:text-emerald-200 flex items-center gap-3 shadow-sm animate-fade-in-up">
            <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                <i data-lucide="check" class="w-4 h-4"></i>
            </div>
            <div class="flex-1 text-xs font-semibold">
                {{ session('status') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-2xl border border-rose-200 dark:border-rose-800 bg-rose-50 dark:bg-rose-950/40 p-4 text-rose-800 dark:text-rose-200 flex items-center gap-3 shadow-sm animate-fade-in-up">
            <div class="w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
            </div>
            <div class="flex-1 text-xs font-semibold">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-200 dark:border-rose-800 bg-rose-50 dark:bg-rose-950/40 p-4 text-rose-800 dark:text-rose-200 shadow-sm animate-fade-in-up">
            <div class="flex items-center gap-2 font-bold text-xs mb-2">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                <span>Terdapat kesalahan validasi formulir:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $initialTab = session('active_tab') ?? 'diagnostics';
    @endphp

    <div x-data="{
        tab: localStorage.getItem('adminSystemTab') || '{{ $initialTab }}',
        q: '',
        logLoading: false,
        logLevel: 'all',
        logLines: '150',
        logEntries: [],
        logSearch: '',
        logFileSize: '{{ $logInfo['size'] }}',

        async fetchLogs() {
            this.logLoading = true;
            try {
                const res = await fetch('{{ route('admin.system.logs') }}?level=' + this.logLevel + '&lines=' + this.logLines, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                this.logEntries = data.logs || [];
                if (data.file_size) this.logFileSize = data.file_size;
            } catch (err) {
                console.error('Failed to load logs', err);
            } finally {
                this.logLoading = false;
                this.$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); });
            }
        },

        filteredLogs() {
            if (!this.logSearch) return this.logEntries;
            const term = this.logSearch.toLowerCase();
            return this.logEntries.filter(l => 
                (l.message && l.message.toLowerCase().includes(term)) || 
                (l.details && l.details.toLowerCase().includes(term)) ||
                (l.timestamp && l.timestamp.includes(term))
            );
        },

        init() {
            this.$watch('tab', v => {
                localStorage.setItem('adminSystemTab', v);
                if (v === 'logs' && this.logEntries.length === 0) {
                    this.fetchLogs();
                }
            });
            if (this.tab === 'logs') {
                this.fetchLogs();
            }
        }
    }">

        @php
            $systemTabs = [
                'diagnostics' => ['Diagnostik Server & Spesifikasi', 'cpu', 'Spesifikasi server, PHP, memory, disk storage, dan ekstensi aktif.'],
                'maintenance' => ['Mode Pemeliharaan & Akses', 'shield-alert', 'Saklar maintenance mode, kunci rahasia bypass URL, kontak darurat, dan status registrasi.'],
                'performance' => ['Cache, Kinerja & Storage', 'zap', 'Aksi pembersihan cache Artisan, kompresi WebP, dan optimasi produksi.'],
                'database'    => ['Manajemen Database & Backup', 'database', 'Daftar tabel, baris, ukuran data, optimasi database, dan unduh backup SQL.'],
                'logs'        => ['Live Log Viewer & Monitor', 'terminal', 'Pemantau kesalahan server laravel.log langsung secara real-time dengan filter level.'],
            ];
        @endphp

        <!-- Main 2-Column Grid Layout: Sidebar Navigation (Left) & Form / Panel (Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Sidebar Navigation -->
            <div class="lg:col-span-4 space-y-4 lg:sticky lg:top-24">
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 p-3.5 shadow-sm space-y-1.5">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 px-2 py-1 flex items-center justify-between">
                        <span>Layer Sistem Core</span>
                        <i data-lucide="layers" class="w-3.5 h-3.5 text-blue-500"></i>
                    </p>

                    @foreach ($systemTabs as $tabKey => [$tabTitle, $tabIcon, $tabDesc])
                        <button type="button" @click="tab = '{{ $tabKey }}'"
                                :class="tab === '{{ $tabKey }}' 
                                    ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25 font-bold' 
                                    : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700/60 font-semibold'"
                                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs transition-all duration-150 text-left group">
                            <span class="flex items-center gap-2.5 min-w-0">
                                <span :class="tab === '{{ $tabKey }}' ? 'text-white' : 'text-slate-400 dark:text-slate-500 group-hover:text-blue-500'" 
                                      class="shrink-0 flex items-center justify-center transition-colors">
                                    <i data-lucide="{{ $tabIcon }}" class="w-4 h-4"></i>
                                </span>
                                <span class="truncate">{{ $tabTitle }}</span>
                            </span>
                            <span :class="tab === '{{ $tabKey }}' ? 'text-white translate-x-0.5' : 'text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100'" 
                                  class="transition-all shrink-0">
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                            </span>
                        </button>
                    @endforeach
                </div>

                <!-- Info Box -->
                <div class="bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-xs text-slate-600 dark:text-slate-300 shadow-sm">
                    <div class="flex items-center gap-1.5 font-bold text-slate-800 dark:text-slate-200 mb-1">
                        <i data-lucide="info" class="w-4 h-4 text-blue-500 shrink-0"></i>
                        <span>Petunjuk Modul Aktif</span>
                    </div>
                    @foreach ($systemTabs as $tabKey => [$tabTitle, $tabIcon, $tabDesc])
                        <div x-show="tab === '{{ $tabKey }}'" x-cloak class="leading-relaxed">
                            <p class="font-bold text-slate-900 dark:text-white">{{ $tabTitle }}</p>
                            <p class="text-slate-500 dark:text-slate-400 mt-1 text-[11px]">{{ $tabDesc }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Content Panels (Form & Tools) -->
            <div class="lg:col-span-8 space-y-6">

                <!-- ══════════════════════════════════════════════════════ -->
                <!-- TAB 1: DIAGNOSTIK SERVER & SPESIFIKASI               -->
                <!-- ══════════════════════════════════════════════════════ -->
                <div x-show="tab === 'diagnostics'" x-cloak class="space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 p-6 shadow-sm">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-700/60 mb-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                                    <i data-lucide="cpu" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Diagnostik Server & Spesifikasi Runtime</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Informasi lingkungan eksekusi sistem, batas memori, dan ekstensi server.</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Healthy
                            </span>
                        </div>

                        <!-- System Specs Table -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">Sistem Operasi Server:</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $diagnostics['os'] }}</span>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">PHP Version & SAPI:</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $diagnostics['php_version'] }} ({{ php_sapi_name() }})</span>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">Laravel Framework:</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">v{{ $diagnostics['laravel_version'] }} (Env: {{ $diagnostics['app_env'] }})</span>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">Waktu Server & Zona Waktu:</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $diagnostics['server_time'] }} ({{ $diagnostics['timezone'] }})</span>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">Batas Memori RAM (memory_limit):</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $diagnostics['memory_limit'] }} (Terpakai: {{ $diagnostics['memory_usage'] }})</span>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">Maksimal Waktu Eksekusi:</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $diagnostics['max_execution_time'] }}</span>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">Batas Unggah File (upload_max_filesize):</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $diagnostics['upload_max_filesize'] }} (Post Max: {{ $diagnostics['post_max_size'] }})</span>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">Koneksi Database Aktif:</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ strtoupper($diagnostics['db_connection']) }} ({{ $diagnostics['db_name'] }})</span>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">Cache Driver / Session Driver:</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $diagnostics['cache_driver'] }} / {{ $diagnostics['session_driver'] }}</span>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-400 block mb-0.5">Mail Driver / Queue Driver:</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $diagnostics['mail_driver'] }} / {{ $diagnostics['queue_driver'] }}</span>
                            </div>
                        </div>

                        <!-- Extensions Checklist -->
                        <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-700/60">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-2">
                                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500"></i>
                                Status Ekstensi Kritis PHP Server
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                @foreach ($diagnostics['php_extensions'] as $extName => $isLoaded)
                                    <div class="flex items-center gap-2 p-2 rounded-lg {{ $isLoaded ? 'bg-emerald-50/70 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-900/40' : 'bg-rose-50 text-rose-700 border border-rose-200' }} text-xs">
                                        <i data-lucide="{{ $isLoaded ? 'check' : 'x' }}" class="w-3.5 h-3.5 shrink-0"></i>
                                        <span class="font-bold uppercase tracking-wider text-[11px]">{{ $extName }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ══════════════════════════════════════════════════════ -->
                <!-- FORM WRAPPER FOR TABS 2 TO 7                         -->
                <!-- ══════════════════════════════════════════════════════ -->
                <form method="POST" action="{{ route('admin.system.update') }}" id="systemForm" class="space-y-6">
                    @csrf
                    <input type="hidden" name="active_tab" :value="tab">

                    <!-- ══════════════════════════════════════════════════ -->
                    <!-- TAB 2: MODE PEMELIHARAAN & AKSES SISTEM           -->
                    <!-- ══════════════════════════════════════════════════ -->
                    <div x-show="tab === 'maintenance'" x-cloak class="space-y-6">
                        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 p-6 shadow-sm">
                            <div class="flex items-center gap-3 pb-4 border-b border-slate-100 dark:border-slate-700/60 mb-5">
                                <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                                    <i data-lucide="shield-alert" class="w-5 h-5"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between flex-wrap gap-2">
                                        <div>
                                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Mode Pemeliharaan (Maintenance Mode)</h3>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Kendalikan akses publik saat sistem sedang mengalami update atau perbaikan.</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.system.preview-maintenance') }}" target="_blank"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-700 dark:text-amber-400 border border-amber-300 dark:border-amber-700/60 text-xs font-bold transition">
                                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                                <span>Pratinjau Halaman Maintenance</span>
                                            </a>
                                            <a href="{{ url('/') }}" target="_blank"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-semibold transition">
                                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                                <span>Buka Web Publik</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Maintenance Toggle Switch -->
                            <div class="p-4 rounded-2xl bg-amber-50/70 dark:bg-amber-950/30 border border-amber-200/80 dark:border-amber-800/50 mb-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="space-y-1">
                                        <label for="maintenance_mode_enabled" class="text-sm font-black text-amber-900 dark:text-amber-200 flex items-center gap-2 cursor-pointer">
                                            <span>Aktifkan Mode Pemeliharaan Website</span>
                                            <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold bg-amber-200 dark:bg-amber-900 text-amber-800 dark:text-amber-200">HTTP 503</span>
                                        </label>
                                        <p class="text-xs text-amber-800/80 dark:text-amber-300/80 leading-relaxed">
                                            Jika dicentang, seluruh pengunjung publik akan dialihkan ke halaman pemeliharaan khusus. <b>Dashboard admin (/admin) selalu tetap dapat diakses.</b>
                                        </p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-1">
                                        <input type="checkbox" id="maintenance_mode_enabled" name="maintenance_mode_enabled" value="1" 
                                               {{ ($settings['maintenance_mode_enabled'] ?? '0') === '1' ? 'checked' : '' }} 
                                               class="sr-only peer">
                                        <div class="w-12 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                                    </label>
                                </div>

                                <!-- Sub-toggle: Admin Bypass Frontend -->
                                <div class="mt-4 pt-3.5 border-t border-amber-200/60 dark:border-amber-800/40 flex items-start justify-between gap-4">
                                    <div>
                                        <label for="maintenance_bypass_admin" class="text-xs font-bold text-amber-950 dark:text-amber-200 flex items-center gap-1.5 cursor-pointer">
                                            <i data-lucide="shield" class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400"></i>
                                            <span>Bypass untuk Admin: Izinkan Admin Login Tetap Melihat Website Normal</span>
                                        </label>
                                        <p class="text-[11px] text-amber-800/70 dark:text-amber-300/70 mt-0.5">
                                            Jika <b>tidak dicentang</b>, Anda (meski sudah login admin) akan melihat halaman maintenance saat membuka web publik agar bisa menguji tampilannya. Jika dicentang, admin otomatis tembus ke website normal.
                                        </p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-0.5">
                                        <input type="checkbox" id="maintenance_bypass_admin" name="maintenance_bypass_admin" value="1" 
                                               {{ ($settings['maintenance_bypass_admin'] ?? '0') === '1' ? 'checked' : '' }} 
                                               class="sr-only peer">
                                        <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-amber-600"></div>
                                    </label>
                                </div>
                            </div>

                            <div class="space-y-4 text-xs">
                                <div>
                                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                                        Kunci Rahasia Bypass URL (Secret Key)
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <div class="relative flex-1">
                                            <input type="text" name="maintenance_bypass_key" id="maintenance_bypass_key"
                                                   value="{{ old('maintenance_bypass_key', $settings['maintenance_bypass_key'] ?? 'bypass-secret-izi') }}"
                                                   placeholder="contoh: bypass-secret-izi"
                                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-mono">
                                        </div>
                                    </div>
                                    <p class="text-[11px] text-slate-400 mt-1">
                                        Gunakan link rahasia: <code class="text-blue-500 font-mono">{{ url('/') }}?bypass={{ $settings['maintenance_bypass_key'] ?? 'bypass-secret-izi' }}</code> untuk membuka akses sementara tanpa login.
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Judul Halaman Pemeliharaan</label>
                                        <input type="text" name="maintenance_title" 
                                               value="{{ old('maintenance_title', $settings['maintenance_title'] ?? 'Sistem Sedang Dalam Pemeliharaan Terjadwal') }}"
                                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs">
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Estimasi Jadwal Selesai</label>
                                        <input type="datetime-local" name="maintenance_estimated_end" 
                                               value="{{ old('maintenance_estimated_end', $settings['maintenance_estimated_end'] ?? '') }}"
                                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs">
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Pesan Penjelasan Pemeliharaan</label>
                                    <textarea name="maintenance_message" rows="3" 
                                              class="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs leading-relaxed">{{ old('maintenance_message', $settings['maintenance_message'] ?? 'Kami sedang melakukan peningkatan infrastruktur server berkala untuk memberikan pengalaman ibadah umrah yang lebih cepat dan aman.') }}</textarea>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Nomor WhatsApp Darurat Saat Pemeliharaan</label>
                                        <input type="text" name="maintenance_emergency_contact" 
                                               value="{{ old('maintenance_emergency_contact', $settings['maintenance_emergency_contact'] ?? $settings['contact_whatsapp'] ?? '') }}"
                                               placeholder="contoh: 08123456789"
                                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs">
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                                            Whitelist IP Bebas Maintenance
                                        </label>
                                        <textarea name="maintenance_allowed_ips" rows="2" 
                                                  placeholder="127.0.0.1&#10;192.168.1.1"
                                                  class="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-mono">{{ old('maintenance_allowed_ips', $settings['maintenance_allowed_ips'] ?? '') }}</textarea>
                                        <p class="text-[10px] text-slate-400 mt-1">IP Anda saat ini: <code class="text-blue-500 font-mono">{{ request()->ip() }}</code></p>
                                    </div>
                                </div>

                                <!-- Registration Open/Closed Switch -->
                                <div class="pt-4 border-t border-slate-100 dark:border-slate-700/60">
                                    <div class="flex items-start justify-between gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                                        <div>
                                            <label for="registration_open" class="text-xs font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 cursor-pointer">
                                                <span>Status Pendaftaran Jemaah Online</span>
                                                <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold {{ ($settings['registration_open'] ?? '1') === '1' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-200' : 'bg-rose-100 text-rose-800' }}">
                                                    {{ ($settings['registration_open'] ?? '1') === '1' ? 'DIBUKA' : 'DITUTUP SEMENTARA' }}
                                                </span>
                                            </label>
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                                Jika dinonaktifkan, formulir pendaftaran jemaah baru akan menampilkan pesan tutup kuota / tutup jadwal.
                                            </p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-0.5">
                                            <input type="checkbox" id="registration_open" name="registration_open" value="1" 
                                                   {{ ($settings['registration_open'] ?? '1') === '1' ? 'checked' : '' }} 
                                                   class="sr-only peer">
                                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>

                                    <div class="mt-3">
                                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Pesan Saat Pendaftaran Ditutup</label>
                                        <input type="text" name="registration_closed_message" 
                                               value="{{ old('registration_closed_message', $settings['registration_closed_message'] ?? 'Pendaftaran online sementara ditutup untuk penyesuaian kuota seat. Silakan hubungi Customer Service kami via WhatsApp.') }}"
                                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ══════════════════════════════════════════════════ -->
                    <!-- TAB 3: CACHE, KINERJA & STORAGE                   -->
                    <!-- ══════════════════════════════════════════════════ -->
                    <div x-show="tab === 'performance'" x-cloak class="space-y-6">
                        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 p-6 shadow-sm">
                            <div class="flex items-center gap-3 pb-4 border-b border-slate-100 dark:border-slate-700/60 mb-5">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                    <i data-lucide="zap" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Aksi Pembersihan Cache & Optimasi</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Jalankan perintah maintenance Artisan secara instan dari panel admin.</p>
                                </div>
                            </div>

                            <!-- Artisan Action Buttons Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
                                <!-- Clear All Cache -->
                                <button type="submit" form="clearAllCacheForm"
                                        class="p-4 rounded-xl border border-blue-200 dark:border-blue-900/50 bg-blue-50/60 dark:bg-blue-950/20 hover:bg-blue-100/60 dark:hover:bg-blue-900/30 text-left transition group">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center">
                                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                        </span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Semua Cache</span>
                                    </div>
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white">Bersihkan Semua Cache</h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Flush Cache Aplikasi, View Blade, Rute, & Config sekaligus.</p>
                                </button>

                                <!-- Clear View Cache -->
                                <button type="submit" form="clearViewCacheForm"
                                        class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 bg-white dark:bg-slate-800 text-left transition group">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 flex items-center justify-center">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">View Blade</span>
                                    </div>
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white">Bersihkan Cache View</h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Kompilasi ulang seluruh file template Blade di storage.</p>
                                </button>

                                <!-- Clear Route Cache -->
                                <button type="submit" form="clearRouteCacheForm"
                                        class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 bg-white dark:bg-slate-800 text-left transition group">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 flex items-center justify-center">
                                            <i data-lucide="compass" class="w-4 h-4"></i>
                                        </span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Routes</span>
                                    </div>
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white">Bersihkan Cache Rute</h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Perbarui pemetaan URL web.php jika ada rute baru.</p>
                                </button>

                                <!-- Clear Config Cache -->
                                <button type="submit" form="clearConfigCacheForm"
                                        class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 bg-white dark:bg-slate-800 text-left transition group">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 flex items-center justify-center">
                                            <i data-lucide="settings" class="w-4 h-4"></i>
                                        </span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Config</span>
                                    </div>
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white">Bersihkan Cache Config</h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Muat ulang seluruh konfigurasi file .env dan config/.</p>
                                </button>

                                <!-- Optimize Production -->
                                <button type="submit" form="optimizeCacheForm"
                                        class="p-4 rounded-xl border border-emerald-200 dark:border-emerald-900/50 bg-emerald-50/60 dark:bg-emerald-950/20 hover:bg-emerald-100/60 dark:hover:bg-emerald-900/30 text-left transition group">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center">
                                            <i data-lucide="zap" class="w-4 h-4"></i>
                                        </span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Optimize</span>
                                    </div>
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white">Optimasi Produksi</h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Cache config & routes untuk kecepatan maksimal server.</p>
                                </button>

                                <!-- Storage Symlink -->
                                <button type="submit" form="storageLinkCacheForm"
                                        class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 bg-white dark:bg-slate-800 text-left transition group">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 flex items-center justify-center">
                                            <i data-lucide="link" class="w-4 h-4"></i>
                                        </span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Symlink</span>
                                    </div>
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white">Perbaiki Storage Symlink</h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Pastikan folder public/storage terhubung ke storage/app/public.</p>
                                </button>
                            </div>

                            <!-- Performance Tuning Settings -->
                            <div class="pt-5 border-t border-slate-100 dark:border-slate-700/60 space-y-4 text-xs">
                                <h4 class="font-bold uppercase tracking-wider text-slate-400 text-[11px]">Parameter Performa & Kompresi</h4>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                                            Durasi Cache Query & Settings (Detik)
                                        </label>
                                        <input type="number" name="cache_lifetime_seconds" 
                                               value="{{ old('cache_lifetime_seconds', $settings['cache_lifetime_seconds'] ?? '86400') }}"
                                               placeholder="86400 (24 jam)"
                                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs">
                                        <p class="text-[10px] text-slate-400 mt-1">Standar: 86400 detik (24 jam). Di-refresh otomatis saat ada data disimpan.</p>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                                            Kualitas Kompresi Otomatis Gambar WebP (%)
                                        </label>
                                        <input type="number" name="image_optimization_quality" min="40" max="100"
                                               value="{{ old('image_optimization_quality', $settings['image_optimization_quality'] ?? '85') }}"
                                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs">
                                        <p class="text-[10px] text-slate-400 mt-1">Disarankan 80-85% untuk keseimbangan terbaik antara ketajaman gambar dan ukuran file kecil.</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                                            Versi Asset / Cache Buster
                                        </label>
                                        <input type="text" name="asset_version" 
                                               value="{{ old('asset_version', $settings['asset_version'] ?? '1.0.1') }}"
                                               placeholder="contoh: 1.0.2"
                                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs">
                                    </div>

                                    <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                                        <div>
                                            <label for="minify_html_enabled" class="font-bold text-slate-800 dark:text-slate-200 block cursor-pointer">
                                                Minify Output HTML Halaman
                                            </label>
                                            <p class="text-[10px] text-slate-400">Kompresi spasi dan baris kosong HTML untuk memangkas bandwidth jaringan.</p>
                                        </div>
                                        <input type="checkbox" id="minify_html_enabled" name="minify_html_enabled" value="1"
                                               {{ ($settings['minify_html_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                                               class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Bottom Fixed / Sticky Save Actions Bar -->
                    <div class="sticky bottom-4 z-20 flex items-center justify-between p-4 rounded-2xl bg-white/95 dark:bg-slate-800/95 backdrop-blur-md border border-slate-200 dark:border-slate-700 shadow-xl">
                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                            <i data-lucide="shield-check" class="w-4 h-4 text-emerald-500"></i>
                            <span class="hidden sm:inline">Perubahan sistem akan langsung disimpan ke database & cache memo.</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="reset" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                                Reset
                            </button>

                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs shadow-md shadow-blue-500/25 transition-all flex items-center gap-2">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                <span>Simpan Seluruh Pengaturan Sistem</span>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- ══════════════════════════════════════════════════════ -->
                <!-- TAB 8: LIVE SYSTEM LOG VIEWER & MONITOR               -->
                <!-- ══════════════════════════════════════════════════ -->
                <div x-show="tab === 'logs'" x-cloak class="space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 p-6 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-100 dark:border-slate-700/60 mb-5 gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-900 text-emerald-400 flex items-center justify-center font-mono font-bold shadow-md">
                                    <i data-lucide="terminal" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Live System Logs & Error Monitor</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        Membaca file <code class="text-blue-500 font-mono">storage/logs/laravel.log</code> secara instan (Ukuran: <span class="font-bold tabular-nums" x-text="logFileSize"></span>).
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="button" @click="fetchLogs()" :disabled="logLoading"
                                        class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-semibold flex items-center gap-1.5 transition">
                                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5" :class="logLoading ? 'animate-spin' : ''"></i>
                                    <span>Muat Ulang Log</span>
                                </button>

                                <form method="POST" action="{{ route('admin.system.clear-logs') }}" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan seluruh file laravel.log?')" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/40 text-rose-600 dark:text-rose-400 text-xs font-bold transition flex items-center gap-1.5">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        <span>Kosongkan Log</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Log Filters -->
                        <div class="flex flex-wrap items-center gap-3 mb-4 text-xs">
                            <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-900 p-1 rounded-xl">
                                <button type="button" @click="logLevel = 'all'; fetchLogs()" 
                                        :class="logLevel === 'all' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-bold shadow-sm' : 'text-slate-500'"
                                        class="px-2.5 py-1 rounded-lg transition">Semua</button>
                                <button type="button" @click="logLevel = 'error'; fetchLogs()" 
                                        :class="logLevel === 'error' ? 'bg-rose-600 text-white font-bold shadow-sm' : 'text-rose-600 dark:text-rose-400'"
                                        class="px-2.5 py-1 rounded-lg transition">ERROR</button>
                                <button type="button" @click="logLevel = 'warning'; fetchLogs()" 
                                        :class="logLevel === 'warning' ? 'bg-amber-500 text-slate-950 font-bold shadow-sm' : 'text-amber-600 dark:text-amber-400'"
                                        class="px-2.5 py-1 rounded-lg transition">WARNING</button>
                                <button type="button" @click="logLevel = 'info'; fetchLogs()" 
                                        :class="logLevel === 'info' ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-blue-600 dark:text-blue-400'"
                                        class="px-2.5 py-1 rounded-lg transition">INFO</button>
                            </div>

                            <div class="relative flex-1 min-w-[200px]">
                                <i data-lucide="search" class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" x-model="logSearch" placeholder="Cari pesan kesalahan di log..." 
                                       class="w-full pl-9 pr-3.5 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs">
                            </div>

                            <select x-model="logLines" @change="fetchLogs()" class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs">
                                <option value="50">50 Baris</option>
                                <option value="150">150 Baris</option>
                                <option value="300">300 Baris</option>
                            </select>
                        </div>

                        <!-- Console Log Viewer Container -->
                        <div class="rounded-2xl bg-slate-950 text-slate-200 border border-slate-800 p-4 font-mono text-xs max-h-[550px] overflow-y-auto space-y-2.5 shadow-inner">
                            <template x-if="logLoading">
                                <div class="py-12 text-center text-slate-500 flex items-center justify-center gap-2">
                                    <i data-lucide="loader-2" class="w-4 h-4 animate-spin text-blue-500"></i>
                                    <span>Membaca log dari server...</span>
                                </div>
                            </template>

                            <template x-if="!logLoading && filteredLogs().length === 0">
                                <div class="py-12 text-center text-slate-500">
                                    <i data-lucide="check-circle" class="w-8 h-8 mx-auto mb-2 text-emerald-500"></i>
                                    <p class="font-bold text-slate-400">Tidak ada log error ditemukan.</p>
                                    <p class="text-[11px] text-slate-600 mt-0.5">Sistem berjalan dengan bersih dan tidak ada catatan kesalahan pada level ini.</p>
                                </div>
                            </template>

                            <template x-for="(entry, idx) in filteredLogs()" :key="idx">
                                <div class="p-3 rounded-xl border border-slate-800/80 bg-slate-900/60 hover:bg-slate-900 transition text-[11px] leading-relaxed">
                                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider"
                                              :class="{
                                                  'bg-rose-500/20 text-rose-400 border border-rose-500/30': entry.level === 'error' || entry.level === 'emergency' || entry.level === 'critical',
                                                  'bg-amber-500/20 text-amber-400 border border-amber-500/30': entry.level === 'warning',
                                                  'bg-blue-500/20 text-blue-400 border border-blue-500/30': entry.level === 'info',
                                                  'bg-slate-700/40 text-slate-400': entry.level === 'debug'
                                              }" x-text="entry.level.toUpperCase()"></span>
                                        <span class="text-slate-500 tabular-nums" x-text="entry.timestamp"></span>
                                        <span class="text-slate-600">|</span>
                                        <span class="text-slate-400 font-semibold" x-text="entry.environment"></span>
                                    </div>
                                    <p class="text-slate-200 font-semibold break-words" x-text="entry.message"></p>
                                    <template x-if="entry.details">
                                        <pre class="mt-2 p-2 rounded bg-slate-950/80 text-slate-400 text-[10px] overflow-x-auto whitespace-pre-wrap max-h-36 border border-slate-800/60" x-text="entry.details"></pre>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- ══════════════════════════════════════════════════ -->
                <!-- TAB 9: MANAJEMEN DATABASE & BACKUP SQL            -->
                <!-- ══════════════════════════════════════════════════ -->
                <div x-show="tab === 'database'" x-cloak class="space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 p-6 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-100 dark:border-slate-700/60 mb-5 gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-cyan-50 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 flex items-center justify-center">
                                    <i data-lucide="database" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Manajemen Database & Backup Cadangan</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Unduh snapshot SQL database secara utuh dan jalankan optimasi tabel MySQL.</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <!-- Download Backup SQL Button -->
                                <a href="{{ route('admin.system.backup-db') }}" 
                                   class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition flex items-center gap-2">
                                    <i data-lucide="download" class="w-4 h-4"></i>
                                    <span>Download Backup SQL</span>
                                </a>

                                <!-- Optimize DB Form -->
                                <form method="POST" action="{{ route('admin.system.optimize-db') }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('Jalankan OPTIMIZE TABLE pada seluruh tabel database?')"
                                            class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-semibold transition flex items-center gap-1.5">
                                        <i data-lucide="wrench" class="w-3.5 h-3.5"></i>
                                        <span>Optimasi Tabel</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Database Overview Statistics -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6 text-xs">
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-400 block text-[11px]">Database Name:</span>
                                <span class="font-extrabold text-slate-900 dark:text-white font-mono">{{ $diagnostics['db_name'] }}</span>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-400 block text-[11px]">Total Ukuran Data:</span>
                                <span class="font-extrabold text-slate-900 dark:text-white tabular-nums">{{ $diagnostics['db_size_mb'] }}</span>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-400 block text-[11px]">Total Tabel:</span>
                                <span class="font-extrabold text-slate-900 dark:text-white tabular-nums">{{ count($databaseTables) }} Tabel</span>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                                <span class="text-slate-400 block text-[11px]">Total Baris Record:</span>
                                <span class="font-extrabold text-slate-900 dark:text-white tabular-nums">
                                    {{ number_format(collect($databaseTables)->sum('rows'), 0, ',', '.') }} Records
                                </span>
                            </div>
                        </div>

                        <!-- Database Tables Table -->
                        <div class="border border-slate-100 dark:border-slate-700/80 rounded-2xl overflow-hidden shadow-sm">
                            <div class="max-h-[420px] overflow-y-auto">
                                <table class="w-full text-xs text-left">
                                    <thead class="bg-slate-50 dark:bg-slate-900/80 text-slate-500 font-bold uppercase tracking-wider sticky top-0 text-[10px]">
                                        <tr>
                                            <th class="py-3 px-4">Nama Tabel</th>
                                            <th class="py-3 px-4">Engine</th>
                                            <th class="py-3 px-4 text-right">Jumlah Baris</th>
                                            <th class="py-3 px-4 text-right">Ukuran Data</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 font-mono text-[11px]">
                                        @forelse ($databaseTables as $table)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition">
                                                <td class="py-2.5 px-4 font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                                    <i data-lucide="table" class="w-3.5 h-3.5 text-blue-500 shrink-0"></i>
                                                    <span>{{ $table['name'] }}</span>
                                                </td>
                                                <td class="py-2.5 px-4 text-slate-500 dark:text-slate-400">{{ $table['engine'] }}</td>
                                                <td class="py-2.5 px-4 text-right font-semibold text-slate-800 dark:text-slate-200 tabular-nums">
                                                    {{ number_format($table['rows'], 0, ',', '.') }}
                                                </td>
                                                <td class="py-2.5 px-4 text-right font-semibold text-slate-800 dark:text-slate-200 tabular-nums">
                                                    {{ $table['size_kb'] > 1024 ? round($table['size_kb'] / 1024, 2) . ' MB' : $table['size_kb'] . ' KB' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="py-6 text-center text-slate-400 font-sans">Tidak ada tabel terdeteksi.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Hidden Artisan Cache Forms for Individual Actions -->
    <form id="clearAllCacheForm" method="POST" action="{{ route('admin.system.clear-cache') }}" class="hidden">
        @csrf
        <input type="hidden" name="type" value="all">
    </form>
    <form id="clearViewCacheForm" method="POST" action="{{ route('admin.system.clear-cache') }}" class="hidden">
        @csrf
        <input type="hidden" name="type" value="view">
    </form>
    <form id="clearRouteCacheForm" method="POST" action="{{ route('admin.system.clear-cache') }}" class="hidden">
        @csrf
        <input type="hidden" name="type" value="route">
    </form>
    <form id="clearConfigCacheForm" method="POST" action="{{ route('admin.system.clear-cache') }}" class="hidden">
        @csrf
        <input type="hidden" name="type" value="config">
    </form>
    <form id="optimizeCacheForm" method="POST" action="{{ route('admin.system.clear-cache') }}" class="hidden">
        @csrf
        <input type="hidden" name="type" value="optimize">
    </form>
    <form id="storageLinkCacheForm" method="POST" action="{{ route('admin.system.clear-cache') }}" class="hidden">
        @csrf
        <input type="hidden" name="type" value="storage_link">
    </form>
</x-admin-layout>

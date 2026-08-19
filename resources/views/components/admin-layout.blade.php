<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Admin' }} - {{ config('app.name', 'IZI Travel') }}</title>

        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] { display: none !important; }

            /* ── Lucide Icon Safeguard (Fix Sizing & Prevent Overlapping) ── */
            svg.lucide, i[data-lucide], [data-lucide] svg {
                display: inline-block !important;
                vertical-align: middle !important;
                flex-shrink: 0 !important;
                max-width: 100% !important;
            }
            svg.lucide.w-3, i[data-lucide].w-3 { width: 0.75rem !important; height: 0.75rem !important; }
            svg.lucide.w-3\.5, i[data-lucide].w-3\.5 { width: 0.875rem !important; height: 0.875rem !important; }
            svg.lucide.w-4, i[data-lucide].w-4 { width: 1rem !important; height: 1rem !important; }
            svg.lucide.w-5, i[data-lucide].w-5 { width: 1.25rem !important; height: 1.25rem !important; }
            svg.lucide.w-6, i[data-lucide].w-6 { width: 1.5rem !important; height: 1.5rem !important; }
            svg.lucide.w-7, i[data-lucide].w-7 { width: 1.75rem !important; height: 1.75rem !important; }
            svg.lucide.w-8, i[data-lucide].w-8 { width: 2rem !important; height: 2rem !important; }
            svg.lucide.w-\[18px\], i[data-lucide].w-\[18px\] { width: 18px !important; height: 18px !important; }

            /* ── Base Typography ── */
            body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
            h1, h2, h3, .font-islamic { font-family: 'El Messiri', 'Plus Jakarta Sans', sans-serif; }

            /* ── Scrollbar ── */
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 9999px; }
            .dark ::-webkit-scrollbar-thumb { background-color: #475569; }

            /* ── Sidebar gradient accent ── */
            .sidebar-accent {
                position: absolute;
                top: 0; right: 0; bottom: 0;
                width: 2px;
                background: linear-gradient(180deg, #3b82f6, #6366f1, #8b5cf6);
                opacity: 0.6;
            }

            /* ── Nav item active indicator ── */
            .nav-active {
                background: linear-gradient(135deg, rgba(59,130,246,0.15), rgba(99,102,241,0.1));
                color: #60a5fa;
                position: relative;
            }
            .nav-active::before {
                content: '';
                position: absolute;
                left: 0; top: 50%;
                transform: translateY(-50%);
                width: 3px; height: 60%;
                border-radius: 0 4px 4px 0;
                background: linear-gradient(180deg, #3b82f6, #818cf8);
                pointer-events: none;
            }

            /* ── Prevent pointer events on sidebar child elements ── */
            aside nav a * {
                pointer-events: none;
            }

            /* ── Sidebar collapse (desktop) ── */
            aside.sidebar-bg { transition: width 0.25s ease; }
            .sidebar-logo-full { transition: height 0.25s ease; }
            aside.is-collapsed { width: 5rem !important; }
            aside.is-collapsed .nav-label,
            aside.is-collapsed .nav-badge,
            aside.is-collapsed .nav-section-label,
            aside.is-collapsed .sidebar-user-info,
            aside.is-collapsed .sidebar-user-menu {
                display: none !important;
            }
            aside.is-collapsed nav a { justify-content: center; }
            aside.is-collapsed .sidebar-logo-bar { justify-content: center; padding-left: 0; padding-right: 0; }
            aside.is-collapsed .sidebar-footer-inner { justify-content: center; }
            aside.is-collapsed .sidebar-logo-full { height: 1.5rem; }

            /* ── Dark mode ── */
            .dark body, .dark .admin-body { background: #0f172a; color: #e2e8f0; }
            .dark .sidebar-bg { background: #0c1222; border-color: rgba(255,255,255,0.05); }
            .dark .topbar-bg { background: rgba(15,23,42,0.85); border-color: rgba(255,255,255,0.05); }
            .dark .content-card { background: #1e293b; border-color: #334155; }
            .dark .content-card-alt { background: #1e293b; }
            .dark .text-invert { color: #e2e8f0; }
            .dark .text-muted-invert { color: #94a3b8; }
            .dark .table-header-bg { background: #1e293b; }
            /* ── Premium Forms (Sharp & Official) ── */
            input[type="text"],
            input[type="number"],
            input[type="date"],
            input[type="email"],
            input[type="password"],
            select,
            textarea {
                width: 100%;
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
                background-color: #ffffff;
                border: 1.5px solid #cbd5e1 !important; /* Sharper border for light mode */
                border-radius: 0.75rem; /* 12px */
                color: #0f172a;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.02);
            }
            input[type="text"]:focus,
            input[type="number"]:focus,
            input[type="date"]:focus,
            input[type="email"]:focus,
            input[type="password"]:focus,
            select:focus,
            textarea:focus {
                outline: none;
                border-color: #2563eb !important; /* Bold royal blue focus */
                box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
                transform: translateY(-0.5px);
            }
            input[type="text"]:hover,
            input[type="number"]:hover,
            input[type="date"]:hover,
            input[type="email"]:hover,
            input[type="password"]:hover,
            select:hover,
            textarea:hover {
                border-color: #94a3b8 !important; /* Slate-400 hover border */
            }
            .dark input[type="text"],
            .dark input[type="number"],
            .dark input[type="date"],
            .dark input[type="email"],
            .dark input[type="password"],
            .dark select,
            .dark textarea {
                background-color: #1e293b !important;
                border: 1.5px solid #475569 !important; /* Sharper dark mode border */
                color: #f8fafc !important;
            }
            .dark input[type="text"]:focus,
            .dark input[type="number"]:focus,
            .dark input[type="date"]:focus,
            .dark input[type="email"]:focus,
            .dark input[type="password"]:focus,
            .dark select:focus,
            .dark textarea:focus {
                border-color: #3b82f6 !important;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25) !important;
            }
            .dark input[type="text"]:hover,
            .dark input[type="number"]:hover,
            .dark input[type="date"]:hover,
            .dark input[type="email"]:hover,
            .dark input[type="password"]:hover,
            .dark select:hover,
            .dark textarea:hover {
                border-color: #64748b !important; /* Slate-500 hover border dark */
            }
            .form-group label {
                font-weight: 700;
                letter-spacing: 0.05em;
                text-transform: uppercase;
                font-size: 0.6875rem; /* 11px */
                color: #64748b;
                margin-bottom: 0.5rem;
                display: block;
                transition: color 0.2s ease;
            }
            .dark .form-group label {
                color: #94a3b8;
            }
            .form-group:focus-within label {
                color: #3b82f6;
            }
            .dark .form-group:focus-within label {
                color: #60a5fa;
            }

            /* ── Animations ── */
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(16px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes slideInRight {
                from { opacity: 0; transform: translateX(24px); }
                to { opacity: 1; transform: translateX(0); }
            }
            @keyframes slideInLeft {
                from { opacity: 0; transform: translateX(-16px); }
                to { opacity: 1; transform: translateX(0); }
            }
            @keyframes countUp {
                from { opacity: 0; transform: translateY(8px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes pulse-soft {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.6; }
            }
            @keyframes shimmer {
                0% { background-position: -200% 0; }
                100% { background-position: 200% 0; }
            }
            @keyframes toast-in {
                from { opacity: 0; transform: translateX(100%); }
                to { opacity: 1; transform: translateX(0); }
            }
            @keyframes toast-out {
                from { opacity: 1; transform: translateX(0); }
                to { opacity: 0; transform: translateX(100%); }
            }

            .animate-fade-in-up { animation: none !important; opacity: 1 !important; transform: none !important; }
            .animate-fade-in { animation: none !important; opacity: 1 !important; }
            .animate-slide-in-right { animation: none !important; opacity: 1 !important; transform: none !important; }
            .animate-slide-in-left { animation: none !important; opacity: 1 !important; transform: none !important; }
            .animate-count-up { animation: none !important; opacity: 1 !important; transform: none !important; }

            .stagger-1, .stagger-2, .stagger-3, .stagger-4, .stagger-5, .stagger-6 { animation-delay: 0s !important; }

            /* ── Card hover ── */
            .card-hover {
                transition: none !important;
            }
            .card-hover:hover {
                transform: none !important;
                box-shadow: none !important;
            }

            /* ── Toggle switch ── */
            .toggle-switch {
                position: relative; display: inline-flex; align-items: center;
                width: 44px; height: 24px;
                background: #cbd5e1; border-radius: 9999px;
                cursor: pointer; transition: background 0.2s;
            }
            .toggle-switch.active { background: #3b82f6; }
            .toggle-switch .toggle-dot {
                position: absolute; left: 2px; top: 2px;
                width: 20px; height: 20px;
                background: white; border-radius: 9999px;
                transition: transform 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            }
            .toggle-switch.active .toggle-dot { transform: translateX(20px); }

            /* ── Toast ── */
            .toast-enter { animation: toast-in 0.35s ease-out both; }
            .toast-leave { animation: toast-out 0.25s ease-in both; }

            /* ── Gradient text ── */
            .gradient-text {
                background: linear-gradient(135deg, #3b82f6, #8b5cf6);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            /* ── File upload zone ── */
            .upload-zone {
                border: 2px dashed #cbd5e1;
                border-radius: 1rem;
                transition: border-color 0.2s, background 0.2s;
            }
            .upload-zone:hover, .upload-zone.dragover {
                border-color: #3b82f6;
                background: rgba(59,130,246,0.03);
            }
            .dark .upload-zone { border-color: #475569; }
            .dark .upload-zone:hover, .dark .upload-zone.dragover {
                border-color: #60a5fa;
                background: rgba(59,130,246,0.08);
            }

            /* ── Sharp and Professional Admin Tables (Tegas & Jelas) ── */
            .content-card,
            main div.overflow-hidden {
                border: 1px solid #cbd5e1 !important;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05) !important;
                border-radius: 0.75rem !important; /* Force standard sharp 12px corners */
            }
            .dark .content-card,
            .dark main div.overflow-hidden {
                border: 1px solid #334155 !important;
            }

            /* Ensure rounded corners are maintained inside container */
            .content-card .overflow-x-auto,
            main div.overflow-hidden .overflow-x-auto {
                border-radius: inherit !important;
            }

            /* Table structure with clear spacing and separate border collapsing to retain rounded border cells */
            main table {
                border-collapse: separate !important;
                border-spacing: 0 !important;
                width: 100% !important;
            }

            /* Header cells styling - bold, clear, flat slate background */
            main table th {
                background-color: #f1f5f9 !important;
                color: #0f172a !important;
                font-weight: 700 !important;
                font-size: 0.75rem !important;
                letter-spacing: 0.05em !important;
                padding: 0.875rem 1.25rem !important;
                border-bottom: 2px solid #94a3b8 !important; /* Thick bottom border for header */
                border-right: 1px solid #cbd5e1 !important; /* Clear vertical border */
                text-transform: uppercase !important;
            }
            .dark main table th {
                background-color: #1e293b !important;
                color: #f8fafc !important;
                border-bottom: 2px solid #475569 !important;
                border-right: 1px solid #334155 !important;
            }

            /* Data cells styling - sharp solid lines, proper font weight and contrast */
            main table td {
                padding: 0.875rem 1.25rem !important;
                border-bottom: 1px solid #cbd5e1 !important; /* Solid row separator */
                border-right: 1px solid #cbd5e1 !important; /* Solid column separator */
                color: #1e293b !important;
                font-weight: 500 !important;
                vertical-align: middle !important;
                background-color: #ffffff !important;
            }
            .dark main table td {
                background-color: #1e293b !important;
                border-bottom: 1px solid #334155 !important;
                border-right: 1px solid #334155 !important;
                color: #e2e8f0 !important;
            }

            /* Remove vertical divider on the last column */
            main table th:last-child,
            main table td:last-child {
                border-right: none !important;
            }

            /* Remove horizontal line on the last row */
            main table tr:last-child td {
                border-bottom: none !important;
            }

            /* Clear Row Hover Action */
            main table tr:hover td {
                background-color: #f8fafc !important;
            }
            .dark main table tr:hover td {
                background-color: #243049 !important;
            }

            /* Top/bottom cells rounded corner mapping */
            main table thead tr:first-child th:first-child {
                border-top-left-radius: 0.75rem;
            }
            main table thead tr:first-child th:last-child {
                border-top-right-radius: 0.75rem;
            }
            main table tbody tr:last-child td:first-child {
                border-bottom-left-radius: 0.75rem;
            }
            main table tbody tr:last-child td:last-child {
                border-bottom-right-radius: 0.75rem;
            }

            /* ── Global Search Input overrides ── */
            input[type="search"].global-search-input {
                border: none !important;
                box-shadow: none !important;
                background: transparent !important;
                padding-left: 2.5rem !important;
                padding-right: 2.5rem !important;
                border-radius: 0 !important;
            }
            input[type="search"].global-search-input:focus {
                border: none !important;
                box-shadow: none !important;
                transform: none !important;
            }
            input[type="search"]::-webkit-search-decoration,
            input[type="search"]::-webkit-search-cancel-button,
            input[type="search"]::-webkit-search-results-button,
            input[type="search"]::-webkit-search-results-decoration {
                -webkit-appearance:none;
            }
        </style>
    </head>
    <body class="antialiased admin-body bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 transition-colors duration-300"
          x-data="{
              sidebarOpen: false,
              sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
              toasts: [],
              addToast(msg, type = 'success') {
                  const id = Date.now();
                  this.toasts.push({ id, msg, type });
                  setTimeout(() => this.removeToast(id), 4000);
              },
              removeToast(id) {
                  this.toasts = this.toasts.filter(t => t.id !== id);
              }
          }"
          @toast.window="addToast($event.detail.message, $event.detail.type || 'success')"
    >

        <!-- ═══════ Toast Notifications ═══════ -->
        <div class="fixed top-6 right-6 z-[100] flex flex-col gap-3 pointer-events-none" style="max-width: 380px;">
            <template x-for="toast in toasts" :key="toast.id">
                <div class="pointer-events-auto toast-enter rounded-xl px-5 py-3.5 shadow-2xl border flex items-start gap-3 backdrop-blur-sm"
                     :class="{
                         'bg-emerald-50/95 border-emerald-200 dark:bg-emerald-900/90 dark:border-emerald-700': toast.type === 'success',
                         'bg-red-50/95 border-red-200 dark:bg-red-900/90 dark:border-red-700': toast.type === 'error',
                         'bg-amber-50/95 border-amber-200 dark:bg-amber-900/90 dark:border-amber-700': toast.type === 'warning',
                         'bg-blue-50/95 border-blue-200 dark:bg-blue-900/90 dark:border-blue-700': toast.type === 'info'
                     }"
                >
                    <div class="shrink-0 mt-0.5">
                        <template x-if="toast.type === 'success'">
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i>
                        </template>
                        <template x-if="toast.type === 'error'">
                            <i data-lucide="x-circle" class="w-5 h-5 text-red-600 dark:text-red-400"></i>
                        </template>
                        <template x-if="toast.type === 'warning'">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600 dark:text-amber-400"></i>
                        </template>
                        <template x-if="toast.type === 'info'">
                            <i data-lucide="info" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                        </template>
                    </div>
                    <p class="text-sm font-semibold flex-1"
                       :class="{
                           'text-emerald-800 dark:text-emerald-200': toast.type === 'success',
                           'text-red-800 dark:text-red-200': toast.type === 'error',
                           'text-amber-800 dark:text-amber-200': toast.type === 'warning',
                           'text-blue-800 dark:text-blue-200': toast.type === 'info'
                       }"
                       x-text="toast.msg"></p>
                    <button @click="removeToast(toast.id)" class="shrink-0 p-0.5 rounded-lg opacity-50 hover:opacity-100 transition">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            </template>
        </div>

        <!-- ═══════ Delete Confirmation Modal ═══════ -->
        <div x-data="{ showDeleteModal: false, deleteForm: null, deleteMessage: '' }"
             @confirm-delete.window="showDeleteModal = true; deleteForm = $event.detail.form; deleteMessage = $event.detail.message || 'Apakah Anda yakin ingin menghapus item ini?'"
             x-cloak
        >
            <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 z-[90] bg-slate-900/60 backdrop-blur-sm"></div>
            <div x-show="showDeleteModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="fixed inset-0 z-[91] flex items-center justify-center p-4"
            >
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center" @click.outside="showDeleteModal = false">
                    <div class="w-14 h-14 rounded-full bg-red-50 dark:bg-red-900/30 mx-auto flex items-center justify-center mb-4">
                        <i data-lucide="trash-2" class="w-7 h-7 text-red-500"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Hapus Item</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6" x-text="deleteMessage"></p>
                    <div class="flex gap-3">
                        <button @click="showDeleteModal = false"
                                class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                            Batal
                        </button>
                        <button @click="if(deleteForm) deleteForm.submit(); showDeleteModal = false;"
                                class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-red-500 hover:bg-red-600 active:scale-95 transition shadow-lg shadow-red-500/20">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══════ Mobile sidebar overlay ═══════ -->
        <div
            x-show="sidebarOpen"
            x-cloak
            x-transition.opacity
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"
        ></div>

        <!-- ═══════ Sidebar ═══════ -->
        <aside
            class="sidebar-bg fixed inset-y-0 left-0 z-50 w-[260px] bg-slate-900 dark:bg-slate-950 text-slate-300 flex flex-col transform transition-transform duration-300 ease-in-out lg:translate-x-0"
            :class="(sidebarOpen ? 'translate-x-0' : '-translate-x-full') + (sidebarCollapsed ? ' is-collapsed' : '')"
        >
            <div class="sidebar-accent"></div>

            <!-- Collapse toggle (desktop only) -->
            <button @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)"
                    class="hidden lg:flex absolute -right-3 top-[60px] w-6 h-6 rounded-full bg-slate-800 dark:bg-slate-700 border border-white/10 text-slate-300 hover:text-white hover:bg-slate-700 items-center justify-center shadow-lg transition z-10"
                    :title="sidebarCollapsed ? 'Perluas Sidebar' : 'Ciutkan Sidebar'">
                <i data-lucide="chevron-left" class="w-3.5 h-3.5 transition-transform" :class="sidebarCollapsed ? 'rotate-180' : ''"></i>
            </button>

            <!-- Logo -->
            <div class="sidebar-logo-bar h-[72px] flex items-center px-6 border-b border-white/[0.06] shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/Izi LOGO WHITE.webp') }}" alt="IZI Travel" class="sidebar-logo-full h-7 w-auto transition-transform group-hover:scale-105" width="480" height="217">
                    <div class="hidden">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Admin Panel</span>
                    </div>
                </a>
            </div>

            <!-- Nav -->
            <nav class="flex-1 px-3 py-5 space-y-0.5 overflow-y-auto">
                <p class="nav-section-label px-3 text-[10px] font-bold uppercase tracking-[0.15em] text-slate-500 mb-2">Menu Utama</p>

                <a href="{{ route('admin.dashboard') }}"
                   :title="sidebarCollapsed ? 'Dashboard' : null"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'nav-active' : 'text-slate-400 hover:text-white hover:bg-white/[0.04]' }}">
                    <i data-lucide="layout-dashboard" class="w-[18px] h-[18px] shrink-0"></i>
                    <span class="nav-label">Dashboard</span>
                </a>

                {{-- Menu "Pengguna" disembunyikan: sistem hanya punya 1 admin. Edit akun via "Profil Saya" (menu pojok kanan atas). --}}

                <div class="pt-5 pb-1">
                    <p class="nav-section-label px-3 text-[10px] font-bold uppercase tracking-[0.15em] text-slate-500 mb-2">Konten</p>
                </div>

                <a href="{{ route('admin.teams.index') }}"
                   :title="sidebarCollapsed ? 'Tim Kami' : null"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('admin.teams.*') ? 'nav-active' : 'text-slate-400 hover:text-white hover:bg-white/[0.04]' }}">
                    <i data-lucide="contact" class="w-[18px] h-[18px] shrink-0"></i>
                    <span class="nav-label">Tim Kami</span>
                    <span class="nav-badge ml-auto text-[11px] font-bold bg-white/[0.06] px-2 py-0.5 rounded-md">{{ \App\Models\Team::count() }}</span>
                </a>

                <a href="{{ route('admin.packages.index') }}"
                   :title="sidebarCollapsed ? 'Paket Umrah' : null"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('admin.packages.*') ? 'nav-active' : 'text-slate-400 hover:text-white hover:bg-white/[0.04]' }}">
                    <i data-lucide="package" class="w-[18px] h-[18px] shrink-0"></i>
                    <span class="nav-label">Paket Umrah</span>
                    <span class="nav-badge ml-auto text-[11px] font-bold bg-white/[0.06] px-2 py-0.5 rounded-md">{{ \App\Models\Package::count() }}</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                   :title="sidebarCollapsed ? 'Kategori Paket' : null"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'nav-active' : 'text-slate-400 hover:text-white hover:bg-white/[0.04]' }}">
                    <i data-lucide="tag" class="w-[18px] h-[18px] shrink-0"></i>
                    <span class="nav-label">Kategori Paket</span>
                    <span class="nav-badge ml-auto text-[11px] font-bold bg-white/[0.06] px-2 py-0.5 rounded-md">{{ \App\Models\Category::count() }}</span>
                </a>

                <a href="{{ route('admin.galleries.index') }}"
                   :title="sidebarCollapsed ? 'Galeri' : null"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('admin.galleries.*') ? 'nav-active' : 'text-slate-400 hover:text-white hover:bg-white/[0.04]' }}">
                    <i data-lucide="image" class="w-[18px] h-[18px] shrink-0"></i>
                    <span class="nav-label">Galeri</span>
                    <span class="nav-badge ml-auto text-[11px] font-bold bg-white/[0.06] px-2 py-0.5 rounded-md">{{ \App\Models\Gallery::count() }}</span>
                </a>

                <a href="{{ route('admin.testimonials.index') }}"
                   :title="sidebarCollapsed ? 'Testimoni' : null"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('admin.testimonials.*') ? 'nav-active' : 'text-slate-400 hover:text-white hover:bg-white/[0.04]' }}">
                    <i data-lucide="message-square-quote" class="w-[18px] h-[18px] shrink-0"></i>
                    <span class="nav-label">Testimoni</span>
                    <span class="nav-badge ml-auto text-[11px] font-bold bg-white/[0.06] px-2 py-0.5 rounded-md">{{ \App\Models\Testimonial::count() }}</span>
                </a>

                <a href="{{ route('admin.articles.index') }}"
                   :title="sidebarCollapsed ? 'Artikel' : null"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('admin.articles.*') ? 'nav-active' : 'text-slate-400 hover:text-white hover:bg-white/[0.04]' }}">
                    <i data-lucide="book-open" class="w-[18px] h-[18px] shrink-0"></i>
                    <span class="nav-label">Artikel</span>
                    <span class="nav-badge ml-auto text-[11px] font-bold bg-white/[0.06] px-2 py-0.5 rounded-md">{{ \App\Models\Article::count() }}</span>
                </a>

                <a href="{{ route('admin.partners.index') }}"
                   :title="sidebarCollapsed ? 'Mitra Maskapai' : null"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('admin.partners.*') ? 'nav-active' : 'text-slate-400 hover:text-white hover:bg-white/[0.04]' }}">
                    <i data-lucide="plane-takeoff" class="w-[18px] h-[18px] shrink-0"></i>
                    <span class="nav-label">Mitra Maskapai</span>
                    <span class="nav-badge ml-auto text-[11px] font-bold bg-white/[0.06] px-2 py-0.5 rounded-md">{{ \App\Models\Partner::count() }}</span>
                </a>

                <a href="{{ route('admin.faqs.index') }}"
                   :title="sidebarCollapsed ? 'FAQ' : null"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('admin.faqs.*') ? 'nav-active' : 'text-slate-400 hover:text-white hover:bg-white/[0.04]' }}">
                    <i data-lucide="help-circle" class="w-[18px] h-[18px] shrink-0"></i>
                    <span class="nav-label">FAQ</span>
                    <span class="nav-badge ml-auto text-[11px] font-bold bg-white/[0.06] px-2 py-0.5 rounded-md">{{ \App\Models\Faq::count() }}</span>
                </a>

                <a href="{{ route('admin.settings.index') }}"
                   :title="sidebarCollapsed ? 'Pengaturan Web' : null"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'nav-active' : 'text-slate-400 hover:text-white hover:bg-white/[0.04]' }}">
                    <i data-lucide="settings" class="w-[18px] h-[18px] shrink-0"></i>
                    <span class="nav-label">Pengaturan Web</span>
                </a>

                <div class="pt-5 pb-1">
                    <p class="nav-section-label px-3 text-[10px] font-bold uppercase tracking-[0.15em] text-slate-500 mb-2">Lainnya</p>
                </div>

                <a href="{{ url('/') }}" target="_blank"
                   :title="sidebarCollapsed ? 'Lihat Website' : null"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold text-slate-400 hover:text-white hover:bg-white/[0.04] transition-all duration-200">
                    <i data-lucide="external-link" class="w-[18px] h-[18px] shrink-0"></i>
                    <span class="nav-label">Lihat Website</span>
                </a>
            </nav>

            <!-- Sidebar footer -->
            <div class="p-3 border-t border-white/[0.06] shrink-0">
                <div class="sidebar-footer-inner flex items-center gap-3 px-3 py-3 rounded-xl bg-white/[0.03]">
                    <div class="relative shrink-0">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center font-bold text-white text-sm shadow-lg shadow-blue-500/20">
                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-emerald-400 rounded-full border-2 border-slate-900"></span>
                    </div>
                    <div class="sidebar-user-info min-w-0 flex-1">
                        <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] text-slate-500 truncate">Administrator</p>
                    </div>
                    <div x-data="{ open: false }" class="sidebar-user-menu relative">
                        <button @click="open = !open" class="p-1.5 rounded-lg text-slate-500 hover:text-white hover:bg-white/10 transition">
                            <i data-lucide="more-vertical" class="w-4 h-4"></i>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute bottom-full right-0 mb-2 w-48 bg-slate-800 dark:bg-slate-900 rounded-xl shadow-2xl border border-white/10 py-1.5 z-50">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-white/5 transition">
                                <i data-lucide="user-cog" class="w-4 h-4"></i>
                                Profil Saya
                            </a>
                            <div class="border-t border-white/5 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 transition">
                                    <i data-lucide="log-out" class="w-4 h-4"></i>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- ═══════ Main content ═══════ -->
        <div class="flex flex-col min-h-screen transition-[padding] duration-300 ease-in-out"
             :class="sidebarCollapsed ? 'lg:pl-20' : 'lg:pl-[260px]'">

            <!-- Topbar -->
            <header class="topbar-bg sticky top-0 z-30 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200/80 dark:border-slate-700/50">
                <div class="h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 gap-4">
                    <!-- Left: hamburger + header -->
                    <div class="flex items-center gap-3 min-w-[120px] sm:min-w-[180px]">
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/5 transition">
                            <i data-lucide="menu" class="w-5 h-5"></i>
                        </button>
                        <div class="min-w-0">
                            {{ $header ?? '' }}
                        </div>
                    </div>

                    <!-- Global Search Trigger (Desktop/Tablet) -->
                    <div class="flex-1 max-w-xs md:max-w-md mx-auto hidden sm:block">
                        <button @click="$dispatch('open-global-search')" class="w-full flex items-center justify-between gap-3 px-3 py-1.5 bg-slate-100 hover:bg-slate-200/80 dark:bg-slate-800 dark:hover:bg-slate-700/80 text-slate-400 hover:text-slate-500 dark:text-slate-500 dark:hover:text-slate-400 rounded-xl border border-slate-200/50 dark:border-slate-700/50 transition text-left text-xs font-semibold">
                            <span class="flex items-center gap-2">
                                <i data-lucide="search" class="w-4 h-4"></i>
                                {{ __('Cari paket, artikel, pengguna...') }}
                            </span>
                            <span class="inline-flex items-center gap-0.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 px-1.5 py-0.5 rounded text-[10px] text-slate-400 dark:text-slate-500 font-bold tabular-nums">
                                ⌘K
                            </span>
                        </button>
                    </div>

                    <!-- Right: actions -->
                    <div class="flex items-center gap-2 ml-auto shrink-0">
                        <!-- Mobile Search Trigger -->
                        <button @click="$dispatch('open-global-search')" class="sm:hidden p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/5 transition">
                            <i data-lucide="search" class="w-[18px] h-[18px]"></i>
                        </button>

                        <!-- Dark mode toggle -->
                        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                                class="p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/5 transition flex items-center justify-center"
                                :title="darkMode ? 'Light Mode' : 'Dark Mode'">
                            <span x-show="!darkMode" class="flex items-center justify-center">
                                <i data-lucide="moon" class="w-[18px] h-[18px]"></i>
                            </span>
                            <span x-show="darkMode" x-cloak class="flex items-center justify-center">
                                <i data-lucide="sun" class="w-[18px] h-[18px]"></i>
                            </span>
                        </button>

                        <!-- Notification bell -->
                        <button class="relative p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/5 transition">
                            <i data-lucide="bell" class="w-[18px] h-[18px]"></i>
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- Divider -->
                        <div class="w-px h-6 bg-slate-200 dark:bg-slate-700 mx-1 hidden sm:block"></div>

                        <!-- Admin badge -->
                        <span class="hidden sm:inline-flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[11px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider">
                            <i data-lucide="shield-check" class="w-3 h-3"></i>
                            Admin
                        </span>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6 animate-fade-in">
                @if (session('status'))
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            window.dispatchEvent(new CustomEvent('toast', {
                                detail: { message: @json(session('status')), type: 'success' }
                            }));
                        });
                    </script>
                @endif

                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="px-4 sm:px-6 lg:px-8 py-4 border-t border-slate-200/60 dark:border-slate-800">
                <div class="flex items-center justify-between text-[11px] text-slate-400 dark:text-slate-600">
                    <span>&copy; {{ date('Y') }} IZI Travel. All rights reserved.</span>
                    <span class="hidden sm:block">Admin Panel v2.0</span>
                </div>
            </footer>
        </div>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
        <script>
            // Everything here has to wait for DOMContentLoaded — window.lucide is set by the
            // deferred Vite bundle script, which runs after a top-level classic <script> like
            // this one would otherwise execute (this used to throw "lucide is not defined" and
            // silently kill the rest of the block below it, including the MutationObserver that
            // keeps icons in sync with Alpine — that's why icons kept vanishing in admin).
            document.addEventListener('DOMContentLoaded', () => {
                // Initial render and tagging
                lucide.createIcons();
                document.querySelectorAll('svg[data-lucide]').forEach(svg => {
                    svg.setAttribute('data-lucide-rendered', svg.getAttribute('data-lucide'));
                });

                let lucideRefreshTimer = null;
                window.refreshLucideIcons = function() {
                    if (lucideRefreshTimer) clearTimeout(lucideRefreshTimer);
                    lucideRefreshTimer = setTimeout(() => {
                        if (window.lucide && typeof window.lucide.createIcons === 'function') {
                            let shouldRun = false;

                            // Case 1: Unprocessed <i> elements
                            if (document.querySelectorAll('i[data-lucide]').length > 0) {
                                shouldRun = true;
                            }

                            // Case 2: svg[data-lucide] whose attribute changed dynamically (from Alpine.js icon pickers)
                            if (!shouldRun) {
                                const svgs = document.querySelectorAll('svg[data-lucide]');
                                for (const svg of svgs) {
                                    if (svg.getAttribute('data-lucide') !== svg.getAttribute('data-lucide-rendered')) {
                                        shouldRun = true;
                                        break;
                                    }
                                }
                            }

                            if (shouldRun) {
                                window.lucide.createIcons();
                                document.querySelectorAll('svg[data-lucide]').forEach(svg => {
                                    svg.setAttribute('data-lucide-rendered', svg.getAttribute('data-lucide'));
                                });
                            }
                        }
                    }, 30);
                };

                // Re-init lucide after Alpine renders dynamic content
                document.addEventListener('alpine:initialized', () => {
                    window.refreshLucideIcons();
                });

                // Watch for Alpine DOM changes to re-init icons dynamically
                const observer = new MutationObserver((mutations) => {
                    let shouldCreate = false;
                    for (const mutation of mutations) {
                        if (mutation.type === 'childList') {
                            for (const node of mutation.addedNodes) {
                                if (node.nodeType === 1) { // Element node
                                    if ((node.tagName === 'I' && node.hasAttribute('data-lucide')) || (node.querySelector && node.querySelector('i[data-lucide]'))) {
                                        shouldCreate = true;
                                        break;
                                    }
                                }
                            }
                        } else if (mutation.type === 'attributes' && mutation.attributeName === 'data-lucide') {
                            shouldCreate = true;
                        }
                        if (shouldCreate) break;
                    }
                    if (shouldCreate) {
                        window.refreshLucideIcons();
                    }
                });
                observer.observe(document.body, {
                    childList: true,
                    subtree: true,
                    attributes: true,
                    attributeFilter: ['data-lucide']
                });
            });
        </script>

        <!-- ═══════ Global Search Modal (Spotlight) ═══════ -->
        <div x-data="globalSearchComponent()"
             @open-global-search.window="openSearch()"
             @keydown.escape.window="closeSearch()"
             @keydown.window.prevent.ctrl.k="openSearch()"
             @keydown.window.prevent.meta.k="openSearch()"
             x-show="isOpen"
             x-cloak
             class="fixed inset-0 z-[100] overflow-y-auto p-4 sm:p-6 md:p-20"
             role="dialog"
             aria-modal="true"
        >
            <!-- Backdrop -->
            <div x-show="isOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                 @click="closeSearch()"
            ></div>

            <!-- Panel -->
            <div x-show="isOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="mx-auto max-w-2xl transform divide-y divide-slate-100 dark:divide-slate-700/50 overflow-hidden rounded-2xl bg-white dark:bg-slate-800 shadow-2xl ring-1 ring-black/5 dark:ring-white/10 transition-all"
            >
                <div class="relative flex items-center px-4">
                    <i data-lucide="search" class="w-5 h-5 text-slate-400 dark:text-slate-500 absolute left-4 pointer-events-none"></i>
                    <input type="search"
                           x-ref="searchInput"
                           x-model="query"
                           @input.debounce.250ms="performSearch()"
                           @keydown.arrow-down.prevent="selectNext()"
                           @keydown.arrow-up.prevent="selectPrev()"
                           @keydown.enter.prevent="goSelected()"
                           class="global-search-input w-full border-0 bg-transparent pl-10 pr-10 py-4 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:ring-0 focus:outline-none text-sm"
                           placeholder="{{ __('Ketik kata kunci untuk mencari paket, artikel, pengguna...') }}"
                    >
                    <button x-show="query" @click="query = ''; results = []; selectedIndex = -1; $refs.searchInput.focus()" class="absolute right-4 p-1 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-white transition">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                    <span x-show="!query" class="absolute right-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-700/50 px-1.5 py-0.5 rounded border border-slate-200/50 dark:border-slate-700/30">ESC</span>
                </div>

                <!-- Results state -->
                <div class="max-h-96 overflow-y-auto p-2" x-show="query.length >= 2">
                    <!-- Loading state -->
                    <div x-show="loading" class="space-y-2 p-3">
                        <div class="flex items-center gap-3 animate-pulse">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700"></div>
                            <div class="flex-1 space-y-1.5">
                                <div class="h-3 w-1/3 bg-slate-100 dark:bg-slate-700 rounded"></div>
                                <div class="h-2.5 w-1/2 bg-slate-100 dark:bg-slate-700 rounded"></div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 animate-pulse">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700"></div>
                            <div class="flex-1 space-y-1.5">
                                <div class="h-3 w-1/4 bg-slate-100 dark:bg-slate-700 rounded"></div>
                                <div class="h-2.5 w-2/3 bg-slate-100 dark:bg-slate-700 rounded"></div>
                            </div>
                        </div>
                    </div>

                    <!-- No Results -->
                    <div x-show="!loading && results.length === 0" class="py-10 text-center">
                        <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-900 mx-auto flex items-center justify-center mb-3">
                            <i data-lucide="search-code" class="w-6 h-6 text-slate-400 dark:text-slate-500"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ __('Tidak ada hasil ditemukan') }}</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ __('Coba gunakan kata kunci lainnya.') }}</p>
                    </div>

                    <!-- Results List -->
                    <div x-show="!loading && results.length > 0" class="space-y-4">
                        <!-- Grouped by Category -->
                        <template x-for="(group, category) in groupedResults" :key="category">
                            <div>
                                <div class="px-3 py-1.5 text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest" x-text="category"></div>
                                <div class="space-y-0.5 mt-1">
                                    <template x-for="item in group" :key="item.id">
                                        <a :href="item.url"
                                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition group/item"
                                           :class="item.index === selectedIndex ? 'bg-blue-600 text-white' : 'hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-800 dark:text-slate-300'"
                                           @mouseenter="selectedIndex = item.index"
                                        >
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors"
                                                 :class="item.index === selectedIndex ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400'"
                                            >
                                                <i :data-lucide="item.icon" class="w-4 h-4"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-bold truncate transition-colors"
                                                   :class="item.index === selectedIndex ? 'text-white' : 'text-slate-800 dark:text-white'"
                                                   x-text="item.title"
                                                ></p>
                                                <p class="text-[11px] truncate transition-colors mt-0.5"
                                                   :class="item.index === selectedIndex ? 'text-blue-100' : 'text-slate-500 dark:text-slate-400'"
                                                   x-text="item.subtitle"
                                                ></p>
                                            </div>
                                            <i data-lucide="chevron-right" class="w-4 h-4 shrink-0 transition"
                                               :class="item.index === selectedIndex ? 'text-white translate-x-0.5' : 'text-slate-300 dark:text-slate-600 group-hover/item:translate-x-0.5'"
                                            ></i>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Initial prompt state -->
                <div class="p-4 text-center text-xs text-slate-400 dark:text-slate-500" x-show="query.length < 2">
                    {{ __('Ketik minimal 2 karakter untuk memulai pencarian global.') }}
                </div>

                <!-- Footer help guide -->
                <div class="flex items-center justify-between px-4 py-3 bg-slate-50 dark:bg-slate-900/50 text-[10px] text-slate-400 dark:text-slate-500 rounded-b-2xl border-t border-slate-100 dark:border-slate-700/30">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center gap-1"><kbd class="bg-white dark:bg-slate-800 px-1 py-0.5 rounded border border-slate-200 dark:border-slate-700">↑↓</kbd> Navigasi</span>
                        <span class="flex items-center gap-1"><kbd class="bg-white dark:bg-slate-800 px-1 py-0.5 rounded border border-slate-200 dark:border-slate-700">Enter</kbd> Pilih</span>
                        <span class="flex items-center gap-1"><kbd class="bg-white dark:bg-slate-800 px-1 py-0.5 rounded border border-slate-200 dark:border-slate-700">ESC</kbd> Tutup</span>
                    </div>
                    <div>
                        <span>Global Search v1.0</span>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function globalSearchComponent() {
                return {
                    isOpen: false,
                    query: '',
                    results: [],
                    loading: false,
                    selectedIndex: -1,

                    openSearch() {
                        this.isOpen = true;
                        this.query = '';
                        this.results = [];
                        this.selectedIndex = -1;
                        this.$nextTick(() => {
                            this.$refs.searchInput.focus();
                        });
                    },

                    closeSearch() {
                        this.isOpen = false;
                    },

                    performSearch() {
                        if (this.query.trim().length < 2) {
                            this.results = [];
                            this.selectedIndex = -1;
                            return;
                        }

                        this.loading = true;
                        fetch(`/admin/global-search?q=${encodeURIComponent(this.query)}`)
                            .then(res => res.json())
                            .then(data => {
                                this.results = data.map((item, idx) => ({
                                    ...item,
                                    id: idx + '-' + item.title,
                                    index: idx
                                }));
                                this.selectedIndex = this.results.length > 0 ? 0 : -1;
                                this.$nextTick(() => {
                                    if (window.lucide) {
                                        window.lucide.createIcons();
                                    }
                                });
                            })
                            .catch(err => {
                                console.error('Error fetching global search results:', err);
                                this.results = [];
                                this.selectedIndex = -1;
                            })
                            .finally(() => {
                                this.loading = false;
                            });
                    },

                    get groupedResults() {
                        return this.results.reduce((groups, item) => {
                            const category = item.category || 'Lainnya';
                            if (!groups[category]) {
                                groups[category] = [];
                            }
                            groups[category].push(item);
                            return groups;
                        }, {});
                    },

                    selectNext() {
                        if (this.results.length === 0) return;
                        this.selectedIndex = (this.selectedIndex + 1) % this.results.length;
                    },

                    selectPrev() {
                        if (this.results.length === 0) return;
                        this.selectedIndex = (this.selectedIndex - 1 + this.results.length) % this.results.length;
                    },

                    goSelected() {
                        if (this.selectedIndex >= 0 && this.selectedIndex < this.results.length) {
                            const selected = this.results[this.selectedIndex];
                            window.location.href = selected.url;
                        }
                    }
                };
            }
        </script>
        @stack('scripts')
    </body>
</html>

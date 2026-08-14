@php
    $siteName = $settings['site_name'] ?? 'IZI Travel';
    $wa_phone = preg_replace('/[^0-9]/', '', $settings['contact_whatsapp'] ?? '6281112345678');
    if (str_starts_with($wa_phone, '0')) {
        $wa_phone = '62' . substr($wa_phone, 1);
    }
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Lacak Status Jemaah - {{ $siteName }}</title>
    <link rel="icon" type="image/png" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <meta name="description" content="Pantau status persiapan keberangkatan umrah Anda dengan nomor paspor dan tanggal lahir.">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; background-color: #fafaf9; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 700 !important; letter-spacing: -0.02em; }
        .font-islamic { font-family: 'El Messiri', serif !important; font-weight: 700 !important; letter-spacing: 0.01em; }
        .islamic-pattern {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cpath d='M30 0 l10 20 l20 10 l-20 10 l-10 20 l-10 -20 l-20 -10 l20 -10 z' fill='%23d97706' fill-opacity='0.02'/%3E%3C/svg%3E");
            background-size: 60px 60px;
        }
        .islamic-pattern-blue-soft {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cpath d='M30 0 l10 20 l20 10 l-20 10 l-10 20 l-10 -20 l-20 -10 l20 -10 z' fill='%233b82f6' fill-opacity='0.015'/%3E%3C/svg%3E");
            background-size: 60px 60px;
        }
        .soft-shadow { box-shadow: 0 10px 30px -10px rgba(7, 30, 61, 0.03), 0 1px 3px rgba(7, 30, 61, 0.01); }
        .scrollbar-none::-webkit-scrollbar { display: none; }
        .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-stone-50 text-slate-800 overflow-x-hidden antialiased">

    <!-- BEGIN: Floating Modern Header -->
    <header class="fixed top-4 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-7xl z-50">
        <nav class="bg-white border border-slate-100/80 rounded-full shadow-[0_10px_35px_-10px_rgba(0,0,0,0.05)] px-6 py-3 flex items-center justify-between transition-all duration-300">
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}">
                    <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO.webp') }}" alt="{{ $siteName }}" class="h-8 w-auto object-contain" width="687" height="240" fetchpriority="high" decoding="async" />
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-1 font-medium text-slate-600">
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#beranda">Beranda</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#paket-umrah">Paket Umrah</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ route('public.gallery') }}">Galeri</a>
                <a class="text-blue-600 bg-blue-50/80 px-3.5 py-2 rounded-full transition duration-200 flex items-center gap-1.5" href="{{ route('public.jemaah.tracking') }}">
                    <i data-lucide="search-check" class="w-3.5 h-3.5"></i>
                    Lacak Jemaah
                </a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#kontak">Kontak</a>
            </div>

            <div class="flex items-center gap-2">
                <a class="bg-blue-600 text-white px-3.5 py-2.5 sm:px-5 sm:py-2.5 rounded-full font-bold hover:bg-blue-700 transition-all duration-300 flex items-center gap-2 text-xs shadow-md active:scale-95" href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path></svg>
                    <span class="hidden sm:inline">Konsultasi/WhatsApp</span>
                </a>
                <button id="mobile-bottom-menu-trigger" class="md:hidden w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-600 hover:bg-slate-100 transition active:scale-95 shadow-sm">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
            </div>
        </nav>
    </header>
    <!-- END: Floating Header -->

    <!-- BEGIN: Banner Header -->
    <section class="relative pt-32 pb-16 bg-blue-950 overflow-hidden islamic-pattern">
        <div class="absolute inset-0 bg-slate-900/60 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <span class="bg-amber-500/10 text-amber-400 text-xs font-bold px-4 py-1.5 rounded-full w-fit uppercase tracking-wider border border-amber-500/20 inline-flex items-center gap-1.5">
                <i data-lucide="search-check" class="w-3.5 h-3.5"></i>
                Lacak Jemaah
            </span>
            <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight">Pantau Kesiapan Keberangkatan Anda</h1>
            <p class="text-slate-300 max-w-lg mx-auto text-xs md:text-sm">Cukup masukkan nomor paspor dan tanggal lahir Anda, lalu lihat detail paket, jadwal keberangkatan, hingga sejauh mana persiapan dokumen Anda sudah selesai.</p>
        </div>
    </section>
    <!-- END: Banner Header -->

    <!-- BEGIN: Content Section -->
    <section class="py-16 bg-stone-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 space-y-6">

            <!-- Search Card -->
            <div class="bg-white rounded-[2rem] border border-slate-100/80 p-6 sm:p-8 soft-shadow relative overflow-hidden">
                <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-amber-500/40 via-amber-500 to-amber-500/40"></div>
                <form method="GET" action="{{ route('public.jemaah.tracking.result') }}" class="space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="relative">
                            <i data-lucide="scan-search" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input
                                type="text"
                                name="passport"
                                placeholder="Nomor Paspor, contoh: A1234567"
                                class="w-full pl-11 pr-4 py-3.5 text-sm border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 rounded-2xl"
                                autofocus
                                required
                            >
                        </div>
                        <div class="relative">
                            <i data-lucide="calendar" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input
                                type="date"
                                name="birth_date"
                                class="w-full pl-11 pr-4 py-3.5 text-sm border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 rounded-2xl"
                                required
                            >
                        </div>
                    </div>
                    <button type="submit" class="group w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 active:scale-[0.97] rounded-2xl font-bold text-sm text-white transition-all duration-300 shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/30">
                        <i data-lucide="search-check" class="w-4.5 h-4.5 transition-transform group-hover:scale-110"></i>
                        Cari Status Keberangkatan
                        <i data-lucide="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-1"></i>
                    </button>
                </form>
            </div>

            <!-- Prompt state -->
            <div class="text-center py-10 space-y-3">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center mx-auto">
                    <i data-lucide="shield-check" class="w-8 h-8 text-blue-400"></i>
                </div>
                <p class="text-sm text-slate-400 max-w-xs mx-auto">Untuk menjaga privasi Anda, pencarian membutuhkan nomor paspor dan tanggal lahir sekaligus — sesuai data yang terdaftar di sistem kami.</p>
            </div>
        </div>
    </section>
    <!-- END: Content Section -->

    <!-- BEGIN: Footer -->
    <footer class="bg-slate-950 text-white py-16 md:py-24 relative overflow-hidden islamic-pattern-blue-soft" id="kontak">
        <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-blue-500/30 to-transparent"></div>
        <div class="absolute -right-20 -bottom-20 w-[600px] h-[600px] bg-blue-600/5 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="max-w-[85rem] mx-auto px-6 sm:px-10 lg:px-16 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-10">
                <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO WHITE.webp') }}" alt="{{ $siteName }}" class="h-11 w-auto object-contain" width="531" height="240" loading="lazy" decoding="async" />
                <div class="flex items-center gap-4 text-xs text-slate-400">
                    <span class="flex items-center gap-1.5"><i data-lucide="phone" class="w-3.5 h-3.5 text-amber-500"></i>{{ $settings['contact_phone'] ?? '+62 811-1234-5678' }}</span>
                    <span class="flex items-center gap-1.5"><i data-lucide="mail" class="w-3.5 h-3.5 text-amber-500"></i>{{ $settings['contact_email'] ?? 'info@izitravel.co.id' }}</span>
                </div>
            </div>
            <div class="border-t border-white/5 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
                <div class="flex gap-6 font-light">
                    <a href="{{ route('public.terms') }}" class="hover:text-amber-500 transition duration-200">Syarat &amp; Ketentuan</a>
                    <a href="{{ route('public.privacy') }}" class="hover:text-amber-500 transition duration-200">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>
    <!-- END: Footer -->

    <!-- BEGIN: Mobile Navigation Drawer -->
    <div id="mobile-drawer" class="fixed inset-0 z-50 invisible flex justify-end transition-all duration-300" role="dialog" aria-modal="true">
        <div id="mobile-drawer-backdrop" class="fixed inset-0 bg-slate-950/40 opacity-0 transition-opacity duration-300 backdrop-blur-sm"></div>
        <div id="mobile-drawer-content" class="relative w-full max-w-xs bg-white h-full shadow-2xl p-6 flex flex-col gap-6 transform translate-x-full transition-transform duration-300 z-10">
            <div class="flex-1 overflow-y-auto scrollbar-none space-y-6 pr-1">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO.webp') }}" alt="{{ $siteName }}" class="h-7 w-auto object-contain" width="180" height="28" decoding="async" />
                    <button id="mobile-drawer-close" class="w-8 h-8 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-800 transition active:scale-95 shadow-sm">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div class="flex flex-col space-y-1">
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#beranda">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="home" class="w-4 h-4"></i></span>
                        Beranda
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#paket-umrah">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="compass" class="w-4 h-4"></i></span>
                        Paket Umrah
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-blue-600 bg-blue-50/50 transition duration-200 font-semibold text-xs" href="{{ route('public.jemaah.tracking') }}">
                        <span class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center"><i data-lucide="search-check" class="w-4 h-4"></i></span>
                        Lacak Jemaah
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#kontak">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="phone-call" class="w-4 h-4"></i></span>
                        Kontak
                    </a>
                </div>
            </div>
            <div class="pt-4 border-t border-slate-100 flex flex-col gap-3 mt-4 shrink-0">
                <a class="w-full bg-blue-600 text-white py-2.5 rounded-xl font-bold hover:bg-blue-700 transition duration-200 flex items-center justify-center gap-2 text-xs shadow-lg shadow-blue-500/10 active:scale-95" href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path></svg>
                    <span>Konsultasi Syiar</span>
                </a>
            </div>
        </div>
    </div>
    <!-- END: Mobile Navigation Drawer -->

    <script src="https://unpkg.com/lucide@0.462.0"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) window.lucide.createIcons();

            const mobileBottomMenuTrigger = document.getElementById('mobile-bottom-menu-trigger');
            const mobileDrawer = document.getElementById('mobile-drawer');
            const mobileDrawerBackdrop = document.getElementById('mobile-drawer-backdrop');
            const mobileDrawerContent = document.getElementById('mobile-drawer-content');
            const mobileDrawerClose = document.getElementById('mobile-drawer-close');
            const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

            const openDrawer = () => {
                mobileDrawer.classList.remove('invisible');
                mobileDrawer.classList.add('flex');
                void mobileDrawer.offsetWidth;
                mobileDrawerBackdrop.classList.remove('opacity-0');
                mobileDrawerBackdrop.classList.add('opacity-100');
                mobileDrawerContent.classList.remove('translate-x-full');
                mobileDrawerContent.classList.add('translate-x-0');
            };

            const closeDrawer = () => {
                mobileDrawerBackdrop.classList.remove('opacity-100');
                mobileDrawerBackdrop.classList.add('opacity-0');
                mobileDrawerContent.classList.remove('translate-x-0');
                mobileDrawerContent.classList.add('translate-x-full');
                setTimeout(() => {
                    mobileDrawer.classList.add('invisible');
                    mobileDrawer.classList.remove('flex');
                }, 300);
            };

            if (mobileBottomMenuTrigger) mobileBottomMenuTrigger.addEventListener('click', openDrawer);
            if (mobileDrawerClose) mobileDrawerClose.addEventListener('click', closeDrawer);
            if (mobileDrawerBackdrop) mobileDrawerBackdrop.addEventListener('click', closeDrawer);
            mobileNavLinks.forEach(link => link.addEventListener('click', closeDrawer));
        });
    </script>
</body>

</html>

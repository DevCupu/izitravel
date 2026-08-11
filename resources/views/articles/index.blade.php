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
    <title>Semua Artikel - {{ $siteName }}</title>
    <link rel="icon" type="image/png" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <meta name="description" content="Kumpulan artikel lengkap mengenai panduan ibadah, tips umrah, serta kabar haramain terbaru bersama {{ $siteName }}.">
    
    @if(!empty($settings['seo_meta_keywords']))
    <meta name="keywords" content="artikel, {{ $settings['seo_meta_keywords'] }}" />
    @endif
    <link rel="canonical" href="{{ url('/artikel') }}" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #fafaf9; /* Stone 50 */
            color: #1e293b;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 700 !important;
            letter-spacing: -0.02em;
        }

        /* Hide scrollbars for Chrome, Safari, Opera, Edge, Firefox, and IE */
        .scrollbar-none::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-none {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-[#fafaf9] text-slate-800 overflow-x-hidden antialiased">

    <!-- BEGIN: Floating Modern Header -->
    <header class="fixed top-4 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-7xl z-50">
        <nav class="bg-white border border-slate-100/80 rounded-full shadow-[0_10px_35px_-10px_rgba(0,0,0,0.05)] px-6 py-3 flex items-center justify-between transition-all duration-300">
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}">
                    <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO.webp') }}" alt="{{ $siteName }}" class="h-8 w-auto object-contain" width="687" height="240" fetchpriority="high" decoding="async" />
                </a>
            </div>
            
            <!-- Desktop Navigation Links -->
            <div class="hidden md:flex items-center space-x-1 font-medium text-slate-600">
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/#beranda') }}">Beranda</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/#tentang-kami') }}">Tentang Kami</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/#paket-umrah') }}">Paket Umrah</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/#galeri') }}">Galeri</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/#testimoni') }}">Testimoni</a>
                <a class="text-blue-600 bg-blue-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ route('public.articles.index') }}">Artikel</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/#kemitraan') }}">Kemitraan</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/#kontak') }}">Kontak</a>
            </div>
            
            <div class="flex items-center gap-2">
                <a class="bg-blue-600 text-white px-3.5 py-2.5 sm:px-5 sm:py-2.5 rounded-full font-bold hover:bg-blue-700 transition-all duration-300 flex items-center gap-2 text-xs shadow-md shadow-blue-500/10 active:scale-95" href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
                    </svg>
                    <span class="hidden sm:inline">WhatsApp</span>
                </a>
            </div>
        </nav>
    </header>
    <!-- END: Floating Header -->

    <!-- Main Container (Editorial Layout) -->
    <main class="pt-32 pb-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <!-- Breadcrumbs -->
        <nav class="flex text-[10px] font-bold text-slate-400 gap-2 items-center tracking-widest uppercase mb-6" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Beranda</a>
            <span>/</span>
            <span class="text-slate-650">Artikel</span>
        </nav>

        <!-- Minimalist Page Title -->
        <div class="border-b border-slate-200/70 pb-8 mb-12 text-left">
            <span class="text-[10px] font-black uppercase text-amber-600 tracking-widest block mb-2.5">Kumpulan Tulisan</span>
            <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">Semua Artikel</h1>
            <p class="text-slate-500 text-xs md:text-sm font-light mt-3 max-w-2xl leading-relaxed">
                Menampilkan kumpulan tulisan pembimbing, berita terbaru, dan tips ibadah yang telah kami publikasikan.
            </p>
        </div>

        @if($articles->count() > 0)
            <!-- Clean Magazine Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($articles as $article)
                    <article class="flex flex-col justify-between group bg-white rounded-3xl p-4 border border-slate-200/40 shadow-sm hover:shadow-md transition-all duration-300">
                        <div>
                            <!-- Aspect Ratio Image -->
                            <div class="relative aspect-[16/10] rounded-2xl overflow-hidden bg-slate-100 mb-5 border border-slate-200/20">
                                <a href="{{ route('articles.show', $article->slug) }}">
                                    <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover transition duration-550 group-hover:scale-102" width="480" height="300" loading="lazy" decoding="async" />
                                </a>
                            </div>
                            
                            <!-- Card Content Info -->
                            <div class="space-y-2 px-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-extrabold text-blue-600 uppercase tracking-widest">{{ $article->category }}</span>
                                    <span class="text-slate-300 text-xs">•</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $article->published_at }}</span>
                                </div>
                                <h3 class="text-base md:text-lg font-bold text-slate-900 leading-snug group-hover:text-blue-600 transition duration-150">
                                    <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                                </h3>
                                <p class="text-slate-505 text-xs font-light leading-relaxed line-clamp-3">
                                    {{ $article->excerpt }}
                                </p>
                            </div>
                        </div>

                        <!-- Mini Tags & Read Time footer -->
                        <div class="mt-5 pt-4 border-t border-slate-100 flex flex-col gap-2 px-1">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                                Oleh {{ $article->author }} • {{ $article->read_time }} Baca
                            </p>
                            @if(!empty($article->tags))
                                @php
                                    $cardTags = array_filter(array_map('trim', explode(',', $article->tags)));
                                @endphp
                                @if(!empty($cardTags))
                                    <div class="flex flex-wrap gap-1.5 mt-1">
                                        @foreach($cardTags as $t)
                                            <a href="{{ route('public.articles.tag', str_replace('#', '', $t)) }}" class="text-[10px] font-semibold text-slate-400 hover:text-blue-600 transition">
                                                {{ str_starts_with($t, '#') ? $t : '#' . $t }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination Links -->
            @if ($articles->hasPages())
                <div class="mt-16 pt-8 border-t border-slate-200/50 flex justify-center">
                    {{ $articles->links() }}
                </div>
            @endif
        @else
            <!-- No Results Clean State -->
            <div class="text-center py-24 bg-white border border-slate-200/40 rounded-3xl max-w-md mx-auto space-y-4 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/5 border border-amber-500/20 text-amber-600 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="font-bold text-slate-900 text-base">Belum Ada Artikel</h3>
                <p class="text-slate-500 text-xs leading-relaxed max-w-xs mx-auto">Tidak ada artikel aktif yang tersedia saat ini.</p>
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:underline pt-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path></svg>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        @endif

    </main>

    <!-- BEGIN: Footer -->
    <footer class="bg-slate-950 text-white py-16 md:py-24 relative overflow-hidden" id="kontak" data-purpose="main-footer">
        <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-blue-500/30 to-transparent"></div>
        
        <div class="max-w-[85rem] mx-auto px-6 sm:px-10 lg:px-16 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-12 mb-16">
                <!-- Col 1 -->
                <div class="lg:col-span-4 space-y-6">
                    <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO WHITE.webp') }}" alt="{{ $siteName }}" class="h-11 w-auto object-contain" width="531" height="240" loading="lazy" decoding="async" />
                    <p class="text-slate-400 text-sm leading-relaxed font-light">
                        {{ $settings['site_description'] ?? 'IZI Travel berkomitmen memberikan pelayanan perjalanan ibadah Umrah dan Haji terbaik secara profesional, amanah, dan terpercaya demi kenyamanan ibadah Anda.' }}
                    </p>
                </div>
                
                <!-- Col 2 -->
                <div class="lg:col-span-2 space-y-6">
                    <h4 class="text-xs font-black uppercase tracking-widest text-amber-500 border-l-2 border-amber-500 pl-3">Menu Utama</h4>
                    <ul class="space-y-3.5 text-xs text-slate-450 font-light">
                        <li><a href="{{ url('/') }}#beranda" class="hover:text-amber-500 transition duration-200">Beranda</a></li>
                        <li><a href="{{ url('/') }}#tentang-kami" class="hover:text-amber-500 transition duration-200">Tentang Kami</a></li>
                        <li><a href="{{ url('/') }}#paket-umrah" class="hover:text-amber-500 transition duration-200">Paket Umrah</a></li>
                        <li><a href="{{ route('public.gallery') }}" class="hover:text-amber-500 transition duration-200">Galeri</a></li>
                    </ul>
                </div>
                
                <!-- Col 3 -->
                <div class="lg:col-span-3 space-y-6">
                    <h4 class="text-xs font-black uppercase tracking-widest text-amber-500 border-l-2 border-amber-500 pl-3">Kontak Hubungi</h4>
                    <ul class="space-y-4 text-xs text-slate-450 font-light">
                        <li class="flex items-start gap-3.5 group/item">
                            <span class="w-8 h-8 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center flex-shrink-0 text-amber-500 transition duration-300">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                            </span>
                            <span class="leading-relaxed">{{ $settings['contact_address'] ?? 'Gedung IZI Travel, Jakarta Selatan, DKI Jakarta 12940' }}</span>
                        </li>
                        <li class="flex items-center gap-3.5 group/item">
                            <span class="w-8 h-8 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center flex-shrink-0 text-amber-500 transition duration-300">
                                <i data-lucide="phone" class="w-4 h-4"></i>
                            </span>
                            <span>{{ $settings['contact_phone'] ?? '+62 21 5200 8888' }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Col 4 -->
                <div class="lg:col-span-3 space-y-6">
                    <h4 class="text-xs font-black uppercase tracking-widest text-amber-500 border-l-2 border-amber-500 pl-3">Legalitas Resmi</h4>
                    <div class="relative overflow-hidden bg-gradient-to-br from-amber-500/10 via-amber-600/[0.03] to-transparent border border-amber-500/25 rounded-2xl p-5 shadow-sm backdrop-blur-sm">
                        <p class="text-[10px] text-amber-400 font-black tracking-widest uppercase mb-1">Izin Resmi PPIU</p>
                        <p class="text-base font-black tracking-widest text-slate-100 font-heading">
                            No: {{ $settings['footer_ppiu_number'] ?? 'A10BS81' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright Bar -->
            <div class="border-t border-white/5 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ $settings['site_name'] ?? 'IZI Travel' }}. All rights reserved.</p>
                <div class="flex gap-6 font-light">
                    <a href="{{ route('public.terms') }}" class="hover:text-amber-500 transition duration-200">Syarat &amp; Ketentuan</a>
                    <a href="{{ route('public.privacy') }}" class="hover:text-amber-500 transition duration-200">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>
    <!-- END: Footer -->

    <!-- BEGIN: Mobile Bottom Navigation Bar (App-Like) -->
    <div class="md:hidden fixed bottom-4 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-md bg-white/95 backdrop-blur-md border border-slate-100 rounded-full shadow-[0_-10px_35px_-10px_rgba(0,0,0,0.1),_0_10px_35px_-10px_rgba(0,0,0,0.05)] px-6 py-3 z-50">
        <div class="flex items-center justify-between text-slate-500 font-medium">
            <a href="{{ url('/') }}#beranda" class="flex flex-col items-center gap-1 hover:text-blue-600 transition active:scale-95 text-slate-500">
                <i data-lucide="home" class="w-5 h-5"></i>
                <span class="text-[9px] font-extrabold uppercase tracking-wider">Beranda</span>
            </a>
            <a href="{{ url('/') }}#paket-umrah" class="flex flex-col items-center gap-1 hover:text-blue-600 transition active:scale-95 text-slate-500">
                <i data-lucide="compass" class="w-5 h-5"></i>
                <span class="text-[9px] font-extrabold uppercase tracking-wider">Paket</span>
            </a>
            <a href="{{ url('/') }}#galeri" class="flex flex-col items-center gap-1 hover:text-blue-600 transition active:scale-95 text-slate-500">
                <i data-lucide="image" class="w-5 h-5"></i>
                <span class="text-[9px] font-extrabold uppercase tracking-wider">Galeri</span>
            </a>
            <a href="{{ route('public.articles.index') }}" class="flex flex-col items-center gap-1 text-blue-600 transition active:scale-95">
                <i data-lucide="book-open" class="w-5 h-5"></i>
                <span class="text-[9px] font-extrabold uppercase tracking-wider">Artikel</span>
            </a>
            <button id="mobile-bottom-menu-trigger" class="flex flex-col items-center gap-1 hover:text-blue-600 transition active:scale-95 text-slate-500">
                <i data-lucide="menu" class="w-5 h-5"></i>
                <span class="text-[9px] font-extrabold uppercase tracking-wider">Menu</span>
            </button>
        </div>
    </div>
    <!-- END: Mobile Bottom Navigation Bar -->

    <!-- BEGIN: Mobile Navigation Drawer (Modern Soft UI Overlay) -->
    <div id="mobile-drawer" class="fixed inset-0 z-[100] invisible transition-all duration-300">
        <!-- Backdrop with soft blur -->
        <div id="mobile-drawer-backdrop" class="absolute inset-0 bg-slate-900/40 backdrop-blur-md opacity-0 transition-opacity duration-300"></div>
        
        <!-- Drawer Content -->
        <div id="mobile-drawer-content" class="absolute top-0 right-0 h-full w-[290px] sm:w-[340px] bg-white/95 backdrop-blur-xl border-l border-slate-100 shadow-2xl p-6 flex flex-col justify-between translate-x-full transition-transform duration-300 ease-out rounded-l-[2rem]">
            <!-- Scrollable content area -->
            <div class="flex-1 overflow-y-auto scrollbar-none space-y-6 pr-1">
                <!-- Header inside drawer -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO.webp') }}" alt="{{ $siteName }}" class="h-7 w-auto object-contain" width="180" height="28" decoding="async" />
                    <button id="mobile-drawer-close" class="w-8 h-8 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-800 transition active:scale-95 shadow-sm">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <!-- Navigation Links (Exact 8 items order) -->
                <div class="flex flex-col space-y-1">
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#beranda">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="home" class="w-4 h-4"></i></span>
                        Beranda
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/55 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#tentang-kami">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="users" class="w-4 h-4"></i></span>
                        Tentang Kami
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/55 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#paket-umrah">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="compass" class="w-4 h-4"></i></span>
                        Paket Umrah
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/55 transition duration-200 font-semibold text-xs" href="{{ route('public.gallery') }}">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="image" class="w-4 h-4"></i></span>
                        Galeri
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/55 transition duration-200 font-semibold text-xs" href="{{ route('public.testimonials') }}">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="message-square" class="w-4 h-4"></i></span>
                        Testimoni
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-blue-650 bg-blue-50/55 transition duration-200 font-semibold text-xs" href="{{ route('public.articles.index') }}">
                        <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100/50"><i data-lucide="book-open" class="w-4 h-4"></i></span>
                        Artikel
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/55 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#kemitraan">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="award" class="w-4 h-4"></i></span>
                        Kemitraan
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/55 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#kontak">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="phone-call" class="w-4 h-4"></i></span>
                        Kontak
                    </a>
                </div>
            </div>
            
            <!-- Fixed Bottom Section -->
            <div class="pt-4 border-t border-slate-100 flex flex-col gap-3 mt-4 shrink-0">
                <a class="w-full bg-blue-600 text-white py-2.5 rounded-xl font-bold hover:bg-blue-700 transition duration-200 flex items-center justify-center gap-2 text-xs shadow-lg shadow-blue-500/10 active:scale-95" href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
                    </svg>
                    <span>Konsultasi Syiar</span>
                </a>
            </div>
        </div>
    </div>
    <!-- END: Mobile Navigation Drawer -->

    <!-- BEGIN: Floating WhatsApp Button -->
    <a href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank" class="fixed bottom-20 md:bottom-6 right-6 z-50 bg-gradient-to-r from-emerald-500 to-green-600 text-white p-4 rounded-full shadow-2xl shadow-emerald-500/30 flex items-center justify-center hover:scale-110 active:scale-95 transition-all duration-300 group" aria-label="Hubungi WhatsApp">
        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
        </svg>
        <span class="max-w-0 overflow-hidden group-hover:max-w-xs group-hover:ml-2 transition-all duration-500 ease-out text-xs font-bold whitespace-nowrap">
            Chat Kami
        </span>
    </a>
    <!-- END: Floating WhatsApp Button -->

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        // Init Lucide
        if (window.lucide) {
            window.lucide.createIcons();
        }

        // Drawer Script
        document.addEventListener('DOMContentLoaded', () => {
            const mobileBottomMenuTrigger = document.getElementById('mobile-bottom-menu-trigger');
            const mobileDrawer = document.getElementById('mobile-drawer');
            const mobileDrawerBackdrop = document.getElementById('mobile-drawer-backdrop');
            const mobileDrawerContent = document.getElementById('mobile-drawer-content');
            const mobileDrawerClose = document.getElementById('mobile-drawer-close');
            const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

            const openDrawer = () => {
                mobileDrawer.classList.remove('invisible');
                mobileDrawer.classList.add('flex');
                // Trigger reflow
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
            
            mobileNavLinks.forEach(link => {
                link.addEventListener('click', closeDrawer);
            });
        });
    </script>
</body>

</html>
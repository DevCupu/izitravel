@php
    $wa_phone = preg_replace('/[^0-9]/', '', $settings['contact_whatsapp'] ?? '6281112345678');
    if (str_starts_with($wa_phone, '0')) {
        $wa_phone = '62' . substr($wa_phone, 1);
    }

    $wa_cofounder = preg_replace('/[^0-9]/', '', $settings['contact_whatsapp_cofounder'] ?? '');
    if ($wa_cofounder && str_starts_with($wa_cofounder, '0')) {
        $wa_cofounder = '62' . substr($wa_cofounder, 1);
    }
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ $settings['site_name'] ?? 'IZI Travel' }} - Galeri &amp; Dokumentasi Perjalanan</title>
    <link rel="icon" type="image/png" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <meta name="description" content="Dokumentasi dan galeri kegiatan perjalanan ibadah Umrah dan Haji Premium bersama IZI Travel.">
    
    @if(!empty($settings['seo_meta_keywords']))
    <meta name="keywords" content="{{ $settings['seo_meta_keywords'] }}" />
    @endif

    @if(!empty($settings['seo_google_analytics_id']))
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings['seo_google_analytics_id'] }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $settings['seo_google_analytics_id'] }}');
    </script>
    @endif
    
    @if(!empty($settings['seo_google_console_verification']))
    <meta name="google-site-verification" content="{{ $settings['seo_google_console_verification'] }}" />
    @endif
    @if(!empty($settings['seo_bing_verification']))
    <meta name="msvalidate.01" content="{{ $settings['seo_bing_verification'] }}" />
    @endif
    @if(!empty($settings['seo_author']))
    <meta name="author" content="{{ $settings['seo_author'] }}" />
    @endif

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ !empty($settings['seo_canonical_url']) ? rtrim($settings['seo_canonical_url'], '/') . '/galeri' : url()->current() }}" />

    <!-- Robots Meta -->
    <meta name="robots" content="index, follow" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="{{ $settings['site_name'] ?? 'IZI Travel' }} - Galeri &amp; Dokumentasi Perjalanan" />
    <meta property="og:description" content="Dokumentasi dan galeri kegiatan perjalanan ibadah Umrah dan Haji Premium bersama IZI Travel." />
    <meta property="og:image" content="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO.webp') }}" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="{{ url()->current() }}" />
    <meta property="twitter:title" content="{{ $settings['site_name'] ?? 'IZI Travel' }} - Galeri &amp; Dokumentasi Perjalanan" />
    <meta property="twitter:description" content="Dokumentasi dan galeri kegiatan perjalanan ibadah Umrah dan Haji Premium bersama IZI Travel." />
    <meta property="twitter:image" content="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO.webp') }}" />

    <!-- Structured Data: ImageObject & ItemList Schema -->
    @if(isset($paginatedAlbums) && $paginatedAlbums->count() > 0)
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "ItemList",
      "name": "Galeri Dokumentasi IZI Travel",
      "description": "Daftar dokumentasi foto dan video perjalanan ibadah Umrah dan Haji Premium IZI Travel.",
      "numberOfItems": "{{ $paginatedAlbums->count() }}",
      "itemListElement": [
        @foreach($paginatedAlbums as $index => $album)
        {
          "@@type": "ListItem",
          "position": {{ $index + 1 }},
          "item": {
            "@@type": "ImageObject",
            "name": "{{ str_replace('"', '\"', $album->name) }}",
            "description": "Dokumentasi perjalanan ibadah album {{ str_replace('"', '\"', $album->name) }}",
            "contentUrl": "{{ $album->cover_url ?? asset('images/Izi LOGO.webp') }}"
          }
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
      ]
    }
    </script>
    @endif

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Google Fonts: Plus Jakarta Sans, Inter, & El Messiri -->
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #fafaf9; /* Stone 50 */
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 700 !important;
            letter-spacing: -0.02em;
        }

        .font-islamic, .font-calligraphy {
            font-family: 'El Messiri', serif !important;
            font-weight: 700 !important;
            letter-spacing: 0.01em;
        }

        .islamic-pattern {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cpath d='M30 0 l10 20 l20 10 l-20 10 l-10 20 l-10 -20 l-20 -10 l20 -10 z' fill='%23d97706' fill-opacity='0.02'/%3E%3C/svg%3E");
            background-size: 60px 60px;
        }

        .soft-shadow {
            box-shadow: 0 10px 30px -10px rgba(7, 30, 61, 0.03), 0 1px 3px rgba(7, 30, 61, 0.01);
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

<body class="bg-stone-50 text-slate-800 overflow-x-hidden antialiased">

    <!-- BEGIN: Floating Modern Header -->
    <header class="fixed top-4 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-7xl z-50">
        <nav class="bg-white border border-slate-100/80 rounded-full shadow-[0_10px_35px_-10px_rgba(0,0,0,0.05)] px-6 py-3 flex items-center justify-between transition-all duration-300">
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}">
                    <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO.webp') }}" alt="{{ $settings['site_name'] ?? 'IZI Travel' }}" class="h-8 w-auto object-contain" width="687" height="240" fetchpriority="high" decoding="async" />
                </a>
            </div>
            
            <!-- Desktop Navigation Links -->
            <div class="hidden md:flex items-center space-x-1 font-medium text-slate-600">
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#beranda">Beranda</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#tentang-kami">Tentang Kami</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#paket-umrah">Paket Umrah</a>
                <a class="text-blue-600 bg-blue-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ route('public.gallery') }}">Galeri</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#testimoni">Testimoni</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#artikel">Artikel</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#kemitraan">Kemitraan</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#kontak">Kontak</a>
            </div>
            
            <div class="flex items-center gap-2">
                <a class="bg-blue-600 text-white px-3.5 py-2.5 sm:px-5 sm:py-2.5 rounded-full font-bold hover:bg-blue-700 transition-all duration-300 flex items-center gap-2 text-xs shadow-md active:scale-95" href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
                    </svg>
                    <span class="hidden sm:inline">Konsultasi/WhatsApp</span>
                </a>
            </div>
        </nav>
    </header>
    <!-- END: Floating Header -->

    <!-- BEGIN: Banner Header -->
    <section class="relative pt-32 pb-16 bg-blue-950 overflow-hidden islamic-pattern">
        <div class="absolute inset-0 bg-slate-900/60 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <span class="bg-amber-500/10 text-amber-400 text-xs font-bold px-4 py-1.5 rounded-full w-fit uppercase tracking-wider border border-amber-500/20">
                Dokumentasi Perjalanan
            </span>
            <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight">Galeri Kegiatan IZI Travel</h1>
            <p class="text-slate-300 max-w-md mx-auto text-xs md:text-sm">Dokumentasi nyata kegiatan manasik, transit bandara, dan ibadah khusyuk jamaah kami di Baitullah.</p>
            
            <!-- Search Bar -->
            <div class="max-w-md mx-auto mt-6 pt-2">
                <form method="GET" action="{{ route('public.gallery') }}" class="flex gap-2">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="w-full pl-10 pr-4 py-3 bg-white text-slate-800 placeholder-slate-400 border border-slate-200/20 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition shadow-lg" 
                               placeholder="Cari album perjalanan...">
                    </div>
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-sm transition active:scale-95 shadow-lg shadow-blue-500/20">
                        Cari
                    </button>
                    @if(request()->filled('search'))
                        <a href="{{ route('public.gallery') }}" class="px-4 py-3 bg-white/10 hover:bg-white/20 text-white border border-white/10 rounded-2xl font-bold text-sm transition text-center justify-center flex items-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </section>
    <!-- END: Banner Header -->

    <!-- BEGIN: Gallery Folder Grid -->
    <section class="py-16 bg-stone-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($paginatedAlbums->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($paginatedAlbums as $album)
                        <!-- Album Folder Card -->
                        <div class="album-folder-card group bg-white rounded-[2rem] border border-slate-100 p-5 soft-shadow hover:shadow-xl transition-all duration-300 flex flex-col gap-5 relative overflow-hidden cursor-pointer" 
                             data-album-name="{{ $album->name }}"
                             data-items="{{ json_encode($album->items) }}">
                            
                            <!-- Folder Tab Shape Design -->
                            <div class="relative aspect-[4/3] rounded-2xl overflow-hidden bg-slate-50 border border-slate-100 flex items-center justify-center shadow-inner">
                                <!-- Cover Image -->
                                @if ($album->cover_url)
                                    <img src="{{ $album->cover_url }}" alt="{{ $album->name }}" class="w-full h-full object-cover transition-transform duration-750 group-hover:scale-[1.03]" loading="lazy" />
                                @else
                                    <div class="flex flex-col items-center gap-2 text-slate-300">
                                        <i data-lucide="folder" class="w-12 h-12 stroke-[1.5]"></i>
                                    </div>
                                @endif
                                
                                <!-- Folder Tag Badge -->
                                <div class="absolute top-4 left-4 bg-slate-950/80 backdrop-blur-md text-white text-[9px] font-extrabold px-3 py-1.5 rounded-full uppercase tracking-wider flex items-center gap-1.5 shadow-sm">
                                    <i data-lucide="folder" class="w-3.5 h-3.5"></i>
                                    Album
                                </div>
                                
                                <!-- Media Count Badge -->
                                <div class="absolute bottom-4 right-4 bg-blue-600 text-white text-[10px] font-black px-3.5 py-2 rounded-xl shadow-md transition group-hover:bg-blue-700 tracking-wider">
                                    {{ $album->count }} Foto &amp; Video
                                </div>

                                <!-- Play Overlay -->
                                <div class="absolute inset-0 bg-slate-950/20 group-hover:bg-slate-950/30 transition duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    <div class="w-14 h-14 bg-white/90 backdrop-blur-md rounded-full flex items-center justify-center text-blue-600 shadow-lg scale-90 group-hover:scale-100 transition-all duration-300">
                                        <i data-lucide="eye" class="w-6 h-6"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Album Description Info -->
                            <div class="flex flex-col">
                                <h3 class="font-extrabold text-slate-900 text-lg group-hover:text-blue-600 transition truncate">{{ $album->name }}</h3>
                                <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5 font-medium">
                                    <i data-lucide="calendar" class="w-4 h-4 text-blue-500"></i>
                                    Lihat dokumentasi perjalanan
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination Links -->
                @if ($paginatedAlbums->hasPages())
                    <div class="mt-12 bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
                        {{ $paginatedAlbums->links() }}
                    </div>
                @endif
            @else
                <div class="max-w-md mx-auto text-center py-20">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mx-auto mb-4">
                        <i data-lucide="folder-open" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Album Tidak Ditemukan</h3>
                    <p class="text-sm text-slate-500 mt-1">Belum ada album atau dokumentasi dengan kata kunci pencarian tersebut.</p>
                    <a href="{{ route('public.gallery') }}" class="inline-flex items-center gap-2 mt-4 text-sm text-blue-600 hover:text-blue-700 font-bold">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i> Lihat Semua Album
                    </a>
                </div>
            @endif
        </div>
    </section>
    <!-- END: Gallery Folder Grid -->

    <!-- Unified Gallery Lightbox Modal -->
    <div id="gallery-lightbox" class="fixed inset-0 z-[100] bg-slate-950/90 backdrop-blur-md hidden opacity-0 transition-opacity duration-300 items-center justify-center p-4" role="dialog" aria-modal="true">
        <!-- Close Button -->
        <button id="lightbox-close" class="absolute top-6 right-6 z-[110] bg-white/10 hover:bg-white/20 text-white p-3 rounded-full transition duration-200 active:scale-95 backdrop-blur-md border border-white/10" aria-label="Tutup Galeri">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
        
        <!-- Prev Button -->
        <button id="lightbox-prev" class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 z-[110] bg-white/10 hover:bg-white/20 text-white w-12 h-12 rounded-full flex items-center justify-center transition duration-200 active:scale-95 backdrop-blur-md border border-white/10" aria-label="Sebelumnya">
            <i data-lucide="chevron-left" class="w-7 h-7"></i>
        </button>
        
        <!-- Next Button -->
        <button id="lightbox-next" class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 z-[110] bg-white/10 hover:bg-white/20 text-white w-12 h-12 rounded-full flex items-center justify-center transition duration-200 active:scale-95 backdrop-blur-md border border-white/10" aria-label="Selanjutnya">
            <i data-lucide="chevron-right" class="w-7 h-7"></i>
        </button>
        
        <!-- Content Container -->
        <div class="max-w-5xl w-full flex flex-col items-center justify-center gap-4 transition-transform duration-300 scale-95" id="lightbox-content-box">
            <!-- Media Area -->
            <div class="relative w-full max-h-[70vh] flex items-center justify-center rounded-3xl overflow-hidden bg-black/40 border border-white/5 shadow-2xl">
                <!-- Image Display -->
                <img id="lightbox-img" src="" alt="" class="max-w-full max-h-[70vh] object-contain hidden transition-all duration-300 opacity-0" />
                
                <!-- Video Embed Wrapper -->
                <div id="lightbox-video-container" class="aspect-video w-full hidden">
                    <iframe id="lightbox-iframe" class="w-full h-full" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
            
            <!-- Caption / Info Area -->
            <div class="w-full max-w-3xl text-center px-4">
                <span id="lightbox-tag" class="text-xs text-amber-400 font-extrabold uppercase tracking-widest block mb-1"></span>
                <h3 id="lightbox-title" class="text-white font-black text-lg md:text-xl tracking-wide"></h3>
                <p id="lightbox-counter" class="text-white/40 text-xs mt-2 font-bold tracking-widest"></p>
            </div>
        </div>
    </div>

    <!-- BEGIN: Footer -->
    <footer class="bg-slate-950 text-white py-16 md:py-24 relative overflow-hidden islamic-pattern-blue-soft animate-fade-in" id="kontak" data-purpose="main-footer">
        <!-- Thin glowing gradient border at the top -->
        <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-blue-500/30 to-transparent"></div>
        
        <!-- Faint blue ambient glow -->
        <div class="absolute -right-20 -bottom-20 w-[600px] h-[600px] bg-blue-600/5 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute -left-20 -top-20 w-[500px] h-[500px] bg-blue-600/3 rounded-full blur-[100px] pointer-events-none"></div>
        
        <!-- Giant Centered Branding Watermark -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none overflow-hidden z-0">
            <span class="text-[12vw] font-black text-white/[0.06] uppercase tracking-[0.2em] leading-none whitespace-nowrap font-heading">
                IZI TRAVEL
            </span>
        </div>
        
        <div class="max-w-[85rem] mx-auto px-6 sm:px-10 lg:px-16 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-12 mb-16">
                <!-- Col 1: Branding & Socials (4 Span) -->
                <div class="lg:col-span-4 space-y-6">
                    <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO WHITE.webp') }}" alt="{{ $settings['site_name'] ?? 'IZI Travel' }}" class="h-11 w-auto object-contain" width="531" height="240" loading="lazy" decoding="async" />
                    <p class="text-slate-400 text-sm leading-relaxed font-light">
                        {{ $settings['site_description'] ?? 'IZI Travel berkomitmen memberikan pelayanan perjalanan ibadah Umrah dan Haji terbaik secara profesional, amanah, dan terpercaya demi kenyamanan ibadah Anda.' }}
                    </p>
                    <div class="space-y-3">
                        <h4 class="text-[10px] font-extrabold uppercase tracking-widest text-slate-500">Koneksi Media Sosial</h4>
                        <div class="flex flex-wrap gap-2.5">
                            @if(isset($settings['social_facebook']) && !empty($settings['social_facebook']))
                            <a class="w-9 h-9 bg-white/5 hover:bg-amber-500/10 hover:border-amber-500/50 text-white hover:text-amber-400 border border-white/10 rounded-xl flex items-center justify-center transition-all duration-300 active:scale-95 shadow-sm" href="{{ $settings['social_facebook'] }}" target="_blank" aria-label="Facebook">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path>
                                </svg>
                            </a>
                            @endif
                            @if(isset($settings['social_instagram']) && !empty($settings['social_instagram']))
                            <a class="w-9 h-9 bg-white/5 hover:bg-amber-500/10 hover:border-amber-500/50 text-white hover:text-amber-400 border border-white/10 rounded-xl flex items-center justify-center transition-all duration-300 active:scale-95 shadow-sm" href="{{ $settings['social_instagram'] }}" target="_blank" aria-label="Instagram">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.17.054 1.805.249 2.227.412.56.216.96.474 1.38.894.42.42.678.82.894 1.38.163.422.358 1.057.412 2.227.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.054 1.17-.249 1.805-.412 2.227-.216.56-.474.96-.894 1.38-.42.42-.82.678-1.38.894-.422.163-1.057.358-2.227.412-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.17-.054-1.805-.249-2.227-.412-.56-.216-.96-.474-1.38-.894-.42-.42-.678-.82-.894-1.38-.163-.422-.358-1.057-.412-2.227-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.054-1.17.249-1.805.412-2.227.216-.56.474-.96.894-1.38.42-.42.82-.678 1.38-.894.422-.163 1.057-.358 2.227-.412 1.266-.058 1.646-.07 4.85-.07zM12 0C8.741 0 8.333.014 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126s1.384 1.078 2.126 1.384c.766.296 1.636.499 2.913.558C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384s1.078-1.384 1.384-2.126c.296-.765.499-1.636.558-2.913.058-1.28.072-1.689.072-4.948 0-3.259-.014-3.668-.072-4.948-.06-1.277-.262-2.148-.558-2.913-.306-.788-.718-1.459-1.384-2.126s-1.384-1.078-2.126-1.384c-.765-.296-1.636-.499-2.913-.558C15.668.014 15.259 0 12 0z"></path>
                                    <path d="M12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4.162 4.162 0 110-8.324 4.162 4.162 0 010 8.324zM18.406 4.406a1.44 1.44 0 100 2.88 1.44 1.44 0 000-2.88z"></path>
                                </svg>
                            </a>
                            @endif
                            @if(isset($settings['social_youtube']) && !empty($settings['social_youtube']))
                            <a class="w-9 h-9 bg-white/5 hover:bg-amber-500/10 hover:border-amber-500/50 text-white hover:text-amber-400 border border-white/10 rounded-xl flex items-center justify-center transition-all duration-300 active:scale-95 shadow-sm" href="{{ $settings['social_youtube'] }}" target="_blank" aria-label="YouTube">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.872.505 9.377.505 9.377.505s7.505 0 9.377-.505a3.017 3.017 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"></path>
                                </svg>
                            </a>
                            @endif
                            @if(isset($settings['social_tiktok']) && !empty($settings['social_tiktok']))
                            <a class="w-9 h-9 bg-white/5 hover:bg-amber-500/10 hover:border-amber-500/50 text-white hover:text-amber-400 border border-white/10 rounded-xl flex items-center justify-center transition-all duration-300 active:scale-95 shadow-sm" href="{{ $settings['social_tiktok'] }}" target="_blank" aria-label="TikTok">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path>
                                </svg>
                            </a>
                            @endif
                            @if(isset($settings['social_twitter']) && !empty($settings['social_twitter']))
                            <a class="w-9 h-9 bg-white/5 hover:bg-amber-500/10 hover:border-amber-500/50 text-white hover:text-amber-400 border border-white/10 rounded-xl flex items-center justify-center transition-all duration-300 active:scale-95 shadow-sm" href="{{ $settings['social_twitter'] }}" target="_blank" aria-label="Twitter">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path>
                                </svg>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Col 2: Quick Links (2 Span) -->
                <div class="lg:col-span-2 space-y-6">
                    <h4 class="text-xs font-black uppercase tracking-widest text-amber-500 border-l-2 border-amber-500 pl-3">Menu Utama</h4>
                    <ul class="space-y-3.5 text-xs text-slate-400">
                        <li>
                            <a href="{{ url('/#beranda') }}" class="hover:text-amber-400 transition duration-200 flex items-center gap-2 group/link">
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-amber-500/60 group-hover/link:text-amber-500 group-hover/link:translate-x-0.5 transition-all"></i>
                                Beranda
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/#tentang-kami') }}" class="hover:text-amber-400 transition duration-200 flex items-center gap-2 group/link">
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-amber-500/60 group-hover/link:text-amber-500 group-hover/link:translate-x-0.5 transition-all"></i>
                                Tentang Kami
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/#paket-umrah') }}" class="hover:text-amber-400 transition duration-200 flex items-center gap-2 group/link">
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-amber-500/60 group-hover/link:text-amber-500 group-hover/link:translate-x-0.5 transition-all"></i>
                                Paket Umrah
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/#galeri') }}" class="hover:text-amber-400 transition duration-200 flex items-center gap-2 group/link">
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-amber-500/60 group-hover/link:text-amber-500 group-hover/link:translate-x-0.5 transition-all"></i>
                                Galeri Kegiatan
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/#testimoni') }}" class="hover:text-amber-400 transition duration-200 flex items-center gap-2 group/link">
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-amber-500/60 group-hover/link:text-amber-500 group-hover/link:translate-x-0.5 transition-all"></i>
                                Testimoni Jemaah
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/#artikel') }}" class="hover:text-amber-400 transition duration-200 flex items-center gap-2 group/link">
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-amber-500/60 group-hover/link:text-amber-500 group-hover/link:translate-x-0.5 transition-all"></i>
                                Berita &amp; Artikel
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/#kemitraan') }}" class="hover:text-amber-400 transition duration-200 flex items-center gap-2 group/link">
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-amber-500/60 group-hover/link:text-amber-500 group-hover/link:translate-x-0.5 transition-all"></i>
                                Program Kemitraan
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Col 3: Hubungi Kami (3 Span) -->
                <div class="lg:col-span-3 space-y-6">
                    <h4 class="text-xs font-black uppercase tracking-widest text-amber-500 border-l-2 border-amber-500 pl-3">Hubungi Kami</h4>
                    <ul class="space-y-4 text-xs text-slate-400">
                        <li class="flex items-start gap-3.5 group/item">
                            <span class="w-8 h-8 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center flex-shrink-0 text-amber-500 group-hover/item:bg-amber-500 group-hover/item:text-slate-950 group-hover/item:border-amber-500 transition duration-300">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                            </span>
                            <span class="leading-relaxed group-hover/item:text-slate-200 transition duration-200">{{ $settings['contact_address'] ?? 'Gedung IZI Travel, Jl. H. R. Rasuna Said Kav. X-7, Kuningan, Jakarta Selatan, DKI Jakarta 12940' }}</span>
                        </li>
                        <li class="flex items-center gap-3.5 group/item">
                            <span class="w-8 h-8 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center flex-shrink-0 text-amber-500 group-hover/item:bg-amber-500 group-hover/item:text-slate-950 group-hover/item:border-amber-500 transition duration-300">
                                <i data-lucide="phone" class="w-4 h-4"></i>
                            </span>
                            <span class="group-hover/item:text-slate-200 transition duration-200">{{ $settings['contact_phone'] ?? '+62 21 5200 8888' }}</span>
                        </li>
                        <li class="flex items-center gap-3.5 group/item">
                            <span class="w-8 h-8 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center flex-shrink-0 text-amber-500 group-hover/item:bg-amber-500 group-hover/item:text-slate-950 group-hover/item:border-amber-500 transition duration-300">
                                <i data-lucide="mail" class="w-4 h-4"></i>
                            </span>
                            <span class="group-hover/item:text-slate-200 transition duration-200">{{ $settings['contact_email'] ?? 'info@izitravel.co.id' }}</span>
                        </li>
                        <li class="flex items-center gap-3.5 group/item">
                            <span class="w-8 h-8 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center flex-shrink-0 text-amber-500 group-hover/item:bg-amber-500 group-hover/item:text-slate-950 group-hover/item:border-amber-500 transition duration-300">
                                <i data-lucide="clock" class="w-4 h-4"></i>
                            </span>
                            <span class="group-hover/item:text-slate-200 transition duration-200">{{ $settings['office_hours'] ?? 'Senin - Sabtu: 08:00 - 17:00' }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Col 4: Maps & Legalitas (3 Span) -->
                <div class="lg:col-span-3 space-y-6">
                    <h4 class="text-xs font-black uppercase tracking-widest text-amber-500 border-l-2 border-amber-500 pl-3">Legalitas &amp; Lokasi</h4>
                    <!-- Google Maps Wrapper -->
                    <div class="rounded-2xl overflow-hidden border border-white/10 shadow-2xl h-28 relative group transition duration-300 hover:border-blue-500/30">
                        @if(isset($settings['contact_gmaps']) && !empty($settings['contact_gmaps']))
                            <iframe src="{{ $settings['contact_gmaps'] }}" class="w-full h-full border-0 grayscale opacity-80 group-hover:grayscale-0 group-hover:opacity-100 transition duration-500" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        @else
                            <img src="{{ asset('images/map_thumbnail.webp') }}" alt="Google Maps Pin" class="w-full h-28 object-cover transition duration-500 group-hover:scale-105" width="600" height="600" loading="lazy" />
                        @endif
                        <div class="absolute inset-0 bg-slate-950/40 group-hover:bg-slate-950/10 transition duration-300 pointer-events-none flex items-end p-2.5">
                            <span class="text-[9px] font-bold text-white/80 bg-slate-900/90 backdrop-blur-sm px-2 py-1 rounded-md border border-white/5 flex items-center gap-1 shadow-sm">
                                <i data-lucide="map" class="w-2.5 h-2.5 text-amber-500"></i>
                                Lokasi Kantor Pusat
                            </span>
                        </div>
                    </div>
                    <!-- Legalitas Badge Box -->
                    <div class="relative overflow-hidden bg-gradient-to-br from-amber-500/10 via-amber-600/[0.03] to-transparent border border-amber-500/25 rounded-2xl p-5 shadow-[0_8px_32px_0_rgba(217,119,6,0.05)] backdrop-blur-sm group hover:border-amber-500/40 transition duration-300">
                        <div class="absolute -right-6 -bottom-6 w-20 h-20 bg-amber-500/10 rounded-full blur-xl pointer-events-none group-hover:bg-amber-500/20 transition duration-300"></div>
                        <div class="flex items-center gap-3.5 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/15 border border-amber-500/35 flex items-center justify-center text-amber-400 group-hover:scale-110 transition duration-300">
                                <i data-lucide="award" class="w-5.5 h-5.5"></i>
                            </div>
                            <div>
                                <span class="text-[10px] text-amber-400 font-black tracking-widest block uppercase">Izin Resmi PPIU</span>
                                <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider block mt-0.5">Kemenag RI</span>
                            </div>
                        </div>
                        <p class="text-base font-black tracking-widest text-slate-100 font-heading">
                            No: {{ $settings['footer_ppiu_number'] ?? 'A10BS81' }}
                        </p>
                        <p class="text-[10px] text-slate-400 mt-2 flex items-center gap-1.5 font-medium">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-400"></i>
                            Terakreditasi A Penyelenggara Umrah
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright bar -->
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

    <!-- BEGIN: Mobile Bottom Navigation Bar -->
    <div class="md:hidden fixed bottom-4 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-md bg-white/95 backdrop-blur-md border border-slate-100 rounded-full shadow-lg px-6 py-3 z-50">
        <div class="flex items-center justify-between text-slate-500 font-medium">
            <a href="{{ url('/') }}#beranda" class="flex flex-col items-center gap-1 hover:text-blue-600 transition active:scale-95">
                <i data-lucide="home" class="w-5 h-5"></i>
                <span class="text-[9px] font-extrabold uppercase tracking-wider">Beranda</span>
            </a>
            <a href="{{ url('/') }}#tentang-kami" class="flex flex-col items-center gap-1 hover:text-blue-600 transition active:scale-95">
                <i data-lucide="info" class="w-5 h-5"></i>
                <span class="text-[9px] font-extrabold uppercase tracking-wider">Tentang</span>
            </a>
            <a href="{{ url('/') }}#paket-umrah" class="flex flex-col items-center gap-1 hover:text-blue-600 transition active:scale-95">
                <i data-lucide="compass" class="w-5 h-5"></i>
                <span class="text-[9px] font-extrabold uppercase tracking-wider">Paket</span>
            </a>
            <a href="{{ route('public.gallery') }}" class="flex flex-col items-center gap-1 text-blue-600 transition active:scale-95">
                <i data-lucide="image" class="w-5 h-5"></i>
                <span class="text-[9px] font-extrabold uppercase tracking-wider">Galeri</span>
            </a>
            <a href="{{ url('/') }}#artikel" class="flex flex-col items-center gap-1 hover:text-blue-600 transition active:scale-95">
                <i data-lucide="book-open" class="w-5 h-5"></i>
                <span class="text-[9px] font-extrabold uppercase tracking-wider">Artikel</span>
            </a>
            <a href="{{ url('/') }}#kontak" class="flex flex-col items-center gap-1 hover:text-blue-600 transition active:scale-95">
                <i data-lucide="message-square" class="w-5 h-5"></i>
                <span class="text-[9px] font-extrabold uppercase tracking-wider">Kontak</span>
            </a>
        </div>
    </div>
    <!-- END: Mobile Bottom Navigation Bar -->

    <!-- BEGIN: Floating WhatsApp Button -->
    <a href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank" class="fixed bottom-20 md:bottom-6 right-6 z-50 bg-gradient-to-r from-emerald-500 to-green-600 text-white p-4 rounded-full shadow-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all duration-300 group" aria-label="Hubungi WhatsApp">
        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
        </svg>
        <span class="max-w-0 overflow-hidden group-hover:max-w-xs group-hover:ml-2 transition-all duration-500 ease-out text-xs font-bold whitespace-nowrap">Chat Kami</span>
    </a>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@0.462.0"></script>
    <script>
        // Init Lucide
        lucide.createIcons();

        // Unified Album Lightbox Logic
        const lightbox = document.getElementById('gallery-lightbox');
        const lightboxClose = document.getElementById('lightbox-close');
        const lightboxPrev = document.getElementById('lightbox-prev');
        const lightboxNext = document.getElementById('lightbox-next');
        const lightboxImg = document.getElementById('lightbox-img');
        const lightboxVideoContainer = document.getElementById('lightbox-video-container');
        const lightboxIframe = document.getElementById('lightbox-iframe');
        const lightboxTag = document.getElementById('lightbox-tag');
        const lightboxTitle = document.getElementById('lightbox-title');
        const lightboxCounter = document.getElementById('lightbox-counter');
        const lightboxContentBox = document.getElementById('lightbox-content-box');
        const albumCards = document.querySelectorAll('.album-folder-card');

        let currentLightboxIndex = 0;
        let activeGalleryItems = [];

        const showLightboxItem = (index) => {
            if (index < 0 || index >= activeGalleryItems.length) return;
            currentLightboxIndex = index;
            const item = activeGalleryItems[index];

            // Set text info
            lightboxTag.textContent = item.category;
            lightboxTitle.textContent = item.title;
            lightboxCounter.textContent = `${index + 1} dari ${activeGalleryItems.length}`;

            // Reset transitions and visibility
            lightboxImg.classList.add('hidden');
            lightboxImg.classList.remove('opacity-100');
            lightboxImg.classList.add('opacity-0');
            lightboxVideoContainer.classList.add('hidden');
            lightboxIframe.setAttribute('src', '');

            if (item.type === 'photo') {
                lightboxImg.setAttribute('src', item.src);
                lightboxImg.classList.remove('hidden');
                // Trigger reflow
                void lightboxImg.offsetWidth;
                lightboxImg.classList.remove('opacity-0');
                lightboxImg.classList.add('opacity-100');
            } else {
                let embedUrl = '';
                
                if (item.video_platform === 'youtube') {
                    embedUrl = `https://www.youtube.com/embed/${item.video_id}?autoplay=1`;
                } else if (item.video_platform === 'instagram') {
                    embedUrl = `https://www.instagram.com/reel/${item.video_id}/embed`;
                }
                
                lightboxIframe.setAttribute('src', embedUrl);
                lightboxVideoContainer.classList.remove('hidden');
            }

            // Show prev/next arrows if count > 1
            if (activeGalleryItems.length <= 1) {
                lightboxPrev.classList.add('hidden');
                lightboxNext.classList.add('hidden');
            } else {
                lightboxPrev.classList.remove('hidden');
                lightboxNext.classList.remove('hidden');
            }
        };

        const openLightbox = (items, startIndex = 0) => {
            activeGalleryItems = items;
            if (activeGalleryItems.length === 0) return;

            lightbox.classList.remove('hidden');
            lightbox.classList.add('flex');
            
            document.body.style.overflow = 'hidden';

            void lightbox.offsetWidth;
            lightbox.classList.remove('opacity-0');
            lightbox.classList.add('opacity-100');
            lightboxContentBox.classList.remove('scale-95');
            lightboxContentBox.classList.add('scale-100');

            showLightboxItem(startIndex);
        };

        const closeLightbox = () => {
            lightbox.classList.remove('opacity-100');
            lightbox.classList.add('opacity-0');
            lightboxContentBox.classList.remove('scale-100');
            lightboxContentBox.classList.add('scale-95');
            
            document.body.style.overflow = '';

            setTimeout(() => {
                lightbox.classList.remove('flex');
                lightbox.classList.add('hidden');
                lightboxIframe.setAttribute('src', '');
                lightboxImg.setAttribute('src', '');
            }, 300);
        };

        const navigateLightbox = (direction) => {
            let nextIndex = currentLightboxIndex + direction;
            if (nextIndex < 0) {
                nextIndex = activeGalleryItems.length - 1; // loop to end
            } else if (nextIndex >= activeGalleryItems.length) {
                nextIndex = 0; // loop to start
            }
            showLightboxItem(nextIndex);
        };

        // Attach event listeners to all album cards
        albumCards.forEach(card => {
            card.addEventListener('click', () => {
                const items = JSON.parse(card.getAttribute('data-items') || '[]');
                openLightbox(items, 0);
            });
        });

        // Close listeners
        lightboxClose.addEventListener('click', closeLightbox);
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });

        // Prev/Next listeners
        lightboxPrev.addEventListener('click', (e) => {
            e.stopPropagation();
            navigateLightbox(-1);
        });
        lightboxNext.addEventListener('click', (e) => {
            e.stopPropagation();
            navigateLightbox(1);
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (lightbox.classList.contains('hidden')) return;
            if (e.key === 'Escape') {
                closeLightbox();
            } else if (e.key === 'ArrowLeft') {
                navigateLightbox(-1);
            } else if (e.key === 'ArrowRight') {
                navigateLightbox(1);
            }
        });
    </script>

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
            <a href="{{ route('public.gallery') }}" class="flex flex-col items-center gap-1 text-blue-600 transition active:scale-95">
                <i data-lucide="image" class="w-5 h-5"></i>
                <span class="text-[9px] font-extrabold uppercase tracking-wider">Galeri</span>
            </a>
            <a href="{{ url('/') }}#artikel" class="flex flex-col items-center gap-1 hover:text-blue-600 transition active:scale-95 text-slate-500">
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
                    <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO.webp') }}" alt="{{ $settings['site_name'] ?? 'IZI Travel' }}" class="h-7 w-auto object-contain" />
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
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#tentang-kami">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="users" class="w-4 h-4"></i></span>
                        Tentang Kami
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#paket-umrah">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="compass" class="w-4 h-4"></i></span>
                        Paket Umrah
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-blue-600 bg-blue-50/55 transition duration-200 font-semibold text-xs" href="{{ route('public.gallery') }}">
                        <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100/50"><i data-lucide="image" class="w-4 h-4"></i></span>
                        Galeri
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#testimoni">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="message-square" class="w-4 h-4"></i></span>
                        Testimoni
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#artikel">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="book-open" class="w-4 h-4"></i></span>
                        Artikel
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#kemitraan">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="award" class="w-4 h-4"></i></span>
                        Kemitraan
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#kontak">
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

    <!-- Drawer Script -->
    <script>
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
            
            // Re-run lucide on dynamic icons if needed
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>
</body>

</html>

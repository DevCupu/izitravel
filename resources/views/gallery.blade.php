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

<body class="bg-stone-50 text-slate-800 overflow-x-hidden antialiased izi-public">

    <!-- BEGIN: Floating Modern Header -->
    @include('public.partials.navigation')
    <main id="main-content">

    <!-- BEGIN: Banner Header -->
    <section class="relative pt-44 pb-16 md:pt-32 bg-blue-950 overflow-hidden islamic-pattern">
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
                                    <img src="{{ $album->cover_url }}" alt="{{ $album->name }}" class="w-full h-full object-cover transition-transform duration-750 group-hover:scale-[1.03]" width="400" height="300" loading="lazy" decoding="async" />
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
    </main>
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
                    <iframe id="lightbox-iframe" title="Pemutar video galeri" class="w-full h-full" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
    @include('public.partials.footer')



    <!-- BEGIN: Floating WhatsApp Button -->
    <a href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank" class="fixed bottom-20 md:bottom-6 right-6 z-50 bg-gradient-to-r from-emerald-500 to-green-600 text-white p-4 rounded-full shadow-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all duration-300 group" aria-label="Hubungi WhatsApp">
        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
        </svg>
        <span class="max-w-0 overflow-hidden group-hover:max-w-xs group-hover:ml-2 transition-all duration-500 ease-out text-xs font-bold whitespace-nowrap">Chat Kami</span>
    </a>

    <!-- Lucide Icons -->
    <script>
        // Init Lucide
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) window.lucide.createIcons();
        });

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





    </body>

</html>

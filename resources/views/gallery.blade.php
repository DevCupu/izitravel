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

<body class="public-interior izi-public overflow-x-hidden antialiased">

    <!-- BEGIN: Floating Modern Header -->
    @include('public.partials.navigation')
    <main id="main-content">

    @include('public.partials.interior-hero', [
        'eyebrow' => 'Dokumentasi perjalanan',
        'title' => 'Momen perjalanan bersama jemaah',
        'description' => 'Lihat suasana manasik, keberangkatan, dan perjalanan ibadah bersama IZI Travel.',
        'breadcrumb' => 'Galeri',
    ])

    <section class="interior-section" aria-labelledby="gallery-title">
        <div class="interior-section-heading">
            <div><h2 id="gallery-title">Album perjalanan</h2><p>Pilih album untuk melihat foto dan video di dalamnya.</p></div>
            @if($paginatedAlbums->total() > 0)<span class="interior-count">{{ $paginatedAlbums->total() }} album</span>@endif
        </div>
        <form method="GET" action="{{ route('public.gallery') }}" class="interior-card interior-search" role="search">
            <label for="gallery-search" class="sr-only">Cari album perjalanan</label>
            <input id="gallery-search" type="search" name="search" value="{{ request('search') }}" class="interior-field" placeholder="Cari album perjalanan" maxlength="100">
            <button type="submit" class="interior-button"><i data-lucide="search" aria-hidden="true"></i>Cari album</button>
            @if(request()->filled('search'))<a href="{{ route('public.gallery') }}" class="inline-flex min-h-12 items-center px-3 text-sm font-bold text-[#3166b1]">Hapus pencarian</a>@endif
        </form>

        @if($paginatedAlbums->count() > 0)
            <div class="gallery-grid mt-7">
                @foreach ($paginatedAlbums as $album)
                    <button type="button" class="album-folder-card gallery-album interior-card" data-items="{{ json_encode($album->items) }}" aria-label="Buka album {{ $album->name }}, {{ $album->count }} foto dan video">
                        <span class="gallery-album__image">
                            @if ($album->cover_url)
                                <img src="{{ $album->cover_url }}" alt="" width="400" height="300" loading="lazy" decoding="async">
                            @else
                                <span class="flex h-full items-center justify-center text-[#3166b1]"><i data-lucide="images" class="size-12" aria-hidden="true"></i></span>
                            @endif
                            <span class="gallery-album__badge">{{ $album->count }} foto &amp; video</span>
                        </span>
                        <span class="gallery-album__body"><span><strong>{{ $album->name }}</strong><small>Buka dokumentasi album</small></span><i data-lucide="arrow-up-right" aria-hidden="true"></i></span>
                    </button>
                @endforeach
            </div>
            @if ($paginatedAlbums->hasPages())<div class="interior-pagination">{{ $paginatedAlbums->links() }}</div>@endif
        @else
            <div class="interior-card interior-empty mt-7">
                <i data-lucide="images" aria-hidden="true"></i>
                <h2>{{ request()->filled('search') ? 'Album tidak ditemukan' : 'Dokumentasi segera hadir' }}</h2>
                <p>{{ request()->filled('search') ? 'Coba kata kunci lain atau lihat semua album perjalanan.' : 'Belum ada album yang ditampilkan saat ini.' }}</p>
                @if(request()->filled('search'))<a href="{{ route('public.gallery') }}" class="interior-button">Lihat semua album</a>@endif
            </div>
        @endif
    </section>
    </main>
    <!-- END: Gallery Folder Grid -->

    <!-- Unified Gallery Lightbox Modal -->
    <div id="gallery-lightbox" class="fixed inset-0 z-[100] bg-slate-950/90 backdrop-blur-md hidden opacity-0 transition-opacity duration-300 items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="lightbox-title">
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
        let lightboxTrigger = null;

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
                lightboxImg.setAttribute('alt', item.title || 'Foto perjalanan');
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

        const openLightbox = (items, startIndex = 0, trigger = null) => {
            activeGalleryItems = items;
            if (activeGalleryItems.length === 0) return;
            lightboxTrigger = trigger;

            lightbox.classList.remove('hidden');
            lightbox.classList.add('flex');

            document.body.style.overflow = 'hidden';

            void lightbox.offsetWidth;
            lightbox.classList.remove('opacity-0');
            lightbox.classList.add('opacity-100');
            lightboxContentBox.classList.remove('scale-95');
            lightboxContentBox.classList.add('scale-100');

            showLightboxItem(startIndex);
            lightboxClose.focus();
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
                lightboxTrigger?.focus();
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
                openLightbox(items, 0, card);
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
            } else if (e.key === 'Tab') {
                const controls = [lightboxClose, lightboxPrev, lightboxNext].filter(button => !button.classList.contains('hidden'));
                const next = controls.indexOf(document.activeElement) + (e.shiftKey ? -1 : 1);
                if (next < 0 || next >= controls.length) {
                    e.preventDefault();
                    controls[e.shiftKey ? controls.length - 1 : 0].focus();
                }
            }
        });
    </script>





    </body>

</html>

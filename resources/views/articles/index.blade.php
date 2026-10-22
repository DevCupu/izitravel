@php
    $siteName = $settings['site_name'] ?? 'IZI Travel';
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
    <meta name="robots" content="index, follow" />

    @php
        $ogTitle = "Semua Artikel - {$siteName}";
        $ogDesc = 'Kumpulan artikel lengkap mengenai panduan ibadah, tips umrah, serta kabar haramain terbaru bersama ' . $siteName . '.';
        $ogImage = asset('images/Izi LOGO.webp');
        if (!empty($settings['seo_og_image'])) {
            $ogImage = str_starts_with($settings['seo_og_image'], 'images/') ? asset($settings['seo_og_image']) : asset('storage/' . $settings['seo_og_image']);
        } elseif (!empty($settings['site_logo'])) {
            $ogImage = str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo']);
        }
    @endphp

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="{{ $ogTitle }}" />
    <meta property="og:description" content="{{ $ogDesc }}" />
    <meta property="og:image" content="{{ $ogImage }}" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="{{ url()->current() }}" />
    <meta property="twitter:title" content="{{ $ogTitle }}" />
    <meta property="twitter:description" content="{{ $ogDesc }}" />
    <meta property="twitter:image" content="{{ $ogImage }}" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Google Fonts: Plus Jakarta Sans & Inter — loaded non-blocking -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap"></noscript>
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

<body class="public-interior izi-public overflow-x-hidden antialiased">

    <!-- BEGIN: Floating Modern Header -->
    @include('public.partials.navigation')

    <main id="main-content">
    @include('public.partials.interior-hero', [
        'eyebrow' => 'Panduan dan cerita',
        'title' => 'Bacaan untuk perjalanan ibadah',
        'description' => 'Temukan panduan praktis, cerita perjalanan, dan kabar terbaru untuk membantu persiapan umrah Anda.',
        'breadcrumb' => 'Artikel',
    ])
    <section class="interior-section" aria-labelledby="article-list-title">
        <div class="interior-section-heading">
            <div><h2 id="article-list-title">Semua artikel</h2><p>Baca sesuai kebutuhan persiapan perjalanan Anda.</p></div>
            @if($articles->total() > 0)<span class="interior-count">{{ $articles->total() }} artikel</span>@endif
        </div>
        @if($articles->count() > 0)
            <div class="article-grid">
                @foreach($articles as $article)
                    @include('public.partials.article-card')
                @endforeach
            </div>
            @if($articles->hasPages())<div class="interior-pagination">{{ $articles->links() }}</div>@endif
        @else
            <div class="interior-card interior-empty">
                <i data-lucide="book-open" aria-hidden="true"></i>
                <h2>Belum ada artikel</h2>
                <p>Tulisan baru sedang kami siapkan. Kunjungi kembali halaman ini nanti.</p>
                <a href="{{ url('/') }}" class="interior-button">Kembali ke beranda</a>
            </div>
        @endif
    </section>
    </main>

    <!-- BEGIN: Footer -->
    @include('public.partials.footer')





    <!-- Lucide Icons -->
    <script>
        // Init Lucide
        if (window.lucide) {
            window.lucide.createIcons();
        }


    </script>
</body>

</html>
@php
    $siteName = $settings['site_name'] ?? 'IZI Travel';
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Artikel Bertag #{{ $tag }} - {{ $siteName }}</title>
    <link rel="icon" type="image/png" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <meta name="description" content="Temukan artikel-artikel bertag #{{ $tag }} mengenai panduan ibadah, tips, serta kabar haramain terbaru bersama IZI Travel.">

    @if(!empty($settings['seo_meta_keywords']))
    <meta name="keywords" content="{{ $tag }}, {{ $settings['seo_meta_keywords'] }}" />
    @endif

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

<body class="public-interior izi-public overflow-x-hidden antialiased">

    <!-- BEGIN: Floating Modern Header -->
    @include('public.partials.navigation')

    <main id="main-content">
    @include('public.partials.interior-hero', [
        'eyebrow' => 'Topik artikel',
        'title' => 'Artikel tentang #' . $tag,
        'description' => 'Jelajahi tulisan yang membahas topik #' . $tag . '.',
        'breadcrumb' => '#' . $tag,
        'actionHref' => route('public.articles.index'),
        'actionLabel' => 'Semua artikel',
    ])
    <section class="interior-section" aria-labelledby="article-list-title">
        <div class="interior-section-heading">
            <div><h2 id="article-list-title">Artikel dalam topik ini</h2><p>Pilih artikel yang ingin Anda baca.</p></div>
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
                <h2>Belum ada artikel di topik ini</h2>
                <p>Coba jelajahi topik lain dari semua tulisan yang tersedia.</p>
                <a href="{{ route('public.articles.index') }}" class="interior-button">Lihat semua artikel</a>
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

@php
    $wa_phone = preg_replace('/[^0-9]/', '', $settings['contact_whatsapp'] ?? '6281112345678');
    if (str_starts_with($wa_phone, '0')) {
        $wa_phone = '62' . substr($wa_phone, 1);
    }

    $wa_cofounder = preg_replace('/[^0-9]/', '', $settings['contact_whatsapp_cofounder'] ?? '');
    if ($wa_cofounder && str_starts_with($wa_cofounder, '0')) {
        $wa_cofounder = '62' . substr($wa_cofounder, 1);
    }

    // Hero background image: pakai yang diupload admin (key "hero_image"), fallback ke default.
    $heroImageUrl = !empty($settings['hero_image'])
        ? (str_starts_with($settings['hero_image'], 'images/') ? asset($settings['hero_image']) : asset('storage/' . $settings['hero_image']))
        : asset('images/hero_kaaba_4k.webp');

    // Find nearest package and upcoming packages for countdown and schedule card
    $nearestPackage = null;
    $upcomingPackages = collect();
    if (isset($packages) && $packages->count() > 0) {
        $upcomingPackages = $packages->filter(function($p) {
            return $p->departure_date && \Carbon\Carbon::parse($p->departure_date)->isFuture();
        })->sortBy('departure_date');
        
        $nearestPackage = $upcomingPackages->first();
        $upcomingPackages = $upcomingPackages->take(3);
    }
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ $settings['site_name'] ?? 'IZI Travel' }} - {{ $settings['site_tagline'] ?? 'Wujudkan Umrah Impian Anda' }}</title>
    <link rel="icon" type="image/png" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <meta name="description" content="{{ $settings['site_description'] ?? 'Penyelenggara perjalanan ibadah Umrah dan Haji Premium dengan layanan bintang 5 di Ring 1 pelataran Masjidil Haram & Nabawi.' }}">
    
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
    <link rel="canonical" href="{{ !empty($settings['seo_canonical_url']) ? $settings['seo_canonical_url'] : url()->current() }}" />

    <!-- Robots Meta -->
    <meta name="robots" content="index, follow" />

    @php
        $ogTitle = $settings['seo_og_title'] ?? (($settings['site_name'] ?? 'IZI Travel') . ' - ' . ($settings['site_tagline'] ?? 'Wujudkan Umrah Impian Anda'));
        $ogDesc = $settings['seo_og_description'] ?? ($settings['site_description'] ?? 'Penyelenggara perjalanan ibadah Umrah dan Haji Premium dengan layanan bintang 5 di Ring 1 pelataran Masjidil Haram & Nabawi.');
        
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

    <!-- Structured Data: Organization & TravelAgency Schema -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "TravelAgency",
      "name": "{{ $settings['site_name'] ?? 'IZI Travel' }}",
      "alternateName": "IZITRAVEL",
      "url": "{{ url('/') }}",
      "logo": "{{ asset($settings['site_logo'] ?? 'images/Izi LOGO.webp') }}",
      "image": "{{ asset($settings['hero_image'] ?? 'images/package_kaaba.webp') }}",
      "description": "{{ $settings['site_description'] ?? 'Penyelenggara perjalanan ibadah Umrah dan Haji Premium dengan layanan bintang 5.' }}",
      "telephone": "{{ $settings['contact_phone'] ?? '' }}",
      "email": "{{ $settings['contact_email'] ?? '' }}",
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "{{ $settings['contact_address'] ?? 'Jl. Urip Sumoharjo No. 12' }}",
        "addressLocality": "Makassar",
        "addressRegion": "Sulawesi Selatan",
        "postalCode": "90232",
        "addressCountry": "ID"
      },
      "sameAs": [
        "{{ $settings['social_facebook'] ?? '' }}",
        "{{ $settings['social_instagram'] ?? '' }}",
        "{{ $settings['social_youtube'] ?? '' }}",
        "{{ $settings['social_tiktok'] ?? '' }}"
      ]
    }
    </script>

    <!-- Structured Data: FAQPage Schema -->
    @if(isset($faqs) && $faqs->count() > 0)
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "FAQPage",
      "mainEntity": [
        @foreach($faqs as $faq)
        {
          "@@type": "Question",
          "name": "{{ str_replace('"', '\"', $faq->question) }}",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "{{ str_replace('"', '\"', strip_tags($faq->answer)) }}"
          }
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
      ]
    }
    </script>
    @endif

    <!-- Preload Hero Image for LCP Optimization -->
    <link rel="preload" as="image" href="{{ $heroImageUrl }}" fetchpriority="high" />

    <!-- Google Fonts Preconnect & Optimized Request -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Google Fonts: Outfit, Inter, El Messiri & Amiri (Islamic Calligraphic Style) — loaded non-blocking -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=El+Messiri:wght@700&family=Outfit:wght@500;700&family=Inter:wght@400;500;600;700&display=swap" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=El+Messiri:wght@700&family=Outfit:wght@500;700&family=Inter:wght@400;500;600;700&display=swap"></noscript>
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #fafaf9; /* Stone 50 - Warm premium alabaster */
        }

        /* 1. Heading: Outfit Bold (700) */
        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', sans-serif !important;
            font-weight: 700 !important;
            letter-spacing: -0.02em;
        }

        /* 2. Subheading: Outfit Medium (500) */
        .subheading, .font-subheading, h1 + p, h2 + p, summary {
            font-family: 'Outfit', sans-serif !important;
            font-weight: 500 !important;
        }

        /* Islamic Calligraphy Accent Style */
        .font-islamic, .font-calligraphy {
            font-family: 'El Messiri', serif !important;
            font-weight: 700 !important;
            letter-spacing: 0.01em;
        }

        /* Arabic Calligraphy (Bismillah / Quranic accents) */
        .font-arabic {
            font-family: 'Amiri', serif !important;
            font-weight: 400 !important;
            line-height: 1.8;
            direction: rtl;
        }

        /* Mihrab (pointed/ogee arch) frame for hero & feature imagery */
        .arch-mihrab {
            border-top-left-radius: 48% 30% !important;
            border-top-right-radius: 48% 30% !important;
            border-bottom-left-radius: 1.75rem !important;
            border-bottom-right-radius: 1.75rem !important;
        }

        /* Ornamental section divider (arabesque) */
        .section-ornament {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin: 0.5rem auto 0;
            color: #d97706;
        }
        .section-ornament::before,
        .section-ornament::after {
            content: "";
            height: 1px;
            width: clamp(2rem, 12vw, 5rem);
            background: linear-gradient(90deg, transparent, rgba(217,119,6,0.45));
        }
        .section-ornament::after {
            background: linear-gradient(90deg, rgba(217,119,6,0.45), transparent);
        }
        .section-ornament svg { opacity: 0.85; }

        /* 3. Button: Inter SemiBold (600) */
        button, .btn, a.btn, a[class*="bg-blue-600"], a[class*="bg-gradient-"], a[class*="bg-slate-50"], .gallery-tab-btn {
            font-family: 'Inter', sans-serif !important;
            font-weight: 600 !important;
        }

        /* Offset for fixed floating header when scrolling to section anchors */
        section[id] {
            scroll-margin-top: 100px;
        }

        .gradient-overlay {
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.4) 0%, rgba(15, 23, 42, 0.65) 100%);
        }

        .soft-shadow {
            box-shadow: 0 10px 30px -10px rgba(7, 30, 61, 0.03), 0 1px 3px rgba(7, 30, 61, 0.01);
        }

        .soft-shadow-hover:hover {
            box-shadow: 0 20px 40px -10px rgba(7, 30, 61, 0.06), 0 1px 3px rgba(7, 30, 61, 0.01);
            transform: translateY(-4px);
        }

        .soft-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.5);
        }

        @keyframes auroraWave1 {
            0% { transform: translate(0px, 0px) scale(1) rotate(-5deg); opacity: 0.7; }
            50% { transform: translate(40px, -20px) scale(1.08) rotate(3deg); opacity: 0.95; }
            100% { transform: translate(0px, 0px) scale(1) rotate(-5deg); opacity: 0.7; }
        }

        @keyframes auroraWave2 {
            0% { transform: translate(0px, 0px) scale(1) rotate(5deg); opacity: 0.6; }
            50% { transform: translate(-50px, 30px) scale(1.1) rotate(-3deg); opacity: 0.85; }
            100% { transform: translate(0px, 0px) scale(1) rotate(5deg); opacity: 0.6; }
        }

        @keyframes auroraWave3 {
            0% { transform: translate(0px, 0px) scale(1.05) rotate(0deg); opacity: 0.5; }
            50% { transform: translate(30px, 40px) scale(0.95) rotate(-5deg); opacity: 0.8; }
            100% { transform: translate(0px, 0px) scale(1.05) rotate(0deg); opacity: 0.5; }
        }

        .animate-aurora-1,
        .animate-aurora-2,
        .animate-aurora-3 {
            will-change: transform, opacity;
            transform: translateZ(0);
        }

        .animate-aurora-1 {
            animation: auroraWave1 35s ease-in-out infinite;
        }

        .animate-aurora-2 {
            animation: auroraWave2 40s ease-in-out infinite;
        }

        .animate-aurora-3 {
            animation: auroraWave3 45s ease-in-out infinite;
        }

        /* Respect reduced-motion preference and ease up on heavy continuous animations */
        @media (prefers-reduced-motion: reduce) {
            .animate-aurora-1,
            .animate-aurora-2,
            .animate-aurora-3,
            .animate-pulse-glow {
                animation: none;
            }
        }

        /* GPU-accelerate the element driven by the mousemove parallax effect */
        #hero-image-card {
            will-change: transform;
        }

        .islamic-pattern {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cpath d='M30 0 l10 20 l20 10 l-20 10 l-10 20 l-10 -20 l-20 -10 l20 -10 z' fill='%23d97706' fill-opacity='0.02'/%3E%3C/svg%3E");
            background-size: 60px 60px;
        }

        .islamic-pattern-blue-soft {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cpath d='M30 0 l10 20 l20 10 l-20 10 l-10 20 l-10 -20 l-20 -10 l20 -10 z' fill='%23026ea9' fill-opacity='0.008'/%3E%3C/svg%3E");
            background-size: 60px 60px;
        }

        /* Hide scrollbars for Chrome, Safari, Opera, Edge, Firefox, and IE */
        .scrollbar-none::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-none {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Entrance Animation for Hero */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }

        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-350 { animation-delay: 350ms; }
        .delay-400 { animation-delay: 400ms; }
        .delay-500 { animation-delay: 500ms; }

        /* Scroll Reveal Styles — resting state only; Motion (resources/js/app.js) animates to visible and holds the end state */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            will-change: opacity, transform;
        }

        /* Apple/Vercel Style Glowing Borders */
        .glow-card {
            position: relative;
        }

        .glow-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1.5px;
            background: radial-gradient(350px circle at var(--mouse-x, 0px) var(--mouse-y, 0px), rgba(59, 130, 246, 0.45), rgba(245, 158, 11, 0.15), transparent 45%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 10;
        }

        .glow-card:hover::after {
            opacity: 1;
        }

        /* Floating WhatsApp Glow Pulse — transform/opacity only so it runs on the compositor, not box-shadow repaint */
        @keyframes pulseGlow {
            0% { transform: scale(0.95); }
            70% { transform: scale(1); }
            100% { transform: scale(0.95); }
        }
        @keyframes pulseRing {
            0% { transform: scale(1); opacity: 0.7; }
            70% { transform: scale(1.7); opacity: 0; }
            100% { transform: scale(1.7); opacity: 0; }
        }
        .animate-pulse-glow {
            /* No position here: the WhatsApp button already has Tailwind's `fixed` class,
               which is enough to position the ::before ring below. Setting `relative` here
               used to fight `fixed` for the position property (same specificity, this rule
               loads after Tailwind's CSS) and broke the button's fixed placement. */
            animation: pulseGlow 2s infinite;
        }
        .animate-pulse-glow::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: rgba(16, 185, 129, 0.7);
            animation: pulseRing 2s infinite;
            pointer-events: none;
        }

        /* Word by Word Reveal Style */
        .reveal-words {
            display: inline-block;
        }
        .reveal-words .reveal-word {
            display: inline-block;
            opacity: 0;
            transform: translateY(12px);
            will-change: opacity, transform;
        }

        /* Premium Scroll Reveal Directions */
        .reveal-left {
            opacity: 0;
            transform: translateX(-35px);
            will-change: opacity, transform;
        }
        .reveal-right {
            opacity: 0;
            transform: translateX(35px);
            will-change: opacity, transform;
        }
        .reveal-scale {
            opacity: 0;
            transform: scale(0.95);
            will-change: opacity, transform;
        }
        .reveal-card, .reveal-child {
            opacity: 0;
            transform: translateY(35px) scale(0.97);
            will-change: opacity, transform;
        }

        /* FAQ Smooth Height Accordion grid styles */
        .faq-details .faq-content-wrapper {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .faq-details[open] .faq-content-wrapper {
            grid-template-rows: 1fr;
        }

        /* Testimonial Touch Slider Viewport and Track Layout */
        #testimonial-viewport {
            overflow: hidden;
            width: 100%;
        }
        #testimonial-track {
            display: flex;
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform;
        }
        #testimonial-track.grabbing {
            transition: none !important;
        }
        .testimonial-slide {
            flex-shrink: 0;
            width: 100%;
            padding: 0 12px;
            display: flex;
            flex-direction: column;
        }
        @media (min-width: 768px) {
            .testimonial-slide {
                width: 50%;
            }
        }
        @media (min-width: 1024px) {
            .testimonial-slide {
                width: 33.333333%;
            }
        }

        /* Premium Micro-Animations and Micro-Interactions */
        .magnetic-button {
            display: inline-flex;
            /* No transform transition here: Motion now drives the magnetic x/y movement
               directly (see script below) — a competing CSS transition on the same
               property caused the WhatsApp button regression fixed earlier, same risk here. */
        }
        
        header nav a {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Kemitraan premium top accent borders on hover */
        .kemitraan-card {
            position: relative;
            overflow: hidden;
        }
        .kemitraan-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 10;
        }
        .kemitraan-card:hover::before {
            transform: scaleX(1);
        }
        .kemitraan-card-blue::before { background: linear-gradient(90deg, #3b82f6, #1d4ed8); }
        .kemitraan-card-amber::before { background: linear-gradient(90deg, #f59e0b, #d97706); }
        .kemitraan-card-emerald::before { background: linear-gradient(90deg, #10b981, #047857); }

        /* Premium Detail Modal */
        #detail-drawer {
            transition: visibility 0.4s;
        }
        #detail-drawer.hidden {
            visibility: hidden;
            pointer-events: none;
        }
        #detail-drawer:not(.hidden) {
            visibility: visible;
            pointer-events: auto;
        }
        #detail-drawer #drawer-backdrop {
            transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        #detail-drawer.active #drawer-backdrop {
            opacity: 1;
        }

        /* Mobile-first: slide up as a bottom-sheet */
        #detail-drawer #drawer-content {
            transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.45s cubic-bezier(0.16, 1, 0.3, 1);
            transform: translateY(100%);
            opacity: 1;
        }
        #detail-drawer.active #drawer-content {
            transform: translateY(0);
            opacity: 1;
        }

        /* Desktop: centered card that scales + lifts in */
        @media (min-width: 640px) {
            #detail-drawer #drawer-content {
                transform: translateY(20px) scale(0.97);
                opacity: 0;
            }
            #detail-drawer.active #drawer-content {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        /* Hide scrollbar but keep scroll on the content area */
        #drawer-scroll {
            scrollbar-width: thin;
            scrollbar-color: rgba(148,163,184,0.4) transparent;
        }
        #drawer-scroll::-webkit-scrollbar { width: 5px; }
        #drawer-scroll::-webkit-scrollbar-thumb {
            background: rgba(148,163,184,0.35);
            border-radius: 99px;
        }

        @media (prefers-reduced-motion: reduce) {
            #detail-drawer #drawer-content,
            #detail-drawer #drawer-backdrop {
                transition: opacity 0.2s ease;
                transform: none;
            }
        }

        /* Redesigned Hero Background */
        #beranda {
            background-color: #0a1628 !important;
            background-image: linear-gradient(to right, #0a1628 0%, #0c1e38 30%, rgba(10, 22, 40, 0.88) 44%, rgba(10, 22, 40, 0.55) 58%, rgba(10, 22, 40, 0.2) 72%, rgba(10, 22, 40, 0.05) 85%, transparent 100%), url('{{ $heroImageUrl }}') !important;
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            position: relative;
            min-height: 100vh;
        }
        
        @media (min-width: 1024px) {
            #beranda {
                background-position: 60% center;
                min-height: 100vh;
            }
        }
        
        @media (max-width: 1023px) {
            #beranda {
                background-size: cover;
                background-position: center center;
                background-image: linear-gradient(to bottom, rgba(10, 22, 40, 0.85) 0%, rgba(10, 22, 40, 0.96) 100%), url('{{ $heroImageUrl }}') !important;
            }
        }

        /* Custom style for Haramain Section */
        .digital-mono {
            font-variant-numeric: tabular-nums;
        }
        .border-gold-glow {
            box-shadow: 0 0 20px rgba(200, 158, 43, 0.12);
        }
    </style>
</head>

<body class="bg-stone-50 text-slate-800 overflow-x-hidden antialiased">
    <!-- Scroll Progress Bar (mengisi saat scroll ke bawah) -->
    <div id="scroll-progress-track" class="fixed top-0 left-0 w-full h-1 z-[60] pointer-events-none bg-transparent">
        <div id="scroll-progress-bar" class="h-full w-full origin-left bg-gradient-to-r from-blue-600 via-emerald-500 to-amber-500 shadow-[0_1px_6px_rgba(37,99,235,0.4)] transition-transform duration-100 ease-out" style="transform: scaleX(0);"></div>
    </div>

    <!-- BEGIN: Floating Modern Header (Fully Rounded Capsule) -->
    <header class="fixed top-4 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-7xl z-50">
        <nav class="bg-white/90 backdrop-blur-md border border-white/60 rounded-full shadow-[0_10px_35px_-10px_rgba(0,0,0,0.05)] px-6 py-3 flex items-center justify-between transition-all duration-300">
            <div class="flex items-center gap-3">
                <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO.webp') }}" alt="{{ $settings['site_name'] ?? 'IZI Travel' }}" class="h-8 w-auto object-contain" width="480" height="168" fetchpriority="high" decoding="async" />
            </div>
            
            <!-- Desktop Navigation Links -->
            <div class="hidden md:flex items-center space-x-1 font-medium text-slate-600">
                <a class="text-blue-600 bg-blue-600/10 px-3.5 py-2 rounded-full transition duration-200" href="#beranda">Beranda</a>
                <a class="hover:text-blue-600 hover:bg-blue-600/5 px-3.5 py-2 rounded-full transition duration-200" href="#tentang-kami">Tentang Kami</a>
                <a class="hover:text-blue-600 hover:bg-blue-600/5 px-3.5 py-2 rounded-full transition duration-200" href="#paket-umrah">Paket Umrah</a>
                <a class="hover:text-blue-600 hover:bg-blue-600/5 px-3.5 py-2 rounded-full transition duration-200" href="#galeri">Galeri</a>
                <a class="hover:text-blue-600 hover:bg-blue-600/5 px-3.5 py-2 rounded-full transition duration-200" href="#testimoni">Testimoni</a>
                <a class="hover:text-blue-600 hover:bg-blue-600/5 px-3.5 py-2 rounded-full transition duration-200" href="#artikel">Artikel</a>
                <a class="hover:text-blue-600 hover:bg-blue-600/5 px-3.5 py-2 rounded-full transition duration-200" href="#kemitraan">Kemitraan</a>
                <a class="hover:text-blue-600 hover:bg-blue-600/5 px-3.5 py-2 rounded-full transition duration-200" href="#kontak">Kontak</a>
            </div>
            
            <div class="flex items-center gap-2">
                <a class="magnetic-button bg-blue-600 text-white px-3.5 py-2.5 sm:px-5 sm:py-2.5 rounded-full font-bold hover:bg-blue-700 transition-all duration-300 flex items-center gap-2 text-xs shadow-md shadow-blue-500/10 active:scale-95" href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank">
                    <!-- WhatsApp Icon -->
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
                    </svg>
                    <span class="hidden sm:inline">Konsultasi/WhatsApp</span>
                </a>
            </div>
        </nav>
    </header>
    <!-- END: Floating Header -->

    <main>
    <!-- BEGIN: HeroSection (Modern Split Layout) -->
    <section id="beranda" class="relative pt-32 pb-20 overflow-hidden" data-purpose="hero-banner">
        <!-- Aurora Wave Effects (Stretched & Wavy Ambient Lights styled in gold/blue) -->
        <div class="absolute -right-[10%] -top-[10%] w-[900px] h-[350px] bg-blue-500/10 rounded-[100%] blur-[100px] pointer-events-none animate-aurora-1"></div>
        <div class="absolute -left-[10%] top-[10%] w-[800px] h-[300px] bg-[#c89e2b]/8 rounded-[100%] blur-[90px] pointer-events-none animate-aurora-2"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-16 flex flex-col lg:flex-row items-center gap-12 relative z-10">
            <!-- Left Column: Content -->
            <div class="lg:w-7/12 flex flex-col justify-center items-center lg:items-start text-center lg:text-left">
                @php $heroCalligraphy = array_key_exists('hero_calligraphy', $settings) ? $settings['hero_calligraphy'] : 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ'; @endphp
                @if (!empty($heroCalligraphy))
                    <p class="font-arabic text-[#c89e2b]/80 text-2xl md:text-3xl mb-4 animate-fade-in-up text-center lg:text-left" dir="rtl">{{ $heroCalligraphy }}</p>
                @endif
                
                <!-- Badge Kemenag (Navy blue glass) -->
                @php $heroBadgeText = $settings['hero_badge'] ?? ('Berizin Resmi Kemenag RI • PPIU ' . ($settings['footer_ppiu_number'] ?? '91202054619660001')); @endphp
                @if (!empty($heroBadgeText))
                    <div class="inline-flex items-center gap-2 bg-[#051c33]/85 border border-[#0d345c] text-white/90 text-xs px-4 py-2 rounded-full w-fit mb-6 shadow-sm animate-fade-in-up delay-100">
                        <i data-lucide="shield-check" class="w-4 h-4 text-[#c89e2b]"></i>
                        <span class="font-semibold tracking-wide">{{ $heroBadgeText }}</span>
                    </div>
                @endif

                @php
                    $hero_tagline = $settings['site_tagline'] ?? 'Jalan Mudah ke Baitullah Semua Kalangan';
                    if (
                        $hero_tagline === 'Wujudkan Umrah Impian Anda Bersama IZI Travel' ||
                        $hero_tagline === 'Perjalanan Umrah Nyaman & Penuh Makna' ||
                        $hero_tagline === 'Jalan Mudah ke Baitullah Semua Kalangan'
                    ) {
                        $line1 = "Jalan Mudah";
                        $line2 = "ke Baitullah";
                        $line3 = "Semua Kalangan";
                        $line3Gold = false; // line3 is white, not gold
                    } else {
                        // Dynamic splitter
                        $words = explode(' ', $hero_tagline);
                        $count = count($words);
                        if ($count >= 5) {
                            $line1 = implode(' ', array_slice($words, 0, 2));
                            $line2 = implode(' ', array_slice($words, 2, 2));
                            $line3 = implode(' ', array_slice($words, 4));
                        } else {
                            $line1 = $hero_tagline;
                            $line2 = '';
                            $line3 = '';
                        }
                        $line3Gold = true;
                    }
                    
                    $site_desc = $settings['site_description'] ?? 'Perjalanan Umrah yang nyaman, aman, dan terpercaya — dari keberangkatan hingga kembali ke tanah air.';
                @endphp

                <h1 class="text-5xl md:text-6xl lg:text-[72px] font-calligraphy text-white leading-[1.08] mb-5 tracking-tight animate-fade-in-up delay-200">
                    <span class="block text-white">{{ $line1 }}</span>
                    @if(!empty($line2))
                        <span class="block text-[#c89e2b]">{{ $line2 }}</span>
                    @endif
                    @if(!empty($line3))
                        <span class="block {{ ($line3Gold ?? true) ? 'text-[#c89e2b]' : 'text-white' }}">{{ $line3 }}</span>
                    @endif
                </h1>

                <p class="text-sm md:text-base text-white/75 mb-7 leading-relaxed font-normal max-w-lg animate-fade-in-up delay-300 mx-auto lg:mx-0">
                    {{ $site_desc }}
                </p>

                <!-- Hero Feature Icons Row -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-x-5 gap-y-4 mb-8 animate-fade-in-up delay-350">
                    @php
                        $heroFeatures = [
                            ['icon' => 'star', 'title' => $settings['hero_feature_1_title'] ?? 'Pelayanan', 'sub' => $settings['hero_feature_1_sub'] ?? 'Nyaman & Aman'],
                            ['icon' => 'building-2', 'title' => $settings['hero_feature_2_title'] ?? 'Hotel Pilihan', 'sub' => $settings['hero_feature_2_sub'] ?? 'Makkah & Madinah'],
                            ['icon' => 'plane', 'title' => $settings['hero_feature_3_title'] ?? 'Tiket PP & Visa', 'sub' => $settings['hero_feature_3_sub'] ?? 'Resmi'],
                            ['icon' => 'users', 'title' => $settings['hero_feature_4_title'] ?? 'Pendampingan', 'sub' => $settings['hero_feature_4_sub'] ?? 'Mutawwif'],
                        ];
                    @endphp
                    @foreach ($heroFeatures as $feat)
                        <div class="flex items-center gap-2.5">
                            <div class="shrink-0 w-8 h-8 rounded-lg bg-white/10 border border-white/15 flex items-center justify-center">
                                <i data-lucide="{{ $feat['icon'] }}" class="w-4 h-4 text-[#c89e2b]"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-white leading-tight truncate">{{ $feat['title'] }}</p>
                                <p class="text-[10px] text-white/55 font-medium leading-tight">{{ $feat['sub'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Actions CTA Buttons -->
                <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4 animate-fade-in-up delay-400 w-full sm:w-auto">
                    <a href="#paket-umrah" class="magnetic-button w-full sm:w-auto bg-[#c89e2b] hover:bg-[#b88e1b] text-[#113a6b] px-8 py-3.5 rounded-full font-bold transition shadow-lg shadow-[#c89e2b]/15 transform active:scale-95 text-sm text-center justify-center flex items-center gap-2">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                        {{ $settings['cta_packages_label'] ?? 'Lihat Paket Umrah' }}
                    </a>
                    <a href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel%2C%20saya%20ingin%20konsultasi%20mengenai%20paket%20umrah" target="_blank" class="magnetic-button w-full sm:w-auto bg-white/5 hover:bg-white/10 text-white border border-white/20 px-8 py-3.5 rounded-full font-bold transition text-sm text-center justify-center flex items-center gap-2 backdrop-blur-sm">
                        <i data-lucide="message-square" class="w-4 h-4"></i>
                        {{ $settings['cta_consultation_label'] ?? 'Konsultasi Gratis' }}
                    </a>
                </div>
                
                <!-- Bottom Stats Bar -->
                @php
                    $heroStats = [
                        ['icon' => 'shield-check', 'value' => $settings['hero_stat_berizin'] ?? 'BERIZIN', 'label' => 'RESMI', 'sub' => $settings['hero_stat_berizin_sub'] ?? 'Kemenag RI'],
                        ['icon' => 'users', 'value' => $settings['about_departed_count'] ?? '500', 'label' => '+ JAMAAH', 'sub' => $settings['hero_stat_jamaah_sub'] ?? 'Telah Berangkat'],
                        ['icon' => 'star', 'value' => $settings['about_satisfaction_rate'] ?? '4.9', 'label' => '/5 RATING', 'sub' => $settings['hero_stat_rating_sub'] ?? 'Dari Jamaah'],
                        ['icon' => 'heart', 'value' => '100%', 'label' => 'AMANAH', 'sub' => $settings['hero_stat_amanah_sub'] ?? '& Terpercaya'],
                    ];
                @endphp
                <div class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl px-5 py-4 mt-10 grid grid-cols-2 sm:grid-cols-4 gap-x-4 gap-y-4 animate-fade-in-up delay-500">
                    @foreach ($heroStats as $stat)
                        <div class="flex items-center gap-3">
                            <div class="shrink-0 w-9 h-9 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center">
                                <i data-lucide="{{ $stat['icon'] }}" class="w-4.5 h-4.5 text-[#c89e2b]" style="width:18px;height:18px"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-black text-white tracking-wider leading-none">
                                    <span class="text-sm">{{ $stat['value'] }}</span>{{ $stat['label'] }}
                                </p>
                                <p class="text-[10px] text-white/50 font-medium mt-0.5 leading-none">{{ $stat['sub'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Column: Info Widget (Glassmorphism card) -->
            <div id="hero-right-col" class="w-full lg:w-5/12 flex flex-col gap-6 relative z-30 animate-fade-in-up delay-300">
                <div id="hero-image-card" class="bg-gradient-to-br from-[#0c2540]/90 via-[#071930]/95 to-[#030d1a]/95 border border-[#c89e2b]/30 rounded-[2.5rem] p-7 shadow-2xl relative overflow-hidden text-white w-full backdrop-blur-xl group hover:border-[#c89e2b]/50 transition-all duration-500">
                    <!-- Localized Islamic pattern overlay -->
                    <div class="absolute inset-0 islamic-pattern opacity-[0.015] pointer-events-none"></div>

                    <!-- Glowing Accents -->
                    <div class="absolute -right-16 -top-16 w-52 h-52 bg-[#c89e2b]/20 rounded-full blur-[60px] pointer-events-none group-hover:scale-110 transition-transform duration-700"></div>
                    <div class="absolute -left-16 -bottom-16 w-52 h-52 bg-blue-500/10 rounded-full blur-[60px] pointer-events-none group-hover:scale-110 transition-transform duration-700"></div>

                    @if ($nearestPackage)
                        <!-- Title Section -->
                        <div class="flex items-center justify-between mb-6 border-b border-white/10 pb-4 relative z-10">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#c89e2b]/30 to-[#c89e2b]/10 text-[#c89e2b] flex items-center justify-center border border-[#c89e2b]/30 shadow-md">
                                    <i data-lucide="plane-takeoff" class="w-5 h-5"></i>
                                </span>
                                <div class="text-left">
                                    <span class="inline-flex items-center gap-1 bg-[#c89e2b]/10 border border-[#c89e2b]/30 text-[#c89e2b] text-[9px] font-black uppercase tracking-widest px-2.5 py-0.5 rounded-full mb-1 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Keberangkatan Terdekat
                                    </span>
                                    <h2 class="text-xs text-white/95 font-bold truncate max-w-[180px] sm:max-w-[210px] mt-0.5">{{ $nearestPackage->name }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Countdown Timer (Split-flap Style) -->
                        <div id="hero-countdown" class="grid grid-cols-4 gap-3 mb-6 relative z-10" data-date="{{ \Carbon\Carbon::parse($nearestPackage->departure_date)->format('Y-m-d') }}">
                            <!-- Days -->
                            <div class="bg-gradient-to-b from-[#18395e] via-[#0d223d] to-[#08172c] border border-white/10 rounded-2xl p-3 text-center relative overflow-hidden shadow-lg select-none group/unit hover:border-[#c89e2b]/30 transition-colors duration-300">
                                <div class="absolute left-0 right-0 top-1/2 h-[1px] bg-slate-950/40 z-10 pointer-events-none"></div>
                                <span class="block text-3xl font-black text-amber-200 tracking-tight font-mono leading-none z-0 relative drop-shadow-[0_2px_3px_rgba(0,0,0,0.4)]" id="countdown-days">--</span>
                                <span class="text-[9px] uppercase tracking-widest text-slate-300 font-bold mt-1.5 block relative z-10 group-hover/unit:text-amber-400 transition-colors duration-300">Hari</span>
                            </div>
                            <!-- Hours -->
                            <div class="bg-gradient-to-b from-[#18395e] via-[#0d223d] to-[#08172c] border border-white/10 rounded-2xl p-3 text-center relative overflow-hidden shadow-lg select-none group/unit hover:border-[#c89e2b]/30 transition-colors duration-300">
                                <div class="absolute left-0 right-0 top-1/2 h-[1px] bg-slate-950/40 z-10 pointer-events-none"></div>
                                <span class="block text-3xl font-black text-amber-200 tracking-tight font-mono leading-none z-0 relative drop-shadow-[0_2px_3px_rgba(0,0,0,0.4)]" id="countdown-hours">--</span>
                                <span class="text-[9px] uppercase tracking-widest text-slate-300 font-bold mt-1.5 block relative z-10 group-hover/unit:text-amber-400 transition-colors duration-300">Jam</span>
                            </div>
                            <!-- Minutes -->
                            <div class="bg-gradient-to-b from-[#18395e] via-[#0d223d] to-[#08172c] border border-white/10 rounded-2xl p-3 text-center relative overflow-hidden shadow-lg select-none group/unit hover:border-[#c89e2b]/30 transition-colors duration-300">
                                <div class="absolute left-0 right-0 top-1/2 h-[1px] bg-slate-950/40 z-10 pointer-events-none"></div>
                                <span class="block text-3xl font-black text-amber-200 tracking-tight font-mono leading-none z-0 relative drop-shadow-[0_2px_3px_rgba(0,0,0,0.4)]" id="countdown-mins">--</span>
                                <span class="text-[9px] uppercase tracking-widest text-slate-300 font-bold mt-1.5 block relative z-10 group-hover/unit:text-amber-400 transition-colors duration-300">Menit</span>
                            </div>
                            <!-- Seconds -->
                            <div class="bg-gradient-to-b from-[#18395e] via-[#0d223d] to-[#08172c] border border-white/10 rounded-2xl p-3 text-center relative overflow-hidden shadow-lg select-none group/unit hover:border-[#c89e2b]/30 transition-colors duration-300">
                                <div class="absolute left-0 right-0 top-1/2 h-[1px] bg-slate-950/40 z-10 pointer-events-none"></div>
                                <span class="block text-3xl font-black text-amber-200 tracking-tight font-mono leading-none z-0 relative drop-shadow-[0_2px_3px_rgba(0,0,0,0.4)]" id="countdown-secs">--</span>
                                <span class="text-[9px] uppercase tracking-widest text-slate-300 font-bold mt-1.5 block relative z-10 group-hover/unit:text-amber-400 transition-colors duration-300">Detik</span>
                            </div>
                        </div>
                    @else
                        <!-- Fallback if no upcoming packages found -->
                        <div class="text-center py-6 border-b border-white/10 mb-6 relative z-10">
                            <p class="text-xs text-white/60">Tidak ada jadwal keberangkatan terdekat saat ini.</p>
                        </div>
                    @endif

                    <!-- Total Departed & Upcoming Schedule -->
                    <div class="space-y-6 relative z-10">
                        <!-- Departed Stat Badge (Trust Seal Banner) -->
                        <div class="bg-gradient-to-r from-emerald-500/10 via-teal-500/5 to-[#0b2038]/50 border border-emerald-500/30 rounded-2xl p-4 flex items-center justify-between shadow-inner relative overflow-hidden group/seal">
                            <div class="absolute -right-8 -top-8 w-20 h-20 bg-emerald-500/10 rounded-full blur-xl pointer-events-none group-hover/seal:scale-125 transition-transform duration-500"></div>
                            <div class="flex items-center gap-3">
                                <span class="p-2.5 rounded-xl bg-gradient-to-tr from-emerald-500/20 to-emerald-400/5 text-emerald-400 border border-emerald-500/30 flex items-center justify-center shadow-md">
                                    <i data-lucide="users" class="w-5 h-5"></i>
                                </span>
                                <div class="text-left">
                                    <p class="text-xs text-emerald-300 font-extrabold uppercase tracking-wider leading-none">{{ $settings['hero_stat_title'] ?? 'Total Keberangkatan' }}</p>
                                    <p class="text-[10px] text-white/60 mt-1 font-medium">{{ $settings['hero_stat_subtitle'] ?? 'Jamaah terberangkatkan' }}</p>
                                </div>
                            </div>
                            <span class="text-lg font-black text-emerald-300 bg-emerald-500/10 border border-emerald-500/30 px-3.5 py-1.5 rounded-xl shadow-sm tracking-wider font-mono">
                                {{ $settings['hero_stat_value'] ?? (($settings['about_departed_count'] ?? '10') . 'K+') }}
                            </span>
                        </div>

                        <!-- Upcoming Schedule List (Boarding Ticket style) -->
                        @if (isset($upcomingPackages) && $upcomingPackages->count() > 0)
                            <div>
                                <div class="flex items-center justify-between mb-4 border-b border-white/10 pb-2">
                                    <h4 class="text-xs font-black uppercase tracking-widest text-[#c89e2b] flex items-center gap-2">
                                        <i data-lucide="calendar-days" class="w-4 h-4 text-[#c89e2b]"></i>
                                        Jadwal Paket Terdekat
                                    </h4>
                                    <span class="text-[9px] text-white/40 font-bold uppercase tracking-wider">Tiket Terbatas</span>
                                </div>
                                <div class="space-y-3">
                                    @foreach ($upcomingPackages as $p)
                                        @php
                                            $deptDate = \Carbon\Carbon::parse($p->departure_date);
                                            $dayStr = $deptDate->format('d');
                                            $monthStr = $deptDate->translatedFormat('M');
                                            $yearStr = $deptDate->format('Y');
                                        @endphp
                                        <div class="bg-gradient-to-r from-white/[0.02] to-white/[0.04] hover:from-[#c89e2b]/5 hover:to-white/[0.08] border border-white/5 hover:border-[#c89e2b]/30 rounded-2xl p-3 flex items-center justify-between transition-all duration-300 group/ticket">
                                            <!-- Date Page Block -->
                                            <div class="bg-gradient-to-b from-[#18395e] to-[#0d223d] border border-white/10 rounded-xl px-2.5 py-1.5 text-center flex flex-col justify-center items-center shadow-md min-w-[52px] group-hover/ticket:border-[#c89e2b]/30 transition-all duration-300">
                                                <span class="text-sm font-black text-amber-200 leading-none">{{ $dayStr }}</span>
                                                <span class="text-[8px] font-bold text-white/70 uppercase tracking-widest mt-0.5 leading-none">{{ $monthStr }}</span>
                                            </div>
                                            
                                            <!-- Ticket Content -->
                                            <div class="min-w-0 flex-1 pl-3.5 pr-2 text-left">
                                                <p class="text-xs font-bold text-white group-hover/ticket:text-amber-200 transition-colors duration-300 truncate">{{ $p->name }}</p>
                                                <p class="text-[10px] text-white/50 flex items-center gap-1 mt-1 font-medium">
                                                    <i data-lucide="tag" class="w-3.5 h-3.5 text-[#c89e2b]"></i>
                                                    {{ $p->category ?? 'Premium' }} • {{ $yearStr }}
                                                </p>
                                            </div>
                                            
                                            <!-- Detail Action Button -->
                                            <a href="{{ route('packages.show', $p->slug) }}" class="flex-shrink-0 bg-white/5 hover:bg-[#c89e2b] text-white hover:text-[#0b223f] border border-white/10 hover:border-transparent text-[10px] font-extrabold px-3.5 py-2 rounded-xl transition-all duration-300 shadow-md active:scale-95 flex items-center gap-1">
                                                <span>Detail</span>
                                                <i data-lucide="chevron-right" class="w-3 h-3 group-hover/ticket:translate-x-0.5 transition-transform duration-300"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Smooth Wave Transition at Hero Bottom -->
        <div class="absolute bottom-[-1px] left-0 w-full overflow-hidden z-20 leading-[0]">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-[50px] md:h-[90px] fill-stone-50">
                <path d="M0,80 C200,110 400,110 600,80 C800,50 1000,50 1200,80 L1200,120 L0,120 Z"></path>
            </svg>
        </div>
    </section>
    <!-- END: HeroSection -->

    <!-- BEGIN: Tentang Kami -->
    <section class="py-16 md:py-24 bg-gradient-to-b from-stone-50 via-stone-100/30 to-stone-50 relative overflow-hidden islamic-pattern" id="tentang-kami" data-purpose="about-us">
        <!-- Blurred Nabawi Mosque Background Image (Split Left with Right Gradient Fade) -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-[25%_center] scale-105" style="background-image: linear-gradient(to right, rgba(250, 250, 249, 0.65) 0%, rgba(250, 250, 249, 0.95) 45%, rgba(250, 250, 249, 1) 60%), url('{{ asset('images/section_madinah_nabawi.webp') }}');"></div>
            <!-- Soft vertical gradient overlay to fade the top and bottom edges seamlessly -->
            <div class="absolute inset-0 bg-gradient-to-b from-stone-50 via-transparent to-stone-50"></div>
        </div>

        <!-- Ambient Glow Blobs -->
        <div class="absolute -left-[10%] top-[20%] w-[500px] h-[300px] bg-blue-400/8 rounded-full blur-[90px] pointer-events-none -z-10"></div>
        <div class="absolute -right-[5%] bottom-[10%] w-[400px] h-[300px] bg-amber-400/6 rounded-full blur-[80px] pointer-events-none -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Top Layout: Description, Stats, and 3D Photo Stack Collage -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center mb-10">
                <!-- Left: Short description & stats -->
                <div class="lg:col-span-6 space-y-6 reveal flex flex-col items-center lg:items-start text-center lg:text-left">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50/90 border border-blue-200/80 text-blue-600 text-xs font-black tracking-widest uppercase shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <i data-lucide="info" class="w-3.5 h-3.5 text-blue-600/80"></i>
                        {{ $settings['about_badge'] ?? 'Tentang Kami' }}
                    </span>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight leading-tight reveal-words text-center lg:text-left">
                        {{ $settings['about_title'] ?? 'Melayani Perjalanan Suci Anda dengan Sepenuh Hati' }}
                    </h2>
                    <p class="text-slate-600 text-sm md:text-base leading-relaxed font-normal text-center lg:text-left">
                        {{ $settings['about_description'] ?? 'Penyelenggara perjalanan ibadah Umrah dan Haji Premium dengan layanan bintang 5 di Ring 1 pelataran Masjidil Haram & Nabawi.' }}
                    </p>
                    <div class="grid grid-cols-2 gap-6 pt-4 w-full">
                        <div class="group relative bg-white/95 backdrop-blur-md p-6 rounded-2xl border border-blue-600/15 hover:border-amber-500/40 shadow-md hover:shadow-xl hover:shadow-amber-500/[0.02] transition-all duration-300 hover:-translate-y-1">
                            <div class="absolute top-4 right-4 text-blue-600/10 group-hover:text-blue-600/20 transition duration-300">
                                <i data-lucide="smile" class="w-8 h-8"></i>
                            </div>
                            <p class="text-4xl font-black bg-gradient-to-r from-blue-600 to-sky-500 bg-clip-text text-transparent"><span class="stat-counter" data-target="{{ $settings['about_satisfaction_rate'] ?? '99' }}">0</span>%</p>
                            <p class="text-[10px] text-slate-400 mt-2 uppercase font-extrabold tracking-wider leading-none">{{ $settings['about_stat_1_label'] ?? 'Kepuasan Jamaah' }}</p>
                        </div>
                        <div class="group relative bg-white/95 backdrop-blur-md p-6 rounded-2xl border border-blue-600/15 hover:border-amber-500/40 shadow-md hover:shadow-xl hover:shadow-amber-500/[0.02] transition-all duration-300 hover:-translate-y-1">
                            <div class="absolute top-4 right-4 text-amber-500/10 group-hover:text-amber-500/20 transition duration-300">
                                <i data-lucide="users" class="w-8 h-8"></i>
                            </div>
                            <p class="text-4xl font-black bg-gradient-to-r from-blue-600 to-sky-500 bg-clip-text text-transparent"><span class="stat-counter" data-target="{{ $settings['about_departed_count'] ?? '10' }}" data-suffix="k">0</span>+</p>
                            <p class="text-[10px] text-slate-400 mt-2 uppercase font-extrabold tracking-wider leading-none">{{ $settings['about_stat_2_label'] ?? 'Jamaah Berangkat' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Overlapping 3D Image Collage -->
                <div class="lg:col-span-6 relative w-full h-[360px] sm:h-[440px] flex items-center justify-center reveal-right">
                    <!-- Base Backdrop Card (Departure Image) -->
                    <div class="absolute left-6 top-6 w-[80%] aspect-[4/3] rounded-3xl overflow-hidden shadow-2xl shadow-slate-900/10 border-4 border-white transform -rotate-2 hover:rotate-0 transition duration-500 select-none">
                        <img src="{{ !empty($settings['about_image_1']) ? (str_starts_with($settings['about_image_1'], 'images/') ? asset($settings['about_image_1']) : asset('storage/' . $settings['about_image_1'])) : asset('images/gallery_departure.webp') }}" alt="Keberangkatan Jemaah" class="w-full h-full object-cover" width="600" height="450" loading="lazy" decoding="async">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/40 to-transparent"></div>
                    </div>
                    
                    <!-- Overlay Foreground Card (Manasik Preparation Image) -->
                    <div class="absolute right-6 bottom-6 w-[60%] aspect-[4/3] rounded-3xl overflow-hidden shadow-2xl shadow-slate-900/15 border-4 border-white transform rotate-3 hover:rotate-0 transition duration-500 z-10 select-none">
                        <img src="{{ !empty($settings['about_image_2']) ? (str_starts_with($settings['about_image_2'], 'images/') ? asset($settings['about_image_2']) : asset('storage/' . $settings['about_image_2'])) : asset('images/gallery_manasik.webp') }}" alt="Bimbingan Manasik" class="w-full h-full object-cover" width="600" height="450" loading="lazy" decoding="async">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/40 to-transparent"></div>
                    </div>
                </div>
            </div>

            <!-- Izin Resmi PPIU -->
            <div class="my-16 md:my-24 reveal-up">
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm shadow-slate-900/[0.03]">
                    <div class="grid lg:grid-cols-12 gap-10 lg:gap-14 items-center p-7 sm:p-10 lg:p-12">

                        <!-- Konten utama -->
                        <div class="lg:col-span-7 space-y-5">
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-3">
                                @if(!empty($settings['about_ppiu_logo']))
                                    <div class="inline-flex items-center justify-center h-11 px-3 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
                                        <img src="{{ str_starts_with($settings['about_ppiu_logo'], 'images/') ? asset($settings['about_ppiu_logo']) : asset('storage/' . $settings['about_ppiu_logo']) }}" alt="Logo Kemenag" class="h-8 w-auto object-contain">
                                    </div>
                                @endif
                                <span class="inline-flex items-center gap-2 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                                    Legalitas Perusahaan
                                </span>
                            </div>

                            @php
                                $ppiuLabelText = $settings['about_ppiu_label'] ?? 'Izin Penyelenggara Resmi Perjalanan Ibadah Umrah (PPIU)';
                            @endphp

                            <h3 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                                {{ $ppiuLabelText }}
                            </h3>
                            <p class="text-sm md:text-[15px] leading-relaxed text-slate-500 dark:text-slate-400 max-w-2xl">
                                {{ $settings['about_ppiu_desc'] ?? 'IZI Travel berkomitmen penuh dalam menyelenggarakan ibadah Umrah dan Haji sesuai syariat Islam, dengan kepastian program keberangkatan dan bimbingan ibadah yang sah & diakui secara hukum.' }}
                            </p>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-7 pt-6 border-t border-slate-200 dark:border-slate-700">
                                <div class="flex items-center gap-3">
                                    <span class="shrink-0 flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-[11px] font-bold text-slate-500 dark:text-slate-400">01</span>
                                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 leading-snug">Izin PPIU Terdaftar</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="shrink-0 flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-[11px] font-bold text-slate-500 dark:text-slate-400">02</span>
                                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 leading-snug">Pengawasan Kementerian Haji dan Umrah</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="shrink-0 flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-[11px] font-bold text-slate-500 dark:text-slate-400">03</span>
                                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 leading-snug">Nomor Izin Dapat Diverifikasi</p>
                                </div>
                            </div>

                            <p class="flex items-start gap-2 pt-1 text-[11px] leading-relaxed text-slate-400 dark:text-slate-500 max-w-2xl">
                                <i data-lucide="scale" class="w-3.5 h-3.5 shrink-0 mt-px text-slate-300"></i>
                                Penyelenggaraan tunduk pada Undang-Undang No. 8 Tahun 2019 tentang Penyelenggaraan Ibadah Haji dan Umrah.
                            </p>
                        </div>

                        <!-- Plat nomor izin -->
                        <div class="lg:col-span-5">
                            <div class="relative overflow-hidden bg-slate-900 dark:bg-slate-950 rounded-xl px-8 py-10 sm:py-12 text-center">
                                <!-- Faint islamic pattern watermark -->
                                <div class="absolute inset-0 islamic-pattern opacity-[0.4] pointer-events-none"></div>
                                <div class="relative">
                                    <div class="inline-flex items-center justify-center gap-2">
                                        <i data-lucide="file-text" class="w-3.5 h-3.5 text-slate-400"></i>
                                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Nomor Izin</p>
                                    </div>
                                    <p class="mt-5 text-[1.5rem] sm:text-[1.9rem] leading-tight tracking-tight font-semibold text-white select-all font-mono">
                                        {{ $settings['footer_ppiu_number'] ?? '91202054619660001' }}
                                    </p>
                                    <div class="mt-7 pt-5 border-t border-white/10">
                                        <p class="text-[11px] font-medium text-slate-400">Diterbitkan &amp; diawasi oleh Kementerian Haji dan Umrah RI</p>
                                        <div class="mt-5 flex items-center justify-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                            <span class="text-[11px] font-bold uppercase tracking-widest text-emerald-400">Resmi Terdaftar</span>
                                        </div>
                                    </div>
                                    <a href="https://haji.go.id/" target="_blank" rel="noopener noreferrer" class="mt-6 inline-flex items-center gap-1.5 text-[10px] font-semibold text-slate-400 hover:text-white transition duration-200">
                                        Verifikasi di Kemenhaj (haji.go.id)
                                        <i data-lucide="external-link" class="w-3 h-3"></i>
                                    </a>
                        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Middle Layout: Bento Grid of Vision & Mission -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12 md:mb-16">
                <!-- Visi Card (Asymmetric 5-Span) -->
                <div class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-900 text-white rounded-[2rem] p-8 md:p-10 border border-blue-500/20 shadow-xl overflow-hidden flex flex-col justify-between lg:col-span-5 min-h-[260px] group hover:scale-[1.01] transition duration-300 reveal-left">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                    <div>
                        <div class="bg-white/15 border border-white/20 p-3.5 rounded-2xl text-white w-fit mb-6 shadow-inner">
                            <i data-lucide="eye" class="w-6 h-6"></i>
                        </div>
                        <h3 class="font-extrabold text-white text-xl mb-4 tracking-tight">{{ $settings['about_vision_label'] ?? 'Visi Kami' }}</h3>
                        <p class="text-slate-300 text-xs md:text-sm leading-relaxed font-light">
                            {{ $settings['about_vision'] ?? 'Menjadi penyelenggara perjalanan ibadah Umrah dan Haji tepercaya yang mengedepankan kemurnian ibadah sesuai sunnah serta pelayanan VIP demi kenyamanan jemaah.' }}
                        </p>
                    </div>
                </div>

                <!-- Misi Card (Asymmetric 7-Span) -->
                <div class="relative bg-white rounded-[2rem] p-8 md:p-10 border border-slate-100/80 shadow-xl shadow-slate-900/[0.02] overflow-hidden lg:col-span-7 min-h-[260px] flex flex-col justify-between group hover:scale-[1.01] transition duration-300 reveal-right">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-amber-500/5 rounded-full blur-3xl"></div>
                    <div>
                        <div class="bg-amber-50 border border-amber-100 p-3.5 rounded-2xl text-amber-500 w-fit mb-6 shadow-sm">
                            <i data-lucide="target" class="w-6 h-6"></i>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-xl mb-4 tracking-tight">{{ $settings['about_mission_label'] ?? 'Misi Kami' }}</h3>
                        <p class="text-slate-500 text-xs md:text-sm leading-relaxed font-light">
                            {{ $settings['about_mission'] ?? 'Memberikan pelayanan akomodasi hotel bintang 5 di pelataran Masjidil Haram & Nabawi, memfasilitasi bimbingan manasik komprehensif, serta melayani jemaah dengan ramah dan profesional layaknya keluarga sendiri.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bottom Layout: Founders section -->
            <div class="text-center space-y-4 mb-8 reveal">
                <h3 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $settings['team_section_title'] ?? 'Di Balik Layar IZI Travel' }}</h3>
                <p class="text-slate-500 max-w-lg mx-auto text-xs md:text-sm leading-relaxed font-light">
                    {{ $settings['team_section_subtitle'] ?? 'Dipimpin oleh tim profesional berpengalaman yang mendedikasikan diri sepenuhnya untuk melayani tamu-tamu Allah SWT.' }}
                </p>
            </div>

            @php
                $founders = $teams->filter(fn($t) => str_contains(strtolower($t->role), 'founder'));
                $otherTeams = $teams->reject(fn($t) => str_contains(strtolower($t->role), 'founder'));
            @endphp

            <!-- Founders Grid (Prominent) -->
            <div class="grid grid-cols-1 {{ $founders->count() === 1 ? 'max-w-md' : ($founders->count() === 2 ? 'md:grid-cols-2 max-w-3xl' : 'md:grid-cols-3 max-w-5xl') }} gap-8 mx-auto mb-12 reveal-stagger">
                @foreach ($founders as $t)
                    <div class="bg-white hover:bg-gradient-to-b hover:from-white hover:to-blue-600/[0.02] p-8 rounded-[2rem] border border-slate-100/70 hover:border-blue-600/15 shadow-md shadow-slate-900/[0.03] hover:shadow-lg hover:shadow-blue-500/[0.04] hover:-translate-y-1 transition duration-300 text-center relative overflow-hidden group reveal-card">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-600 to-sky-400 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
                        @if($t->image_url)
                            <img src="{{ $t->image_url }}" alt="{{ $t->name }}" class="w-24 h-24 rounded-full object-cover shadow-lg shadow-blue-500/10 mb-6 mx-auto border-4 border-white transition-all duration-300 group-hover:scale-[1.04]" width="96" height="96" loading="lazy" decoding="async">
                        @else
                            <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-blue-600 to-amber-400 flex items-center justify-center text-white text-3xl font-extrabold shadow-lg shadow-blue-500/10 mb-6 mx-auto border-4 border-white transition-all duration-300 group-hover:scale-[1.04]">{{ $t->initial ?? '...' }}</div>
                        @endif
                        <h4 class="font-extrabold text-slate-900 text-lg mb-1">{{ $t->name }}</h4>
                        <p class="text-blue-600 text-xs font-bold uppercase tracking-wider mb-4">{{ $t->role }}</p>
                        <p class="text-slate-500 text-xs leading-relaxed font-light">
                            {{ $t->description }}
                        </p>
                    </div>
                @endforeach
            </div>

            <!-- Other Team Members (Smaller & Grouped) -->
            @if($otherTeams->isNotEmpty())
                <div class="max-w-4xl mx-auto mt-16">
                    <div class="relative flex items-center justify-center mb-10 reveal">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-slate-200/60"></div>
                        </div>
                        <div class="relative bg-stone-50 px-4">
                            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ $settings['team_other_section_label'] ?? 'Tim Pendukung & Pembimbing' }}</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-2 gap-3 md:gap-6 reveal-stagger">
                        @foreach ($otherTeams as $t)
                            <div class="bg-white/80 hover:bg-white backdrop-blur-sm p-4 md:p-5 rounded-2xl border border-slate-100 hover:border-blue-600/10 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col sm:flex-row items-center sm:items-start gap-3 sm:gap-4 text-center sm:text-left group {{ $loop->last && $loop->iteration % 2 !== 0 ? 'col-span-2 sm:col-span-1 md:col-span-1' : '' }} reveal-card">
                                @if($t->image_url)
                                    <img src="{{ $t->image_url }}" alt="{{ $t->name }}" class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover shadow-sm border-2 border-white transition-all duration-300 group-hover:scale-105 flex-shrink-0" width="56" height="56" loading="lazy" decoding="async">
                                @else
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-gradient-to-tr from-blue-600 to-amber-400 flex items-center justify-center text-white text-base sm:text-lg font-extrabold shadow-sm border-2 border-white transition-all duration-300 group-hover:scale-105 flex-shrink-0">{{ $t->initial ?? '...' }}</div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <h5 class="font-extrabold text-slate-900 text-xs sm:text-sm mb-0.5 group-hover:text-blue-600 transition duration-200">{{ $t->name }}</h5>
                                    <p class="text-blue-600 text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider mb-2 leading-none">{{ $t->role }}</p>
                                    <p class="text-slate-500 text-[10px] sm:text-xs leading-relaxed font-light">
                                        {{ $t->description }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- END: Tentang Kami -->

    <!-- BEGIN: Why Choose Us -->
    <section class="py-16 md:py-24 bg-gradient-to-br from-blue-600/12 via-blue-600/3 to-stone-50 islamic-pattern-blue-soft relative overflow-hidden" data-purpose="features-grid">
        <!-- Blurred Kaaba Background Image (Split Right with Left Gradient Fade) -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-[75%_center] scale-105" style="background-image: linear-gradient(to right, rgba(250, 250, 249, 1) 40%, rgba(250, 250, 249, 0.95) 55%, rgba(250, 250, 249, 0.65) 100%), url('{{ asset('images/section_kaaba_detail.webp') }}');"></div>
            <!-- Soft vertical gradient overlay to fade the top and bottom edges seamlessly -->
            <div class="absolute inset-0 bg-gradient-to-b from-stone-50 via-transparent to-stone-50"></div>
        </div>

        <!-- Section Header (Premium Split Layout) -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16 reveal text-center lg:text-left relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 items-center lg:items-start">
                <div class="space-y-3 flex flex-col items-center lg:items-start">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50/90 border border-blue-200/80 text-blue-600 text-xs font-black tracking-widest uppercase shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <i data-lucide="award" class="w-3.5 h-3.5 text-blue-600/80"></i>
                        {{ $settings['features_badge'] ?? 'Kenapa Kami' }}
                    </span>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight leading-tight text-center lg:text-left">
                        {{ $settings['features_section_title'] ?? 'Keunggulan Layanan Kami' }}
                    </h2>
                </div>
                <p class="text-slate-500 text-sm md:text-base max-w-xl font-light leading-relaxed lg:pb-1 text-center lg:text-left">
                    {{ $settings['features_section_subtitle'] ?? 'Mitra tepercaya perjalanan ibadah Anda dengan standar pelayanan tinggi dan kekeluargaan.' }}
                </p>
            </div>
        </div>

        <!-- Bento Grid Layout -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 reveal-stagger" data-stagger="true">
                <!-- Feature 1: Wide Dark Bento Card -->
                <div class="reveal-card md:col-span-2 lg:col-span-2 bg-gradient-to-br from-blue-950 via-slate-900 to-blue-900 border border-blue-800/30 hover:border-amber-500/20 p-8 md:p-10 rounded-[2rem] shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col justify-between group relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-amber-500/10 rounded-full blur-3xl"></div>
                    <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-blue-600/15 rounded-full blur-3xl"></div>
                    <div>
                        <div class="mb-6 bg-amber-500/10 text-amber-400 p-4 rounded-2xl w-fit border border-amber-500/20 shadow-inner">
                            @if (!empty($settings['feature_1_image']))
                                <img src="{{ str_starts_with($settings['feature_1_image'], 'images/') ? asset($settings['feature_1_image']) : asset('storage/' . $settings['feature_1_image']) }}" alt="" class="w-8 h-8 object-contain" width="32" height="32" loading="lazy" decoding="async">
                            @else
                                <i data-lucide="{{ $settings['feature_1_icon'] ?? 'award' }}" class="w-8 h-8"></i>
                            @endif
                        </div>
                        <h3 class="font-extrabold text-white text-xl mb-3 group-hover:text-amber-400 transition duration-300">{{ $settings['feature_1_title'] ?? 'Legalitas Resmi Kemenag' }}</h3>
                        <p class="text-blue-100/70 text-xs md:text-sm leading-relaxed max-w-xl font-light">{{ $settings['feature_1_desc'] ?? 'Memiliki izin PPIU resmi dari Kementerian Agama RI untuk kepastian keamanan hukum perjalanan Anda.' }}</p>
                    </div>
                </div>

                <!-- Feature 2: Standard Card -->
                <div class="reveal-card lg:col-span-1 bg-white hover:bg-gradient-to-br hover:from-white hover:to-blue-600/[0.03] border border-slate-100/70 hover:border-blue-600/15 p-8 rounded-[2rem] shadow-md shadow-slate-900/[0.02] hover:shadow-lg hover:shadow-blue-500/[0.04] hover:-translate-y-1 transition duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="mb-6 bg-blue-50 text-blue-600 p-4 rounded-2xl w-fit border border-blue-100/50">
                            @if (!empty($settings['feature_2_image']))
                                <img src="{{ str_starts_with($settings['feature_2_image'], 'images/') ? asset($settings['feature_2_image']) : asset('storage/' . $settings['feature_2_image']) }}" alt="" class="w-8 h-8 object-contain" width="32" height="32" loading="lazy" decoding="async">
                            @else
                                <i data-lucide="{{ $settings['feature_2_icon'] ?? 'file-check' }}" class="w-8 h-8"></i>
                            @endif
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-lg mb-3 group-hover:text-blue-600 transition">{{ $settings['feature_2_title'] ?? 'Jaminan Visa Umrah' }}</h3>
                        <p class="text-slate-500 text-xs leading-relaxed font-light">{{ $settings['feature_2_desc'] ?? 'Proses penerbitan visa yang aman, transparan, and terkonfirmasi langsung ke sistem kedutaan.' }}</p>
                    </div>
                </div>

                <!-- Feature 3: Standard Card -->
                <div class="reveal-card lg:col-span-1 bg-white hover:bg-gradient-to-br hover:from-white hover:to-blue-600/[0.03] border border-slate-100/70 hover:border-blue-600/15 p-8 rounded-[2rem] shadow-md shadow-slate-900/[0.02] hover:shadow-lg hover:shadow-blue-500/[0.04] hover:-translate-y-1 transition duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="mb-6 bg-amber-50 text-amber-600 p-4 rounded-2xl w-fit border border-amber-100/50">
                            @if (!empty($settings['feature_3_image']))
                                <img src="{{ str_starts_with($settings['feature_3_image'], 'images/') ? asset($settings['feature_3_image']) : asset('storage/' . $settings['feature_3_image']) }}" alt="" class="w-8 h-8 object-contain" width="32" height="32" loading="lazy" decoding="async">
                            @else
                                <i data-lucide="{{ $settings['feature_3_icon'] ?? 'building-2' }}" class="w-8 h-8"></i>
                            @endif
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-lg mb-3 group-hover:text-blue-600 transition">{{ $settings['feature_3_title'] ?? 'Hotel Dekat Pelataran' }}</h3>
                        <p class="text-slate-500 text-xs leading-relaxed font-light">{{ $settings['feature_3_desc'] ?? 'Akomodasi hotel bintang pilihan dengan jarak yang dekat memudahkan Anda beribadah di Masjidil Haram &amp; Nabawi.' }}</p>
                    </div>
                </div>

                <!-- Feature 4: Standard Card -->
                <div class="reveal-card lg:col-span-1 bg-white hover:bg-gradient-to-br hover:from-white hover:to-blue-600/[0.03] border border-slate-100/70 hover:border-blue-600/15 p-8 rounded-[2rem] shadow-md shadow-slate-900/[0.02] hover:shadow-lg hover:shadow-blue-500/[0.04] hover:-translate-y-1 transition duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="mb-6 bg-blue-50 text-blue-600 p-4 rounded-2xl w-fit border border-blue-100/50">
                            @if (!empty($settings['feature_4_image']))
                                <img src="{{ str_starts_with($settings['feature_4_image'], 'images/') ? asset($settings['feature_4_image']) : asset('storage/' . $settings['feature_4_image']) }}" alt="" class="w-8 h-8 object-contain" width="32" height="32" loading="lazy" decoding="async">
                            @else
                                <i data-lucide="{{ $settings['feature_4_icon'] ?? 'compass' }}" class="w-8 h-8"></i>
                            @endif
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-lg mb-3 group-hover:text-blue-600 transition">{{ $settings['feature_4_title'] ?? 'Muthawwif Khas Nusantara' }}</h3>
                        <p class="text-slate-500 text-xs leading-relaxed font-light">{{ $settings['feature_4_desc'] ?? 'Muthawwif &amp; pembimbing ibadah bersertifikasi, membimbing sesuai sunnah dengan keramahan khas Indonesia.' }}</p>
                    </div>
                </div>

                <!-- Feature 5: Standard Card -->
                <div class="reveal-card lg:col-span-1 bg-white hover:bg-gradient-to-br hover:from-white hover:to-blue-600/[0.03] border border-slate-100/70 hover:border-blue-600/15 p-8 rounded-[2rem] shadow-md shadow-slate-900/[0.02] hover:shadow-lg hover:shadow-blue-500/[0.04] hover:-translate-y-1 transition duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="mb-6 bg-amber-50 text-amber-600 p-4 rounded-2xl w-fit border border-amber-100/50">
                            @if (!empty($settings['feature_5_image']))
                                <img src="{{ str_starts_with($settings['feature_5_image'], 'images/') ? asset($settings['feature_5_image']) : asset('storage/' . $settings['feature_5_image']) }}" alt="" class="w-8 h-8 object-contain" width="32" height="32" loading="lazy" decoding="async">
                            @else
                                <i data-lucide="{{ $settings['feature_5_icon'] ?? 'phone-call' }}" class="w-8 h-8"></i>
                            @endif
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-lg mb-3 group-hover:text-blue-600 transition">{{ $settings['feature_5_title'] ?? 'Layanan Siaga &amp; Peduli' }}</h3>
                        <p class="text-slate-500 text-xs leading-relaxed font-light">{{ $settings['feature_5_desc'] ?? 'Customer support dan tim handling operasional siaga melayani Anda 24 jam dengan asas kekeluargaan.' }}</p>
                    </div>
                </div>

                <!-- Feature 6: Wide Horizontal Card -->
                <div class="reveal-card md:col-span-2 lg:col-span-3 bg-gradient-to-br from-blue-600 via-indigo-750 to-blue-900 text-white p-8 md:p-10 rounded-[2rem] shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col sm:flex-row items-start sm:items-center gap-5 group relative overflow-hidden text-left">
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-sky-500/20 rounded-full blur-2xl"></div>
                    
                    <div class="bg-white/10 text-white p-4 rounded-2xl border border-white/20 shadow-inner flex-shrink-0 w-fit">
                        @if (!empty($settings['feature_6_image']))
                            <img src="{{ str_starts_with($settings['feature_6_image'], 'images/') ? asset($settings['feature_6_image']) : asset('storage/' . $settings['feature_6_image']) }}" alt="" class="w-8 h-8 object-contain" width="32" height="32" loading="lazy" decoding="async">
                        @else
                            <i data-lucide="{{ $settings['feature_6_icon'] ?? 'plane-takeoff' }}" class="w-8 h-8"></i>
                        @endif
                    </div>
                    <div class="space-y-2 w-full">
                        <h3 class="font-extrabold text-white text-lg md:text-xl tracking-tight leading-tight">{{ $settings['feature_6_title'] ?? 'Kepastian Tiket Terbang' }}</h3>
                        <p class="text-blue-100/90 text-xs md:text-sm leading-relaxed max-w-3xl font-light">{{ $settings['feature_6_desc'] ?? 'Kepastian tanggal keberangkatan dengan tiket pesawat premium (PP) yang telah issued sejak pendaftaran.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Why Choose Us -->

    <!-- BEGIN: RegistrationFlow -->
    <section class="py-16 md:py-24 relative overflow-hidden bg-stone-50" data-purpose="registration-flow">
        <!-- Background Image overlay -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-[center_center] scale-105" style="background-image: linear-gradient(to bottom, rgba(250, 250, 249, 0.93) 0%, rgba(250, 250, 249, 0.98) 100%), url('{{ asset('images/section.webp') }}');"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="space-y-3 flex flex-col items-center mb-12">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50/90 border border-blue-200/80 text-blue-600 text-xs font-black tracking-widest uppercase shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <i data-lucide="help-circle" class="w-3.5 h-3.5 text-blue-600/80"></i>
                    Cara Daftar
                </span>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight reveal-words">{{ $settings['registration_title'] ?? 'Alur Pendaftaran Mudah' }}</h2>
                @include('partials.ornament')
                <p class="text-slate-500 font-light text-xs md:text-sm max-w-md mx-auto leading-relaxed">{{ $settings['registration_subtitle'] ?? '6 langkah mudah mempersiapkan perjalanan suci Anda bersama IZI Travel' }}</p>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:flex md:flex-nowrap gap-y-8 gap-x-4 md:gap-0 items-start max-w-5xl mx-auto reveal">
                @php
                    $stepsJson = $settings['registration_steps'] ?? null;
                    $steps = [];
                    if ($stepsJson) {
                        $steps = json_decode($stepsJson, true);
                    }
                    if (empty($steps)) {
                        for ($i = 1; $i <= 6; $i++) {
                            $title = $settings['registration_step_' . $i . '_title'] ?? null;
                            $desc = $settings['registration_step_' . $i . '_description'] ?? null;
                            $icon = $settings['registration_step_' . $i . '_icon'] ?? null;
                            if ($title || $desc) {
                                $steps[] = [
                                    'title' => $title ?? '',
                                    'description' => $desc ?? '',
                                    'icon' => $icon ?? 'compass'
                                ];
                            }
                        }
                    }
                    if (empty($steps)) {
                        $steps = [
                            ['title' => 'Pilih Paket', 'description' => 'Pilih paket yang sesuai dengan tanggal dan keinginan Anda.', 'icon' => 'message-square'],
                            ['title' => 'Konsultasi', 'description' => 'Hubungi customer service kami untuk detail keberangkatan.', 'icon' => 'compass'],
                            ['title' => 'Kirim Berkas', 'description' => 'Lengkapi dokumen paspor, foto, dan syarat administrasi.', 'icon' => 'credit-card'],
                            ['title' => 'Uang Muka (DP)', 'description' => 'Lakukan deposit untuk mengamankan kursi penerbangan Anda.', 'icon' => 'file-text'],
                            ['title' => 'Manasik', 'description' => 'Ikuti bimbingan manasik teori & praktek sesuai sunnah.', 'icon' => 'book-open'],
                            ['title' => 'Berangkat', 'description' => 'Pelepasan di bandara dan mulai perjalanan ibadah Anda.', 'icon' => 'plane-takeoff'],
                        ];
                    }
                @endphp
                @foreach ($steps as $index => $step)
                    @if ($index > 0)
                        <!-- Arrow Connector -->
                        <div class="hidden md:flex items-center h-16 px-2 text-slate-200/80">
                            <svg class="w-8 h-4 stroke-current animate-pulse" fill="none" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    @endif
                    <div class="flex flex-col items-center group flex-1">
                        <div class="w-16 h-16 bg-gradient-to-tr from-blue-600 to-sky-400 rounded-2xl flex items-center justify-center text-white mb-4 shadow-lg shadow-blue-500/25 transform transition duration-300 group-hover:scale-105 group-hover:shadow-blue-500/35">
                            <i data-lucide="{{ $step['icon'] ?? 'compass' }}" class="w-7 h-7"></i>
                        </div>
                        <p class="font-extrabold text-slate-900 text-sm md:text-base">{{ $step['title'] ?? '' }}</p>
                        <p class="text-xs text-slate-400 mt-1 max-w-[150px] mx-auto leading-relaxed hidden md:block">{{ $step['description'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- END: RegistrationFlow -->

    <!-- BEGIN: FeaturedPackages -->
    <section class="relative py-16 md:py-24 mt-8 overflow-hidden" id="paket-umrah" data-purpose="packages-grid">
        <!-- Blurred Makkah Grand Mosque Sunset Background Image (Split Left with Right Gradient Fade) -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-[15%_center] scale-105" style="background-image: linear-gradient(to right, rgba(250, 250, 249, 0.65) 0%, rgba(250, 250, 249, 0.95) 45%, rgba(250, 250, 249, 1) 60%), url('{{ asset('images/section_makkah_wide.webp') }}');"></div>
            <!-- Soft vertical gradient overlay to fade the top and bottom edges seamlessly -->
            <div class="absolute inset-0 bg-gradient-to-b from-stone-50 via-transparent to-stone-50"></div>
        </div>

        <!-- Premium Ambient Glow Blobs -->
        <div class="absolute left-1/2 -translate-x-1/2 top-1/4 w-[500px] h-[250px] bg-emerald-400/10 rounded-full blur-[100px] pointer-events-none -z-10 animate-aurora-1"></div>
        <div class="absolute left-1/3 top-1/2 w-[350px] h-[250px] bg-blue-400/8 rounded-full blur-[90px] pointer-events-none -z-10 animate-aurora-2"></div>
        <div class="absolute right-1/4 bottom-1/4 w-[400px] h-[300px] bg-amber-400/6 rounded-full blur-[110px] pointer-events-none -z-10 animate-aurora-3"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="mb-12 text-center reveal">
                <div class="space-y-3 flex flex-col items-center">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50/90 border border-blue-200/80 text-blue-600 text-xs font-black tracking-widest uppercase shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <i data-lucide="compass" class="w-3.5 h-3.5 text-blue-600/80"></i>
                        {{ $settings['packages_label'] ?? 'Paket Pilihan' }}
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight reveal-words">
                        {{ $settings['packages_section_title'] ?? 'Paket Umrah Kami' }}
                    </h2>
                    @include('partials.ornament')
                    <p class="text-slate-500 text-xs sm:text-sm md:text-base max-w-2xl font-light leading-relaxed pt-2 mx-auto">
                        {{ $settings['packages_section_subtitle'] ?? 'Pilihan paket perjalanan terbaik dengan fasilitas hotel premium Ring 1 demi kenyamanan ibadah Anda.' }}
                    </p>
                </div>
            </div>
            @php
                $groupedPackages = $packages->groupBy(function ($p) {
                    return $p->category ?: 'Lainnya';
                });
                
                $dbCategories = \App\Models\Category::where('is_active', true)->orderBy('order')->pluck('name')->toArray();
                
                // Append any category names from grouped packages that aren't in $dbCategories
                foreach ($groupedPackages as $catName => $pkgs) {
                    $nameToUse = $catName ?: 'Lainnya';
                    if (!in_array($nameToUse, $dbCategories)) {
                        $dbCategories[] = $nameToUse;
                    }
                }
                
                $categoryOrder = array_unique($dbCategories);
                
                $sortedGroups = collect($categoryOrder)
                    ->mapWithKeys(function($c) use ($groupedPackages) {
                        $packagesOfCat = $groupedPackages->get($c);
                        if ($c === 'Lainnya' && !$packagesOfCat) {
                            $packagesOfCat = $groupedPackages->get('');
                        }
                        return $packagesOfCat ? [$c => $packagesOfCat] : [];
                    });
            @endphp

            @foreach ($sortedGroups as $categoryName => $categoryPackages)
                @php
                    $icon = 'package';
                    $colorClass = 'bg-slate-50 border-slate-100 text-slate-600';
                    
                    $lowerName = strtolower($categoryName);
                    if (str_contains($lowerName, 'ekonomi') || str_contains($lowerName, 'hemat') || str_contains($lowerName, 'promo')) {
                        $icon = 'tag';
                        $colorClass = 'bg-emerald-50 border-emerald-100 text-emerald-600';
                    } elseif (str_contains($lowerName, 'vvip') || str_contains($lowerName, 'luxury') || str_contains($lowerName, 'gold') || str_contains($lowerName, 'super')) {
                        $icon = 'gem';
                        $colorClass = 'bg-amber-50 border-amber-100 text-amber-600';
                    } elseif (str_contains($lowerName, 'premium') || str_contains($lowerName, 'vip') || str_contains($lowerName, 'exclusive')) {
                        $icon = 'crown';
                        $colorClass = 'bg-blue-50 border-blue-100 text-blue-600';
                    }
                @endphp
                <div class="mb-14 last:mb-0">
                    <div class="flex items-center gap-3 mb-8 reveal">
                        <div class="w-10 h-10 rounded-2xl {{ $colorClass }} shadow-sm flex items-center justify-center">
                            <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Paket {{ $categoryName }}</h3>
                            <p class="text-xs text-slate-400 font-medium">{{ $categoryPackages->count() }} paket tersedia</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 reveal-stagger" data-stagger="true">
                        @foreach ($categoryPackages as $package)
                            @php
                                $badgeClasses = match ($package->badge_color) {
                                    'amber' => 'bg-amber-500 text-slate-950',
                                    'emerald' => 'bg-emerald-600 text-white',
                                    'indigo' => 'bg-indigo-600 text-white',
                                    'rose' => 'bg-rose-600 text-white',
                                    default => 'bg-slate-900 text-white',
                                };
                                $isFeatured = !empty($package->badge_label);
                                $cardBorderClass = $isFeatured && $package->badge_color === 'amber' 
                                    ? 'border-amber-500/25 hover:border-amber-500/50 shadow-md shadow-amber-500/[0.02] hover:shadow-lg hover:shadow-amber-500/[0.06]' 
                                    : 'border-slate-100/80 hover:border-blue-600/20 shadow-md shadow-slate-900/[0.02] hover:shadow-lg hover:shadow-blue-500/[0.04]';
                            @endphp
                            <div class="reveal-card bg-white rounded-3xl transition-all duration-300 flex flex-col justify-between border {{ $cardBorderClass }} hover:-translate-y-1.5 group relative overflow-visible" data-purpose="package-item">
                                <div class="w-full">
                                    <div class="relative aspect-[16/10] overflow-hidden select-none rounded-t-3xl">
                                        <img src="{{ $package->image_url }}" alt="{{ $package->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" width="640" height="400" loading="lazy" decoding="async" />
                                        @if ($package->badge_label)
                                            <span class="absolute top-4 left-4 {{ $badgeClasses }} text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm">{{ $package->badge_label }}</span>
                                        @endif
                                    </div>
                                    <div class="p-6">
                                        <h3 class="font-extrabold text-base md:text-lg text-slate-900 mb-4 group-hover:text-blue-600 transition leading-snug line-clamp-2 min-h-[2.5rem]">{{ $package->name }}</h3>
                                        
                                        <div class="grid grid-cols-2 gap-3 mb-2">
                                            <div class="col-span-2 flex items-center gap-3 bg-slate-50/50 p-3 rounded-xl border border-slate-100/50">
                                                <span class="bg-blue-600/10 text-blue-600 p-2 rounded-xl border border-blue-600/10 flex items-center justify-center flex-shrink-0"><i data-lucide="calendar" class="w-5 h-5"></i></span>
                                                <div>
                                                    <p class="text-[10px] md:text-xs text-slate-400 font-extrabold uppercase tracking-wider leading-none mb-1">Keberangkatan</p>
                                                    <p class="text-xs md:text-sm font-bold text-slate-700 leading-none">{{ $package->departure_date->locale('id')->translatedFormat('d F Y') }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3 bg-slate-50/50 p-3 rounded-xl border border-slate-100/50 min-w-0">
                                                <span class="bg-blue-600/10 text-blue-600 p-2 rounded-xl border border-blue-600/10 flex items-center justify-center flex-shrink-0"><i data-lucide="plane" class="w-5 h-5"></i></span>
                                                <div class="min-w-0">
                                                    <p class="text-[10px] md:text-xs text-slate-400 font-extrabold uppercase tracking-wider leading-none mb-1">Maskapai</p>
                                                    <p class="text-xs md:text-sm font-bold text-slate-700 leading-none truncate">{{ $package->airline }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3 bg-slate-50/50 p-3 rounded-xl border border-slate-100/50 min-w-0">
                                                <span class="bg-blue-600/10 text-blue-600 p-2 rounded-xl border border-blue-600/10 flex items-center justify-center flex-shrink-0"><i data-lucide="hotel" class="w-5 h-5"></i></span>
                                                <div class="min-w-0">
                                                    <p class="text-[10px] md:text-xs text-slate-400 font-extrabold uppercase tracking-wider leading-none mb-1">Hotel</p>
                                                    <p class="text-xs md:text-sm font-bold text-slate-700 leading-none truncate">{{ $package->hotel }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="px-6 pb-6 pt-5 border-t-2 border-dashed border-slate-200/85 bg-blue-600/[0.01] rounded-b-3xl flex items-center justify-between relative">
                                    <!-- Ticket notches overlaying the dashed line -->
                                    <div class="absolute left-0 top-0 -translate-x-1/2 -translate-y-1/2 w-5 h-5 rounded-full bg-[#fafaf9] border border-slate-200/60 shadow-[inset_-2px_0_3px_rgba(0,0,0,0.02)] z-10"></div>
                                    <div class="absolute right-0 top-0 translate-x-1/2 -translate-y-1/2 w-5 h-5 rounded-full bg-[#fafaf9] border border-slate-200/60 shadow-[inset_2px_0_3px_rgba(0,0,0,0.02)] z-10"></div>

                                    <div class="flex items-center gap-3">
                                        <!-- Tiny Barcode Stub -->
                                        <div class="hidden xs:flex items-center gap-[1.5px] opacity-20 group-hover:opacity-35 transition duration-300 select-none flex-shrink-0">
                                            <div class="w-[1.5px] h-6 bg-slate-800"></div>
                                            <div class="w-[3px] h-6 bg-slate-800"></div>
                                            <div class="w-[1px] h-6 bg-slate-800"></div>
                                            <div class="w-[4px] h-6 bg-slate-800"></div>
                                            <div class="w-[1.5px] h-6 bg-slate-800"></div>
                                            <div class="w-[3px] h-6 bg-slate-800"></div>
                                            <div class="w-[1px] h-6 bg-slate-800"></div>
                                            <div class="w-[2px] h-6 bg-slate-800"></div>
                                        </div>
                                        <div>
                                            <p class="text-[10px] md:text-xs text-slate-400 font-bold uppercase tracking-wider mb-0.5">{{ $settings['packages_price_label'] ?? 'Mulai dari' }}</p>
                                            <p class="font-extrabold text-lg md:text-xl text-blue-600">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('packages.show', $package->slug) }}" aria-label="Detail paket {{ $package->name }}" class="magnetic-button inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl text-xs md:text-sm font-bold transition shadow-sm shadow-blue-500/10 active:scale-95 group/btn">
                                        <span>{{ $settings['packages_detail_btn'] ?? 'Detail' }}</span>
                                        <i data-lucide="arrow-right" class="w-4 h-4 transition-transform duration-200 group-hover/btn:translate-x-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
    </section>
    <!-- END: FeaturedPackages -->

    <!-- BEGIN: Partners -->
    <section class="py-8 md:py-10 bg-slate-50/20" data-purpose="partners-logo-cloud">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl md:text-3xl font-extrabold text-center text-slate-900 mb-6 tracking-tight reveal">{{ $settings['partners_section_title'] ?? 'Mitra Maskapai Penerbangan' }}</h2>
            <div class="flex flex-wrap justify-center items-center gap-8 sm:gap-10 md:gap-16 opacity-85 reveal">
                @foreach ($partners as $partner)
                    <div class="flex items-center justify-center grayscale hover:grayscale-0 transition duration-300">
                        @if ($partner->logo_type === 'svg')
                            <div class="h-10 md:h-16 flex items-center justify-center text-slate-700 dark:text-slate-350 [&>svg]:!h-full [&>svg]:w-auto">
                                {!! $partner->logo_path !!}
                            </div>
                        @else
                            <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="h-10 md:h-16 w-auto object-contain" loading="lazy" decoding="async">
                        @endif
                    </div>
                @endforeach
                <!-- Premium Hotel Link -->
                <div class="text-amber-500 font-extrabold text-lg md:text-2xl italic hover:text-amber-600 transition duration-300">
                    {{ $settings['partners_extra'] ?? '+ Akomodasi Bintang 5' }}
                </div>
            </div>
        </div>
    </section>
    <!-- END: Partners -->

    <!-- BEGIN: Gallery -->
    <section class="py-16 md:py-24 bg-stone-50" id="galeri" data-purpose="gallery-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4 mb-12 reveal">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50/90 border border-blue-200/80 text-blue-600 text-xs font-black tracking-widest uppercase shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <i data-lucide="images" class="w-3.5 h-3.5 text-blue-600/80"></i>
                    {{ $settings['gallery_label'] ?? 'Galeri &amp; Dokumentasi' }}
                </span>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight reveal-words">{{ $settings['gallery_section_title'] ?? 'Galeri Kegiatan &amp; Testimoni' }}</h2>
                @include('partials.ornament')
                <p class="text-slate-500 max-w-md mx-auto text-xs md:text-sm">{{ $settings['gallery_section_subtitle'] ?? 'Dokumentasi perjalanan jamaah IZI Travel dan testimoni langsung dari Baitullah.' }}</p>
                
            @php
                $albums = $galleries->groupBy(function($item) {
                    return trim($item->category_label) ?: 'Umum';
                })->map(function($items, $name) {
                    $coverItem = $items->firstWhere('type', 'photo') ?? $items->first();
                    
                    $mappedItems = $items->map(function($item) {
                        return [
                            'type' => $item->type,
                            'src' => $item->image_url,
                            'title' => $item->title,
                            'category' => $item->type === 'video' ? ($item->video_platform === 'youtube' ? 'YouTube' : 'Instagram Reel') : ($item->category_label ?? 'Foto'),
                            'video_id' => $item->video_id,
                            'video_platform' => $item->video_platform,
                        ];
                    })->values();

                    return (object) [
                        'name' => $name,
                        'cover_url' => $coverItem ? $coverItem->image_url : null,
                        'items' => $mappedItems,
                        'count' => $items->count(),
                        'last_updated' => $items->max('updated_at'),
                    ];
                })->sortByDesc('last_updated');
                
                $totalAlbumsCount = $albums->count();
                $displayAlbums = $albums->take(6);
            @endphp
            
            <!-- Gallery Grid (Albums Folder View) -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-8" id="gallery-grid">
                @foreach ($displayAlbums as $album)
                    <!-- Album Folder Card -->
                    <div class="album-folder-card reveal-scale group bg-white hover:bg-gradient-to-b hover:from-white hover:to-blue-600/[0.02] rounded-2xl sm:rounded-[2rem] border border-slate-100/80 hover:border-blue-600/15 p-3 sm:p-5 soft-shadow hover:shadow-xl transition-all duration-300 flex flex-col gap-3 sm:gap-5 relative overflow-hidden cursor-pointer" 
                         data-album-name="{{ $album->name }}"
                         data-items="{{ json_encode($album->items) }}">
                        
                        <!-- Folder Tab Shape Design -->
                        <div class="relative aspect-[4/3] rounded-xl sm:rounded-2xl overflow-hidden bg-slate-50 border border-slate-100/80 group-hover:border-blue-600/15 flex items-center justify-center shadow-inner">
                            <!-- Cover Image -->
                            @if ($album->cover_url)
                                <img src="{{ $album->cover_url }}" alt="{{ $album->name }}" class="w-full h-full object-cover transition-transform duration-750 group-hover:scale-[1.03]" width="400" height="300" loading="lazy" decoding="async" />
                            @else
                                <div class="flex flex-col items-center gap-2 text-slate-300">
                                    <i data-lucide="folder" class="w-8 h-8 sm:w-12 sm:h-12 stroke-[1.5]"></i>
                                </div>
                            @endif
                            
                            <!-- Folder Tag Badge on Top Left -->
                            <div class="absolute top-2 left-2 sm:top-4 sm:left-4 bg-slate-950/80 backdrop-blur-md text-white text-[8px] sm:text-[9px] font-extrabold px-2 py-1 sm:px-3 sm:py-1.5 rounded-full uppercase tracking-wider flex items-center gap-1 sm:gap-1.5 shadow-sm">
                                <i data-lucide="folder" class="w-3 sm:w-3.5 h-3 sm:h-3.5"></i>
                                Album
                            </div>
                            
                            <!-- Media Count Badge on Bottom Right -->
                            <div class="absolute bottom-2 right-2 sm:bottom-4 sm:right-4 bg-blue-600 text-white text-[8px] sm:text-[10px] font-black px-2 py-1 sm:px-3.5 sm:py-2 rounded-lg sm:rounded-xl shadow-md transition group-hover:bg-blue-700 tracking-wider">
                                {{ $album->count }} <span class="hidden sm:inline">Foto &amp; Video</span><span class="inline sm:hidden">Media</span>
                            </div>

                            <!-- Play Overlay if it contains videos -->
                            <div class="absolute inset-0 bg-slate-950/20 group-hover:bg-slate-950/30 transition duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <div class="w-10 h-10 sm:w-14 sm:h-14 bg-white/90 backdrop-blur-md rounded-full flex items-center justify-center text-blue-600 shadow-lg scale-90 group-hover:scale-100 transition-all duration-300">
                                    <i data-lucide="eye" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Album Description Info -->
                        <div class="flex flex-col">
                            <h3 class="font-extrabold text-slate-900 text-sm sm:text-lg group-hover:text-blue-600 transition truncate">{{ $album->name }}</h3>
                            <p class="text-[10px] sm:text-xs text-slate-400 mt-0.5 sm:mt-1 flex items-center gap-1 sm:gap-1.5 font-medium">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-blue-600"></i>
                                <span>Lihat dokumentasi<span class="hidden sm:inline"> perjalanan</span></span>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($totalAlbumsCount > 6)
                <div class="text-center mt-12">
                    <a href="{{ route('public.gallery') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 hover:text-blue-600 font-extrabold text-sm rounded-2xl shadow-sm hover:shadow transition-all duration-200 active:scale-95">
                        <i data-lucide="folder-open" class="w-4 h-4 text-blue-500"></i>
                        <span>Lihat Semua Album</span>
                    </a>
                </div>
            @endif
        </div>

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
    </section>
    <!-- END: Gallery -->

    <!-- BEGIN: Testimonials -->
    <section class="py-16 md:py-24 bg-gradient-to-tr from-blue-600/12 via-blue-600/3 to-stone-50 overflow-hidden relative" id="testimoni" data-purpose="testimonials">
        <!-- Ambient Glowing Orbs -->
        <div class="absolute left-1/4 top-1/4 w-[450px] h-[220px] bg-blue-400/5 rounded-full blur-[100px] pointer-events-none -z-10 animate-aurora-1"></div>
        <div class="absolute right-1/4 bottom-1/4 w-[400px] h-[200px] bg-emerald-400/5 rounded-full blur-[100px] pointer-events-none -z-10 animate-aurora-2"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 reveal flex flex-col items-center gap-3">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50/90 border border-blue-200/80 text-blue-600 text-xs font-black tracking-widest uppercase shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <i data-lucide="message-circle" class="w-3.5 h-3.5 text-blue-600/80"></i>
                    {{ $settings['testimonials_label'] ?? 'Testimoni' }}
                </span>
                <h2 class="text-3xl font-extrabold text-slate-900 mb-4 tracking-tight reveal-words">{{ $settings['testimonials_section_title'] ?? 'Testimonials' }}</h2>
                @include('partials.ornament')
                <p class="text-slate-500 font-medium text-sm md:text-base">{{ $settings['testimonials_section_subtitle'] ?? 'Apa kata jamaah yang telah mempercayakan perjalanan ibadah mereka kepada kami.' }}</p>
            </div>
            <!-- Testimonial Slider Viewport with Left/Right Arrows -->
            <div class="relative px-0 md:px-8" id="testimonial-slider-container">
                <!-- Left & Right Arrow Buttons (Visible on MD and larger screens) -->
                <button id="prev-testimonial" class="hidden md:flex absolute -left-4 top-1/2 -translate-y-1/2 z-20 bg-white hover:bg-slate-50 text-slate-700 hover:text-blue-600 w-11 h-11 rounded-full shadow-lg border border-slate-100/80 items-center justify-center transition active:scale-95 duration-200" aria-label="Previous Testimonial">
                    <i data-lucide="chevron-left" class="w-6 h-6"></i>
                </button>
                <button id="next-testimonial" class="hidden md:flex absolute -right-4 top-1/2 -translate-y-1/2 z-20 bg-white hover:bg-slate-50 text-slate-700 hover:text-blue-600 w-11 h-11 rounded-full shadow-lg border border-slate-100/80 items-center justify-center transition active:scale-95 duration-200" aria-label="Next Testimonial">
                    <i data-lucide="chevron-right" class="w-6 h-6"></i>
                </button>

                <!-- Slider Viewport -->
                <div id="testimonial-viewport" class="reveal">
                    <!-- Flex Track -->
                    <div id="testimonial-track" class="cursor-grab active:cursor-grabbing">
                        @if($testimonials->count() > 0)
                            @foreach ($testimonials as $testimonial)
                                @php
                                    $videoUrl = $testimonial->video_url ?? '';
                                    $parsedEmbedUrl = null;
                                    $thumbnailUrl = null;
                                    if (!empty($videoUrl)) {
                                        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|live|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $videoUrl, $matches)) {
                                            $videoId = $matches[1];
                                            $parsedEmbedUrl = "https://www.youtube.com/embed/" . $videoId . "?autoplay=1";
                                            $thumbnailUrl = "https://img.youtube.com/vi/" . $videoId . "/hqdefault.jpg";
                                        } elseif (preg_match('/instagram\.com\/(?:reel|p)\/([a-zA-Z0-9_-]+)/i', $videoUrl, $matches)) {
                                            $parsedEmbedUrl = "https://www.instagram.com/reel/" . $matches[1] . "/embed";
                                            $thumbnailUrl = "https://www.instagram.com/reel/" . $matches[1] . "/media/?size=l";
                                        } elseif (preg_match('/(?:vimeo\.com\/(?:channels\/[^\/]+\/|groups\/[^\/]+\/video\/|album\/[^\/]+\/video\/|video\/|)|player\.vimeo\.com\/video\/)([0-9]+)/i', $videoUrl, $matches)) {
                                            $videoId = $matches[1];
                                            $parsedEmbedUrl = "https://player.vimeo.com/video/" . $videoId . "?autoplay=1";
                                            $thumbnailUrl = 'vimeo';
                                        }
                                    }
                                    
                                    // Generate initials
                                    $initial = 'U';
                                    if (!empty($testimonial->name)) {
                                        $initial = strtoupper(substr(trim($testimonial->name), 0, 1));
                                    }
                                    
                                    // Custom gradient colors based on initial character
                                    $gradients = [
                                        'A' => 'from-rose-500 to-red-600',
                                        'B' => 'from-pink-500 to-rose-600',
                                        'C' => 'from-purple-500 to-indigo-600',
                                        'D' => 'from-indigo-500 to-blue-600',
                                        'E' => 'from-blue-500 to-sky-600',
                                        'F' => 'from-cyan-500 to-blue-600',
                                        'G' => 'from-teal-500 to-emerald-600',
                                        'H' => 'from-emerald-500 to-green-600',
                                        'I' => 'from-green-500 to-lime-600',
                                        'J' => 'from-orange-500 to-amber-600',
                                        'K' => 'from-amber-500 to-yellow-600',
                                        'L' => 'from-red-500 to-orange-600',
                                        'M' => 'from-blue-600 to-indigo-700',
                                        'N' => 'from-violet-600 to-purple-700',
                                        'O' => 'from-rose-600 to-pink-700',
                                        'P' => 'from-teal-600 to-cyan-700',
                                        'Q' => 'from-emerald-600 to-teal-700',
                                        'R' => 'from-blue-500 to-indigo-600',
                                        'S' => 'from-sky-500 to-blue-600',
                                        'T' => 'from-indigo-600 to-violet-750',
                                        'U' => 'from-violet-500 to-purple-600',
                                        'V' => 'from-purple-600 to-fuchsia-700',
                                        'W' => 'from-fuchsia-500 to-pink-600',
                                        'X' => 'from-pink-600 to-rose-700',
                                        'Y' => 'from-rose-500 to-orange-600',
                                        'Z' => 'from-orange-600 to-red-700'
                                    ];
                                    $gradient = $gradients[$initial] ?? 'from-blue-500 to-indigo-600';
                                @endphp
                                <div class="testimonial-slide select-none group">
                                    <!-- Outer Shell (Double-Bezel) -->
                                    <div class="w-full h-full p-2 bg-slate-100/40 dark:bg-slate-900/10 border border-slate-200/40 dark:border-slate-800/10 rounded-[2.2rem] transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:-translate-y-2 group-hover:shadow-xl group-hover:shadow-blue-500/5 group-hover:border-blue-600/15 flex flex-col">
                                        <!-- Inner Core -->
                                        <div class="bg-white rounded-[1.8rem] p-6 sm:p-8 flex flex-col justify-between h-full border border-slate-100/80 shadow-sm relative overflow-hidden flex-1">
                                            <div>
                                                <div class="flex items-center justify-between gap-3 sm:gap-4 mb-4 sm:mb-6">
                                                    <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                                                        @if(!empty($testimonial->photo))
                                                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full overflow-hidden border-2 border-white dark:border-slate-900 shadow-md flex-shrink-0 relative">
                                                                 <img src="{{ asset('storage/' . $testimonial->photo) }}" alt="{{ $testimonial->name }}" class="w-full h-full object-cover" width="48" height="48" loading="lazy" decoding="async">
                                                            </div>
                                                        @else
                                                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br {{ $gradient }} flex items-center justify-center text-white font-black text-sm sm:text-base border-2 border-white dark:border-slate-900 shadow-md flex-shrink-0 relative">
                                                                {{ $initial }}
                                                            </div>
                                                        @endif
                                                        <div class="min-w-0">
                                                            <h3 class="font-extrabold text-slate-900 text-sm md:text-base truncate sm:whitespace-normal group-hover:text-blue-600 transition duration-300">{{ $testimonial->name }}</h3>
                                                            <p class="text-xs text-slate-400 truncate">{{ $testimonial->location }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex text-amber-500 gap-0.5">
                                                        @for ($i = 0; $i < $testimonial->rating; $i++)
                                                            <i data-lucide="star" class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-current"></i>
                                                        @endfor
                                                    </div>
                                                </div>
 
                                                @if(!empty($parsedEmbedUrl))
                                                    <div class="relative w-full aspect-video rounded-2xl overflow-hidden mb-4 border border-slate-100 shadow-inner z-20 group/video cursor-pointer"
                                                         onclick="window.playTestimonialVideo('{{ $parsedEmbedUrl }}')">
                                                        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 to-indigo-950 flex flex-col items-center justify-center p-4 text-center">
                                                            <img src="{{ asset('images/section_makkah_wide.webp') }}" class="absolute inset-0 w-full h-full object-cover opacity-30 blur-[1px]" alt="Instagram Reel Fallback">
                                                            <div class="absolute inset-0 flex flex-col items-center justify-center p-4 text-center">
                                                                <span class="text-amber-400 text-[10px] font-bold uppercase tracking-widest mb-1">{{ $testimonial->location }}</span>
                                                                <span class="text-white font-extrabold text-sm truncate max-w-full">{{ $testimonial->name }}</span>
                                                                @if(str_contains($parsedEmbedUrl, 'instagram.com'))
                                                                    <span class="text-white/60 text-[9px] font-bold mt-1 flex items-center gap-1.5">
                                                                        <i data-lucide="instagram" class="w-3 h-3 text-pink-500"></i> Instagram Reel
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @if(!empty($thumbnailUrl))
                                                            <img src="{{ $thumbnailUrl }}" alt="Video testimonial thumbnail" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover/video:scale-105" onerror="this.style.display='none'">
                                                        @endif
                                                        <div class="absolute inset-0 bg-slate-950/20 group-hover/video:bg-slate-950/30 transition-all duration-300 flex items-center justify-center z-30">
                                                            <div class="w-12 h-12 rounded-full bg-white/95 text-blue-600 flex items-center justify-center shadow-lg active:scale-95 transition-all duration-300 group-hover/video:scale-110">
                                                                <i data-lucide="play" class="w-5 h-5 fill-current ml-0.5"></i>
                        </div>
                            </div>
                        </div>
                                                @endif

                                                <div class="relative mt-2">
                                                    <span class="absolute -top-4 -left-2 text-slate-100/80 dark:text-slate-800/80 text-6xl font-serif pointer-events-none select-none">“</span>
                                                    <p class="text-slate-600 dark:text-slate-300 text-xs sm:text-sm leading-relaxed mb-4 sm:mb-6 font-medium italic relative z-10 pl-3">
                                                        {{ $testimonial->message }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Fallback static items -->
                            <!-- Testimonial 1 -->
                            <div class="testimonial-slide select-none group">
                                <div class="w-full h-full p-2 bg-slate-100/40 dark:bg-slate-900/10 border border-slate-200/40 dark:border-slate-800/10 rounded-[2.2rem] transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:-translate-y-2 group-hover:shadow-xl group-hover:shadow-blue-500/5 group-hover:border-blue-600/15 flex flex-col">
                                    <div class="bg-white rounded-[1.8rem] p-6 sm:p-8 flex flex-col justify-between h-full border border-slate-100/80 shadow-sm relative overflow-hidden flex-1">
                                        <div>
                                            <div class="flex items-center justify-between gap-3 sm:gap-4 mb-4 sm:mb-6">
                                                <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center text-white font-black text-sm sm:text-base border-2 border-white dark:border-slate-900 shadow-md flex-shrink-0 relative">
                                                        M
                                                    </div>
                                                    <div class="min-w-0">
                                                        <h3 class="font-extrabold text-slate-900 text-sm md:text-base truncate sm:whitespace-normal group-hover:text-blue-600 transition duration-300">H. Muhammad Ridwan</h3>
                                                        <p class="text-xs text-slate-400 truncate">Jakarta</p>
                                                    </div>
                                                </div>
                                                <div class="flex text-amber-500 gap-0.5">
                                                    @for ($i = 0; $i < 5; $i++)
                                                        <i data-lucide="star" class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-current"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="relative mt-2">
                                                <span class="absolute -top-4 -left-2 text-slate-100/80 dark:text-slate-800/80 text-6xl font-serif pointer-events-none select-none">“</span>
                                                <p class="text-slate-650 dark:text-slate-350 text-xs sm:text-sm leading-relaxed mb-4 sm:mb-6 font-medium italic relative z-10 pl-3">
                                                    Sangat puas dengan pelayanan IZI Travel. Hotel di Makkah dan Madinah sangat dekat dengan Masjidil Haram dan Nabawi. Pembimbing umrah sangat sabar dan menguasai manasik dengan baik.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Testimonial 2 -->
                            <div class="testimonial-slide select-none group">
                                <div class="w-full h-full p-2 bg-slate-100/40 dark:bg-slate-900/10 border border-slate-200/40 dark:border-slate-800/10 rounded-[2.2rem] transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:-translate-y-2 group-hover:shadow-xl group-hover:shadow-blue-500/5 group-hover:border-blue-600/15 flex flex-col">
                                    <div class="bg-white rounded-[1.8rem] p-6 sm:p-8 flex flex-col justify-between h-full border border-slate-100/80 shadow-sm relative overflow-hidden flex-1">
                                        <div>
                                            <div class="flex items-center justify-between gap-3 sm:gap-4 mb-4 sm:mb-6">
                                                <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center text-white font-black text-sm sm:text-base border-2 border-white dark:border-slate-900 shadow-md flex-shrink-0 relative">
                                                        S
                                                    </div>
                                                    <div class="min-w-0">
                                                        <h3 class="font-extrabold text-slate-900 text-sm md:text-base truncate sm:whitespace-normal group-hover:text-blue-600 transition duration-300">Hj. Siti Aminah</h3>
                                                        <p class="text-xs text-slate-400 truncate">Bandung</p>
                                                    </div>
                                                </div>
                                                <div class="flex text-amber-500 gap-0.5">
                                                    @for ($i = 0; $i < 5; $i++)
                                                        <i data-lucide="star" class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-current"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="relative mt-2">
                                                <span class="absolute -top-4 -left-2 text-slate-100/80 dark:text-slate-800/80 text-6xl font-serif pointer-events-none select-none">“</span>
                                                <p class="text-slate-650 dark:text-slate-350 text-xs sm:text-sm leading-relaxed mb-4 sm:mb-6 font-medium italic relative z-10 pl-3">
                                                    Proses pendaftaran, pembuatan paspor dan visa semuanya dibantu sampai selesai. Jadwal keberangkatan tepat waktu dan fasilitas bus AC selama di Arab Saudi sangat nyaman. Terima kasih!
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Testimonial 3 -->
                            <div class="testimonial-slide select-none group">
                                <div class="w-full h-full p-2 bg-slate-100/40 dark:bg-slate-900/10 border border-slate-200/40 dark:border-slate-800/10 rounded-[2.2rem] transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:-translate-y-2 group-hover:shadow-xl group-hover:shadow-blue-500/5 group-hover:border-blue-600/15 flex flex-col">
                                    <div class="bg-white rounded-[1.8rem] p-6 sm:p-8 flex flex-col justify-between h-full border border-slate-100/80 shadow-sm relative overflow-hidden flex-1">
                                        <div>
                                            <div class="flex items-center justify-between gap-3 sm:gap-4 mb-4 sm:mb-6">
                                                <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-rose-500 to-red-600 flex items-center justify-center text-white font-black text-sm sm:text-base border-2 border-white dark:border-slate-900 shadow-md flex-shrink-0 relative">
                                                        A
                                                    </div>
                                                    <div class="min-w-0">
                                                        <h3 class="font-extrabold text-slate-900 text-sm md:text-base truncate sm:whitespace-normal group-hover:text-blue-600 transition duration-300">H. Achmad Fauzi</h3>
                                                        <p class="text-xs text-slate-400 truncate">Surabaya</p>
                                                    </div>
                                                </div>
                                                <div class="flex text-amber-500 gap-0.5">
                                                    @for ($i = 0; $i < 5; $i++)
                                                        <i data-lucide="star" class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-current"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="relative mt-2">
                                                <span class="absolute -top-4 -left-2 text-slate-100/80 dark:text-slate-800/80 text-6xl font-serif pointer-events-none select-none">“</span>
                                                <p class="text-slate-650 dark:text-slate-350 text-xs sm:text-sm leading-relaxed mb-4 sm:mb-6 font-medium italic relative z-10 pl-3">
                                                    Pelayanan prima sejak di tanah air hingga kembali ke Indonesia. Fasilitas hotel bintang 5 sesuai dengan yang dijanjikan, makanan prasmanan selalu cocok dengan lidah Indonesia.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Testimonial Controls & Dots Navigation (Sleek Floating Pill) -->
            <div class="flex flex-col items-center gap-4 mt-8 sm:mt-12">
                <div class="inline-flex items-center gap-3 bg-slate-100/80 dark:bg-slate-900/60 backdrop-blur-md px-4 py-2 rounded-full border border-slate-200/50 dark:border-slate-800/50 shadow-sm" id="testimonial-controls-wrapper">
                    <button id="toggle-play-testimonial" class="flex items-center justify-center bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 hover:text-blue-600 w-7 h-7 rounded-full shadow-sm border border-slate-100 dark:border-slate-700 transition active:scale-95 duration-200" aria-label="Pause Autoplay">
                        <i data-lucide="pause" class="w-3.5 h-3.5" id="play-pause-icon"></i>
                    </button>
                    <div class="h-4 w-[1px] bg-slate-200 dark:bg-slate-700"></div>
                    <div class="flex justify-center items-center gap-2" id="testimonial-dots">
                    </div>
                </div>
                <!-- Premium Progress Bar -->
                <div class="w-24 h-[3px] bg-slate-200/60 dark:bg-slate-800/60 rounded-full overflow-hidden relative">
                    <div id="testimonial-progress" class="absolute top-0 left-0 h-full bg-blue-600 w-0 rounded-full"></div>
                </div>

                @if(isset($allTestimonialsCount) && $allTestimonialsCount > 7)
                    <div class="mt-4 flex justify-center">
                        <a href="{{ route('public.testimonials') }}" 
                           class="inline-flex items-center gap-2 px-6 py-2.5 bg-white hover:bg-slate-50 text-blue-600 hover:text-blue-750 font-bold rounded-full border border-slate-200/80 shadow-sm hover:shadow transition-all duration-200 active:scale-95 text-xs sm:text-sm">
                            <i data-lucide="message-square" class="w-4 h-4"></i>
                            <span>Lihat Semua Testimoni ({{ $allTestimonialsCount }})</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <!-- END: Testimonials -->

    <!-- BEGIN: Articles -->
    <section class="py-16 md:py-24 bg-stone-50 overflow-hidden relative" id="artikel" data-purpose="articles-section">
        <!-- Glow backdrops -->
        <div class="absolute left-1/4 top-1/4 w-[450px] h-[220px] bg-blue-400/5 rounded-full blur-[100px] pointer-events-none -z-10 animate-aurora-1"></div>
        <div class="absolute right-1/4 bottom-1/4 w-[400px] h-[200px] bg-emerald-400/5 rounded-full blur-[100px] pointer-events-none -z-10 animate-aurora-2"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4 mb-12 reveal">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50/90 border border-blue-200/80 text-blue-600 text-xs font-black tracking-widest uppercase shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <i data-lucide="book-open" class="w-3.5 h-3.5 text-blue-600/80"></i>
                    {{ $settings['articles_label'] ?? 'Artikel &amp; Inspirasi' }}
                </span>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight reveal-words">{{ $settings['articles_section_title'] ?? 'Kabar &amp; Tips Umrah Terbaru' }}</h2>
                @include('partials.ornament')
                <p class="text-slate-500 max-w-md mx-auto text-xs md:text-sm">{{ $settings['articles_section_subtitle'] ?? 'Dapatkan panduan ibadah terpercaya, informasi destinasi, serta tips kesehatan untuk kelancaran umrah Anda.' }}</p>
                
                <!-- Category Filter Tabs -->
                <div class="w-full overflow-x-auto scrollbar-none py-2 px-4 flex justify-start sm:justify-center mt-6">
                    <div class="relative flex items-center gap-1 bg-slate-100/80 p-1 rounded-full border border-slate-200/40 z-10 whitespace-nowrap flex-nowrap mx-auto">
                        <div id="article-tab-pill" class="absolute bg-white rounded-full shadow-sm border border-slate-200/10 transition-all duration-355 ease-out z-0" style="height: 32px; top: 4px; left: 4px; width: 80px;"></div>
                        <button class="article-tab-btn active px-4 sm:px-5 py-2 rounded-full text-xs font-bold transition duration-200 text-blue-600 relative z-10 shrink-0" data-filter="all">{{ $settings['articles_filter_all'] ?? 'Semua' }}</button>
                        <button class="article-tab-btn px-4 sm:px-5 py-2 rounded-full text-xs font-bold transition duration-200 text-slate-500 hover:text-blue-600 relative z-10 shrink-0" data-filter="panduan-umrah">{{ $settings['articles_filter_panduan'] ?? 'Panduan Umrah' }}</button>
                        <button class="article-tab-btn px-4 sm:px-5 py-2 rounded-full text-xs font-bold transition duration-200 text-slate-500 hover:text-blue-600 relative z-10 shrink-0" data-filter="tips-doa">{{ $settings['articles_filter_tips'] ?? 'Tips &amp; Doa' }}</button>
                        <button class="article-tab-btn px-4 sm:px-5 py-2 rounded-full text-xs font-bold transition duration-200 text-slate-500 hover:text-blue-600 relative z-10 shrink-0" data-filter="info-haramain">{{ $settings['articles_filter_haramain'] ?? 'Info Haramain' }}</button>
                    </div>
                </div>
            </div>

            <!-- Articles Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 reveal-stagger" id="articles-grid" data-stagger="true">
                @foreach ($articles as $article)
                              <div class="article-card reveal-card soft-card glow-card rounded-3xl overflow-hidden soft-shadow soft-shadow-hover transition-all duration-300 flex flex-col justify-between" 
                         data-category="{{ Str::slug($article->category) }}" 
                         data-purpose="article-item">
                        <div>
                            <!-- Cover Image -->
                            <div class="relative h-52 overflow-hidden bg-slate-100">
                                <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover transition-transform duration-700 hover:scale-105" width="370" height="208" loading="lazy" decoding="async" />
                                <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-md text-slate-800 text-[10px] font-black px-3 py-1.5 rounded-xl uppercase tracking-wider border border-slate-100/50 shadow-sm">{{ $article->category }}</span>
                            </div>
                            
                            <!-- Card Body -->
                            <div class="p-6 md:p-8">
                                <div class="flex items-center gap-3 text-[10px] text-slate-400 font-bold mb-3.5">
                                    <span class="flex items-center gap-1.5"><i data-lucide="calendar" class="w-3.5 h-3.5"></i> {{ $article->published_at }}</span>
                                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                    <span class="flex items-center gap-1.5"><i data-lucide="clock" class="w-3.5 h-3.5"></i> {{ $article->read_time }} {{ $settings['articles_read_suffix'] ?? 'Baca' }}</span>
                                </div>
                                <h3 class="font-extrabold text-base md:text-lg text-slate-900 leading-snug mb-3 hover:text-blue-600 transition duration-200">
                                    <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                                </h3>
                                <p class="text-slate-500 text-xs md:text-sm leading-relaxed font-light line-clamp-3">
                                    {{ $article->excerpt }}
                                </p>
                                @if(!empty($article->tags))
                                    @php
                                        $cardTags = array_filter(array_map('trim', explode(',', $article->tags)));
                                    @endphp
                                    @if(!empty($cardTags))
                                        <div class="flex flex-wrap gap-1.5 mt-3">
                                            @foreach($cardTags as $tag)
                                                <a href="{{ route('public.articles.tag', str_replace('#', '', $tag)) }}" class="text-[10px] font-black text-blue-500 hover:text-blue-700 transition tracking-wide">
                                                    {{ str_starts_with($tag, '#') ? $tag : '#' . $tag }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer / Author -->
                        <div class="px-6 pb-6 md:px-8 md:pb-8 pt-4 border-t border-slate-50 bg-slate-50/10 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 font-extrabold text-xs flex items-center justify-center border border-blue-100/40">
                                    {{ collect(explode(' ', $article->author))->map(fn($w) => substr($w, 0, 1))->take(2)->join('') }}
                                </div>
                                <div>
                                    <p class="text-xs font-extrabold text-slate-800">{{ $article->author }}</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $article->author_role }}</p>
                                </div>
                            </div>
                            <a class="group flex items-center gap-1 text-xs font-extrabold text-blue-600 hover:text-blue-700 transition" 
                                    href="{{ route('articles.show', $article->slug) }}">
                                <span>{{ $settings['articles_read_more'] ?? 'Baca' }}</span>
                                <i data-lucide="arrow-right" class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($articles->count() >= 6)
                <div class="flex justify-center mt-12 reveal">
                    <a href="{{ route('public.articles.index') }}"
                       class="inline-flex items-center gap-2 px-7 py-3.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.97] rounded-full font-bold text-sm text-white transition-all duration-200 shadow-lg shadow-blue-500/25">
                        {{ $settings['articles_view_all'] ?? 'Lihat Semua Artikel' }}
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            @endif
        </div>
    </section>
    <!-- END: Articles -->

    <!-- BEGIN: Kabar Haramain & Waktu Tanah Suci -->
    <section class="py-16 md:py-24 bg-[#071930] text-white relative overflow-hidden" id="haramain-info">
        <!-- Ambient Light Blobs -->
        <div class="absolute -left-[10%] top-[10%] w-[600px] h-[300px] bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute -right-[10%] bottom-[10%] w-[500px] h-[300px] bg-amber-500/10 rounded-full blur-[100px] pointer-events-none"></div>
        
        <!-- Stars/Pattern overlay -->
        <div class="absolute inset-0 bg-cover opacity-[0.03] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22%3E%3Cpath d=%22M30 0 l10 20 l20 10 l-20 10 l-10 20 l-10 -20 l-20 -10 l20 -10 z%22 fill=%22%23ffffff%22/%3E%3C/svg%3E'); background-size: 60px 60px;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Section Header -->
            <div class="text-center space-y-4 mb-16 reveal">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/5 border border-white/10 text-amber-400 text-xs font-black tracking-widest uppercase shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                    {{ $settings['haramain_badge'] ?? 'Info Haramain Live & Waktu Shalat' }}
                </span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">
                    {{ $settings['haramain_title'] ?? 'Kabar Tanah Suci & Jadwal Shalat' }}
                </h2>
                <p class="text-white/60 max-w-xl mx-auto text-xs md:text-sm leading-relaxed font-light">
                    {{ $settings['haramain_subtitle'] ?? 'Pantau kondisi langsung Masjidil Haram & Masjidil Nabawi, waktu shalat aktual, serta informasi cuaca Makkah secara real-time.' }}
                </p>
            </div>

            <!-- Main Layout Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Column: Clock & Prayer Times Card (5-Span) -->
                <div class="lg:col-span-5 bg-white/5 border border-white/10 backdrop-blur-md rounded-[2rem] p-6 sm:p-8 shadow-2xl relative overflow-hidden reveal-left">
                    <!-- Glow decoration -->
                    <div class="absolute -right-12 -top-12 w-32 h-32 bg-[#c89e2b]/10 rounded-full blur-2xl pointer-events-none"></div>

                    <!-- City Toggle Tabs -->
                    <div class="flex bg-white/5 border border-white/10 rounded-full p-1 mb-8 gap-1 overflow-x-auto scrollbar-none">
                        <button onclick="setHaramainTab('makkah')" id="tab-btn-makkah" class="flex-1 whitespace-nowrap text-center py-2 px-3 rounded-full text-xs font-bold transition duration-300 bg-[#c89e2b] text-[#071930] shadow-md shadow-[#c89e2b]/10">
                            Makkah
                        </button>
                        <button onclick="setHaramainTab('madinah')" id="tab-btn-madinah" class="flex-1 whitespace-nowrap text-center py-2 px-3 rounded-full text-xs font-bold transition duration-300 text-white hover:bg-white/5">
                            Madinah
                        </button>
                        <button onclick="setHaramainTab('wib')" id="tab-btn-wib" class="flex-1 whitespace-nowrap text-center py-2 px-3 rounded-full text-xs font-bold transition duration-300 text-white hover:bg-white/5">
                            WIB
                        </button>
                        <button onclick="setHaramainTab('wita')" id="tab-btn-wita" class="flex-1 whitespace-nowrap text-center py-2 px-3 rounded-full text-xs font-bold transition duration-300 text-white hover:bg-white/5 font-medium">
                            WITA
                        </button>
                    </div>

                    <!-- Location Title & Realtime Clock -->
                    <div class="text-center mb-8">
                        <h3 id="clock-location-title" class="text-lg font-bold text-[#c89e2b] tracking-wide">Makkah Al-Mukarramah</h3>
                        <div id="clock-time" class="text-4xl md:text-5xl font-black text-white my-3 tracking-wider font-mono">15:35:00 PM</div>
                        <p id="clock-date" class="text-xs text-white/60 font-semibold">Jum'at, 17 Juli 2026</p>
                    </div>

                    <!-- Next Prayer Live Countdown -->
                    <div class="bg-gradient-to-r from-[#c89e2b]/10 to-[#c89e2b]/20 border border-[#c89e2b]/30 rounded-2xl p-4 text-center mb-6">
                        <p class="text-xs text-[#c89e2b] font-bold uppercase tracking-wider">Shalat Berikutnya</p>
                        <h4 id="next-prayer-name" class="text-lg font-extrabold text-white mt-1">Maghrib</h4>
                        <div id="next-prayer-countdown" class="text-xl font-mono font-black text-white tracking-widest mt-1.5">(01:25:16)</div>
                    </div>

                    <!-- Prayer Timings Grid -->
                    <div class="space-y-3 text-left">
                        <!-- Subuh -->
                        <div class="flex items-center justify-between bg-white/5 border border-white/10 rounded-xl px-4 py-3 hover:bg-white/10 transition">
                            <span class="text-sm font-semibold text-white/80">Subuh</span>
                            <span id="prayer-time-subuh" class="text-sm font-bold font-mono text-white">04:21</span>
                        </div>
                        <!-- Dzuhur -->
                        <div class="flex items-center justify-between bg-white/5 border border-white/10 rounded-xl px-4 py-3 hover:bg-white/10 transition">
                            <span class="text-sm font-semibold text-white/80">Dzuhur</span>
                            <span id="prayer-time-dzuhur" class="text-sm font-bold font-mono text-white">12:27</span>
                        </div>
                        <!-- Ashar -->
                        <div class="flex items-center justify-between bg-white/5 border border-white/10 rounded-xl px-4 py-3 hover:bg-white/10 transition">
                            <span class="text-sm font-semibold text-white/80">Ashar</span>
                            <span id="prayer-time-ashar" class="text-sm font-bold font-mono text-white">15:41</span>
                        </div>
                        <!-- Maghrib -->
                        <div class="flex items-center justify-between bg-white/5 border border-white/10 rounded-xl px-4 py-3 hover:bg-white/10 transition">
                            <span class="text-sm font-semibold text-white/80">Maghrib</span>
                            <span id="prayer-time-maghrib" class="text-sm font-bold font-mono text-white">19:06</span>
                        </div>
                        <!-- Isya -->
                        <div class="flex items-center justify-between bg-white/5 border border-white/10 rounded-xl px-4 py-3 hover:bg-white/10 transition">
                            <span class="text-sm font-semibold text-white/80">Isya</span>
                            <span id="prayer-time-isya" class="text-sm font-bold font-mono text-white">20:36</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Live Stream & Weather / Info (7-Span) -->
                <div class="lg:col-span-7 space-y-6 reveal-right">
                    <!-- Live Haramain Player Card -->
                    <div class="bg-gradient-to-b from-slate-900/90 via-slate-900/80 to-slate-950/90 border border-amber-500/20 backdrop-blur-2xl rounded-[2.5rem] p-5 sm:p-7 shadow-[0_25px_60px_-15px_rgba(0,0,0,0.7)] relative overflow-hidden group">
                        <!-- Ambient Background Glows -->
                        <div class="absolute -top-24 -right-24 w-72 h-72 bg-amber-500/10 rounded-full blur-3xl pointer-events-none group-hover:bg-amber-500/15 transition-all duration-700"></div>
                        <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-red-600/10 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="relative z-10 space-y-4">
                            <!-- Top Header Bar -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-3 border-b border-white/10">
                                <div class="text-left space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-red-500/20 border border-red-500/40 text-red-400 text-[11px] font-black uppercase tracking-wider">
                                            <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                                            LIVE
                                        </span>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-500/10 border border-amber-500/30 text-amber-300 text-[10px] font-bold">
                                            <i data-lucide="sparkles" class="w-3 h-3 text-amber-400"></i> HD 1080p
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-black text-white tracking-wide flex items-center gap-2 pt-1">
                                        Siaran Langsung Masjidil Haram
                                    </h3>
                                    <p class="text-xs text-white/60 font-medium">Tayangan langsung 24/7 dari Baitullah Makkah Al-Mukarramah</p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-2">
                                    <button onclick="refreshHaramainStream()" title="Muat Ulang Siaran" 
                                            class="p-2 rounded-xl bg-white/5 hover:bg-white/10 text-white/70 hover:text-white border border-white/10 text-xs font-semibold transition duration-200 active:scale-95 flex items-center gap-1.5">
                                        <i data-lucide="rotate-cw" class="w-4 h-4"></i>
                                        <span class="hidden sm:inline">Refresh</span>
                                    </button>
                                    <a id="youtube-direct-link" href="https://www.youtube.com/channel/UCos52azQNBgW63_9uDJoPDA" target="_blank" rel="noopener noreferrer" 
                                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-red-600 to-rose-700 hover:from-red-500 hover:to-rose-600 text-white shadow-lg shadow-red-950/30 border border-red-400/30 text-xs font-extrabold transition-all duration-300 hover:scale-105 active:scale-95">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                        <span>Buka di YouTube</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Video Player Frame (16:9 Aspect Ratio) -->
                            <div class="relative w-full aspect-video rounded-2xl overflow-hidden border border-amber-500/30 shadow-[0_10px_30px_rgba(0,0,0,0.8)] bg-slate-950 group/player">
                                <!-- Overlay Badges on Top of Video -->
                                <div class="absolute top-3 left-3 z-20 pointer-events-none flex items-center gap-2">
                                    <span class="px-3 py-1 rounded-full bg-black/70 backdrop-blur-md border border-amber-500/30 text-amber-300 text-[10px] font-extrabold uppercase tracking-wider flex items-center gap-1.5 shadow-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                        Makkah Live Stream
                                    </span>
                                </div>

                                <!-- YouTube Iframe -->
                                <iframe id="haramain-iframe" class="absolute inset-0 w-full h-full hidden" 
                                        src="about:blank" 
                                        title="Siaran Langsung Masjidil Haram Makkah" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                        allowfullscreen>
                                </iframe>

                                <!-- Facade / Placeholder Overlay -->
                                <div id="haramain-facade" class="absolute inset-0 w-full h-full flex flex-col items-center justify-center bg-cover bg-center transition-all duration-500 cursor-pointer group" 
                                     style="background-image: linear-gradient(to bottom, rgba(15, 23, 42, 0.4), rgba(15, 23, 42, 0.8)), url('{{ asset('images/package_kaaba.webp') }}');">
                                     <!-- Play Button -->
                                     <button id="haramain-play-btn" aria-label="Putar siaran langsung" class="w-16 h-16 rounded-full bg-amber-500 hover:bg-amber-400 text-slate-950 flex items-center justify-center shadow-lg shadow-amber-500/30 transition-all duration-300 transform group-hover:scale-110 active:scale-95 z-20">
                                         <i data-lucide="play" class="w-8 h-8 fill-current ml-1"></i>
                                     </button>
                                     <span class="mt-4 text-xs font-bold text-white tracking-widest uppercase bg-black/40 px-4.5 py-2 rounded-full border border-white/10 z-20 select-none">Klik untuk Memutar Live Stream</span>
                                </div>
                            </div>

                            <!-- Footer Stats / Info Bar -->
                            <div class="flex flex-wrap items-center justify-between gap-3 text-xs text-white/50 pt-1 font-medium">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="radio" class="w-3.5 h-3.5 text-amber-400 animate-pulse"></i>
                                    <span>Saluran Resmi KSA Qur'an TV</span>
                                </div>
                                <div class="flex items-center gap-4 text-[11px]">
                                    <span class="flex items-center gap-1 text-emerald-400">
                                        <i data-lucide="check-circle2" class="w-3.5 h-3.5"></i> Live Broadcast Ready
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Weather and Density Stat Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-left">
                        <!-- Makkah Weather Widget -->
                        <div class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-5 shadow-xl flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-xs text-white/50 font-bold uppercase tracking-wider">Cuaca Makkah</p>
                                <h4 id="weather-temp" class="text-3xl font-black text-white">41°C</h4>
                                <p id="weather-desc" class="text-xs text-[#c89e2b] font-semibold">Cerah Berawan</p>
                            </div>
                            <div class="p-3 bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded-2xl flex items-center justify-center">
                                <i id="weather-icon" data-lucide="sun" class="w-8 h-8"></i>
                            </div>
                        </div>

                        <!-- Live Density Widget (Pilgrims Today) -->
                        <div class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-5 shadow-xl flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-xs text-white/50 font-bold uppercase tracking-wider">Estimasi Jamaah Hari Ini</p>
                                <h4 id="density-counter" class="text-3xl font-black text-emerald-400 font-mono">{{ number_format((int)($settings['haramain_density_base'] ?? 254025), 0, ',', '.') }}</h4>
                                <p class="text-xs text-white/40 flex items-center gap-1 font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                    Kepadatan Masjidil Haram
                                </p>
                            </div>
                            <div class="p-3 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-2xl flex items-center justify-center">
                                <i data-lucide="users" class="w-8 h-8 animate-pulse"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Kabar Haramain & Waktu Tanah Suci -->

    <!-- BEGIN: Kemitraan -->
    <section class="py-16 md:py-24 relative overflow-hidden" id="kemitraan" data-purpose="partnership-section">
        <!-- Background Image overlay -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-[center_center] scale-105" style="background-image: linear-gradient(to bottom, rgba(250, 250, 249, 0.93) 0%, rgba(250, 250, 249, 0.98) 100%), url('{{ asset('images/section.webp') }}');"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center space-y-4 mb-16 reveal">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50/90 border border-blue-200/80 text-blue-600 text-xs font-black tracking-widest uppercase shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <i data-lucide="handshake" class="w-3.5 h-3.5 text-blue-600/80"></i>
                    {{ $settings['partnership_badge'] ?? 'Program Kemitraan' }}
                </span>
                <h2 class="text-3xl font-extrabold text-blue-950 tracking-tight reveal-words">{{ $settings['partnership_title'] ?? 'Mari Bergabung Menjadi Mitra Syiar Baitullah' }}</h2>
                <p class="text-blue-900/70 max-w-2xl mx-auto text-xs md:text-sm leading-relaxed">{{ $settings['partnership_subtitle'] ?? 'Menjadi mitra syiar baitullah berkesempatan mendapatkan komisi hingga puluhan juta rupiah bahkan berkesempatan untuk umroh.' }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 reveal-stagger" data-stagger="true">
                <!-- Tier 1: Freelance -->
                <div class="reveal-card kemitraan-card kemitraan-card-blue bg-gradient-to-b from-blue-600/[0.02] to-white hover:from-blue-600/[0.06] glow-card p-8 rounded-3xl border border-blue-600/5 hover:border-blue-600/15 shadow-xl shadow-slate-900/5 hover:-translate-y-1 transition duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="bg-blue-50/80 p-4 rounded-2xl text-blue-600 w-fit">
                                <i data-lucide="user-check" class="w-8 h-8"></i>
                            </div>
                            <span class="bg-blue-50 text-blue-600 text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-wider">{{ $settings['partnership_tier_1_badge'] ?? 'Freelance' }}</span>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-lg mb-1">{{ $settings['partnership_tier_1_title'] ?? 'Mitra Freelance' }}</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-3">{{ $settings['partnership_reg_label'] ?? 'Biaya Pendaftaran' }}</p>
                        <p class="text-3xl font-black text-blue-600 mb-6">{{ $settings['partnership_tier_1_price'] ?? 'FREE' }}</p>
                        <div class="h-px bg-slate-100 mb-6"></div>
                        <ul class="space-y-3.5 text-xs text-slate-600">
                            <li class="flex items-center gap-3">
                                <i data-lucide="check" class="w-4 h-4 text-emerald-500 flex-shrink-0"></i>
                                {{ $settings['partnership_tier_1_feature_1'] ?? 'Komisi Menarik per Jemaah' }}
                            </li>
                            <li class="flex items-center gap-3">
                                <i data-lucide="check" class="w-4 h-4 text-emerald-500 flex-shrink-0"></i>
                                {{ $settings['partnership_tier_1_feature_2'] ?? 'Dukungan brosur digital &amp; marketing kit' }}
                            </li>
                            <li class="flex items-center gap-3">
                                <i data-lucide="check" class="w-4 h-4 text-emerald-500 flex-shrink-0"></i>
                                {{ $settings['partnership_tier_1_feature_3'] ?? 'Bebas target bulanan &amp; tanpa modal' }}
                            </li>
                            <li class="flex items-center gap-3">
                                <i data-lucide="check" class="w-4 h-4 text-emerald-500 flex-shrink-0"></i>
                                {{ $settings['partnership_tier_1_feature_4'] ?? 'Waktu kerja fleksibel' }}
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Tier 2: Agen -->
                <div class="reveal-card kemitraan-card kemitraan-card-amber bg-gradient-to-b from-blue-600/[0.02] to-white hover:from-blue-600/[0.06] glow-card p-8 rounded-3xl border border-blue-600/5 hover:border-blue-600/15 shadow-xl shadow-slate-900/5 hover:-translate-y-1 transition duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="bg-amber-50 p-4 rounded-2xl text-amber-500 w-fit">
                                <i data-lucide="briefcase" class="w-8 h-8"></i>
                            </div>
                            <span class="bg-amber-100/70 text-amber-700 text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-wider">{{ $settings['partnership_tier_2_badge'] ?? 'Agen Resmi' }}</span>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-lg mb-1">{{ $settings['partnership_tier_2_title'] ?? 'Mitra Agen' }}</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-3">{{ $settings['partnership_reg_label'] ?? 'Biaya Pendaftaran' }}</p>
                        <p class="text-3xl font-black text-amber-500 mb-6">{{ $settings['partnership_tier_2_price'] ?? 'Rp 1.000.000' }}</p>
                        <div class="h-px bg-slate-100 mb-6"></div>
                        <ul class="space-y-3.5 text-xs text-slate-600">
                            <li class="flex items-center gap-3">
                                <i data-lucide="check" class="w-4 h-4 text-emerald-500 flex-shrink-0"></i>
                                {{ $settings['partnership_tier_2_feature_1'] ?? 'Komisi maksimal &amp; bonus menarik' }}
                            </li>
                            <li class="flex items-center gap-3">
                                <i data-lucide="check" class="w-4 h-4 text-emerald-500 flex-shrink-0"></i>
                                {{ $settings['partnership_tier_2_feature_2'] ?? 'Starter kit fisik (spanduk &amp; brosur cetak)' }}
                            </li>
                            <li class="flex items-center gap-3">
                                <i data-lucide="check" class="w-4 h-4 text-emerald-500 flex-shrink-0"></i>
                                {{ $settings['partnership_tier_2_feature_3'] ?? 'Sertifikat keagenan resmi IZI Travel' }}
                            </li>
                            <li class="flex items-center gap-3">
                                <i data-lucide="check" class="w-4 h-4 text-emerald-500 flex-shrink-0"></i>
                                {{ $settings['partnership_tier_2_feature_4'] ?? 'Pembekalan &amp; prioritas bimbingan produk' }}
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Tier 3: Benefit & Reward -->
                <div class="reveal-card kemitraan-card kemitraan-card-emerald bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 text-white glow-card p-8 rounded-3xl border border-blue-900/30 shadow-xl shadow-blue-950/20 hover:-translate-y-1 transition duration-300 flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-amber-500/10 rounded-full blur-3xl"></div>
                    <div class="absolute -left-10 -top-10 w-40 h-40 bg-blue-500/10 rounded-full blur-3xl"></div>
                    
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div>
                            <div class="mb-6 bg-amber-500 text-slate-950 p-4 rounded-2xl w-fit font-bold shadow-md shadow-amber-500/20">
                                <i data-lucide="gift" class="w-8 h-8"></i>
                            </div>
                            <h3 class="font-extrabold text-amber-400 text-lg mb-1">{{ $settings['partnership_tier_3_title'] ?? 'Keuntungan &amp; Reward' }}</h3>
                            <p class="text-slate-400 text-[10px] uppercase font-bold tracking-wider mb-6">{{ $settings['partnership_tier_3_subtitle'] ?? 'Potensi Syiar Kemitraan' }}</p>
                            
                            <div class="space-y-4">
                                <!-- Komisi -->
                                <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                                    <p class="text-[9px] text-amber-500 font-bold uppercase tracking-wider">{{ $settings['partnership_reward_1_label'] ?? 'Komisi per Jemaah' }}</p>
                                    <p class="text-xl font-black mt-0.5">{{ $settings['partnership_reward_1_value'] ?? 'Hingga Rp 2.000.000' }}</p>
                                    <p class="text-[10px] text-slate-400 mt-1 leading-snug">{{ $settings['partnership_reward_1_desc'] ?? 'Pendapatan langsung per jemaah yang melakukan pelunasan.' }}</p>
                                </div>
                                
                                <!-- Reward -->
                                <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                                    <p class="text-[9px] text-amber-500 font-bold uppercase tracking-wider">{{ $settings['partnership_reward_2_label'] ?? 'Reward Prestasi' }}</p>
                                    <p class="text-xl font-black mt-0.5">{{ $settings['partnership_reward_2_value'] ?? 'Umroh Gratis' }}</p>
                                    <p class="text-[10px] text-slate-400 mt-1 leading-snug">{{ $settings['partnership_reward_2_desc'] ?? 'Kesempatan ibadah umrah gratis bagi mitra yang mencapai target syiar.' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kemitraan Call To Action -->
            <div class="mt-16 bg-white border border-slate-100/80 rounded-3xl p-8 md:p-10 shadow-xl shadow-slate-900/5 flex flex-col md:flex-row items-center justify-between gap-8 max-w-4xl mx-auto relative overflow-hidden reveal-scale">
                <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-blue-500/5 rounded-full blur-2xl"></div>
                <div class="text-left space-y-2 relative z-10">
                    <h4 class="font-extrabold text-slate-900 text-lg md:text-xl">{{ $settings['partnership_cta_title'] ?? 'Tertarik Menjadi Mitra IZI Travel?' }}</h4>
                    <p class="text-slate-400 text-xs md:text-sm font-light">{{ $settings['partnership_cta_desc'] ?? 'Dapatkan proposal penawaran kemitraan resmi dan diskusikan peluang kerja sama bersama tim kami.' }}</p>
                </div>
                <a class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3.5 rounded-full font-bold shadow-lg shadow-blue-500/15 transform active:scale-95 transition-all duration-300 flex items-center gap-2.5 text-xs flex-shrink-0 relative z-10" href="https://wa.me/{{ $wa_phone }}?text=Halo%20Admin%20IZI%20Travel,%20saya%20tertarik%20untuk%20bergabung%2520menjadi%2520mitra%2520syiar%2520Baitullah." target="_blank">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
                    </svg>
                    {{ $settings['partnership_cta_button'] ?? 'Hubungi WhatsApp Kemitraan' }}
                </a>
            </div>
        </div>
    </section>
    <!-- END: Kemitraan -->



    <!-- BEGIN: FAQ -->
    <section class="py-16 md:py-24 relative overflow-hidden bg-stone-100/40" data-purpose="faq-section">
        <!-- Background Image overlay -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-[center_center] scale-105" style="background-image: linear-gradient(to bottom, rgba(250, 250, 249, 0.93) 0%, rgba(250, 250, 249, 0.98) 100%), url('{{ asset('images/section.webp') }}');"></div>
        </div>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="space-y-3 flex flex-col items-center mb-12">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50/90 border border-blue-200/80 text-blue-600 text-xs font-black tracking-widest uppercase shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <i data-lucide="help-circle" class="w-3.5 h-3.5 text-blue-600/80"></i>
                    Tanya Jawab
                </span>
                <h2 class="text-3xl font-extrabold text-center text-slate-900 tracking-tight reveal-words">{{ $settings['faq_section_title'] ?? 'Tanya Jawab (FAQ)' }}</h2>
                @include('partials.ornament')
            </div>
            <div class="space-y-4 reveal">
                @foreach ($faqs as $faq)
                    <details class="faq-details group border border-slate-100/85 group-open:border-blue-600/20 rounded-2xl overflow-hidden transition-all duration-300 bg-white group-open:bg-blue-600/[0.02] soft-shadow">
                        <summary class="flex items-center justify-between p-5 cursor-pointer hover:bg-blue-600/[0.03] group-open:bg-blue-600/[0.05] list-none font-bold text-slate-800 transition">
                            {{ $faq->question }}
                            <svg class="w-5 h-5 text-slate-400 transition-transform duration-300 group-open:-rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="faq-content-wrapper">
                            <div class="overflow-hidden">
                                <div class="p-5 text-slate-500 leading-relaxed text-sm font-light border-t border-slate-100/60">
                                    {!! nl2br(e($faq->answer)) !!}
                                </div>
                            </div>
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
    <!-- END: FAQ -->

    <!-- BEGIN: CTASection -->
    <section class="py-16 md:py-24 relative overflow-hidden bg-stone-50" data-purpose="cta-section">
        <!-- Background Image overlay -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-[center_center] scale-105" style="background-image: linear-gradient(to bottom, rgba(250, 250, 249, 0.75) 0%, rgba(250, 250, 249, 0.9) 100%), url('{{ asset('images/section.webp') }}');"></div>
        </div>
        <div class="absolute inset-0 bg-stone-100/10 pointer-events-none z-10"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 text-center">
            <div class="bg-gradient-to-br from-white/95 to-blue-600/[0.06] backdrop-blur-xl border border-blue-600/10 p-6 sm:p-12 md:p-16 rounded-[2rem] shadow-2xl shadow-blue-600/[0.03] reveal">
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 mb-6 tracking-tight">
                    {{ $settings['cta_title'] ?? 'Siap Memulai Perjalanan Suci Anda?' }}
                </h2>
                <p class="text-lg text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                    {{ $settings['cta_description'] ?? 'Hubungi kami sekarang untuk mendapatkan rekomendasi paket terbaik sesuai kebutuhan dan budget perjalanan ibadah Anda.' }}
                </p>
                <a href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank" class="bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 hover:from-amber-400 hover:to-amber-500 px-10 py-4 rounded-full font-extrabold flex items-center gap-3 mx-auto shadow-lg shadow-amber-500/20 hover:shadow-amber-500/30 transition-all duration-300 transform active:scale-95 w-fit text-sm">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
                    </svg>
                    {{ $settings['cta_button'] ?? 'Hubungi WhatsApp' }}
                </a>
            </div>
        </div>
    </section>
    <!-- END: CTASection -->
    </main>

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
                    <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO WHITE.webp') }}" alt="{{ $settings['site_name'] ?? 'IZI Travel' }}" class="h-11 w-auto object-contain" width="480" height="217" loading="lazy" decoding="async" />
                    <p class="text-slate-400 text-sm leading-relaxed font-light">
                        {{ $settings['site_description'] ?? 'IZI Travel berkomitmen memberikan pelayanan perjalanan ibadah Umrah dan Haji terbaik secara profesional, amanah, dan terpercaya demi kenyamanan ibadah Anda.' }}
                    </p>
                    <div class="space-y-3">
                        <h3 class="text-[10px] font-extrabold uppercase tracking-widest text-slate-500">Koneksi Media Sosial</h3>
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
                    <h3 class="text-xs font-black uppercase tracking-widest text-amber-500 border-l-2 border-amber-500 pl-3">Menu Utama</h3>
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
                    <h3 class="text-xs font-black uppercase tracking-widest text-amber-500 border-l-2 border-amber-500 pl-3">{{ $settings['footer_contact_heading'] ?? 'Hubungi Kami' }}</h3>
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
                    <h3 class="text-xs font-black uppercase tracking-widest text-amber-500 border-l-2 border-amber-500 pl-3">Legalitas &amp; Lokasi</h3>
                    <!-- Google Maps Wrapper -->
                    <div class="rounded-2xl overflow-hidden border border-white/10 shadow-2xl h-28 relative group transition duration-300 hover:border-blue-500/30">
                        @if(isset($settings['contact_gmaps']) && !empty($settings['contact_gmaps']))
                            <iframe src="{{ $settings['contact_gmaps'] }}" title="Lokasi Kantor IZI Travel" class="w-full h-full border-0 grayscale opacity-80 group-hover:grayscale-0 group-hover:opacity-100 transition duration-500" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        @else
                            <img src="{{ asset('images/map_thumbnail.webp') }}" alt="Google Maps Pin" class="w-full h-28 object-cover transition duration-500 group-hover:scale-105" width="600" height="600" loading="lazy" decoding="async" />
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
                            No: {{ $settings['footer_ppiu_number'] ?? '91202054619660001' }}
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

    <!-- Lucide Icons: bundled via resources/js/app.js (window.lucide) -->
    <script>
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
                // Micro-trigger reflow
                void lightboxImg.offsetWidth;
                lightboxImg.classList.remove('opacity-0');
                lightboxImg.classList.add('opacity-100');
            } else {
                let embedUrl = '';
                
                if (item.video_platform === 'youtube') {
                    embedUrl = `https://www.youtube.com/embed/${item.video_id}?autoplay=1`;
                } else if (item.video_platform === 'instagram') {
                    embedUrl = `https://www.instagram.com/reel/${item.video_id}/embed`;
                } else if (item.video_platform === 'vimeo') {
                    embedUrl = `https://player.vimeo.com/video/${item.video_id}?autoplay=1`;
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
            
            // Body scroll lock
            document.body.style.overflow = 'hidden';

            // Animate fade in
            void lightbox.offsetWidth;
            lightbox.classList.remove('opacity-0');
            lightbox.classList.add('opacity-100');
            lightboxContentBox.classList.remove('scale-95');
            lightboxContentBox.classList.add('scale-100');

            showLightboxItem(startIndex);

            // Pause testimonial autoplay when lightbox is opened
            if (typeof window.pauseTestimonialAutoplay === 'function') {
                window.pauseTestimonialAutoplay();
            }
        };

        const closeLightbox = () => {
            lightbox.classList.remove('opacity-100');
            lightbox.classList.add('opacity-0');
            lightboxContentBox.classList.remove('scale-100');
            lightboxContentBox.classList.add('scale-95');
            
            // Unlock scroll
            document.body.style.overflow = '';

            setTimeout(() => {
                lightbox.classList.remove('flex');
                lightbox.classList.add('hidden');
                lightboxIframe.setAttribute('src', '');
                lightboxImg.setAttribute('src', '');
            }, 300);

            // Resume testimonial autoplay when lightbox is closed
            if (typeof window.resumeTestimonialAutoplay === 'function') {
                window.resumeTestimonialAutoplay();
            }
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

        // Video Testimonial Player Logic
        const parseVideoLink = (link) => {
            if (!link) return null;
            link = link.trim();
            
            // 1. YouTube
            const youtubeRegex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|live|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i;
            const youtubeMatch = link.match(youtubeRegex);
            if (youtubeMatch) {
                return {
                    platform: 'youtube',
                    id: youtubeMatch[1]
                };
            }

            // 2. Instagram
            const instagramRegex = /instagram\.com\/(?:reel|p)\/([a-zA-Z0-9_-]+)/i;
            const instagramMatch = link.match(instagramRegex);
            if (instagramMatch) {
                return {
                    platform: 'instagram',
                    id: instagramMatch[1]
                };
            }

            // 3. Vimeo
            const vimeoRegex = /(?:vimeo\.com\/(?:channels\/[^\/]+\/|groups\/[^\/]+\/video\/|album\/[^\/]+\/video\/|video\/|)|player\.vimeo\.com\/video\/)([0-9]+)/i;
            const vimeoMatch = link.match(vimeoRegex);
            if (vimeoMatch) {
                return {
                    platform: 'vimeo',
                    id: vimeoMatch[1]
                };
            }

            return null;
        };

        const testimonialPlayBtns = document.querySelectorAll('.play-testimonial-btn');
        testimonialPlayBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const videoUrl = btn.getAttribute('data-video-url');
                const name = btn.getAttribute('data-name');
                const location = btn.getAttribute('data-location');
                
                const parsed = parseVideoLink(videoUrl);
                if (parsed) {
                    const item = {
                        type: 'video',
                        src: '',
                        title: `Testimoni dari ${name} (${location})`,
                        category: 'Video Jamaah',
                        video_id: parsed.id,
                        video_platform: parsed.platform
                    };
                    openLightbox([item], 0);
                } else {
                    console.warn('Format video tidak didukung atau tautan tidak valid:', videoUrl);
                }
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

        // Mobile swipe support (simple touch tracking)
        let touchstartX = 0;
        let touchendX = 0;

        lightbox.addEventListener('touchstart', e => {
            touchstartX = e.changedTouches[0].screenX;
        }, { passive: true });

        lightbox.addEventListener('touchend', e => {
            touchendX = e.changedTouches[0].screenX;
            handleSwipeGesture();
        }, { passive: true });

        const handleSwipeGesture = () => {
            const swipeThreshold = 50;
            if (touchendX < touchstartX - swipeThreshold) {
                navigateLightbox(1); // Swiped Left -> Next
            }
            if (touchendX > touchstartX + swipeThreshold) {
                navigateLightbox(-1); // Swiped Right -> Prev
            }
        };

        // Advanced Interactions & Performance-Driven Animations
        document.addEventListener("DOMContentLoaded", () => {
            // Interactive 3D Mouse Parallax Effect for Hero
            // (hero-image-card/hero-right-col were referenced by CSS/JS but never actually present
            // in the markup — a leftover from an older hero layout. Restored the ids on the current
            // schedule-card widget so this tilt effect actually runs. Also: this whole thing has to
            // live inside DOMContentLoaded — Motion/lucide are set by the deferred Vite bundle
            // script, which runs after any top-level classic <script> code like this used to be.)
            const heroSection = document.querySelector('[data-purpose="hero-banner"]');
            const heroRightCol = document.getElementById('hero-right-col');
            const imageCard = document.getElementById('hero-image-card');

            if (heroSection && imageCard) {
                heroRightCol.style.perspective = '1000px';

                // Motion spring instead of a CSS transition — the tilt now chases the cursor with
                // real physics (slight overshoot/settle) instead of snapping along a fixed easing curve.
                const tiltSpring = { type: Motion.spring, stiffness: 150, damping: 20, mass: 0.5 };

                let ticking = false;
                heroSection.addEventListener('mousemove', (e) => {
                    if (ticking) return;
                    ticking = true;
                    window.requestAnimationFrame(() => {
                        const rect = heroSection.getBoundingClientRect();
                        const x = (e.clientX - rect.left) / rect.width - 0.5;
                        const y = (e.clientY - rect.top) / rect.height - 0.5;

                        // 3D rotation on the schedule card, max 15deg
                        Motion.animate(imageCard, { rotateX: -y * 15, rotateY: x * 15, z: 10 }, tiltSpring);

                        ticking = false;
                    });
                });

                heroSection.addEventListener('mouseleave', () => {
                    Motion.animate(imageCard, { rotateX: 0, rotateY: 0, z: 0 }, tiltSpring);
                });
            }

            // Render Lucide Icons
            lucide.createIcons();



            // 2. Nav Header Capsule Transformation & Scrollspy Active States
            const navHeader = document.querySelector('header nav');
            const sections = document.querySelectorAll('section[id], footer[id]');
            const desktopNavLinks = document.querySelectorAll('.hidden.md\\:flex a');
            const mobileNavLinks = document.querySelectorAll('.md\\:hidden a');

            const scrollProgressBar = document.getElementById('scroll-progress-bar');

            // Cache section offsets to prevent forced reflows during scrollspy calculations
            let cachedSections = [];
            const recacheSections = () => {
                cachedSections = [];
                sections.forEach(section => {
                    cachedSections.push({
                        id: section.getAttribute('id'),
                        top: section.offsetTop,
                        height: section.offsetHeight
                    });
                });
            };

            // Initial cache and cache updates on resize / load
            recacheSections();
            window.addEventListener('resize', recacheSections);
            window.addEventListener('load', recacheSections);

            const onScrollHandler = () => {
                // Scroll progress bar (mengisi saat scroll ke bawah)
                if (scrollProgressBar) {
                    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
                    const ratio = docHeight > 0 ? Math.min(window.scrollY / docHeight, 1) : 0;
                    scrollProgressBar.style.transform = `scaleX(${ratio})`;
                }

                const siteLogo = navHeader.querySelector('img');
                const navCta = navHeader.querySelector('a.magnetic-button');

                // Header shrink & glassmorphism
                if (window.scrollY > 30) {
                    navHeader.classList.add('bg-white/90', 'backdrop-blur-md', 'border-white/60', 'shadow-[0_10px_35px_-10px_rgba(0,0,0,0.05)]', '!py-2');
                    navHeader.classList.remove('bg-transparent', 'border-transparent', 'shadow-none', 'py-3');
                    if (siteLogo) {
                        siteLogo.classList.remove('brightness-0', 'invert');
                    }
                    if (navCta) {
                        navCta.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700', 'shadow-blue-500/10');
                        navCta.classList.remove('bg-[#c89e2b]', 'text-[#113a6b]', 'hover:bg-[#b88e1b]', 'shadow-[#c89e2b]/15');
                    }
                } else {
                    navHeader.classList.remove('bg-white/90', 'backdrop-blur-md', 'border-white/60', 'shadow-[0_10px_35px_-10px_rgba(0,0,0,0.05)]', '!py-2');
                    navHeader.classList.add('bg-transparent', 'border-transparent', 'shadow-none', 'py-3');
                    if (siteLogo) {
                        siteLogo.classList.add('brightness-0', 'invert');
                    }
                    if (navCta) {
                        navCta.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700', 'shadow-blue-500/10');
                        navCta.classList.add('bg-[#c89e2b]', 'text-[#113a6b]', 'hover:bg-[#b88e1b]', 'shadow-[#c89e2b]/15');
                    }
                }

                // Scrollspy active section identifier using cached heights
                let currentId = 'beranda';
                const scrollPosition = window.scrollY + 160;

                cachedSections.forEach(sec => {
                    if (scrollPosition >= sec.top && scrollPosition < sec.top + sec.height) {
                        currentId = sec.id;
                    }
                });

                const isTop = window.scrollY <= 30;

                const setActiveNav = (links, activeClasses, inactiveClasses, useTopStyles) => {
                    links.forEach(link => {
                        const href = link.getAttribute('href');
                        if (href === `#${currentId}`) {
                            link.className = useTopStyles 
                                ? "text-[#c89e2b] bg-[#c89e2b]/10 px-3.5 py-2 rounded-full font-bold transition duration-200" 
                                : activeClasses;
                        } else {
                            link.className = useTopStyles 
                                ? "hover:text-white hover:bg-white/10 px-3.5 py-2 rounded-full transition duration-200 text-white/80" 
                                : inactiveClasses;
                        }
                    });
                };

                setActiveNav(
                    desktopNavLinks, 
                    "text-blue-600 bg-blue-50/80 px-3.5 py-2 rounded-full font-bold transition duration-200", 
                    "hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200 text-slate-600",
                    isTop
                );
                setActiveNav(
                    mobileNavLinks, 
                    "flex flex-col items-center gap-1 text-blue-600 transition active:scale-95", 
                    "flex flex-col items-center gap-1 hover:text-blue-600 transition active:scale-95 text-slate-500",
                    false
                );
            };

            // Throttle scroll event to prevent layout thrashing and main thread blocking
            let isScrolling = false;
            window.addEventListener('scroll', () => {
                if (!isScrolling) {
                    window.requestAnimationFrame(() => {
                        onScrollHandler();
                        isScrolling = false;
                    });
                    isScrolling = true;
                }
            });
            onScrollHandler();

            // 2b. Typewriter effect for Hero Tagline
            const typingTarget = document.getElementById('hero-typing-target');
            if (typingTarget) {
                const words = ["Umrah Impian", "Umrah Nyaman", "Umrah Sunnah", "Haji Khusus"];
                let wordIndex = 0;
                let charIndex = words[0].length;
                let isDeleting = false;
                let typeSpeed = 150;

                const type = () => {
                    const currentWord = words[wordIndex];
                    if (isDeleting) {
                        typingTarget.textContent = currentWord.substring(0, charIndex - 1);
                        charIndex--;
                        typeSpeed = 75; // Faster deleting
                    } else {
                        typingTarget.textContent = currentWord.substring(0, charIndex + 1);
                        charIndex++;
                        typeSpeed = 120; // Normal typing
                    }

                    if (!isDeleting && charIndex === currentWord.length) {
                        isDeleting = true;
                        typeSpeed = 2200; // Pause at end of word
                    } else if (isDeleting && charIndex === 0) {
                        isDeleting = false;
                        wordIndex = (wordIndex + 1) % words.length;
                        typeSpeed = 400; // Pause before typing next word
                    }

                    setTimeout(type, typeSpeed);
                };
                
                // Start after initial delay
                setTimeout(type, 1800);
            }

            // 4. Stats Counter Count-up
            const runCounters = () => {
                const counters = document.querySelectorAll('.stat-counter');
                counters.forEach(counter => {
                    const target = parseInt(counter.getAttribute('data-target'), 10);
                    const suffix = counter.getAttribute('data-suffix') || '';
                    const duration = 1500; // ms
                    const startTime = performance.now();

                    const updateCount = (currentTime) => {
                        const elapsedTime = currentTime - startTime;
                        const progress = Math.min(elapsedTime / duration, 1);
                        const easeProgress = progress * (2 - progress); // Ease out Quad
                        const value = Math.floor(easeProgress * target);
                        counter.textContent = value + suffix;

                        if (progress < 1) {
                            requestAnimationFrame(updateCount);
                        } else {
                            counter.textContent = target + suffix;
                        }
                    };
                    requestAnimationFrame(updateCount);
                });
            };

            const statsSection = document.querySelector('#tentang-kami');
            if (statsSection) {
                const statsObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            runCounters();
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.1 });
                statsObserver.observe(statsSection);
            }



            const track = document.getElementById('testimonial-track');
            const slides = document.querySelectorAll('.testimonial-slide');
            const prevBtn = document.getElementById('prev-testimonial');
            const nextBtn = document.getElementById('next-testimonial');
            const dotsContainer = document.getElementById('testimonial-dots');
            const progressBar = document.getElementById('testimonial-progress');
            const togglePlayBtn = document.getElementById('toggle-play-testimonial');
            const playPauseIcon = document.getElementById('play-pause-icon');
            const sliderContainer = document.getElementById('testimonial-slider-container');
            
            if (track && slides.length > 0) {
                let currentIndex = 0;
                let itemsPerSlide = 1;
                let isDragging = false;
                let startPos = 0;
                let currentTranslate = 0;
                let prevTranslate = 0;
                let animationID = 0;

                // Autoplay settings
                const AUTOPLAY_DURATION = 5000; // 5 seconds per slide
                let isAutoplayPaused = false;
                let isHovered = false;
                let isLightboxActive = false;
                let elapsed = 0;
                let lastTime = 0;
                let progressAnimID = 0;

                const getItemsPerSlide = () => {
                    if (window.innerWidth >= 1024) return 3;
                    if (window.innerWidth >= 768) return 2;
                    return 1;
                };

                const updateSliderDimensions = () => {
                    itemsPerSlide = getItemsPerSlide();
                    const maxIndex = Math.max(0, slides.length - itemsPerSlide);
                    if (currentIndex > maxIndex) {
                        currentIndex = maxIndex;
                    }
                    buildDots();
                    setPositionByIndex();
                };

                const buildDots = () => {
                    if (!dotsContainer) return;
                    dotsContainer.innerHTML = '';
                    const totalDots = Math.max(1, slides.length - itemsPerSlide + 1);
                    
                    for (let i = 0; i < totalDots; i++) {
                        const dot = document.createElement('span');
                        dot.className = `w-2.5 h-2.5 rounded-full cursor-pointer transition-all duration-300 ${i === currentIndex ? 'bg-blue-600 w-6' : 'bg-slate-200 hover:bg-slate-400'}`;
                        dot.addEventListener('click', () => {
                            currentIndex = i;
                            setPositionByIndex();
                            resetAutoplayTimer();
                        });
                        dotsContainer.appendChild(dot);
                    }
                };

                const updateDots = () => {
                    if (!dotsContainer) return;
                    const dots = dotsContainer.children;
                    for (let i = 0; i < dots.length; i++) {
                        if (i === currentIndex) {
                            dots[i].className = 'w-2.5 h-2.5 rounded-full cursor-pointer transition-all duration-300 bg-blue-600 w-6';
                        } else {
                            dots[i].className = 'w-2.5 h-2.5 rounded-full cursor-pointer transition-all duration-300 bg-slate-200 hover:bg-slate-400';
                        }
                    }
                };

                const setPositionByIndex = () => {
                    const slideWidth = slides[0].getBoundingClientRect().width;
                    currentTranslate = currentIndex * -slideWidth;
                    prevTranslate = currentTranslate;
                    track.style.transform = `translateX(${currentTranslate}px)`;
                    updateDots();

                    // Loop mode always has active arrows
                    if (prevBtn) {
                        prevBtn.disabled = false;
                        prevBtn.style.opacity = '1';
                    }
                    if (nextBtn) {
                        nextBtn.disabled = false;
                        nextBtn.style.opacity = '1';
                    }
                };

                const goToNextSlide = () => {
                    const maxIndex = Math.max(0, slides.length - itemsPerSlide);
                    if (currentIndex < maxIndex) {
                        currentIndex++;
                    } else {
                        currentIndex = 0;
                    }
                    setPositionByIndex();
                };

                const goToPrevSlide = () => {
                    const maxIndex = Math.max(0, slides.length - itemsPerSlide);
                    if (currentIndex > 0) {
                        currentIndex--;
                    } else {
                        currentIndex = maxIndex;
                    }
                    setPositionByIndex();
                };

                const resetAutoplayTimer = () => {
                    elapsed = 0;
                    if (progressBar) {
                        progressBar.style.width = '0%';
                    }
                    lastTime = performance.now();
                };

                // Advanced Autoplay Loop using requestAnimationFrame
                const startProgressTimer = () => {
                    lastTime = performance.now();
                    cancelAnimationFrame(progressAnimID);
                    
                    const animateProgress = () => {
                        const now = performance.now();
                        const delta = now - lastTime;
                        lastTime = now;

                        const shouldPause = isAutoplayPaused || isHovered || isDragging || isLightboxActive;

                        if (!shouldPause) {
                            elapsed += delta;
                            if (elapsed >= AUTOPLAY_DURATION) {
                                elapsed = 0;
                                goToNextSlide();
                            }
                        }

                        if (progressBar) {
                            const percentage = shouldPause && isAutoplayPaused ? 0 : Math.min((elapsed / AUTOPLAY_DURATION) * 100, 100);
                            progressBar.style.width = `${percentage}%`;
                        }

                        progressAnimID = requestAnimationFrame(animateProgress);
                    };

                    progressAnimID = requestAnimationFrame(animateProgress);
                };

                // Register global hooks for Lightbox player interaction
                window.pauseTestimonialAutoplay = () => {
                    isLightboxActive = true;
                };

                window.resumeTestimonialAutoplay = () => {
                    isLightboxActive = false;
                    lastTime = performance.now();
                };

                window.playTestimonialVideo = (embedUrl) => {
                    isLightboxActive = true; // pause autoplay
                    if (window.openVideoTestimonialModal) {
                        window.openVideoTestimonialModal(embedUrl);
                    }
                };

                // Hover Pause events
                if (sliderContainer) {
                    sliderContainer.addEventListener('mouseenter', () => {
                        isHovered = true;
                    });
                    sliderContainer.addEventListener('mouseleave', () => {
                        isHovered = false;
                        lastTime = performance.now();
                    });
                }

                // Play/Pause manual toggle
                if (togglePlayBtn) {
                    togglePlayBtn.addEventListener('click', () => {
                        isAutoplayPaused = !isAutoplayPaused;
                        if (isAutoplayPaused) {
                            if (playPauseIcon) {
                                playPauseIcon.setAttribute('data-lucide', 'play');
                            }
                            togglePlayBtn.setAttribute('aria-label', 'Play Autoplay');
                            elapsed = 0;
                            if (progressBar) progressBar.style.width = '0%';
                        } else {
                            if (playPauseIcon) {
                                playPauseIcon.setAttribute('data-lucide', 'pause');
                            }
                            togglePlayBtn.setAttribute('aria-label', 'Pause Autoplay');
                            lastTime = performance.now();
                        }
                        if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
                            lucide.createIcons();
                        }
                    });
                }

                // Drag/Touch Swipe Logic
                const dragStart = (e) => {
                    isDragging = true;
                    startPos = getPositionX(e);
                    animationID = requestAnimationFrame(animation);
                    track.classList.add('grabbing');
                    resetAutoplayTimer();
                };

                const dragMove = (e) => {
                    if (!isDragging) return;
                    const currentPosition = getPositionX(e);
                    currentTranslate = prevTranslate + currentPosition - startPos;
                };

                const dragEnd = () => {
                    isDragging = false;
                    cancelAnimationFrame(animationID);
                    track.classList.remove('grabbing');
                    
                    const movedBy = currentTranslate - prevTranslate;
                    const slideWidth = slides[0].getBoundingClientRect().width;
                    const maxIndex = Math.max(0, slides.length - itemsPerSlide);
                    
                    if (movedBy < -slideWidth * 0.2) {
                        if (currentIndex < maxIndex) {
                            currentIndex += 1;
                        } else {
                            currentIndex = 0;
                        }
                    } else if (movedBy > slideWidth * 0.2) {
                        if (currentIndex > 0) {
                            currentIndex -= 1;
                        } else {
                            currentIndex = maxIndex;
                        }
                    }
                    
                    setPositionByIndex();
                    resetAutoplayTimer();
                };

                const getPositionX = (e) => {
                    return e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
                };

                const animation = () => {
                    track.style.transform = `translateX(${currentTranslate}px)`;
                    if (isDragging) requestAnimationFrame(animation);
                };

                track.addEventListener('touchstart', dragStart, { passive: true });
                track.addEventListener('touchmove', dragMove, { passive: true });
                track.addEventListener('touchend', dragEnd);

                track.addEventListener('mousedown', dragStart);
                track.addEventListener('mousemove', dragMove);
                track.addEventListener('mouseup', dragEnd);
                track.addEventListener('mouseleave', dragEnd);

                if (prevBtn) {
                    prevBtn.addEventListener('click', () => {
                        goToPrevSlide();
                        resetAutoplayTimer();
                    });
                }

                if (nextBtn) {
                    nextBtn.addEventListener('click', () => {
                        goToNextSlide();
                        resetAutoplayTimer();
                    });
                }

                window.addEventListener('resize', updateSliderDimensions);
                updateSliderDimensions();
                startProgressTimer();
            }

            // 7. FAQ Smooth Height Accordion Toggle
            document.querySelectorAll('.faq-details').forEach(el => {
                const summary = el.querySelector('summary');
                const wrapper = el.querySelector('.faq-content-wrapper');
                
                summary.addEventListener('click', (e) => {
                    e.preventDefault();
                    
                    if (el.hasAttribute('open')) {
                        wrapper.style.gridTemplateRows = '0fr';
                        const onTransitionEnd = (evt) => {
                            if (evt.propertyName === 'grid-template-rows') {
                                wrapper.removeEventListener('transitionend', onTransitionEnd);
                                el.removeAttribute('open');
                            }
                        };
                        wrapper.addEventListener('transitionend', onTransitionEnd);
                    } else {
                        el.setAttribute('open', '');
                        wrapper.offsetHeight; // force repaint
                        wrapper.style.gridTemplateRows = '1fr';
                    }
                });
            });

            // 8. Magnetic Buttons — Motion spring instead of a raw style.transform snap
            const magneticSpring = { type: Motion.spring, stiffness: 300, damping: 20, mass: 0.5 };
            document.querySelectorAll('.magnetic-button').forEach(btn => {
                btn.addEventListener('mousemove', (e) => {
                    const rect = btn.getBoundingClientRect();
                    const x = e.clientX - rect.left - rect.width / 2;
                    const y = e.clientY - rect.top - rect.height / 2;
                    Motion.animate(btn, { x: x * 0.15, y: y * 0.15 }, magneticSpring);
                });

                btn.addEventListener('mouseleave', () => {
                    Motion.animate(btn, { x: 0, y: 0 }, magneticSpring);
                });
            });

            // 9. Process headings with .reveal-words class into individual spans
            const revealWordsElements = document.querySelectorAll('.reveal-words');
            revealWordsElements.forEach(el => {
                const walkTextNodes = (node) => {
                    if (node.nodeType === Node.TEXT_NODE) {
                        const words = node.nodeValue.split(/(\s+)/);
                        const fragment = document.createDocumentFragment();
                        words.forEach(word => {
                            if (word.trim() === '') {
                                fragment.appendChild(document.createTextNode(word));
                            } else {
                                const span = document.createElement('span');
                                span.textContent = word;
                                span.classList.add('reveal-word');
                                fragment.appendChild(span);
                            }
                        });
                        node.parentNode.replaceChild(fragment, node);
                    } else {
                        Array.from(node.childNodes).forEach(walkTextNodes);
                    }
                };
                walkTextNodes(el);
            });

            // 10. Scroll reveal via Motion — replaces the old CSS-transition + IntersectionObserver
            // system. Explicit [from, to] keyframes so Motion doesn't need to decompose the CSS
            // resting transform already set on these elements (used as the no-JS fallback state).
            const REVEAL_KEYFRAMES = {
                reveal: { opacity: [0, 1], y: [30, 0] },
                'reveal-left': { opacity: [0, 1], x: [-35, 0] },
                'reveal-right': { opacity: [0, 1], x: [35, 0] },
                'reveal-scale': { opacity: [0, 1], scale: [0.95, 1] },
            };
            const revealSpring = { type: Motion.spring, bounce: 0.15, duration: 0.7 };
            const revealViewport = { margin: '0px 0px -40px 0px', amount: 0.05 };

            document.querySelectorAll('.reveal-stagger').forEach(parent => {
                const stop = Motion.inView(parent, () => {
                    const children = parent.querySelectorAll('.reveal-card, .reveal-child');
                    Motion.animate(
                        children,
                        { opacity: [0, 1], y: [35, 0], scale: [0.97, 1] },
                        { delay: Motion.stagger(0.08), ...revealSpring }
                    );
                    stop();
                }, revealViewport);
            });

            document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale').forEach(el => {
                if (el.closest('.reveal-stagger')) return; // handled above as a staggered group

                const variant = ['reveal-left', 'reveal-right', 'reveal-scale'].find(cls => el.classList.contains(cls)) || 'reveal';
                const stop = Motion.inView(el, () => {
                    Motion.animate(el, REVEAL_KEYFRAMES[variant], revealSpring);
                    stop();
                }, revealViewport);
            });

            document.querySelectorAll('.reveal-words').forEach(el => {
                const stop = Motion.inView(el, () => {
                    const wordSpans = el.querySelectorAll('.reveal-word');
                    Motion.animate(
                        wordSpans,
                        { opacity: [0, 1], y: [12, 0] },
                        { delay: Motion.stagger(0.045), duration: 0.5, ease: 'easeOut' }
                    );
                    stop();
                }, revealViewport);
            });

            // 11. Glow Card Border Effect (Performance Throttled)
            const glowCards = document.querySelectorAll('.glow-card');
            glowCards.forEach(card => {
                let glowTicking = false;
                card.addEventListener('mousemove', e => {
                    if (!glowTicking) {
                        window.requestAnimationFrame(() => {
                            const rect = card.getBoundingClientRect();
                            const x = e.clientX - rect.left;
                            const y = e.clientY - rect.top;
                            card.style.setProperty('--mouse-x', `${x}px`);
                            card.style.setProperty('--mouse-y', `${y}px`);
                            glowTicking = false;
                        });
                        glowTicking = true;
                    }
                });
            });

            // 12. Interactive Package Search Filtering Logic
            const searchMonth = document.getElementById('search-month');
            const searchTier = document.getElementById('search-tier');
            const searchPrice = document.getElementById('search-price');
            const searchBtn = document.getElementById('search-btn');
            const resetFilterBtn = document.getElementById('reset-filter-btn');
            const noResultsState = document.getElementById('no-results-state');
            const packageCards = document.querySelectorAll('.package-card');

            if (searchMonth && searchTier && searchPrice && searchBtn && resetFilterBtn && noResultsState) {
                const filterPackages = () => {
                    const selectedMonth = searchMonth.value;
                    const selectedTier = searchTier.value;
                    const selectedPriceRange = searchPrice.value;
                    let visibleCount = 0;

                    packageCards.forEach(card => {
                        const cardMonth = card.getAttribute('data-month');
                        const cardTier = card.getAttribute('data-tier');
                        const cardPrice = parseInt(card.getAttribute('data-price'), 10);

                        const matchesMonth = (selectedMonth === 'all' || cardMonth === selectedMonth);
                        const matchesTier = (selectedTier === 'all' || cardTier === selectedTier);

                        let matchesPrice = false;
                        if (selectedPriceRange === 'all') {
                            matchesPrice = true;
                        } else if (selectedPriceRange === 'low') {
                            matchesPrice = (cardPrice < 35000000);
                        } else if (selectedPriceRange === 'mid') {
                            matchesPrice = (cardPrice >= 35000000 && cardPrice <= 45000000);
                        } else if (selectedPriceRange === 'high') {
                            matchesPrice = (cardPrice > 45000000);
                        }

                        if (matchesMonth && matchesTier && matchesPrice) {
                            card.style.display = 'flex';
                            setTimeout(() => {
                                card.style.opacity = '1';
                                card.style.transform = 'scale(1)';
                            }, 50);
                            visibleCount++;
                        } else {
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.95)';
                            setTimeout(() => {
                                card.style.display = 'none';
                            }, 250);
                        }
                    });

                    if (visibleCount === 0) {
                        setTimeout(() => {
                            noResultsState.classList.remove('hidden');
                            noResultsState.classList.add('flex');
                        }, 250);
                    } else {
                        noResultsState.classList.add('hidden');
                        noResultsState.classList.remove('flex');
                    }
                };

                const resetFilters = () => {
                    searchMonth.value = 'all';
                    searchTier.value = 'all';
                    searchPrice.value = 'all';
                    filterPackages();
                };

                searchBtn.addEventListener('click', filterPackages);
                resetFilterBtn.addEventListener('click', resetFilters);
            }

            // 14. Articles Category Tab Filter & Sliding Pill Logic
            const articleTabBtns = document.querySelectorAll('.article-tab-btn');
            const articleTabPill = document.getElementById('article-tab-pill');
            const articleCards = document.querySelectorAll('.article-card');

            const updateArticleTabPill = (btn) => {
                if (!articleTabPill || !btn) return;
                articleTabPill.style.width = `${btn.offsetWidth}px`;
                articleTabPill.style.left = `${btn.offsetLeft}px`;
                articleTabPill.style.height = `${btn.offsetHeight}px`;
                articleTabPill.style.top = `${btn.offsetTop}px`;
            };

            // Set initial position of the pill
            const activeArticleBtn = document.querySelector('.article-tab-btn.active');
            if (activeArticleBtn) {
                setTimeout(() => {
                    updateArticleTabPill(activeArticleBtn);
                    // Center the active tab initially on load if overflowed
                    const wrapper = activeArticleBtn.closest('.overflow-x-auto');
                    if (wrapper) {
                        const containerHalfWidth = wrapper.clientWidth / 2;
                        const btnHalfWidth = activeArticleBtn.offsetWidth / 2;
                        const scrollLeft = activeArticleBtn.offsetLeft - containerHalfWidth + btnHalfWidth;
                        wrapper.scrollLeft = scrollLeft;
                    }
                }, 150);
            }

            articleTabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    articleTabBtns.forEach(b => {
                        b.classList.remove('active', 'text-blue-600');
                        b.classList.add('text-slate-500');
                    });
                    btn.classList.add('active', 'text-blue-600');
                    btn.classList.remove('text-slate-500');
                    updateArticleTabPill(btn);

                    // Smoothly center the clicked tab in the scrollable wrapper
                    const wrapper = btn.closest('.overflow-x-auto');
                    if (wrapper) {
                        const containerHalfWidth = wrapper.clientWidth / 2;
                        const btnHalfWidth = btn.offsetWidth / 2;
                        const scrollLeft = btn.offsetLeft - containerHalfWidth + btnHalfWidth;
                        wrapper.scrollTo({
                            left: scrollLeft,
                            behavior: 'smooth'
                        });
                    }

                    const filter = btn.getAttribute('data-filter');
                    
                    articleCards.forEach(card => {
                        const category = card.getAttribute('data-category');
                        if (filter === 'all' || category === filter) {
                            card.style.display = 'flex';
                            setTimeout(() => {
                                card.style.opacity = '1';
                                card.style.transform = 'scale(1)';
                            }, 50);
                        } else {
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.95)';
                            setTimeout(() => {
                                card.style.display = 'none';
                            }, 250);
                        }
                    });
                });
            });

            window.addEventListener('resize', () => {
                const activeBtn = document.querySelector('.article-tab-btn.active');
                if (activeBtn) updateArticleTabPill(activeBtn);
            });
        });
    </script>

    <!-- BEGIN: Haramain, Clock, Weather & Prayer Times Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ==========================================
            // 1. HERO COUNTDOWN TIMER
            // ==========================================
            const countdownEl = document.getElementById('hero-countdown');
            if (countdownEl) {
                const targetDateStr = countdownEl.getAttribute('data-date');
                if (targetDateStr) {
                    const targetDate = new Date(targetDateStr + 'T00:00:00');
                    
                    const updateCountdown = () => {
                        const now = new Date();
                        const diff = targetDate - now;
                        
                        if (diff <= 0) {
                            document.getElementById('countdown-days').textContent = '00';
                            document.getElementById('countdown-hours').textContent = '00';
                            document.getElementById('countdown-mins').textContent = '00';
                            document.getElementById('countdown-secs').textContent = '00';
                            return;
                        }
                        
                        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        const secs = Math.floor((diff % (1000 * 60)) / 1000);
                        
                        document.getElementById('countdown-days').textContent = String(days).padStart(2, '0');
                        document.getElementById('countdown-hours').textContent = String(hours).padStart(2, '0');
                        document.getElementById('countdown-mins').textContent = String(mins).padStart(2, '0');
                        document.getElementById('countdown-secs').textContent = String(secs).padStart(2, '0');
                    };
                    
                    updateCountdown();
                    setInterval(updateCountdown, 1000);
                }
            }

            // ==========================================
            // 2. REAL-TIME MULTI-ZONE CLOCK
            // ==========================================
            let currentTab = 'makkah'; // makkah | madinah | wib | wita

            const timezoneOffsets = {
                makkah: 3,     // UTC+3
                madinah: 3,    // UTC+3
                wib: 7,        // UTC+7 (WIB)
                wita: 8        // UTC+8 (WITA)
            };

            const locationNames = {
                makkah: 'Makkah Al-Mukarramah',
                madinah: 'Madinah Al-Munawwarah',
                wib: 'Jakarta, Indonesia (WIB)',
                wita: 'Makassar, Indonesia (WITA)'
            };

            const dayNames = ['Ahad', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'];
            const monthNames = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            const updateClock = () => {
                const now = new Date();
                // Get UTC time
                const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
                // Adjust to timezone offset
                const offset = timezoneOffsets[currentTab];
                const localTime = new Date(utc + (3600000 * offset));

                // Format Time
                let hours = localTime.getHours();
                const minutes = String(localTime.getMinutes()).padStart(2, '0');
                const seconds = String(localTime.getSeconds()).padStart(2, '0');
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
                const formattedTime = `${String(hours).padStart(2, '0')}:${minutes}:${seconds} ${ampm}`;

                // Format Date
                const dayName = dayNames[localTime.getDay()];
                const date = localTime.getDate();
                const monthName = monthNames[localTime.getMonth()];
                const year = localTime.getFullYear();
                const formattedDate = `${dayName}, ${date} ${monthName} ${year}`;

                // Update DOM
                const clockTimeEl = document.getElementById('clock-time');
                const clockDateEl = document.getElementById('clock-date');
                if (clockTimeEl) clockTimeEl.textContent = formattedTime;
                if (clockDateEl) clockDateEl.textContent = formattedDate;

                // Update next prayer countdown
                updateNextPrayerCountdown(localTime);
            };

            // Expose setHaramainTab to global window scope
            window.setHaramainTab = (tab) => {
                currentTab = tab;
                
                // Toggle tab buttons visual classes
                const tabs = ['makkah', 'madinah', 'wib', 'wita'];
                tabs.forEach(t => {
                    const btn = document.getElementById(`tab-btn-${t}`);
                    if (btn) {
                        if (t === tab) {
                            btn.className = "flex-1 whitespace-nowrap text-center py-2 px-3 rounded-full text-xs font-bold transition duration-300 bg-[#c89e2b] text-[#071930] shadow-md shadow-[#c89e2b]/10";
                        } else {
                            btn.className = "flex-1 whitespace-nowrap text-center py-2 px-3 rounded-full text-xs font-bold transition duration-300 text-white hover:bg-white/5";
                        }
                    }
                });

                // Update location title
                const titleEl = document.getElementById('clock-location-title');
                if (titleEl) titleEl.textContent = locationNames[tab];

                // Render current timings
                renderPrayerTimings();
                
                // Force clock update immediately
                updateClock();
            };

            // ==========================================
            // 3. PRAYER TIMINGS & COUNTDOWN LOGIC
            // ==========================================
            const prayerTimings = {
                makkah: { Subuh: '04:21', Dzuhur: '12:27', Ashar: '15:41', Maghrib: '19:06', Isya: '20:36' },
                madinah: { Subuh: '04:18', Dzuhur: '12:28', Ashar: '15:49', Maghrib: '19:11', Isya: '20:41' },
                wib: { Subuh: '04:42', Dzuhur: '12:00', Ashar: '15:22', Maghrib: '17:54', Isya: '19:08' },
                wita: { Subuh: '04:54', Dzuhur: '12:12', Ashar: '15:35', Maghrib: '18:09', Isya: '19:22' }
            };

            // Fetch live prayer times from API
            const fetchPrayerTimes = async () => {
                try {
                    // Fetch Makkah (coordinates: Lat 21.3891, Lon 39.8579)
                    const makkahRes = await fetch('https://api.aladhan.com/v1/timings?latitude=21.3891&longitude=39.8579&method=4');
                    if (makkahRes.ok) {
                        const data = await makkahRes.json();
                        const timings = data.data.timings;
                        prayerTimings.makkah = {
                            Subuh: timings.Fajr,
                            Dzuhur: timings.Dhuhr,
                            Ashar: timings.Asr,
                            Maghrib: timings.Maghrib,
                            Isya: timings.Isha
                        };
                    }
                    
                    // Fetch Madinah (coordinates: Lat 24.4672, Lon 39.6111)
                    const madinahRes = await fetch('https://api.aladhan.com/v1/timings?latitude=24.4672&longitude=39.6111&method=4');
                    if (madinahRes.ok) {
                        const data = await madinahRes.json();
                        const timings = data.data.timings;
                        prayerTimings.madinah = {
                            Subuh: timings.Fajr,
                            Dzuhur: timings.Dhuhr,
                            Ashar: timings.Asr,
                            Maghrib: timings.Maghrib,
                            Isya: timings.Isha
                        };
                    }

                    // Fetch WIB (Jakarta, coordinates: Lat -6.2088, Lon 106.8456)
                    const wibRes = await fetch('https://api.aladhan.com/v1/timings?latitude=-6.2088&longitude=106.8456&method=15'); // Kemenag RI method 15
                    if (wibRes.ok) {
                        const data = await wibRes.json();
                        const timings = data.data.timings;
                        prayerTimings.wib = {
                            Subuh: timings.Fajr,
                            Dzuhur: timings.Dhuhr,
                            Ashar: timings.Asr,
                            Maghrib: timings.Maghrib,
                            Isya: timings.Isha
                        };
                    }

                    // Fetch WITA (Makassar, coordinates: Lat -5.1476, Lon 119.4327)
                    const witaRes = await fetch('https://api.aladhan.com/v1/timings?latitude=-5.1476&longitude=119.4327&method=15'); // Kemenag RI method 15
                    if (witaRes.ok) {
                        const data = await witaRes.json();
                        const timings = data.data.timings;
                        prayerTimings.wita = {
                            Subuh: timings.Fajr,
                            Dzuhur: timings.Dhuhr,
                            Ashar: timings.Asr,
                            Maghrib: timings.Maghrib,
                            Isya: timings.Isha
                        };
                    }
                    
                    // Re-render after successful fetches
                    renderPrayerTimings();
                } catch (e) {
                    console.log('Error fetching prayer times, using high-precision fallback.', e);
                }
            };

            const renderPrayerTimings = () => {
                const timings = prayerTimings[currentTab];
                document.getElementById('prayer-time-subuh').textContent = timings.Subuh;
                document.getElementById('prayer-time-dzuhur').textContent = timings.Dzuhur;
                document.getElementById('prayer-time-ashar').textContent = timings.Ashar;
                document.getElementById('prayer-time-maghrib').textContent = timings.Maghrib;
                document.getElementById('prayer-time-isya').textContent = timings.Isya;
            };

            const updateNextPrayerCountdown = (localTime) => {
                const timings = prayerTimings[currentTab];
                
                // Parse prayer times into minutes since midnight
                const parseToMinutes = (timeStr) => {
                    const parts = timeStr.split(':');
                    return parseInt(parts[0], 10) * 60 + parseInt(parts[1], 10);
                };

                const nowMinutes = localTime.getHours() * 60 + localTime.getMinutes();
                const nowSecondsInMinute = localTime.getSeconds();

                const prayers = [
                    { name: 'Subuh', minutes: parseToMinutes(timings.Subuh) },
                    { name: 'Dzuhur', minutes: parseToMinutes(timings.Dzuhur) },
                    { name: 'Ashar', minutes: parseToMinutes(timings.Ashar) },
                    { name: 'Maghrib', minutes: parseToMinutes(timings.Maghrib) },
                    { name: 'Isya', minutes: parseToMinutes(timings.Isya) }
                ];

                // Find next prayer
                let nextPrayer = null;
                let minutesDiff = 0;

                for (let i = 0; i < prayers.length; i++) {
                    if (prayers[i].minutes > nowMinutes) {
                        nextPrayer = prayers[i];
                        minutesDiff = prayers[i].minutes - nowMinutes;
                        break;
                    }
                }

                // If all prayers today have passed, the next prayer is Subuh tomorrow
                if (!nextPrayer) {
                    nextPrayer = prayers[0]; // Subuh
                    minutesDiff = (24 * 60 - nowMinutes) + prayers[0].minutes;
                }

                // Convert differences to hours, minutes, seconds countdown
                let totalSeconds = minutesDiff * 60 - nowSecondsInMinute;
                if (totalSeconds < 0) totalSeconds = 0;

                const countdownHours = Math.floor(totalSeconds / 3600);
                const countdownMins = Math.floor((totalSeconds % 3600) / 60);
                const countdownSecs = totalSeconds % 60;

                const formattedCountdown = `${String(countdownHours).padStart(2, '0')}:${String(countdownMins).padStart(2, '0')}:${String(countdownSecs).padStart(2, '0')}`;

                const nextPrayerNameEl = document.getElementById('next-prayer-name');
                const nextPrayerCountdownEl = document.getElementById('next-prayer-countdown');
                
                if (nextPrayerNameEl) nextPrayerNameEl.textContent = nextPrayer.name;
                if (nextPrayerCountdownEl) nextPrayerCountdownEl.textContent = formattedCountdown;
            };

            // ==========================================
            // 4. LIVE STREAM MASJIDIL HARAM
            // ==========================================
            const makkahSetting = @json($settings['haramain_youtube_makkah'] ?? 'UCos52azQNBgW63_9uDJoPDA');

            function getYoutubeStreamData(input) {
                if (!input) {
                    return {
                        embed: 'https://www.youtube.com/embed/live_stream?channel=UCos52azQNBgW63_9uDJoPDA',
                        direct: 'https://www.youtube.com/channel/UCos52azQNBgW63_9uDJoPDA'
                    };
                }
                input = String(input).trim();

                let embed = input;
                let direct = input;

                // Match standard video link: watch?v=..., live/..., youtu.be/..., shorts/..., embed/..., or v/...
                const videoMatch = input.match(/(?:youtube\.com\/(?:watch\?.*v=|v\/|live\/|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/i);
                if (videoMatch && videoMatch[1]) {
                    embed = `https://www.youtube.com/embed/${videoMatch[1]}?autoplay=1&mute=1`;
                    direct = `https://www.youtube.com/watch?v=${videoMatch[1]}`;
                } else if (/^[a-zA-Z0-9_-]{11}$/.test(input)) {
                    embed = `https://www.youtube.com/embed/${input}?autoplay=1&mute=1`;
                    direct = `https://www.youtube.com/watch?v=${input}`;
                } else if (input.startsWith('UC') || input.includes('channel/UC')) {
                    const channelId = input.replace(/.*channel\//, '').split('/')[0].split('?')[0];
                    embed = `https://www.youtube.com/embed/live_stream?channel=${channelId}&autoplay=1&mute=1`;
                    direct = `https://www.youtube.com/channel/${channelId}`;
                } else if (!input.startsWith('http://') && !input.startsWith('https://')) {
                    embed = `https://www.youtube.com/embed/live_stream?channel=${input}&autoplay=1&mute=1`;
                    direct = `https://www.youtube.com/channel/${input}`;
                }

                return { embed, direct };
            }

            const streamData = getYoutubeStreamData(makkahSetting);

            // Set initial direct link
            const youtubeDirectLink = document.getElementById('youtube-direct-link');
            if (youtubeDirectLink && streamData.direct) {
                youtubeDirectLink.setAttribute('href', streamData.direct);
            }

            // Facade click-to-load logic
            const haramainFacade = document.getElementById('haramain-facade');
            const haramainIframe = document.getElementById('haramain-iframe');

            const loadHaramainStream = () => {
                if (haramainIframe && haramainIframe.getAttribute('src') === 'about:blank' && streamData.embed) {
                    // Set stream src and add autoplay
                    const autoplayEmbed = streamData.embed.includes('?') 
                        ? streamData.embed + '&autoplay=1&mute=1' 
                        : streamData.embed + '?autoplay=1&mute=1';
                    
                    haramainIframe.setAttribute('src', autoplayEmbed);
                    haramainIframe.classList.remove('hidden');
                    
                    if (haramainFacade) {
                        haramainFacade.classList.add('opacity-0', 'pointer-events-none');
                        setTimeout(() => haramainFacade.classList.add('hidden'), 500);
                    }
                }
            };

            if (haramainFacade) {
                haramainFacade.addEventListener('click', loadHaramainStream);
            }

            window.refreshHaramainStream = () => {
                if (haramainIframe) {
                    if (haramainIframe.getAttribute('src') === 'about:blank') {
                        loadHaramainStream();
                    } else if (streamData.embed) {
                        const autoplayEmbed = streamData.embed.includes('?') 
                            ? streamData.embed + '&autoplay=1&mute=1' 
                            : streamData.embed + '?autoplay=1&mute=1';
                        
                        haramainIframe.setAttribute('src', 'about:blank');
                        setTimeout(() => {
                            haramainIframe.setAttribute('src', autoplayEmbed);
                        }, 100);
                    }
                }
            };

            // ==========================================
            // 5. WEATHER IN MAKKAH (API CALL)
            // ==========================================
            const fetchMakkahWeather = async () => {
                try {
                    const res = await fetch('https://api.open-meteo.com/v1/forecast?latitude=21.3891&longitude=39.8579&current_weather=true');
                    if (res.ok) {
                        const data = await res.json();
                        const temp = Math.round(data.current_weather.temperature);
                        const code = data.current_weather.weathercode;
                        
                        // Map code to Indonesian description and Lucide icon name
                        let desc = 'Cerah';
                        let iconName = 'sun';

                        if (code === 0) { desc = 'Cerah, Hangat'; iconName = 'sun'; }
                        else if (code >= 1 && code <= 3) { desc = 'Cerah Berawan'; iconName = 'cloud-sun'; }
                        else if (code >= 45 && code <= 48) { desc = 'Berkabut'; iconName = 'cloud'; }
                        else if (code >= 51 && code <= 67) { desc = 'Gerimis Ringan'; iconName = 'cloud-drizzle'; }
                        else if (code >= 71 && code <= 77) { desc = 'Hujan Abu'; iconName = 'cloud-snow'; }
                        else if (code >= 80 && code <= 82) { desc = 'Hujan'; iconName = 'cloud-rain'; }
                        else if (code >= 95 && code <= 99) { desc = 'Badai Petir'; iconName = 'cloud-lightning'; }

                        const tempEl = document.getElementById('weather-temp');
                        const descEl = document.getElementById('weather-desc');
                        const iconEl = document.getElementById('weather-icon');

                        if (tempEl) tempEl.textContent = `${temp}°C`;
                        if (descEl) descEl.textContent = desc;

                        if (iconEl && window.lucide) {
                            iconEl.setAttribute('data-lucide', iconName);
                            window.lucide.createIcons();
                        }
                    }
                } catch (e) {
                    console.log('Error fetching Makkah weather, using default fallback.', e);
                    // Default fallback
                    const tempEl = document.getElementById('weather-temp');
                    const descEl = document.getElementById('weather-desc');
                    if (tempEl) tempEl.textContent = '41°C';
                    if (descEl) descEl.textContent = 'Cerah Berawan';
                }
            };

            // ==========================================
            // 6. LIVE PILGRIMS ESTIMATE COUNTER
            // ==========================================
            let baseDensity = {{ (int)($settings['haramain_density_base'] ?? 254025) }};
            const densityCounterEl = document.getElementById('density-counter');

            const updateDensityCounter = () => {
                if (densityCounterEl) {
                    // Fluctuates slightly up and down to feel live
                    const fluctuation = Math.floor(Math.random() * 50) - 25; // +/- 25
                    baseDensity += fluctuation;
                    
                    // Format with comma
                    densityCounterEl.textContent = baseDensity.toLocaleString('id-ID');
                }
            };

            // Initialize Page Actions
            renderPrayerTimings();
            updateClock();
            setInterval(updateClock, 1000);
            
            fetchPrayerTimes();
            fetchMakkahWeather();
            
            setInterval(updateDensityCounter, 3000);

            // Re-fetch weather and times every 30 minutes
            setInterval(() => {
                fetchPrayerTimes();
                fetchMakkahWeather();
            }, 1800000);
        });
    </script>

    <!-- BEGIN: Mobile Bottom Navigation Bar (App-Like) -->
    <div class="md:hidden fixed bottom-4 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-md bg-white/95 backdrop-blur-md border border-slate-100 rounded-full shadow-[0_-10px_35px_-10px_rgba(0,0,0,0.1),_0_10px_35px_-10px_rgba(0,0,0,0.05)] px-6 py-3 z-50">
        <div class="flex items-center justify-between text-slate-500 font-medium">
            <a href="#beranda" class="flex flex-col items-center gap-1 text-blue-600 transition active:scale-95">
                <i data-lucide="home" class="w-5 h-5"></i>
                <span class="text-[9px] font-extrabold uppercase tracking-wider">Beranda</span>
            </a>
            <a href="#paket-umrah" class="flex flex-col items-center gap-1 hover:text-blue-600 transition active:scale-95">
                <i data-lucide="compass" class="w-5 h-5"></i>
                <span class="text-[9px] font-extrabold uppercase tracking-wider">Paket</span>
            </a>
            <a href="#galeri" class="flex flex-col items-center gap-1 hover:text-blue-600 transition active:scale-95">
                <i data-lucide="image" class="w-5 h-5"></i>
                <span class="text-[9px] font-extrabold uppercase tracking-wider">Galeri</span>
            </a>
            <a href="#artikel" class="flex flex-col items-center gap-1 hover:text-blue-600 transition active:scale-95">
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
                    <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO.webp') }}" alt="{{ $settings['site_name'] ?? 'IZI Travel' }}" class="h-7 w-auto object-contain" width="180" height="28" decoding="async" />
                    <button id="mobile-drawer-close" aria-label="Tutup menu" class="w-8 h-8 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-800 transition active:scale-95 shadow-sm">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <!-- Navigation Links (Exact 8 items order) -->
                <div class="flex flex-col space-y-1">
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="#beranda">
                        <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100/50"><i data-lucide="home" class="w-4 h-4"></i></span>
                        Beranda
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="#tentang-kami">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="users" class="w-4 h-4"></i></span>
                        Tentang Kami
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="#paket-umrah">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="compass" class="w-4 h-4"></i></span>
                        Paket Umrah
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="#galeri">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="image" class="w-4 h-4"></i></span>
                        Galeri
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="#testimoni">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="message-square" class="w-4 h-4"></i></span>
                        Testimoni
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="#artikel">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="book-open" class="w-4 h-4"></i></span>
                        Artikel
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="#kemitraan">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="award" class="w-4 h-4"></i></span>
                        Kemitraan
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank">
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
    <a href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank" class="fixed bottom-20 md:bottom-6 right-6 z-50 bg-gradient-to-r from-emerald-500 to-green-600 text-white p-4 rounded-full shadow-2xl shadow-emerald-500/30 flex items-center justify-center animate-pulse-glow hover:scale-110 active:scale-95 transition-all duration-300 group" aria-label="Hubungi WhatsApp">
        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
        </svg>
        <span class="max-w-0 overflow-hidden group-hover:max-w-xs group-hover:ml-2 transition-all duration-500 ease-out text-xs font-bold whitespace-nowrap">
            Chat WhatsApp
        </span>
    </a>

    <!-- Video Testimonial Modal (Premium & Smartphone-Adaptive for Instagram) -->
    <div id="video-testimonial-modal" class="fixed inset-0 z-[100] bg-slate-950/80 backdrop-blur-md hidden opacity-0 transition-opacity duration-300 items-center justify-center p-4" role="dialog" aria-modal="true">
        <div class="absolute inset-0 cursor-pointer" onclick="window.closeVideoTestimonialModal()"></div>
        <div class="relative w-full max-w-4xl bg-black rounded-3xl overflow-hidden shadow-2xl transition-transform duration-300 scale-95 flex flex-col items-center border border-white/10" id="video-modal-content">
            <!-- Close Button -->
            <button onclick="window.closeVideoTestimonialModal()" class="absolute top-4 right-4 z-[110] bg-slate-900/60 hover:bg-slate-800/80 text-white p-2 rounded-full transition active:scale-95 border border-white/10" aria-label="Tutup Video">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <!-- Video Wrapper -->
            <div id="video-modal-iframe-container" class="w-full h-full aspect-video flex items-center justify-center">
                <!-- iframe will be injected here -->
            </div>
        </div>
    </div>

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
        });

        // Global Modal Logic for Video Testimonials
        window.closeVideoTestimonialModal = () => {
            const modal = document.getElementById('video-testimonial-modal');
            const modalContent = document.getElementById('video-modal-content');
            const iframeContainer = document.getElementById('video-modal-iframe-container');
            
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                iframeContainer.innerHTML = '';
                
                // Resume autoplay
                if (typeof window.resumeTestimonialAutoplay === 'function') {
                    window.resumeTestimonialAutoplay();
                }
            }, 300);
        };

        window.openVideoTestimonialModal = (embedUrl) => {
            const modal = document.getElementById('video-testimonial-modal');
            const modalContent = document.getElementById('video-modal-content');
            const iframeContainer = document.getElementById('video-modal-iframe-container');
            
            // Check if it is instagram
            const isInstagram = embedUrl.includes('instagram.com');
            
            if (isInstagram) {
                // Portrait style (smartphone aspect ratio)
                modalContent.style.maxWidth = '380px';
                iframeContainer.style.aspectRatio = '9/16';
                iframeContainer.style.height = '75vh';
                iframeContainer.style.maxHeight = '650px';
            } else {
                // Landscape style
                modalContent.style.maxWidth = '896px';
                iframeContainer.style.aspectRatio = '16/9';
                iframeContainer.style.height = 'auto';
                iframeContainer.style.maxHeight = 'none';
            }
            
            iframeContainer.innerHTML = `<iframe class="w-full h-full" title="Video testimoni" src="${embedUrl}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Trigger reflow
            void modal.offsetWidth;
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
            
            if (window.lucide) {
                window.lucide.createIcons();
            }
        };

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            const modal = document.getElementById('video-testimonial-modal');
            if (modal && !modal.classList.contains('hidden') && e.key === 'Escape') {
                window.closeVideoTestimonialModal();
            }
        });
    </script>
</body>
</html>
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
    $heroMobileImageUrl = !empty($settings['hero_image'])
        ? (!empty($settings['hero_image_mobile']) && ($settings['hero_image_mobile_source'] ?? '') === $settings['hero_image']
            ? asset('storage/' . $settings['hero_image_mobile'])
            : $heroImageUrl)
        : Vite::asset('resources/images/hero_kaaba_mobile.webp');

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

    <!-- Each viewport only preloads the artwork it actually displays. -->
    <link rel="preload" as="image" href="{{ $heroImageUrl }}" media="(min-width: 768px)" fetchpriority="high" />
    <link rel="preload" as="image" href="{{ Vite::asset('resources/images/hero-kaaba-foreground-mobile.webp') }}" media="(max-width: 767px)" fetchpriority="high" />
    <link rel="preload" as="image" href="{{ $heroMobileImageUrl }}" media="(max-width: 767px)" />
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sections = document.querySelectorAll('[data-lazy-bg]');
            const showBackground = section => {
                section.classList.add('is-background-visible');
                if (section.dataset.lazyBg) {
                    section.style.setProperty('--deferred-bg', `url(${JSON.stringify(section.dataset.lazyBg)})`);
                }
            };

            if (!('IntersectionObserver' in window)) {
                sections.forEach(showBackground);
                return;
            }

            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) return;
                    showBackground(entry.target);
                    observer.unobserve(entry.target);
                });
            }, { rootMargin: '600px 0px' });
            sections.forEach(section => observer.observe(section));
        }, { once: true });
    </script>

    <!-- Google Fonts Preconnect & Optimized Request -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Google Fonts: Outfit, Inter, El Messiri, Amiri & Poppins (Islamic Calligraphic Style) — loaded non-blocking -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=El+Messiri:wght@700&family=Outfit:wght@500;700&family=Inter:wght@400;500;600;700&family=Instrument+Serif:ital@0;1&family=Poppins:wght@400;500;600;700&display=swap" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=El+Messiri:wght@700&family=Outfit:wght@500;700&family=Inter:wght@400;500;600;700&family=Instrument+Serif:ital@0;1&display=swap"></noscript>
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #fafaf9; /* Stone 50 - Warm premium alabaster */
        }

        /* Render the hero navbar in its correct state before the deferred app bundle runs. */
        .public-nav-hero {
            background-color: transparent;
            border-color: transparent;
            box-shadow: none;
        }
        .public-nav-hero .public-nav-logo { filter: brightness(0) invert(1); }
        .public-nav-hero .public-nav-link { color: rgba(255, 255, 255, .82); }
        .public-nav-hero .public-nav-link:hover { color: #fff; }
        .public-nav-hero .public-nav-link.is-active,
        .public-nav-hero .public-nav-link.is-active:hover { color: #fcde05; text-decoration-color: #fcde05; }
        .public-nav-hero .public-nav-consult { background-color: #fcde05; color: #102b52; }
        .public-nav-hero .public-nav-consult:hover { background-color: #f2cf00; }
        .public-nav-hero.is-scrolled {
            background-color: rgba(255, 255, 255, .96);
            border-color: rgba(255, 255, 255, .75);
            box-shadow: 0 12px 32px rgba(16, 43, 82, .14);
            -webkit-backdrop-filter: blur(16px);
            backdrop-filter: blur(16px);
        }
        .public-nav-hero.is-scrolled .public-nav-logo { filter: none; }
        .public-nav-hero.is-scrolled .public-nav-link { color: #475569; }
        .public-nav-hero.is-scrolled .public-nav-link:hover,
        .public-nav-hero.is-scrolled .public-nav-link.is-active { color: #245392; }
        .public-nav-hero.is-scrolled .public-nav-link.is-active { text-decoration-color: #3166b1; }
        .public-nav-hero.is-scrolled .public-nav-consult { background-color: #3166b1; color: #fff; }
        .public-nav-hero.is-scrolled .public-nav-consult:hover { background-color: #245392; }

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
            color: var(--primary-yellow) !important;
        }

        /* Poppins for hero text */
        .font-poppins {
            font-family: 'Poppins', sans-serif !important;
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
        button, .btn, a.btn, a[class*="bg-blue-600"], a[class*="bg-gradient-"], a[class*="bg-stone-50"], .gallery-tab-btn {
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

        /* Translucent phone frame for the departure schedule. */
        #hero-image-card {
            width: 100%;
            max-width: 410px;
            margin-inline: auto;
            padding: 7px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 3rem;
            background: linear-gradient(145deg, rgba(220, 235, 255, 0.28), rgba(9, 30, 58, 0.58) 22%, rgba(9, 30, 58, 0.68) 82%, rgba(220, 235, 255, 0.2));
            box-shadow: 0 30px 70px rgba(10, 32, 66, 0.35), inset 0 1px 1px rgba(255, 255, 255, 0.35);
        }

        #hero-image-card .hero-phone-screen {
            position: relative;
            overflow: hidden;
            padding: 1.25rem 1.15rem 0.8rem;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: calc(3rem - 8px);
            background: linear-gradient(145deg, rgba(40, 92, 158, 0.48), rgba(16, 43, 82, 0.58));
        }

        #hero-image-card .hero-phone-screen::after {
            content: '';
            position: absolute;
            z-index: 20;
            top: -20%;
            bottom: -20%;
            left: -45%;
            width: 45%;
            background: linear-gradient(105deg, transparent 0%, rgba(255, 255, 255, 0.03) 28%, rgba(255, 255, 255, 0.3) 50%, rgba(255, 242, 190, 0.12) 62%, transparent 100%);
            pointer-events: none;
            animation: heroUiShine 3.6s linear infinite;
            animation-delay: -1.8s;
        }

        #hero-image-card .hero-phone-camera {
            position: relative;
            width: 86px;
            height: 18px;
            margin: 0 auto 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 999px;
            background: rgba(5, 19, 38, 0.85);
        }

        #hero-image-card .hero-phone-camera::after {
            content: '';
            position: absolute;
            top: 6px;
            right: 16px;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: rgba(96, 144, 198, 0.7);
        }

        #hero-image-card .hero-phone-home {
            width: 88px;
            height: 4px;
            margin: 1.1rem auto 0;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.55);
        }

        #hero-countdown > div {
            padding: 0.75rem 0.25rem;
            border-color: rgba(255, 255, 255, 0.15);
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.08);
        }

        @media (max-width: 390px) {
            #hero-image-card .hero-phone-screen {
                padding-inline: 0.85rem;
            }

            #hero-countdown {
                gap: 0.4rem;
            }

            #hero-countdown > div > span:first-child {
                font-size: 1.5rem;
            }

            #hero-image-card .hero-phone-stat {
                gap: 0.5rem;
                padding: 0.75rem;
            }

            #hero-image-card .hero-phone-stat > div {
                min-width: 0;
                gap: 0.5rem;
            }

            #hero-image-card .hero-phone-stat > div > div {
                min-width: 0;
            }

            #hero-image-card .hero-phone-stat-value {
                flex-shrink: 0;
                padding: 0.375rem 0.5rem;
                font-size: 0.875rem;
            }

            #hero-image-card .hero-phone-ticket-name {
                display: -webkit-box;
                overflow: hidden;
                white-space: normal;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 2;
            }
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
            /* flex-start, not the stretch default: all 12 slides sit in one unbroken flex row
               (it's a slider, not a wrapping grid), so stretch was forcing every card to match
               the height of the single tallest slide anywhere in the track (usually a video
               card), leaving short text-only cards with a huge dead gap at the bottom. */
            align-items: flex-start;
            /* No transition here: dragging sets style.transform directly every frame (needs to
               stay instant), and the "commit to nearest slide" snap is driven by Motion.animate()
               in the script below — a competing CSS transition on the same property caused the
               WhatsApp button regression fixed earlier, same risk here. */
            will-change: transform;
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
        .kemitraan-card-blue::before { background: linear-gradient(90deg, var(--primary-blue), var(--primary-yellow)); }
        .kemitraan-card-amber::before { background: linear-gradient(90deg, var(--primary-yellow), #c5ad04); }
        .kemitraan-card-emerald::before { background: linear-gradient(90deg, #10b981, #047857); }


        /* Redesigned Hero Background */
        #beranda {
            background-color: var(--primary-blue) !important;
            background-image: linear-gradient(to right, rgba(49, 102, 177, 0.94) 0%, rgba(49, 102, 177, 0.86) 32%, rgba(49, 102, 177, 0.72) 50%, rgba(252, 222, 5, 0.18) 68%, rgba(252, 222, 5, 0.08) 84%, transparent 100%), url('{{ $heroImageUrl }}') !important;
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
                /* #hero-photo-band (below) renders the real photo in its own shorter band —
                   avoids the crop-zoom a full-section cover forces on a square image.
                   This is just the plain navy fallback/fade for the rest of the section. */
                background-size: cover;
                background-position: center center;
                background-image: linear-gradient(to bottom, rgba(49, 102, 177, 0.78) 0%, rgba(49, 102, 177, 0.96) 78%, var(--primary-blue) 100%) !important;
            }

            /* Declared only inside this mobile media query so desktop browsers never fetch
               this image at all (unlike an <img> tag, a non-matching-media background-image
               is guaranteed to not be downloaded) — keeps desktop payload untouched. */
            #hero-photo-band {
                background-image: url('{{ $heroImageUrl }}');
                background-size: cover;
                background-position: center 22%;
                background-repeat: no-repeat;
            }
        }

        /* Custom style for Haramain Section */
        .digital-mono {
            font-variant-numeric: tabular-nums;
        }
        .border-gold-glow {
            box-shadow: 0 0 20px rgba(200, 158, 43, 0.12);
        }

        /* About section: editorial 2026 treatment built on the existing IZI palette. */
        #tentang-kami.about-modern-section {
            background:
                linear-gradient(180deg, rgba(250, 250, 249, 0.96), rgba(245, 247, 250, 0.82)),
                var(--deferred-bg, none);
            background-size: cover;
            background-position: center;
        }

        #tentang-kami .about-intro-copy {
            position: relative;
            padding: clamp(1rem, 3vw, 2rem) 0;
        }

        #tentang-kami .about-eyebrow {
            position: relative;
            gap: 0.65rem;
            padding: 0;
            border: 0;
            background: transparent;
            box-shadow: none;
            color: var(--primary-blue);
            letter-spacing: 0.18em;
        }

        #tentang-kami .about-eyebrow::before {
            content: '';
            width: 2.25rem;
            height: 2px;
            border-radius: 999px;
            background: var(--primary-yellow);
        }

        #tentang-kami .about-title {
            max-width: 12ch;
            margin: 0;
            font-size: clamp(2.5rem, 4.7vw, 4.5rem);
            letter-spacing: -0.065em;
            line-height: 0.98;
        }

        #tentang-kami .about-description {
            max-width: 54ch;
            color: rgba(41, 50, 65, 0.72);
            font-size: clamp(0.85rem, 1.25vw, 1rem);
            line-height: 1.7;
        }

        #tentang-kami .about-stat-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.85rem;
            max-width: 32rem;
            padding-top: 0.5rem;
        }

        #tentang-kami .about-stat-card {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-height: 5.25rem;
            padding: 0.95rem 1rem;
            border: 1px solid rgba(255, 255, 255, 0.78);
            border-radius: 1.25rem;
            background: rgba(255, 255, 255, 0.72);
            box-shadow: 0 16px 34px rgba(24, 58, 102, 0.08), inset 0 1px 0 rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(14px);
        }

        #tentang-kami .about-stat-icon {
            display: inline-flex;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            width: 2.4rem;
            height: 2.4rem;
            border: 1px solid rgba(49, 102, 177, 0.15);
            border-radius: 0.85rem;
            background: rgba(49, 102, 177, 0.08);
            color: var(--primary-blue);
        }

        #tentang-kami .about-stat-value {
            color: var(--primary-blue);
            font-size: clamp(1.45rem, 2.5vw, 2rem);
            font-weight: 900;
            letter-spacing: -0.06em;
            line-height: 0.95;
        }

        #tentang-kami .about-stat-label {
            margin-top: 0.35rem;
            color: rgba(41, 50, 65, 0.62);
            font-size: 0.62rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            line-height: 1.15;
            text-transform: uppercase;
        }

        #tentang-kami .about-collage {
            position: relative;
            min-height: clamp(27rem, 42vw, 34rem);
            isolation: isolate;
        }

        #tentang-kami .about-collage-card {
            border-radius: 1.75rem;
            box-shadow: 0 28px 60px rgba(16, 48, 91, 0.16);
        }

        /* Size hierarchy: back = hero, base = mid, front = accent (smallest) */
        #tentang-kami .about-collage-base {
            right: 0;
            top: 0;
            width: 48%;
            height: 60%;
            aspect-ratio: auto;
            z-index: 0;
            transform: rotate(4deg);
            box-shadow: 0 18px 40px rgba(16, 48, 91, 0.12);
        }

        #tentang-kami .about-collage-back {
            left: 0;
            top: 0;
            width: 72%;
            height: 88%;
            aspect-ratio: auto;
            z-index: 10;
            transform: rotate(-2.5deg);
        }

        #tentang-kami .about-collage-front {
            right: 0;
            bottom: 0;
            width: 40%;
            height: 42%;
            aspect-ratio: auto;
            z-index: 20;
            transform: rotate(3.5deg);
        }

        #tentang-kami .about-legal-section {
            margin-block: clamp(4rem, 8vw, 7rem);
        }

        #tentang-kami .about-legal-card {
            border-radius: 2rem;
            background: rgba(255, 255, 255, 0.58);
            box-shadow: 0 24px 70px rgba(24, 58, 102, 0.1);
        }

        #tentang-kami .about-legal-copy {
            min-width: 0;
        }

        #tentang-kami .about-legal-kicker {
            color: var(--primary-blue);
            letter-spacing: 0.18em;
        }

        #tentang-kami .about-legal-title {
            max-width: 16ch;
            font-size: clamp(2rem, 3.5vw, 3.2rem);
            letter-spacing: -0.055em;
            line-height: 1;
        }

        #tentang-kami .about-legal-description {
            max-width: 55ch;
            font-size: clamp(0.85rem, 1.2vw, 0.98rem);
            line-height: 1.7;
        }

        #tentang-kami .about-legal-points {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.7rem;
            padding-top: 0.5rem;
            border-top: 1px solid rgba(49, 102, 177, 0.12);
        }

        #tentang-kami .about-legal-point {
            display: flex;
            align-items: flex-start;
            gap: 0.7rem;
            min-height: 5.3rem;
            padding: 0.85rem;
            border: 1px solid rgba(255, 255, 255, 0.85);
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.58);
        }

        #tentang-kami .about-legal-number {
            display: inline-flex;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            border-radius: 0.7rem;
            background: var(--primary-blue);
            color: var(--primary-yellow);
            font-size: 0.65rem;
            font-weight: 900;
            letter-spacing: 0.08em;
        }

        #tentang-kami .about-legal-note {
            padding-left: 0.85rem;
            border-left: 2px solid var(--primary-yellow);
            font-size: 0.72rem;
            line-height: 1.6;
        }

        #tentang-kami .about-license-card {
            min-height: 100%;
            border-radius: 1.5rem;
            background: linear-gradient(145deg, var(--primary-blue), #183e70);
            box-shadow: 0 24px 55px rgba(16, 48, 91, 0.2);
        }

        #tentang-kami .about-license-number {
            font-size: clamp(1.35rem, 2.4vw, 2rem);
            letter-spacing: 0.02em;
        }

        #tentang-kami .about-license-status {
            color: var(--primary-yellow);
        }

        #tentang-kami .about-principles-grid {
            gap: 1rem;
        }

        #tentang-kami .about-principle-card {
            min-height: 18rem;
            border-radius: 1.75rem;
            padding: clamp(1.5rem, 3vw, 2.5rem);
        }

        #tentang-kami .about-vision-card {
            background: linear-gradient(145deg, var(--primary-blue), #183e70);
            box-shadow: 0 24px 55px rgba(16, 48, 91, 0.18);
        }

        #tentang-kami .about-mission-card {
            background: rgba(255, 255, 255, 0.68);
            box-shadow: 0 20px 50px rgba(24, 58, 102, 0.08);
        }

        #tentang-kami .about-principle-title {
            font-size: clamp(1.25rem, 2vw, 1.55rem);
            letter-spacing: -0.04em;
        }

        #tentang-kami .about-principle-copy {
            max-width: 52ch;
            font-size: 0.82rem;
            line-height: 1.75;
        }

        /* Why us: a calmer bento grid with a deeper branded canvas. */
        [data-purpose="features-grid"].features-modern-section {
            background: linear-gradient(135deg, #0e315f 0%, var(--primary-blue) 52%, #1a477d 100%);
        }

        [data-purpose="features-grid"] .features-global-bg {
            opacity: 1;
        }

        [data-purpose="features-grid"] .features-photo-bg {
            opacity: 0.22;
            filter: saturate(0.75) contrast(0.95);
        }

        [data-purpose="features-grid"] .features-fade-bg {
            background: linear-gradient(180deg, rgba(14, 49, 95, 0.72) 0%, rgba(14, 49, 95, 0.9) 65%, rgba(14, 49, 95, 0.98) 100%);
        }

        [data-purpose="features-grid"] .features-pattern {
            opacity: 0.025;
        }

        [data-purpose="features-grid"] .features-header {
            margin-bottom: clamp(2.75rem, 6vw, 4.5rem);
        }

        [data-purpose="features-grid"] .features-eyebrow {
            gap: 0.65rem;
            padding: 0;
            border: 0;
            background: transparent;
            color: var(--primary-yellow);
            letter-spacing: 0.18em;
        }

        [data-purpose="features-grid"] .features-eyebrow::before {
            content: '';
            width: 2.25rem;
            height: 2px;
            border-radius: 999px;
            background: var(--primary-yellow);
        }

        [data-purpose="features-grid"] .features-title {
            max-width: 12ch;
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            letter-spacing: -0.065em;
            line-height: 0.96;
        }

        [data-purpose="features-grid"] .features-subtitle {
            max-width: 38ch;
            color: rgba(255, 255, 255, 0.72);
            font-size: clamp(0.85rem, 1.25vw, 1rem);
            line-height: 1.7;
        }

        [data-purpose="features-grid"] .features-grid-modern {
            gap: 1rem;
            grid-auto-rows: 1fr;
        }

        [data-purpose="features-grid"] .feature-modern-card {
            min-height: 15rem;
            padding: clamp(1.35rem, 2.4vw, 2rem);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 1.5rem;
            background: rgba(19, 68, 128, 0.86) !important;
            background-image: none !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 18px 42px rgba(3, 18, 43, 0.18), inset 0 1px 0 rgba(255, 255, 255, 0.12);
            transition: transform 300ms ease, border-color 300ms ease, background 300ms ease;
        }

        [data-purpose="features-grid"] .feature-modern-card::before {
            content: '';
            position: absolute;
            inset: 0.75rem 1rem auto;
            height: 1px;
            background: linear-gradient(90deg, var(--primary-yellow), transparent 72%);
            opacity: 0.65;
        }

        [data-purpose="features-grid"] .feature-modern-card:hover {
            transform: translateY(-5px);
            border-color: rgba(252, 222, 5, 0.42);
            background: rgba(24, 78, 143, 0.92) !important;
            background-image: none !important;
        }

        [data-purpose="features-grid"] .feature-modern-icon,
        [data-purpose="features-grid"] .features-grid-modern > .feature-modern-card > div:nth-child(2) > div:first-child {
            width: 3rem;
            height: 3rem;
            margin-bottom: 2rem;
            margin-left: 0;
            margin-right: auto;
            align-self: flex-start;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 3rem;
            border: 1px solid rgba(252, 222, 5, 0.28);
            border-radius: 1rem;
            background: rgba(252, 222, 5, 0.12);
            color: var(--primary-yellow);
            transform: none;
        }

        [data-purpose="features-grid"] .features-grid-modern > .feature-modern-card > div:nth-child(2) > div:first-child svg,
        [data-purpose="features-grid"] .features-grid-modern > .feature-modern-card > div:nth-child(2) > div:first-child img {
            display: block;
            flex: 0 0 auto;
            transform: none;
        }

        [data-purpose="features-grid"] .feature-modern-title,
        [data-purpose="features-grid"] .features-grid-modern > .feature-modern-card h3 {
            margin-bottom: 0.65rem;
            color: #fff;
            font-size: clamp(1.05rem, 1.6vw, 1.25rem);
            letter-spacing: -0.025em;
        }

        [data-purpose="features-grid"] .feature-modern-copy,
        [data-purpose="features-grid"] .features-grid-modern > .feature-modern-card p {
            max-width: 34ch;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.78rem;
            line-height: 1.7;
        }

        @media (max-width: 640px) {
            [data-purpose="features-grid"] .features-title {
                max-width: 13ch;
                font-size: clamp(2.35rem, 11vw, 3.25rem);
            }

            [data-purpose="features-grid"] .feature-modern-card {
                min-height: 13.5rem;
            }

            [data-purpose="features-grid"] .feature-modern-icon {
                margin-bottom: 1.5rem;
            }
        }

        /* Packages, partners, and gallery share one polished travel-catalog system. */
        #paket-umrah.packages-modern-section {
            background: linear-gradient(135deg, #0e315f 0%, var(--primary-blue) 54%, #1a477d 100%);
        }

        #paket-umrah .packages-photo-bg {
            opacity: 0.2;
            filter: saturate(0.7) contrast(0.95);
        }

        #paket-umrah .packages-fade-bg {
            background: linear-gradient(180deg, rgba(14, 49, 95, 0.72) 0%, rgba(14, 49, 95, 0.9) 70%, rgba(14, 49, 95, 0.98) 100%);
        }

        [data-purpose="faq-section"] .faq-eyebrow {
            gap: 0.65rem;
            padding: 0;
            border: 0;
            background: transparent;
            letter-spacing: 0.18em;
            color: var(--primary-yellow);
        }

        [data-purpose="faq-section"] .faq-eyebrow::before {
            content: '';
            width: 2.25rem;
            height: 2px;
            border-radius: 999px;
            background: currentColor;
        }

        #paket-umrah .packages-eyebrow,
        #testimoni .testimonials-eyebrow,
        #artikel .articles-eyebrow,
        #kemitraan .partnership-eyebrow {
            gap: 0.65rem;
            padding: 0;
            border: 0;
            background: transparent;
            letter-spacing: 0.18em;
        }

        #paket-umrah .packages-eyebrow {
            color: var(--primary-yellow);
        }

        #testimoni .testimonials-eyebrow,
        #artikel .articles-eyebrow,
        #kemitraan .partnership-eyebrow {
            color: var(--primary-blue);
        }

        #paket-umrah .packages-eyebrow::before,
        #testimoni .testimonials-eyebrow::before,
        #artikel .articles-eyebrow::before,
        #kemitraan .partnership-eyebrow::before {
            content: '';
            width: 2.25rem;
            height: 2px;
            border-radius: 999px;
            background: currentColor;
        }

        #paket-umrah .packages-title,
        #testimoni .testimonials-title,
        #artikel .articles-title,
        #kemitraan .partnership-title,
        [data-purpose="faq-section"] .faq-title {
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            letter-spacing: -0.065em;
            line-height: 0.96;
        }

        #paket-umrah .packages-subtitle,
        #testimoni .testimonials-subtitle,
        #artikel .articles-subtitle,
        #kemitraan .partnership-subtitle {
            max-width: 42ch;
            font-size: clamp(0.85rem, 1.2vw, 1rem);
            line-height: 1.7;
        }

        #paket-umrah .packages-subtitle {
            color: rgba(255, 255, 255, 0.7);
        }

        #paket-umrah .package-filter-shell {
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.07);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(14px);
        }

        #paket-umrah #package-tab-pill {
            background: var(--primary-yellow) !important;
            border-color: rgba(252, 222, 5, 0.35);
            box-shadow: 0 8px 20px rgba(3, 18, 43, 0.18);
        }

        #paket-umrah .package-modern-card {
            border-color: rgba(49, 102, 177, 0.14) !important;
            border-radius: 1.75rem;
            background: #ffffff !important;
            box-shadow: 0 18px 45px rgba(3, 18, 43, 0.14), inset 0 1px 0 rgba(255, 255, 255, 0.9);
            transition: transform 300ms ease, box-shadow 300ms ease, border-color 300ms ease;
        }

        #paket-umrah .package-modern-card:hover {
            transform: translateY(-6px);
            border-color: rgba(252, 222, 5, 0.78) !important;
            box-shadow: 0 28px 60px rgba(3, 18, 43, 0.22);
        }

        #paket-umrah .package-modern-image {
            aspect-ratio: 16 / 10 !important;
            border-radius: 1.75rem 1.75rem 0 0;
            background: #eaf2fb;
        }

        #paket-umrah .package-modern-image::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(3, 18, 43, 0.02) 35%, rgba(3, 18, 43, 0.5) 100%);
            pointer-events: none;
        }

        #paket-umrah .package-modern-image > img {
            display: block;
        }

        #paket-umrah .package-modern-card > div:first-child > .p-4 {
            padding: 1.25rem 1.25rem 1.1rem;
        }

        #paket-umrah .package-modern-card h3 {
            min-height: 2.7rem;
            margin-bottom: 1rem;
            color: #102f59;
            font-size: 1rem;
            letter-spacing: -0.025em;
        }

        #paket-umrah .package-modern-card .grid {
            gap: 0.55rem;
            margin-bottom: 0;
        }

        #paket-umrah .package-modern-card .grid > div {
            min-height: 3.35rem;
            padding: 0.7rem;
            border-color: rgba(49, 102, 177, 0.1) !important;
            border-radius: 0.9rem;
            background: #f7faff !important;
        }

        #paket-umrah .package-modern-card .grid > div > span {
            width: 2rem;
            height: 2rem;
            padding: 0;
            border-color: rgba(49, 102, 177, 0.12) !important;
            border-radius: 0.65rem;
            background: #eaf2fb !important;
            color: var(--primary-blue);
        }

        #paket-umrah .package-modern-card .grid > div p:first-child {
            color: #68809d;
            letter-spacing: 0.12em;
        }

        #paket-umrah .package-modern-card .grid > div p:last-child {
            color: #183a67;
            line-height: 1.25;
        }

        #paket-umrah .package-modern-footer {
            padding: 1.1rem 1.25rem 1.25rem;
            border-top: 1px solid rgba(49, 102, 177, 0.12) !important;
            border-top-style: solid !important;
            background: linear-gradient(180deg, #ffffff, #f8fbff) !important;
            border-radius: 0 0 1.75rem 1.75rem;
        }

        #paket-umrah .package-modern-footer > div:first-of-type,
        #paket-umrah .package-modern-footer > div:nth-of-type(2) {
            display: none;
        }

        #paket-umrah .package-modern-footer p:first-child {
            color: #7187a2;
            letter-spacing: 0.14em;
        }

        #paket-umrah .package-modern-footer p:last-child {
            color: var(--primary-blue);
            font-size: 1.1rem;
            letter-spacing: -0.025em;
        }

        #paket-umrah .package-modern-footer > div:last-child {
            flex: 0 0 auto;
        }

        #paket-umrah .package-modern-footer a {
            border-radius: 0.9rem;
            background: var(--primary-blue) !important;
            box-shadow: 0 10px 20px rgba(49, 102, 177, 0.18);
        }

        #paket-umrah .package-modern-footer a:hover {
            background: #244e87 !important;
        }

        #partners-logo-cloud.partners-modern-section {
            background: rgba(250, 250, 249, 0.9);
        }

        #partners-logo-cloud .partners-title {
            color: var(--primary-blue);
            font-size: clamp(1.45rem, 3vw, 2.2rem);
            letter-spacing: -0.045em;
        }

        #partners-logo-cloud .partners-logo-row {
            padding: 1.25rem 0;
            border-block: 1px solid rgba(49, 102, 177, 0.1);
        }

        #partners-logo-cloud .partners-extra {
            color: var(--primary-blue);
            font-size: 1rem;
            font-style: normal;
            letter-spacing: -0.02em;
        }

        @media (max-width: 640px) {
            #paket-umrah .packages-title,
            #testimoni .testimonials-title,
            #artikel .articles-title,
            #kemitraan .partnership-title,
            [data-purpose="faq-section"] .faq-title {
                max-width: 13ch;
                margin-inline: auto;
                font-size: clamp(2.35rem, 11vw, 3.25rem);
            }

            #paket-umrah .package-filter-shell {
                border-radius: 1.25rem;
            }

        }

        @media (min-width: 641px) and (max-width: 1023px) {
            /* Full-width but short canvas: keep the cards from flattening out */
            #tentang-kami .about-collage-base {
                width: 46%;
                height: 66%;
            }

            #tentang-kami .about-collage-back {
                width: 56%;
                height: 100%;
            }

            #tentang-kami .about-collage-front {
                width: 38%;
                height: 48%;
            }
        }

        @media (max-width: 640px) {
            #tentang-kami .about-title {
                max-width: 13ch;
                font-size: clamp(2.35rem, 11vw, 3.25rem);
            }

            #tentang-kami .about-stat-card {
                gap: 0.55rem;
                padding: 0.8rem;
            }

            #tentang-kami .about-stat-icon {
                width: 2.15rem;
                height: 2.15rem;
            }

            #tentang-kami .about-collage {
                min-height: 24rem;
            }

            #tentang-kami .about-collage-base {
                right: 0;
                top: 0;
                width: 52%;
                height: 60%;
            }

            #tentang-kami .about-collage-back {
                left: 0;
                top: 0;
                width: 70%;
                height: 82%;
            }

            #tentang-kami .about-collage-front {
                right: 0;
                bottom: 0;
                width: 42%;
                height: 44%;
            }

            #tentang-kami .about-legal-points {
                grid-template-columns: 1fr;
            }

            #tentang-kami .about-principle-card {
                min-height: 15rem;
            }
        }

        /* Focused photo-led hero: a calm composition with one clear message. */
        #beranda {
            --hero-accent: var(--primary-yellow);
            min-height: clamp(560px, 78vh, 760px);
            padding-top: 8.5rem;
            padding-bottom: 7rem;
            background-color: var(--primary-blue) !important;
            background-image:
                linear-gradient(90deg, rgba(49, 102, 177, 0.98) 0%, rgba(49, 102, 177, 0.92) 34%, rgba(49, 102, 177, 0.78) 62%, rgba(49, 102, 177, 0.58) 100%),
                linear-gradient(180deg, rgba(49, 102, 177, 0.24) 0%, rgba(49, 102, 177, 0.78) 100%),
                url('{{ $heroImageUrl }}') !important;
            background-position: center center;
            background-size: cover;
        }

        #beranda > .max-w-7xl {
            min-height: clamp(420px, 59vh, 560px);
            align-items: flex-end;
        }

        #beranda .w-full.lg\\:w-7\\/12 {
            align-items: flex-start;
            text-align: left;
            max-width: 680px;
        }

        #beranda .hero-copy-block {
            width: min(100%, 660px);
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        #hero-photo-band {
            display: none;
        }

        #beranda h1 {
            max-width: 11ch;
            margin: 0;
            color: #fff;
            font-size: clamp(3.5rem, 6.5vw, 6rem);
            font-weight: 800;
            letter-spacing: -0.07em;
            line-height: 0.9;
            text-wrap: balance;
        }

        #beranda .hero-title-gloss {
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.25), 0 0 18px rgba(255, 255, 255, 0.15);
        }

        #beranda .hero-title-gloss-gold {
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.38), 0 0 22px rgba(252, 222, 5, 0.28);
        }

        #beranda .hero-text-shine {
            position: relative;
        }

        #beranda .hero-text-shine::after {
            content: attr(data-text);
            position: absolute;
            inset: 0;
            pointer-events: none;
            color: transparent;
            -webkit-text-fill-color: transparent;
            text-shadow: none;
            background: linear-gradient(105deg, transparent 43%, var(--hero-text-shine-color) 50%, transparent 57%);
            background-size: 250% 100%;
            background-position: 100% 0;
            -webkit-background-clip: text;
            background-clip: text;
            animation: heroTextShine 3.6s linear infinite;
            animation-delay: -1.7s;
        }

        #beranda .hero-text-shine-white {
            --hero-text-shine-color: rgba(255, 236, 151, 0.92);
        }

        #beranda .hero-text-shine-gold {
            --hero-text-shine-color: rgba(255, 255, 255, 0.96);
        }

        @keyframes heroTextShine {
            0%, 48% { background-position: 100% 0; }
            72%, 100% { background-position: 0% 0; }
        }

        @keyframes heroUiShine {
            0%, 48% { transform: translate3d(-160%, 0, 0) skewX(-14deg); }
            72%, 100% { transform: translate3d(470%, 0, 0) skewX(-14deg); }
        }

        #beranda .hero-gloss-button,
        #beranda .hero-badge-shine {
            position: relative;
            isolation: isolate;
            overflow: hidden;
        }

        #beranda .hero-gloss-button::after,
        #beranda .hero-badge-shine::after {
            content: '';
            position: absolute;
            top: -30%;
            bottom: -30%;
            left: -40%;
            width: 34%;
            background: linear-gradient(105deg, transparent 0%, rgba(255, 255, 255, 0.06) 28%, rgba(255, 255, 255, 0.72) 50%, rgba(255, 242, 190, 0.25) 62%, transparent 100%);
            pointer-events: none;
            animation: heroUiShine 3.6s cubic-bezier(0.16, 1, 0.3, 1) infinite;
            animation-delay: -1.7s;
        }

        #beranda .hero-badge-shine::after {
            animation-delay: -1.4s;
        }

        #beranda .hero-gloss-button:nth-child(2)::after {
            animation-delay: -1.1s;
        }

        #beranda h1 .hero-accent-dot {
            color: var(--hero-accent);
        }

        #beranda .hero-supporting-copy {
            position: relative;
            max-width: 52ch;
            margin: 0;
            padding-left: 0.85rem;
            color: rgba(255, 255, 255, 0.88);
            font-size: clamp(0.75rem, 1vw, 0.9rem);
            font-weight: 400;
            letter-spacing: -0.01em;
            line-height: 1.5;
        }

        #beranda .hero-supporting-copy::before {
            content: '';
            position: absolute;
            top: 0.2em;
            left: 0;
            width: 3px;
            height: 2.25em;
            border-radius: 999px;
            background: var(--hero-accent);
            box-shadow: 0 0 18px rgba(252, 222, 5, 0.3);
        }

        #beranda .hero-kaaba-foreground {
            position: absolute;
            z-index: 1;
            left: 50%;
            bottom: 4rem;
            width: clamp(1400px, 112vw, 1603px);
            max-width: none;
            aspect-ratio: 1603 / 981;
            transform: translateX(-50%);
            opacity: 0.68;
            pointer-events: none;
            -webkit-mask-image: linear-gradient(to bottom, #000 54%, rgba(0, 0, 0, 0.8) 76%, transparent 100%);
            mask-image: linear-gradient(to bottom, #000 54%, rgba(0, 0, 0, 0.8) 76%, transparent 100%);
        }

        #beranda .hero-mobile-image {
            display: none;
        }

        #beranda .hero-mobile-cover {
            display: contents;
        }

        #hero-left-col .hero-features { order: 1; }
        #hero-left-col .hero-actions { order: 2; }
        #hero-left-col .hero-stats { order: 3; }

        @media (max-width: 1023px) {
            #beranda {
                min-height: 640px;
                padding-top: 8rem;
                background-image:
                    linear-gradient(180deg, rgba(49, 102, 177, 0.72) 0%, rgba(49, 102, 177, 0.94) 68%, rgba(49, 102, 177, 1) 100%),
                    url('{{ $heroImageUrl }}') !important;
                background-position: 58% center;
            }

            #beranda .hero-badge-shine {
                margin-left: 0;
                margin-right: 0;
            }

            #beranda > .max-w-7xl {
                min-height: 470px;
                align-items: flex-end;
            }

            #beranda h1 {
                margin-bottom: 0;
                font-size: clamp(3.25rem, 14vw, 5.3rem);
            }

            #beranda .hero-supporting-copy {
                max-width: 48ch;
                font-size: clamp(0.75rem, 3.2vw, 0.85rem);
            }

            #beranda .hero-kaaba-foreground {
                bottom: 0;
                width: max(950px, 180vw);
                opacity: 0.32;
            }
        }

        @media (max-width: 767px) {
            #beranda {
                padding-top: 0;
                padding-bottom: 5rem;
                background-color: #3166b1 !important;
                background-image: none !important;
            }

            #hero-photo-band {
                display: none;
            }

            #beranda .hero-kaaba-foreground {
                display: none;
            }

            #beranda > .max-w-7xl {
                align-items: stretch;
                gap: 0;
            }

            #hero-left-col {
                display: contents;
            }

            #beranda .hero-mobile-cover {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                width: calc(100% + 2rem);
                margin: 0 -1rem;
                padding: 9rem 1rem 0;
                background-image:
                    linear-gradient(105deg, rgba(38, 83, 155, 0.94) 0%, rgba(49, 102, 177, 0.88) 48%, rgba(49, 102, 177, 0.76) 100%),
                    url('{{ $heroMobileImageUrl }}');
                background-position: center, 42% 30%;
                background-size: cover;
            }

            #beranda .hero-badge-shine {
                display: none;
            }

            #beranda h1 {
                max-width: 100%;
                font-size: clamp(2rem, 8.9vw, 2.5rem);
                letter-spacing: -0.04em;
                line-height: 1.12;
                word-spacing: 0.06em;
                text-align: left;
            }

            #beranda h1 .hero-text-shine {
                display: inline-block;
                vertical-align: baseline;
            }

            #beranda .hero-copy-block {
                order: 1;
                align-items: flex-start;
                text-align: left;
                gap: 1rem;
                margin-bottom: 1.5rem;
            }

            #beranda .hero-supporting-copy {
                max-width: 54ch;
                padding-left: 0;
                font-size: clamp(0.84rem, 3.4vw, 0.96rem);
                line-height: 1.55;
                text-align: left;
            }

            #beranda .hero-supporting-copy::before {
                display: none;
            }

            #beranda .hero-actions {
                order: 2;
                position: relative;
                z-index: 2;
                width: auto;
                margin: 0;
            }

            #beranda .hero-actions > a:first-child {
                display: none;
            }

            #beranda .hero-actions > a:last-child {
                width: auto;
                min-width: 12rem;
                padding: 0.8rem 1.5rem;
                border-color: var(--primary-yellow);
                border-radius: 0.65rem;
                background: var(--primary-yellow);
                color: var(--primary-blue);
                box-shadow: 0 8px 24px rgba(252, 222, 5, 0.18);
            }

            #beranda .hero-actions > a:last-child:hover {
                background: #f2d500;
            }

            #beranda .hero-mobile-image { order: 3; }

            #hero-right-col {
                order: 6;
                margin-top: 1.25rem;
            }

            #beranda .hero-features { order: 4; }
            #beranda .hero-stats { order: 5; }

            #beranda .hero-features {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.65rem;
                overflow: visible;
                margin-top: 1.25rem;
                margin-bottom: 0;
            }

            #beranda .hero-features > div {
                min-width: 0;
                gap: 0.5rem;
                padding: 0.65rem;
            }

            #beranda .hero-features p {
                white-space: normal;
            }

            #beranda .hero-mobile-image {
                display: block;
                position: relative;
                width: calc(100% + 2rem);
                height: clamp(235px, 62vw, 350px);
                margin: -2.5rem -1rem 0;
                overflow: hidden;
                pointer-events: none;
                background-size: max(500px, 100vw) auto;
            }
        }

        @media (max-width: 359px) {
            #beranda .hero-features > div {
                gap: 0.375rem;
                padding: 0.5rem;
            }

            #beranda .hero-features > div > div:first-child {
                width: 1.75rem;
                height: 1.75rem;
            }

            #beranda .hero-features p:first-child {
                font-size: 0.625rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            #beranda .animate-fade-in-up,
            #beranda .reveal-words {
                animation: none;
                opacity: 1;
                transform: none;
            }

            #beranda .hero-gloss-button::after,
            #beranda .hero-badge-shine::after,
            #beranda .hero-text-shine::after,
            #hero-image-card .hero-phone-screen::after {
                display: none;
            }
        }
    </style>
</head>

<body class="bg-stone-50 text-stone-800 overflow-x-hidden antialiased izi-public">
    <!-- Scroll Progress Bar (mengisi saat scroll ke bawah) -->
    <div id="scroll-progress-track" class="fixed top-0 left-0 w-full h-1 z-[60] pointer-events-none bg-transparent">
        <div id="scroll-progress-bar" class="h-full w-full origin-left bg-gradient-to-r from-[var(--primary-blue)] via-[var(--primary-yellow)] to-[var(--primary-blue)] shadow-[0_1px_6px_rgba(49,102,177,0.35)] transition-transform duration-100 ease-out" style="transform: scaleX(0);"></div>
    </div>

    <!-- BEGIN: Floating Modern Header (Fully Rounded Capsule) -->
    @include('public.partials.navigation')

    <main id="main-content">
    <!-- BEGIN: HeroSection (Modern Split Layout) -->
    <section id="beranda" class="relative pt-32 pb-20 overflow-hidden" data-purpose="hero-banner">
        <!-- Tablet photo band keeps the background visible above the schedule card. -->
        <div id="hero-photo-band" class="lg:hidden absolute top-0 left-0 w-full h-[46vh] max-h-[420px] overflow-hidden pointer-events-none animate-fade-in-up">
            <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(49, 102, 177, 0.90) 0%, rgba(49, 102, 177, 0.96) 68%, #3166b1 100%);"></div>
        </div>

        <div class="hero-kaaba-foreground hero-kaaba-art" aria-hidden="true"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10 lg:pb-16 flex flex-col lg:flex-row items-center gap-8 lg:gap-12 relative z-10">
            <!-- Left Column: Content -->
            <div id="hero-left-col" class="w-full lg:w-7/12 flex flex-col justify-center items-start text-left">
                <div class="hero-mobile-cover {{ empty($settings['hero_image']) ? 'hero-mobile-default-bg' : '' }}">
                <!-- Badge Kemenag (Navy blue glass) -->
                @php $heroBadgeText = $settings['hero_badge'] ?? ('Berizin Resmi Kemenag RI • PPIU ' . ($settings['footer_ppiu_number'] ?? '91202054619660001')); @endphp
                @if (!empty($heroBadgeText))
                    <div class="hero-badge-shine inline-flex items-center gap-2 bg-white/[0.06] border border-[var(--primary-yellow)]/25 text-white/90 text-xs px-4 py-2 rounded-full w-fit mx-auto lg:mx-0 mb-6 animate-fade-in-up delay-100">
                        <i data-lucide="shield-check" class="w-4 h-4 text-[var(--primary-yellow)]"></i>
                        <span class="font-semibold tracking-wide font-poppins">{{ $heroBadgeText }}</span>
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

                <div class="hero-copy-block">
                <h1 class="font-poppins text-white reveal-words" aria-label="{{ $hero_tagline }}">
                    <span class="block text-white hero-title-gloss hero-text-shine hero-text-shine-white" data-text="{{ $line1 }}">{{ $line1 }}</span>
                    @if(!empty($line2))
                        <span class="block text-[var(--primary-yellow)] hero-title-gloss-gold hero-text-shine hero-text-shine-gold" data-text="{{ $line2 }}">{{ $line2 }}</span>
                    @endif
                    @if(!empty($line3))
                        <span class="block hero-text-shine {{ ($line3Gold ?? true) ? 'text-[var(--primary-yellow)] hero-title-gloss-gold hero-text-shine-gold' : 'text-white hero-title-gloss hero-text-shine-white' }}" data-text="{{ $line3 }}">{{ $line3 }}</span>
                    @endif
                </h1>

                <p class="hero-supporting-copy animate-fade-in-up delay-300 font-poppins">
                    {{ $site_desc }}
                </p>
                </div>

                <!-- Actions CTA Buttons -->
                <div class="hero-actions flex flex-col sm:flex-row justify-center lg:justify-start gap-4 animate-fade-in-up delay-400 w-full sm:w-auto">
                    <a href="#paket-umrah" class="hero-gloss-button w-full sm:w-auto bg-[var(--primary-yellow)] hover:brightness-95 text-[var(--primary-blue)] px-8 py-3.5 rounded-full font-bold transition shadow-md shadow-[rgba(252,222,5,0.22)] text-sm text-center justify-center flex items-center gap-2 font-poppins">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                        {{ $settings['cta_packages_label'] ?? 'Lihat Paket Umrah' }}
                    </a>
                    <a href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel%2C%20saya%20ingin%20konsultasi%20mengenai%20paket%20umrah" target="_blank" class="hero-gloss-button w-full sm:w-auto bg-white/5 hover:bg-white/10 text-white border border-white/20 px-8 py-3.5 rounded-full font-bold transition text-sm text-center justify-center flex items-center gap-2 font-poppins">
                        <i data-lucide="message-square" class="w-4 h-4"></i>
                        {{ $settings['cta_consultation_label'] ?? 'Konsultasi Gratis' }}
                    </a>
                </div>

                <div class="hero-mobile-image hero-kaaba-art" aria-hidden="true"></div>
                </div>

                <!-- Hero features: all four cards stay visible in a mobile grid -->
                <div class="hero-features w-full flex sm:grid sm:grid-cols-4 gap-3 sm:gap-x-5 sm:gap-y-4 mb-6 lg:mb-8 overflow-x-auto sm:overflow-visible scrollbar-none snap-x snap-mandatory animate-fade-in-up delay-350">
                    @php
                        $heroFeatures = [
                            ['icon' => 'star', 'title' => $settings['hero_feature_1_title'] ?? 'Pelayanan', 'sub' => $settings['hero_feature_1_sub'] ?? 'Nyaman & Aman'],
                            ['icon' => 'building-2', 'title' => $settings['hero_feature_2_title'] ?? 'Hotel Pilihan', 'sub' => $settings['hero_feature_2_sub'] ?? 'Makkah & Madinah'],
                            ['icon' => 'plane', 'title' => $settings['hero_feature_3_title'] ?? 'Tiket PP & Visa', 'sub' => $settings['hero_feature_3_sub'] ?? 'Resmi'],
                            ['icon' => 'users', 'title' => $settings['hero_feature_4_title'] ?? 'Pendampingan', 'sub' => $settings['hero_feature_4_sub'] ?? 'Mutawwif'],
                        ];
                    @endphp
                    @foreach ($heroFeatures as $feat)
                        <div class="flex items-center gap-2.5 shrink-0 snap-start bg-white/5 border border-white/10 rounded-2xl px-3.5 py-2.5 sm:bg-transparent sm:border-0 sm:rounded-none sm:px-0 sm:py-0 sm:shrink">
                            <div class="shrink-0 w-8 h-8 rounded-lg bg-white/10 border border-white/15 flex items-center justify-center">
                                <i data-lucide="{{ $feat['icon'] }}" class="w-4 h-4 text-[var(--primary-yellow)]"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-white leading-tight whitespace-nowrap sm:whitespace-normal sm:truncate font-poppins">{{ $feat['title'] }}</p>
                                <p class="text-[10px] text-white/80 font-medium leading-tight whitespace-nowrap sm:whitespace-normal font-poppins">{{ $feat['sub'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Bottom Stats Bar -->
                @php
                    $heroStats = [
                        ['icon' => 'shield-check', 'value' => $settings['hero_stat_berizin'] ?? 'BERIZIN', 'label' => ' RESMI', 'sub' => $settings['hero_stat_berizin_sub'] ?? 'Kemenag RI'],
                        ['icon' => 'users', 'value' => $settings['about_departed_count'] ?? '500', 'label' => '+ JAMAAH', 'sub' => $settings['hero_stat_jamaah_sub'] ?? 'Telah Berangkat'],
                        ['icon' => 'star', 'value' => ($settings['about_satisfaction_rate'] ?? '99') . '%', 'label' => ' KEPUASAN', 'sub' => $settings['hero_stat_rating_sub'] ?? 'Dari Jamaah'],
                        ['icon' => 'heart', 'value' => '100%', 'label' => ' AMANAH', 'sub' => $settings['hero_stat_amanah_sub'] ?? '& Terpercaya'],
                    ];
                @endphp
                <div class="hero-stats bg-white/5 border border-white/10 rounded-2xl px-5 py-4 mt-6 lg:mt-10 grid grid-cols-2 sm:grid-cols-4 gap-x-4 gap-y-4 animate-fade-in-up delay-500">
                    @foreach ($heroStats as $stat)
                        <div class="flex items-center gap-3">
                            <div class="shrink-0 w-9 h-9 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center">
                                <i data-lucide="{{ $stat['icon'] }}" class="w-4.5 h-4.5 text-[var(--primary-yellow)]" style="width:18px;height:18px"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-black text-white tracking-wider leading-none font-poppins">
                                    <span class="text-sm">{{ $stat['value'] }}</span>{{ $stat['label'] }}
                                </p>
                                <p class="text-[10px] text-white/75 font-medium mt-0.5 leading-none font-poppins">{{ $stat['sub'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Column: Departure schedule card -->
            <div id="hero-right-col" class="w-full lg:w-5/12 lg:self-start {{ !empty($heroBadgeText) ? 'lg:mt-[3.6rem]' : 'lg:mt-0' }} flex flex-col gap-6 relative z-30 animate-fade-in-up delay-300">
                <div id="hero-image-card" class="relative text-white font-poppins">
                  <div class="hero-phone-screen">
                    <!-- Localized Islamic pattern overlay -->
                    <div class="absolute inset-0 islamic-pattern opacity-[0.015] pointer-events-none"></div>

                    <div class="hero-phone-camera" aria-hidden="true"></div>
                    <div class="relative z-10 flex items-center justify-between gap-3 mb-5">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-9 h-9 rounded-xl bg-[var(--primary-yellow)] text-[var(--primary-blue)] flex items-center justify-center shrink-0">
                                <i data-lucide="plane" class="w-4 h-4"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="text-[11px] font-bold leading-tight">IZI Travel</p>
                                <p class="text-[9px] text-white/70 leading-tight">Jadwal Umrah</p>
                            </div>
                        </div>
                        <span class="text-[9px] font-semibold text-white/85 border border-white/20 rounded-full px-2.5 py-1 whitespace-nowrap">PPIU Resmi</span>
                    </div>

                    @if ($nearestPackage)
                        <!-- Title Section -->
                        <div class="flex items-center justify-between mb-6 border-b border-white/10 pb-4 relative z-10">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-xl bg-[rgba(252,222,5,0.12)] text-[var(--primary-yellow)] flex items-center justify-center border border-[var(--primary-yellow)]/25">
                                    <i data-lucide="plane-takeoff" class="w-5 h-5"></i>
                                </span>
                                <div class="text-left">
                                    <span class="inline-flex items-center gap-1 bg-[rgba(252,222,5,0.12)] border border-[var(--primary-yellow)]/30 text-[var(--primary-yellow)] text-[9px] font-black uppercase tracking-widest px-2.5 py-0.5 rounded-full mb-1">
                                        Keberangkatan Terdekat
                                    </span>
                                    <h2 class="text-xs text-white/95 font-bold truncate max-w-[180px] sm:max-w-[210px] mt-0.5">{{ $nearestPackage->name }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Countdown Timer (Split-flap Style) -->
                        <div id="hero-countdown" class="grid grid-cols-4 gap-3 mb-6 relative z-10" data-date="{{ \Carbon\Carbon::parse($nearestPackage->departure_date)->format('Y-m-d') }}">
                            <!-- Days -->
                            <div class="bg-blue-700 border border-white/10 rounded-2xl p-3 text-center relative overflow-hidden select-none">
                                <span class="block text-3xl font-black text-[var(--primary-yellow)] tracking-tight font-mono leading-none relative z-0" id="countdown-days">--</span>
                                <span class="text-[9px] uppercase tracking-widest text-stone-300 font-bold mt-1.5 block relative z-10">Hari</span>
                            </div>
                            <!-- Hours -->
                            <div class="bg-blue-700 border border-white/10 rounded-2xl p-3 text-center relative overflow-hidden select-none">
                                <span class="block text-3xl font-black text-[var(--primary-yellow)] tracking-tight font-mono leading-none relative z-0" id="countdown-hours">--</span>
                                <span class="text-[9px] uppercase tracking-widest text-stone-300 font-bold mt-1.5 block relative z-10">Jam</span>
                            </div>
                            <!-- Minutes -->
                            <div class="bg-blue-700 border border-white/10 rounded-2xl p-3 text-center relative overflow-hidden select-none">
                                <span class="block text-3xl font-black text-[var(--primary-yellow)] tracking-tight font-mono leading-none relative z-0" id="countdown-mins">--</span>
                                <span class="text-[9px] uppercase tracking-widest text-stone-300 font-bold mt-1.5 block relative z-10">Menit</span>
                            </div>
                            <!-- Seconds -->
                            <div class="bg-blue-700 border border-white/10 rounded-2xl p-3 text-center relative overflow-hidden select-none">
                                <span class="block text-3xl font-black text-[var(--primary-yellow)] tracking-tight font-mono leading-none relative z-0" id="countdown-secs">--</span>
                                <span class="text-[9px] uppercase tracking-widest text-stone-300 font-bold mt-1.5 block relative z-10">Detik</span>
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
                        <div class="hero-phone-stat border border-[var(--primary-yellow)]/25 rounded-2xl p-4 flex items-center justify-between gap-3 relative overflow-hidden" style="background: linear-gradient(90deg, rgba(252, 222, 5, 0.12), rgba(49, 102, 177, 0.26));">
                            <div class="flex items-center gap-3">
                                <span class="p-2.5 rounded-xl bg-[rgba(252,222,5,0.12)] text-[var(--primary-yellow)] border border-[var(--primary-yellow)]/25 flex items-center justify-center">
                                    <i data-lucide="users" class="w-5 h-5"></i>
                                </span>
                                <div class="text-left">
                                    <p class="text-xs text-[var(--primary-yellow)] font-extrabold uppercase tracking-wider leading-none">{{ $settings['hero_stat_title'] ?? 'Total Keberangkatan' }}</p>
                                    <p class="text-[10px] text-white/80 mt-1 font-medium">{{ $settings['hero_stat_subtitle'] ?? 'Jamaah terberangkatkan' }}</p>
                                </div>
                            </div>
                            <span class="hero-phone-stat-value text-lg font-black text-[var(--primary-yellow)] border border-[var(--primary-yellow)]/25 px-3.5 py-1.5 rounded-xl tracking-wider font-mono">
                                {{ $settings['hero_stat_value'] ?? (($settings['about_departed_count'] ?? '10') . 'K+') }}
                            </span>
                        </div>

                        <!-- Upcoming Schedule List (Boarding Ticket style) -->
                        @if (isset($upcomingPackages) && $upcomingPackages->count() > 0)
                            <div>
                                <div class="flex items-center justify-between mb-4 border-b border-white/10 pb-2">
                                    <h4 class="text-xs font-black uppercase tracking-widest text-[var(--primary-yellow)] flex items-center gap-2">
                                        <i data-lucide="calendar-days" class="w-4 h-4 text-[var(--primary-yellow)]"></i>
                                        Jadwal Berikutnya
                                    </h4>
                                </div>
                                <div class="space-y-3">
                                    @foreach ($upcomingPackages as $p)
                                        @php
                                            $deptDate = \Carbon\Carbon::parse($p->departure_date);
                                            $dayStr = $deptDate->format('d');
                                            $monthStr = $deptDate->translatedFormat('M');
                                            $yearStr = $deptDate->format('Y');
                                        @endphp
                                        <div class="bg-white/[0.03] hover:bg-[rgba(252,222,5,0.05)] border border-white/5 hover:border-[var(--primary-yellow)]/30 rounded-2xl p-3 flex items-center justify-between transition-colors duration-300 group/ticket">
                                            <!-- Date Page Block -->
                                            <div class="border border-white/10 rounded-xl px-2.5 py-1.5 text-center flex flex-col justify-center items-center min-w-[52px]" style="background: rgba(49, 102, 177, 0.75);">
                                                <span class="text-sm font-black text-[var(--primary-yellow)] leading-none">{{ $dayStr }}</span>
                                                <span class="text-[8px] font-bold text-white/70 uppercase tracking-widest mt-0.5 leading-none">{{ $monthStr }}</span>
                                            </div>

                                            <!-- Ticket Content -->
                                            <div class="min-w-0 flex-1 pl-3.5 pr-2 text-left">
                                                <p class="hero-phone-ticket-name text-xs font-bold text-white truncate">{{ $p->name }}</p>
                                                <p class="text-[10px] text-white/50 flex items-center gap-1 mt-1 font-medium">
                                                    <i data-lucide="tag" class="w-3.5 h-3.5 text-[var(--primary-yellow)]"></i>
                                                    {{ $p->category ?? 'Premium' }} • {{ $yearStr }}
                                                </p>
                                            </div>

                                            <!-- Detail Action Button -->
                                            <a href="{{ route('packages.show', $p->slug) }}" class="flex-shrink-0 bg-white/5 hover:bg-[var(--primary-yellow)] text-white hover:text-[var(--primary-blue)] border border-white/10 hover:border-transparent text-[10px] font-extrabold px-3.5 py-2 rounded-xl transition-colors duration-300 shadow-sm flex items-center gap-1">
                                                <span>Detail</span>
                                                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="hero-phone-home" aria-hidden="true"></div>
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

    <!-- Promo carousel (auto-slide) — below hero, above Tentang Kami -->
    @include('partials.promo-carousel')

    <!-- BEGIN: Tentang Kami -->
    <section class="about-modern-section py-16 md:py-24 bg-gradient-to-b from-stone-50 via-stone-100/30 to-stone-50 relative overflow-hidden islamic-pattern" id="tentang-kami" data-purpose="about-us" data-lazy-bg="{{ asset('images/section_madinah_nabawi.webp') }}">
        <!-- Blurred Nabawi Mosque Background (tinted so frosted glass panels have depth) -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-[25%_center] scale-105" style="background-image: linear-gradient(to right, rgba(250, 250, 249, 0.74) 0%, rgba(250, 250, 249, 0.8) 45%, rgba(250, 250, 249, 0.86) 60%), var(--deferred-bg, none);"></div>
            <!-- Soft vertical gradient overlay to fade the top and bottom edges seamlessly -->
            <div class="absolute inset-0 bg-gradient-to-b from-stone-50 via-transparent to-stone-50"></div>
            <!-- Color orbs that give the frosted glass something to gleam -->
            <div class="absolute -left-24 top-24 w-80 h-80 rounded-full bg-blue-400/30 blur-3xl"></div>
            <div class="absolute -right-20 bottom-40 w-96 h-96 rounded-full bg-emerald-300/25 blur-3xl"></div>
            <div class="absolute left-1/3 top-0 w-72 h-72 rounded-full bg-amber-200/30 blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Top Layout: Description, Stats, and 3D Photo Stack Collage -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center mb-10">
                <!-- Left: Short description & stats -->
                <div class="about-intro-copy lg:col-span-6 space-y-6 reveal flex flex-col items-center lg:items-start text-center lg:text-left">
                    <span class="about-eyebrow inline-flex items-center px-4 py-1.5 rounded-full bg-white/60 dark:bg-white/10 backdrop-blur-md border border-white/60 dark:border-white/20 text-blue-700 dark:text-blue-300 text-xs font-bold tracking-[0.14em] uppercase shadow-sm shadow-stone-900/5">
                        {{ $settings['about_badge'] ?? 'Tentang Kami' }}
                    </span>
                    <h2 class="about-title text-3xl md:text-4xl lg:text-5xl font-black text-stone-900 tracking-tight leading-tight reveal-words text-center lg:text-left">
                        {{ $settings['about_title'] ?? 'Melayani Perjalanan Suci Anda dengan Sepenuh Hati' }}
                    </h2>
                    <p class="about-description text-stone-600 text-xs sm:text-sm leading-[1.75] font-normal text-center lg:text-left max-w-xl">
                        {{ $settings['about_description'] ?? 'Sebuah perusahaan yang bergerak di bidang biro perjalanan umroh dengan pelayanan dan kualitas nyaman yang menyasar para generasi muda juga kalangan menengah kebawah. Paket Umroh izitravel: Paket Hemat: menawarkan pengalaman ibadah umroh yang lebih nyaman dan terstruktur. Paket Hemat Banget: berfokus pada efisiensi biaya dengan menyediakan fasilitas standar yang esensial bagi jamaah yang ingin umroh dengan anggaran seminimal mungkin.' }}
                    </p>
                    <div class="about-stat-grid flex flex-wrap justify-center lg:justify-start gap-3 pt-4 w-full">
                        <div class="about-stat-card inline-flex items-center gap-2.5 bg-white/60 dark:bg-white/10 pl-3 pr-4 py-2.5 rounded-full backdrop-blur-xl border border-white/60 dark:border-white/20 shadow-lg shadow-stone-900/5">
                            <span class="about-stat-icon shrink-0 w-8 h-8 rounded-full bg-blue-600/10 dark:bg-blue-400/20 border border-white/40 text-blue-600 dark:text-blue-300 flex items-center justify-center"><i data-lucide="smile" class="w-4 h-4"></i></span>
                            <div>
                                <p class="about-stat-value text-lg font-black text-blue-600 dark:text-blue-300 leading-none"><span class="stat-counter" data-target="{{ $settings['about_satisfaction_rate'] ?? '99' }}">0</span>%</p>
                                <p class="about-stat-label text-[10px] text-stone-500 dark:text-stone-300 uppercase font-extrabold tracking-wider leading-tight">{{ $settings['about_stat_1_label'] ?? 'Kepuasan Jamaah' }}</p>
                            </div>
                        </div>
                        <div class="about-stat-card inline-flex items-center gap-2.5 bg-white/60 dark:bg-white/10 pl-3 pr-4 py-2.5 rounded-full backdrop-blur-xl border border-white/60 dark:border-white/20 shadow-lg shadow-stone-900/5">
                            <span class="about-stat-icon shrink-0 w-8 h-8 rounded-full bg-blue-600/10 dark:bg-blue-400/20 border border-white/40 text-blue-600 dark:text-blue-300 flex items-center justify-center"><i data-lucide="users" class="w-4 h-4"></i></span>
                            <div>
                                <p class="about-stat-value text-lg font-black text-blue-600 dark:text-blue-300 leading-none"><span class="stat-counter" data-target="{{ $settings['about_departed_count'] ?? '10' }}" data-suffix="k">0</span>+</p>
                                <p class="about-stat-label text-[10px] text-stone-500 dark:text-stone-300 uppercase font-extrabold tracking-wider leading-tight">{{ $settings['about_stat_2_label'] ?? 'Jamaah Berangkat' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Overlapping 3D Image Collage -->
                <div class="about-collage lg:col-span-6 relative w-full h-[360px] sm:h-[440px] flex items-center justify-center reveal-right">
<!-- Base Card (rearmost photo in the stack) -->
                    <div class="about-collage-card about-collage-base absolute right-6 top-6 w-[60%] aspect-[4/3] rounded-2xl overflow-hidden shadow-xl shadow-stone-900/[0.07] border-4 border-white transform rotate-1 hover:rotate-0 transition duration-500 z-0 select-none">
                        <img src="{{ !empty($settings['about_image_3']) ? (str_starts_with($settings['about_image_3'], 'images/') ? asset($settings['about_image_3']) : asset('storage/' . $settings['about_image_3'])) : asset('images/gallery_makkah1.webp') }}" alt="Suasana Perjalanan Ibadah" class="w-full h-full object-cover" width="600" height="450" loading="lazy" decoding="async">
                    </div>

<!-- Base Backdrop Card (Departure Image) -->
                    <div class="about-collage-card about-collage-back absolute left-6 top-6 w-[80%] aspect-[4/3] rounded-2xl overflow-hidden shadow-xl shadow-stone-900/[0.07] border-4 border-white transform -rotate-1 hover:rotate-0 transition duration-500 z-10 select-none">
                        <img src="{{ !empty($settings['about_image_1']) ? (str_starts_with($settings['about_image_1'], 'images/') ? asset($settings['about_image_1']) : asset('storage/' . $settings['about_image_1'])) : asset('images/gallery_departure.webp') }}" alt="Keberangkatan Jemaah" class="w-full h-full object-cover" width="600" height="450" loading="lazy" decoding="async">
                    </div>

                    <!-- Overlay Foreground Card (Manasik Preparation Image) -->
                    <div class="about-collage-card about-collage-front absolute right-6 bottom-6 w-[60%] aspect-[4/3] rounded-2xl overflow-hidden shadow-xl shadow-stone-900/10 border-4 border-white transform rotate-1 hover:rotate-0 transition duration-500 z-20 select-none">
                        <img src="{{ !empty($settings['about_image_2']) ? (str_starts_with($settings['about_image_2'], 'images/') ? asset($settings['about_image_2']) : asset('storage/' . $settings['about_image_2'])) : asset('images/gallery_manasik.webp') }}" alt="Bimbingan Manasik" class="w-full h-full object-cover" width="600" height="450" loading="lazy" decoding="async">
                    </div>
                </div>
            </div>

            <!-- Izin Resmi PPIU -->
            <div class="about-legal-section my-16 md:my-24 reveal-up">
                <div class="about-legal-card relative overflow-hidden bg-white/55 dark:bg-slate-900/40 backdrop-blur-2xl rounded-3xl border border-white/60 dark:border-white/10 ring-1 ring-white/40 shadow-xl shadow-stone-900/5">
                    <div class="pointer-events-none absolute inset-x-8 top-0 h-px bg-gradient-to-r from-transparent via-white/40 to-transparent"></div>
                    <div class="grid lg:grid-cols-12 gap-10 lg:gap-14 items-center p-7 sm:p-10 lg:p-12">

                        <!-- Konten utama -->
                        <div class="about-legal-copy lg:col-span-7 space-y-5">
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-3">
                                @if(!empty($settings['about_ppiu_logo']))
                                    <div class="inline-flex items-center justify-center h-11 px-3 rounded-lg bg-white/70 dark:bg-white/10 backdrop-blur-md border border-white/60 dark:border-white/20">
                                        <img src="{{ str_starts_with($settings['about_ppiu_logo'], 'images/') ? asset($settings['about_ppiu_logo']) : asset('storage/' . $settings['about_ppiu_logo']) }}" alt="Logo Kemenag" class="h-8 w-auto object-contain">
                                    </div>
                                @endif
                                <span class="about-legal-kicker inline-flex items-center gap-2 text-[11px] font-bold uppercase tracking-[0.18em] text-blue-700 dark:text-blue-400">
                                    <i data-lucide="check-circle-2" class="w-4 h-4 text-blue-600 shrink-0"></i>
                                    Legalitas Perusahaan
                                </span>
                            </div>

                            @php
                                $ppiuLabelText = $settings['about_ppiu_label'] ?? 'Izin Penyelenggara Resmi Perjalanan Ibadah Umrah (PPIU)';
                            @endphp

                            <h3 class="about-legal-title text-2xl md:text-3xl font-extrabold text-stone-900 dark:text-white tracking-tight leading-tight">
                                {{ $ppiuLabelText }}
                            </h3>
                            <p class="about-legal-description text-sm md:text-[15px] leading-relaxed text-stone-500 dark:text-stone-400 max-w-2xl">
                                {{ $settings['about_ppiu_desc'] ?? 'IZI Travel berkomitmen penuh dalam menyelenggarakan ibadah Umrah dan Haji sesuai syariat Islam, dengan kepastian program keberangkatan dan bimbingan ibadah yang sah & diakui secara hukum.' }}
                            </p>

                            <div class="about-legal-points grid grid-cols-1 sm:grid-cols-3 gap-3 mt-7 pt-6 border-t border-white/40 dark:border-white/10">
                                <div class="about-legal-point flex items-center gap-3 bg-white/60 dark:bg-white/5 backdrop-blur-md rounded-xl border border-white/60 dark:border-white/10 px-3 py-2.5">
                                    <span class="about-legal-number shrink-0 flex items-center justify-center w-8 h-8 rounded-lg bg-white/80 dark:bg-white/10 border border-white/60 dark:border-white/20 text-[11px] font-bold text-stone-500 dark:text-stone-300">01</span>
                                    <p class="text-xs font-semibold text-stone-700 dark:text-stone-300 leading-snug">Izin PPIU Terdaftar</p>
                                </div>
                                <div class="about-legal-point flex items-center gap-3 bg-white/60 dark:bg-white/5 backdrop-blur-md rounded-xl border border-white/60 dark:border-white/10 px-3 py-2.5">
                                    <span class="about-legal-number shrink-0 flex items-center justify-center w-8 h-8 rounded-lg bg-white/80 dark:bg-white/10 border border-white/60 dark:border-white/20 text-[11px] font-bold text-stone-500 dark:text-stone-300">02</span>
                                    <p class="text-xs font-semibold text-stone-700 dark:text-stone-300 leading-snug">Pengawasan Kementerian Haji dan Umrah</p>
                                </div>
                                <div class="about-legal-point flex items-center gap-3 bg-white/60 dark:bg-white/5 backdrop-blur-md rounded-xl border border-white/60 dark:border-white/10 px-3 py-2.5">
                                    <span class="about-legal-number shrink-0 flex items-center justify-center w-8 h-8 rounded-lg bg-white/80 dark:bg-white/10 border border-white/60 dark:border-white/20 text-[11px] font-bold text-stone-500 dark:text-stone-300">03</span>
                                    <p class="text-xs font-semibold text-stone-700 dark:text-stone-300 leading-snug">Nomor Izin Dapat Diverifikasi</p>
                                </div>
                            </div>

                            <p class="about-legal-note flex items-start gap-2 pt-1 text-[11px] leading-relaxed text-stone-500 dark:text-stone-400 max-w-2xl">
                                <i data-lucide="scale" class="w-3.5 h-3.5 shrink-0 mt-px text-stone-300"></i>
                                Penyelenggaraan tunduk pada Undang-Undang No. 8 Tahun 2019 tentang Penyelenggaraan Ibadah Haji dan Umrah.
                            </p>
                        </div>

                        <!-- Plat nomor izin -->
                        <div class="lg:col-span-5">
                            <div class="about-license-card relative overflow-hidden bg-blue-950/70 dark:bg-slate-950/50 backdrop-blur-xl rounded-xl px-8 py-10 sm:py-12 text-center border border-white/15 ring-1 ring-white/20 shadow-xl shadow-blue-950/20">
                                <!-- Faint islamic pattern watermark -->
                                <div class="absolute inset-0 islamic-pattern opacity-[0.4] pointer-events-none"></div>
                                <div class="relative">
                                    <div class="inline-flex items-center justify-center gap-2">
                                        <i data-lucide="file-text" class="w-3.5 h-3.5 text-stone-400"></i>
                                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400">Nomor Izin</p>
                                    </div>
                                    <p class="about-license-number mt-5 text-[1.5rem] sm:text-[1.9rem] leading-tight tracking-tight font-semibold text-white select-all font-mono">
                                        {{ $settings['footer_ppiu_number'] ?? '91202054619660001' }}
                                    </p>
                                    <div class="mt-7 pt-5 border-t border-white/10">
                                        <p class="text-[11px] font-medium text-stone-400">Diterbitkan &amp; diawasi oleh Kementerian Haji dan Umrah RI</p>
                                        <div class="mt-5 flex items-center justify-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                                            <span class="about-license-status text-[11px] font-bold uppercase tracking-widest text-blue-400">Resmi Terdaftar</span>
                                        </div>
                                    </div>
                                    <a href="https://haji.go.id/" target="_blank" rel="noopener noreferrer" class="mt-6 inline-flex items-center gap-1.5 text-[10px] font-semibold text-stone-400 hover:text-white transition duration-200">
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
            <div class="about-principles-grid grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12 md:mb-16">
                <!-- Visi Card (Asymmetric 5-Span) -->
                <div class="about-principle-card about-vision-card relative bg-gradient-to-br from-blue-600/80 via-blue-700/75 to-blue-800/80 backdrop-blur-2xl text-white rounded-[1.75rem] p-8 md:p-10 border border-white/25 ring-1 ring-white/20 shadow-xl shadow-blue-800/25 overflow-hidden flex flex-col justify-between lg:col-span-5 min-h-[260px]">
                    <div class="pointer-events-none absolute inset-x-8 top-0 h-px bg-gradient-to-r from-transparent via-white/50 to-transparent"></div>
                    <div>
                        <div class="bg-white/10 border border-white/20 backdrop-blur-md p-3.5 rounded-xl text-white w-fit mb-6">
                            <i data-lucide="eye" class="w-6 h-6"></i>
                        </div>
                        <h3 class="about-principle-title font-extrabold text-white text-xl mb-4 tracking-tight">{{ $settings['about_vision_label'] ?? 'Visi Kami' }}</h3>
                        <p class="about-principle-copy text-blue-100/80 text-xs md:text-sm leading-relaxed font-light">
                            {{ $settings['about_vision'] ?? 'Menjadi penyelenggara perjalanan ibadah Umrah dan Haji tepercaya yang mengedepankan kemurnian ibadah sesuai sunnah serta pelayanan VIP demi kenyamanan jemaah.' }}
                        </p>
                    </div>
                </div>

                <!-- Misi Card (Asymmetric 7-Span) -->
                <div class="about-principle-card about-mission-card relative bg-white/60 dark:bg-white/10 backdrop-blur-2xl rounded-[1.75rem] p-8 md:p-10 border border-white/60 dark:border-white/20 ring-1 ring-white/40 shadow-xl shadow-stone-900/5 overflow-hidden lg:col-span-7 min-h-[260px] flex flex-col justify-between">
                    <div class="pointer-events-none absolute inset-x-8 top-0 h-px bg-gradient-to-r from-transparent via-white/60 to-transparent"></div>
                    <div>
                        <div class="bg-blue-50 dark:bg-white/10 backdrop-blur-md border border-white/60 dark:border-white/20 text-blue-600 dark:text-blue-300 p-3.5 rounded-xl w-fit mb-6">
                            <i data-lucide="target" class="w-6 h-6"></i>
                        </div>
                        <h3 class="about-principle-title font-extrabold text-stone-900 text-xl mb-4 tracking-tight">{{ $settings['about_mission_label'] ?? 'Misi Kami' }}</h3>
                        <p class="about-principle-copy text-stone-500 text-xs md:text-sm leading-relaxed font-light">
                            {{ $settings['about_mission'] ?? 'Memberikan pelayanan akomodasi hotel bintang 5 di pelataran Masjidil Haram & Nabawi, memfasilitasi bimbingan manasik komprehensif, serta melayani jemaah dengan ramah dan profesional layaknya keluarga sendiri.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bottom Layout: Founders section -->
            <div class="text-center space-y-4 mb-8 reveal">
                <h3 class="text-2xl md:text-3xl font-extrabold text-stone-900 tracking-tight">{{ $settings['team_section_title'] ?? 'Di Balik Layar IZI Travel' }}</h3>
                <p class="text-stone-500 max-w-lg mx-auto text-xs md:text-sm leading-relaxed font-light">
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
                    <div class="bg-white/60 dark:bg-white/10 backdrop-blur-xl p-8 rounded-[1.75rem] border border-white/60 dark:border-white/15 hover:border-blue-600/15 ring-1 ring-white/40 shadow-lg shadow-stone-900/5 transition duration-300 text-center relative overflow-hidden reveal-card">
                        <div class="pointer-events-none absolute inset-x-8 top-0 h-px bg-gradient-to-r from-transparent via-white/60 to-transparent"></div>
                        @if($t->image_url)
                            <img src="{{ $t->image_url }}" alt="{{ $t->name }}" class="w-24 h-24 rounded-full object-cover shadow-md shadow-blue-500/10 mb-6 mx-auto border-4 border-white" width="96" height="96" loading="lazy" decoding="async">
                        @else
                            <div class="w-24 h-24 rounded-full bg-blue-600 flex items-center justify-center text-white text-3xl font-extrabold shadow-md shadow-blue-500/10 mb-6 mx-auto border-4 border-white">{{ $t->initial ?? '...' }}</div>
                        @endif
                        <h4 class="font-extrabold text-stone-900 text-lg mb-1">{{ $t->name }}</h4>
                        <p class="text-blue-600 text-xs font-bold uppercase tracking-wider mb-4">{{ $t->role }}</p>
                        <p class="text-stone-500 text-xs leading-relaxed font-light">
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
                            <div class="w-full border-t border-stone-200/60"></div>
                        </div>
                        <div class="relative bg-white/60 dark:bg-stone-800/50 backdrop-blur-md px-4 border border-white/40 dark:border-white/10 rounded-full py-1.5">
                            <span class="text-xs font-black text-stone-500 uppercase tracking-widest">{{ $settings['team_other_section_label'] ?? 'Tim Pendukung & Pembimbing' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-2 gap-3 md:gap-6 reveal-stagger">
                        @foreach ($otherTeams as $t)
                            <div class="relative overflow-hidden bg-white/60 dark:bg-white/10 backdrop-blur-xl p-4 md:p-5 rounded-2xl border border-white/60 dark:border-white/15 hover:border-blue-600/10 ring-1 ring-white/30 shadow-md shadow-stone-900/5 transition-all duration-300 flex flex-col sm:flex-row items-center sm:items-start gap-3 sm:gap-4 text-center sm:text-left group {{ $loop->last && $loop->iteration % 2 !== 0 ? 'col-span-2 sm:col-span-1 md:col-span-1' : '' }} reveal-card">
                                <div class="pointer-events-none absolute inset-x-4 top-0 h-px bg-gradient-to-r from-transparent via-white/50 to-transparent"></div>
                                @if($t->image_url)
                                    <img src="{{ $t->image_url }}" alt="{{ $t->name }}" class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover shadow-sm border-2 border-white flex-shrink-0" width="56" height="56" loading="lazy" decoding="async">
                                @else
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-blue-600 flex items-center justify-center text-white text-base sm:text-lg font-extrabold shadow-sm border-2 border-white flex-shrink-0">{{ $t->initial ?? '...' }}</div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <h5 class="font-extrabold text-stone-900 text-xs sm:text-sm mb-0.5 group-hover:text-blue-600 transition duration-200">{{ $t->name }}</h5>
                                    <p class="text-blue-600 text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider mb-2 leading-none">{{ $t->role }}</p>
                                    <p class="text-stone-500 text-[10px] sm:text-xs leading-relaxed font-light">
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
    <section class="features-modern-section py-16 md:py-24 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 relative overflow-hidden" data-purpose="features-grid" data-lazy-bg="{{ asset('images/section_kaaba_detail.webp') }}">
        <!-- Blurred Kaaba Background Image (Split Right with Blue Gradient Fade) -->
        <div class="features-global-bg absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="features-photo-bg absolute inset-0 bg-cover bg-[75%_center] scale-105 opacity-30" style="background-image: linear-gradient(to right, rgba(2, 110, 169, 1) 40%, rgba(2, 110, 169, 0.9) 55%, rgba(2, 110, 169, 0.6) 100%), var(--deferred-bg, none);"></div>
            <!-- Soft vertical gradient overlay to fade the top and bottom edges seamlessly -->
            <div class="features-fade-bg absolute inset-0 bg-gradient-to-b from-blue-600 via-transparent to-blue-800"></div>
            <!-- Stars/Pattern overlay -->
            <div class="features-pattern absolute inset-0 bg-cover opacity-[0.03] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22%3E%3Cpath d=%22M30 0 l10 20 l20 10 l-20 10 l-10 20 l-10 -20 l-20 -10 l20 -10 z%22 fill=%22%23ffffff%22/%3E%3C/svg%3E'); background-size: 60px 60px;"></div>
            <!-- Color orbs that give the frosted glass panels depth -->
            <div class="absolute -right-24 top-32 w-96 h-96 rounded-full bg-amber-400/20 blur-3xl"></div>
            <div class="absolute -left-20 bottom-24 w-80 h-80 rounded-full bg-emerald-400/15 blur-3xl"></div>
        </div>

        <!-- Section Header (Premium Split Layout) -->
        <div class="features-header max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16 reveal text-center lg:text-left relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 items-center lg:items-start">
                <div class="space-y-3 flex flex-col items-center lg:items-start">
                    <span class="features-eyebrow inline-flex items-center px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-amber-400 text-xs font-bold tracking-[0.14em] uppercase">
                        {{ $settings['features_badge'] ?? 'Kenapa Kami' }}
                    </span>
                    <h2 class="features-title text-3xl md:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight text-center lg:text-left">
                        {{ $settings['features_section_title'] ?? 'Keunggulan Layanan Kami' }}
                    </h2>
                </div>
                <p class="features-subtitle text-white/80 text-sm md:text-base max-w-xl font-light leading-relaxed lg:pb-1 text-center lg:text-left">
                    {{ $settings['features_section_subtitle'] ?? 'Mitra tepercaya perjalanan ibadah Anda dengan standar pelayanan tinggi dan kekeluargaan.' }}
                </p>
            </div>
        </div>

        <!-- Bento Grid Layout -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="features-grid-modern grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 reveal-stagger" data-stagger="true">
                <!-- Feature 1 -->
                <div class="feature-modern-card reveal-card lg:col-span-1 relative overflow-hidden bg-white/10 backdrop-blur-xl hover:border-blue-600/15 border border-white/15 ring-1 ring-white/20 p-8 rounded-[1.75rem] shadow-xl shadow-blue-950/20 flex flex-col justify-between">
                    <div class="pointer-events-none absolute inset-x-5 top-0 h-px bg-gradient-to-r from-transparent via-white/40 to-transparent"></div>
                    <div>
                        <div class="mb-6 bg-white/15 backdrop-blur-md text-amber-400 p-4 rounded-xl w-fit border border-white/20">
                            @if (!empty($settings['feature_1_image']))
                                <img src="{{ str_starts_with($settings['feature_1_image'], 'images/') ? asset($settings['feature_1_image']) : asset('storage/' . $settings['feature_1_image']) }}" alt="" class="w-8 h-8 object-contain" width="32" height="32" loading="lazy" decoding="async">
                            @else
                                <i data-lucide="{{ $settings['feature_1_icon'] ?? 'award' }}" class="w-8 h-8"></i>
                            @endif
                        </div>
                        <h3 class="font-extrabold text-white text-lg mb-3">{{ $settings['feature_1_title'] ?? 'Legalitas Resmi Kemenag' }}</h3>
                        <p class="text-white/85 text-xs leading-relaxed font-light">{{ $settings['feature_1_desc'] ?? 'Memiliki izin PPIU resmi dari Kementerian Agama RI untuk kepastian keamanan hukum perjalanan Anda.' }}</p>
                    </div>
                </div>

                <!-- Feature 2: Standard Card -->
                <div class="feature-modern-card reveal-card lg:col-span-1 relative overflow-hidden bg-white/10 backdrop-blur-xl hover:border-blue-600/15 border border-white/15 ring-1 ring-white/20 p-8 rounded-[1.75rem] shadow-xl shadow-blue-950/20 flex flex-col justify-between">
                    <div class="pointer-events-none absolute inset-x-5 top-0 h-px bg-gradient-to-r from-transparent via-white/40 to-transparent"></div>
                    <div>
                        <div class="mb-6 bg-white/15 backdrop-blur-md text-amber-400 p-4 rounded-2xl w-fit border border-white/20">
                            @if (!empty($settings['feature_2_image']))
                                <img src="{{ str_starts_with($settings['feature_2_image'], 'images/') ? asset($settings['feature_2_image']) : asset('storage/' . $settings['feature_2_image']) }}" alt="" class="w-8 h-8 object-contain" width="32" height="32" loading="lazy" decoding="async">
                            @else
                                <i data-lucide="{{ $settings['feature_2_icon'] ?? 'file-check' }}" class="w-8 h-8"></i>
                            @endif
                        </div>
                        <h3 class="font-extrabold text-white text-lg mb-3">{{ $settings['feature_2_title'] ?? 'Jaminan Visa Umrah' }}</h3>
                        <p class="text-white/85 text-xs leading-relaxed font-light">{{ $settings['feature_2_desc'] ?? 'Proses penerbitan visa yang aman, transparan, and terkonfirmasi langsung ke sistem kedutaan.' }}</p>
                    </div>
                </div>

                <!-- Feature 3: Standard Card -->
                <div class="feature-modern-card reveal-card lg:col-span-1 relative overflow-hidden bg-white/10 backdrop-blur-xl hover:border-blue-600/15 border border-white/15 ring-1 ring-white/20 p-8 rounded-[1.75rem] shadow-xl shadow-blue-950/20 flex flex-col justify-between">
                    <div class="pointer-events-none absolute inset-x-5 top-0 h-px bg-gradient-to-r from-transparent via-white/40 to-transparent"></div>
                    <div>
                        <div class="mb-6 bg-white/15 backdrop-blur-md text-amber-400 p-4 rounded-xl w-fit border border-white/20">
                            @if (!empty($settings['feature_3_image']))
                                <img src="{{ str_starts_with($settings['feature_3_image'], 'images/') ? asset($settings['feature_3_image']) : asset('storage/' . $settings['feature_3_image']) }}" alt="" class="w-8 h-8 object-contain" width="32" height="32" loading="lazy" decoding="async">
                            @else
                                <i data-lucide="{{ $settings['feature_3_icon'] ?? 'building-2' }}" class="w-8 h-8"></i>
                            @endif
                        </div>
                        <h3 class="font-extrabold text-white text-lg mb-3">{{ $settings['feature_3_title'] ?? 'Hotel Dekat Pelataran' }}</h3>
                        <p class="text-white/85 text-xs leading-relaxed font-light">{{ $settings['feature_3_desc'] ?? 'Akomodasi hotel bintang pilihan dengan jarak yang dekat memudahkan Anda beribadah di Masjidil Haram &amp; Nabawi.' }}</p>
                    </div>
                </div>

                <!-- Feature 4: Standard Card -->
                <div class="feature-modern-card reveal-card lg:col-span-1 relative overflow-hidden bg-white/10 backdrop-blur-xl hover:border-blue-600/15 border border-white/15 ring-1 ring-white/20 p-8 rounded-[1.75rem] shadow-xl shadow-blue-950/20 flex flex-col justify-between">
                    <div class="pointer-events-none absolute inset-x-5 top-0 h-px bg-gradient-to-r from-transparent via-white/40 to-transparent"></div>
                    <div>
                        <div class="mb-6 bg-white/15 backdrop-blur-md text-amber-400 p-4 rounded-2xl w-fit border border-white/20">
                            @if (!empty($settings['feature_4_image']))
                                <img src="{{ str_starts_with($settings['feature_4_image'], 'images/') ? asset($settings['feature_4_image']) : asset('storage/' . $settings['feature_4_image']) }}" alt="" class="w-8 h-8 object-contain" width="32" height="32" loading="lazy" decoding="async">
                            @else
                                <i data-lucide="{{ $settings['feature_4_icon'] ?? 'compass' }}" class="w-8 h-8"></i>
                            @endif
                        </div>
                        <h3 class="font-extrabold text-white text-lg mb-3">{{ $settings['feature_4_title'] ?? 'Muthawwif Khas Nusantara' }}</h3>
                        <p class="text-white/85 text-xs leading-relaxed font-light">{{ $settings['feature_4_desc'] ?? 'Muthawwif &amp; pembimbing ibadah bersertifikasi, membimbing sesuai sunnah dengan keramahan khas Indonesia.' }}</p>
                    </div>
                </div>

                <!-- Feature 5: Standard Card -->
                <div class="feature-modern-card reveal-card lg:col-span-1 relative overflow-hidden bg-white/10 backdrop-blur-xl hover:border-blue-600/15 border border-white/15 ring-1 ring-white/20 p-8 rounded-[1.75rem] shadow-xl shadow-blue-950/20 flex flex-col justify-between">
                    <div class="pointer-events-none absolute inset-x-5 top-0 h-px bg-gradient-to-r from-transparent via-white/40 to-transparent"></div>
                    <div>
                        <div class="mb-6 bg-white/15 backdrop-blur-md text-amber-400 p-4 rounded-xl w-fit border border-white/20">
                            @if (!empty($settings['feature_5_image']))
                                <img src="{{ str_starts_with($settings['feature_5_image'], 'images/') ? asset($settings['feature_5_image']) : asset('storage/' . $settings['feature_5_image']) }}" alt="" class="w-8 h-8 object-contain" width="32" height="32" loading="lazy" decoding="async">
                            @else
                                <i data-lucide="{{ $settings['feature_5_icon'] ?? 'phone-call' }}" class="w-8 h-8"></i>
                            @endif
                        </div>
                        <h3 class="font-extrabold text-white text-lg mb-3">{{ $settings['feature_5_title'] ?? 'Layanan Siaga &amp; Peduli' }}</h3>
                        <p class="text-white/85 text-xs leading-relaxed font-light">{{ $settings['feature_5_desc'] ?? 'Customer support dan tim handling operasional siaga melayani Anda 24 jam dengan asas kekeluargaan.' }}</p>
                    </div>
                </div>

                <!-- Feature 6 -->
                <div class="feature-modern-card reveal-card lg:col-span-1 relative overflow-hidden bg-white/10 backdrop-blur-xl hover:border-blue-600/15 border border-white/15 ring-1 ring-white/20 p-8 rounded-[1.75rem] shadow-xl shadow-blue-950/20 flex flex-col justify-between">
                    <div class="pointer-events-none absolute inset-x-5 top-0 h-px bg-gradient-to-r from-transparent via-white/40 to-transparent"></div>
                    <div>
                        <div class="mb-6 bg-white/15 backdrop-blur-md text-amber-400 p-4 rounded-xl w-fit border border-white/20">
                            @if (!empty($settings['feature_6_image']))
                                <img src="{{ str_starts_with($settings['feature_6_image'], 'images/') ? asset($settings['feature_6_image']) : asset('storage/' . $settings['feature_6_image']) }}" alt="" class="w-8 h-8 object-contain" width="32" height="32" loading="lazy" decoding="async">
                            @else
                                <i data-lucide="{{ $settings['feature_6_icon'] ?? 'plane-takeoff' }}" class="w-8 h-8"></i>
                            @endif
                        </div>
                        <h3 class="font-extrabold text-white text-lg mb-3">{{ $settings['feature_6_title'] ?? 'Kepastian Tiket Terbang' }}</h3>
                        <p class="text-white/85 text-xs leading-relaxed font-light">{{ $settings['feature_6_desc'] ?? 'Kepastian tanggal keberangkatan dengan tiket pesawat premium (PP) yang telah issued sejak pendaftaran.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Why Choose Us -->

    <!-- BEGIN: RegistrationFlow -->
    <section class="section-soft-bg py-16 md:py-24 relative overflow-hidden bg-stone-50" data-purpose="registration-flow" data-lazy-bg="">
        <!-- Background Image overlay -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-[center_center] scale-105" style="background-image: linear-gradient(to bottom, rgba(250, 250, 249, 0.93) 0%, rgba(250, 250, 249, 0.98) 100%), var(--deferred-bg, none);"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="space-y-3 flex flex-col items-center mb-12">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-600/[0.07] border border-blue-600/20 text-blue-700 text-xs font-bold tracking-[0.14em] uppercase">
                    Cara Daftar
                </span>
                <h2 class="text-3xl font-extrabold text-stone-900 tracking-tight reveal-words">{{ $settings['registration_title'] ?? 'Alur Pendaftaran Mudah' }}</h2>
                @include('partials.ornament')
                <p class="text-stone-500 font-light text-xs md:text-sm max-w-md mx-auto leading-relaxed">{{ $settings['registration_subtitle'] ?? '7 langkah mudah mempersiapkan perjalanan suci Anda bersama IZI Travel' }}</p>
            </div>
            <div class="relative max-w-5xl mx-auto px-3 sm:px-0 reveal-stagger">
                <!-- Vertical Spine -->
                <div class="absolute left-1/2 -translate-x-1/2 top-8 bottom-14 md:bottom-16 w-px bg-blue-600/30" aria-hidden="true"></div>

                @php
                    $stepsJson = $settings['registration_steps'] ?? null;
                    $steps = [];
                    if ($stepsJson) {
                        $steps = json_decode($stepsJson, true);
                    }
                    if (empty($steps)) {
                        for ($i = 1; $i <= 7; $i++) {
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
                            ['title' => 'Pilih Paket', 'description' => 'Pilih paket yang sesuai dengan tanggal dan keinginan Anda.', 'icon' => 'package'],
                            ['title' => 'Konsultasi', 'description' => 'Hubungi customer service kami untuk detail keberangkatan.', 'icon' => 'message-circle'],
                            ['title' => 'Kirim Berkas', 'description' => 'Lengkapi dokumen yaitu KK, KTP, paspor, dan vaksin meningitis juga polio.', 'icon' => 'id-card'],
                            ['title' => 'Uang Muka (DP)', 'description' => 'Lakukan deposit untuk mengamankan kursi penerbangan kamu.', 'icon' => 'wallet'],
                            ['title' => 'Pelunasan', 'description' => 'Lakukan transaksi pelunasan paket yang dipilih maksimal H-30 jadwal keberangkatan.', 'icon' => 'badge-check'],
                            ['title' => 'Manasik', 'description' => 'Bimbingan terkait panduan ibadah umrah sesuai Al-Qur\'an dan Sunnah.', 'icon' => 'book-open'],
                            ['title' => 'Berangkat', 'description' => 'Pelepasan di bandara dan mulai perjalanan ibadah Anda.', 'icon' => 'plane-takeoff'],
                        ];
                    }
                @endphp

                <div class="space-y-10 md:space-y-14">
                    @foreach ($steps as $step)
                        @php $isLeft = $loop->iteration % 2 !== 0; @endphp
                        <div class="relative grid grid-cols-[1fr_auto_1fr] gap-x-4 md:gap-x-5 items-start">
                            <!-- Node: flat circle, gold number, navy icon -->
                            <div class="col-start-2 row-start-1 z-20 flex justify-center pt-1 md:pt-2">
                                <div class="relative">
                                    <div class="reveal-card w-12 h-12 md:w-16 md:h-16 rounded-full bg-white border border-blue-200 flex flex-col items-center justify-center gap-[2px] shadow-[0_1px_3px_rgba(7,30,61,0.05)]">
                                        <span class="text-blue-600 font-bold text-[9px] md:text-[10px] tracking-[0.18em] leading-none" style="font-variant-numeric: tabular-nums;">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                        <i data-lucide="{{ $step['icon'] ?? 'compass' }}" class="w-4 h-4 md:w-5 md:h-5 text-blue-600"></i>
                                    </div>
                                    <!-- Connector Tail (spans the gap to the text, all breakpoints) -->
                                    <div class="absolute top-1/2 -translate-y-1/2 h-px w-4 md:w-5 bg-blue-600/40 {{ $isLeft ? 'right-full' : 'left-full' }}" aria-hidden="true"></div>
                                </div>
                            </div>

                            <!-- Step Text: symmetric measure, anchored toward the spine -->
                            <div class="row-start-1 min-w-0 pt-3 md:pt-6 {{ $isLeft ? 'col-start-1' : 'col-start-3' }}">
                                <div class="{{ $isLeft ? 'text-left md:ml-auto md:text-right' : 'text-right md:mr-auto md:text-left' }} md:max-w-[360px]">
                                    <h3 class="font-heading text-lg md:text-[21px] leading-snug font-bold text-stone-900 tracking-tight">{{ $step['title'] ?? '' }}</h3>
                                    <p class="text-sm md:text-[15px] leading-[1.75] text-stone-600 mt-1.5">{{ $step['description'] ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Destination Mark -->
                <div class="relative flex items-center justify-center gap-4 mt-12 md:mt-16">
                    <span class="h-px w-12 bg-blue-600/25" aria-hidden="true"></span>
                    <div class="reveal-card flex items-center gap-2.5">
                        <svg class="w-7 h-7" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                            <rect x="5" y="6" width="22" height="18" rx="2" stroke="#0a2540" stroke-width="1.9"/>
                            <rect x="5" y="11" width="22" height="2.6" fill="#c89e2b"/>
                            <rect x="14" y="15.5" width="4" height="8.5" stroke="#0a2540" stroke-width="1.4"/>
                            <circle cx="23.5" cy="8.5" r="1.7" fill="#c89e2b"/>
                        </svg>
                        <span class="font-heading text-sm font-bold text-blue-700 tracking-wide">Ke Baitullah</span>
                    </div>
                    <span class="h-px w-12 bg-blue-600/25" aria-hidden="true"></span>
                </div>
            </div>
        </div>
    </section>
    <!-- END: RegistrationFlow -->

    @include('public.partials.home-packages')

    <!-- BEGIN: Partners -->
    <section class="partners-modern-section py-8 md:py-10 bg-stone-50/20" id="partners-logo-cloud" data-purpose="partners-logo-cloud">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="partners-title text-2xl md:text-3xl font-extrabold text-center text-stone-900 mb-6 tracking-tight reveal">{{ $settings['partners_section_title'] ?? 'Mitra Maskapai Penerbangan' }}</h2>
            <div class="partners-logo-row flex flex-wrap justify-center items-center gap-8 sm:gap-10 md:gap-16 opacity-85 reveal">
                @foreach ($partners as $partner)
                    <div class="flex items-center justify-center grayscale hover:grayscale-0 transition duration-300">
                        @if ($partner->logo_type === 'svg')
                            <div class="h-10 md:h-16 flex items-center justify-center text-stone-700 dark:text-stone-400 [&>svg]:!h-full [&>svg]:w-auto">
                                {!! $partner->logo_path !!}
                            </div>
                        @else
                            <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="h-10 md:h-16 w-auto object-contain" loading="lazy" decoding="async">
                        @endif
                    </div>
                @endforeach
                <!-- Premium Hotel Link -->
                <div class="partners-extra text-amber-500 font-extrabold text-lg md:text-2xl italic hover:text-amber-600 transition duration-300">
                    {{ $settings['partners_extra'] ?? '+ Akomodasi Bintang 5' }}
                </div>
            </div>
        </div>
    </section>
    <!-- END: Partners -->

    @include('public.partials.home-gallery')

    @include('public.partials.home-testimonials')

    @include('public.partials.home-articles')

    @include('public.partials.home-haramain')

    <!-- BEGIN: Kemitraan -->
    <section class="section-soft-bg py-16 md:py-24 relative overflow-hidden" id="kemitraan" data-purpose="partnership-section" data-lazy-bg="">
        <!-- Background Image overlay -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-[center_center] scale-105" style="background-image: linear-gradient(to bottom, rgba(250, 250, 249, 0.93) 0%, rgba(250, 250, 249, 0.98) 100%), var(--deferred-bg, none);"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="partnership-header mb-12 text-center reveal">
                <div class="space-y-3 flex flex-col items-center">
                    <span class="partnership-eyebrow inline-flex items-center px-4 py-1.5 rounded-full bg-blue-50/90 border border-blue-200/80 text-blue-600 text-xs font-bold tracking-[0.14em] uppercase">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <i data-lucide="handshake" class="w-3.5 h-3.5"></i>
                        {{ $settings['partnership_badge'] ?? 'Program Kemitraan' }}
                    </span>
                    <h2 class="partnership-title text-3xl sm:text-4xl font-black text-stone-900 tracking-tight reveal-words">
                        {{ $settings['partnership_title'] ?? 'Mari Bergabung Menjadi Mitra Syiar Baitullah' }}
                    </h2>
                    @include('partials.ornament')
                    <p class="partnership-subtitle text-blue-900/70 font-light text-xs sm:text-sm md:text-base max-w-2xl leading-relaxed pt-2 mx-auto">
                        {{ $settings['partnership_subtitle'] ?? 'Menjadi mitra syiar baitullah berkesempatan mendapatkan komisi hingga puluhan juta rupiah bahkan berkesempatan untuk umroh.' }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 reveal-stagger" data-stagger="true">
                <!-- Tier 1: Freelance -->
                <div class="reveal-card kemitraan-card kemitraan-card-blue bg-gradient-to-b from-blue-600/[0.02] to-white hover:from-blue-600/[0.06] glow-card p-8 rounded-3xl border border-blue-600/5 hover:border-blue-600/15 shadow-xl shadow-stone-900/5 hover:-translate-y-1 transition duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="bg-blue-50/80 p-4 rounded-2xl text-blue-600 w-fit">
                                <i data-lucide="user-check" class="w-8 h-8"></i>
                            </div>
                            <span class="bg-blue-50 text-blue-600 text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-wider">{{ $settings['partnership_tier_1_badge'] ?? 'Freelance' }}</span>
                        </div>
                        <h3 class="font-extrabold text-stone-900 text-lg mb-1">{{ $settings['partnership_tier_1_title'] ?? 'Mitra Freelance' }}</h3>
                        <p class="text-[10px] text-stone-500 font-bold uppercase tracking-wider mb-3">{{ $settings['partnership_reg_label'] ?? 'Biaya Pendaftaran' }}</p>
                        <p class="text-3xl font-black text-blue-600 mb-6">{{ $settings['partnership_tier_1_price'] ?? 'FREE' }}</p>
                        <div class="h-px bg-stone-100 mb-6"></div>
                        <ul class="space-y-3.5 text-xs text-stone-600">
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
                <div class="reveal-card kemitraan-card kemitraan-card-amber bg-gradient-to-b from-blue-600/[0.02] to-white hover:from-blue-600/[0.06] glow-card p-8 rounded-3xl border border-blue-600/5 hover:border-blue-600/15 shadow-xl shadow-stone-900/5 hover:-translate-y-1 transition duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="bg-amber-50 p-4 rounded-2xl text-amber-500 w-fit">
                                <i data-lucide="briefcase" class="w-8 h-8"></i>
                            </div>
                            <span class="bg-amber-100/70 text-amber-700 text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-wider">{{ $settings['partnership_tier_2_badge'] ?? 'Agen Resmi' }}</span>
                        </div>
                        <h3 class="font-extrabold text-stone-900 text-lg mb-1">{{ $settings['partnership_tier_2_title'] ?? 'Mitra Agen' }}</h3>
                        <p class="text-[10px] text-stone-500 font-bold uppercase tracking-wider mb-3">{{ $settings['partnership_reg_label'] ?? 'Biaya Pendaftaran' }}</p>
                        <p class="text-3xl font-black text-amber-700 mb-6">{{ $settings['partnership_tier_2_price'] ?? 'Rp 1.000.000' }}</p>
                        <div class="h-px bg-stone-100 mb-6"></div>
                        <ul class="space-y-3.5 text-xs text-stone-600">
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
                <div class="reveal-card kemitraan-card kemitraan-card-emerald bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 text-white glow-card p-8 rounded-3xl border border-blue-500/20 shadow-xl shadow-blue-900/20 hover:-translate-y-1 transition duration-300 flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-amber-500/10 rounded-full blur-3xl"></div>
                    <div class="absolute -left-10 -top-10 w-40 h-40 bg-blue-500/10 rounded-full blur-3xl"></div>

                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div>
                            <div class="mb-6 bg-amber-500 text-stone-950 p-4 rounded-2xl w-fit font-bold shadow-md shadow-amber-500/20">
                                <i data-lucide="gift" class="w-8 h-8"></i>
                            </div>
                            <h3 class="font-extrabold text-amber-400 text-lg mb-1">{{ $settings['partnership_tier_3_title'] ?? 'Keuntungan &amp; Reward' }}</h3>
                            <p class="text-stone-400 text-[10px] uppercase font-bold tracking-wider mb-6">{{ $settings['partnership_tier_3_subtitle'] ?? 'Potensi Syiar Kemitraan' }}</p>

                            <div class="space-y-4">
                                <!-- Komisi -->
                                <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                                    <p class="text-[9px] text-amber-500 font-bold uppercase tracking-wider">{{ $settings['partnership_reward_1_label'] ?? 'Komisi per Jemaah' }}</p>
                                    <p class="text-xl font-black mt-0.5">{{ $settings['partnership_reward_1_value'] ?? 'Hingga Rp 2.000.000' }}</p>
                                    <p class="text-[10px] text-stone-400 mt-1 leading-snug">{{ $settings['partnership_reward_1_desc'] ?? 'Pendapatan langsung per jemaah yang melakukan pelunasan.' }}</p>
                                </div>

                                <!-- Reward -->
                                <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                                    <p class="text-[9px] text-amber-500 font-bold uppercase tracking-wider">{{ $settings['partnership_reward_2_label'] ?? 'Reward Prestasi' }}</p>
                                    <p class="text-xl font-black mt-0.5">{{ $settings['partnership_reward_2_value'] ?? 'Umroh Gratis' }}</p>
                                    <p class="text-[10px] text-stone-400 mt-1 leading-snug">{{ $settings['partnership_reward_2_desc'] ?? 'Kesempatan ibadah umrah gratis bagi mitra yang mencapai target syiar.' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kemitraan Call To Action -->
            <div class="mt-16 bg-white border border-stone-100/80 rounded-3xl p-8 md:p-10 shadow-xl shadow-stone-900/5 flex flex-col md:flex-row items-center justify-between gap-8 max-w-4xl mx-auto relative overflow-hidden reveal-scale">
                <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-blue-500/5 rounded-full blur-2xl"></div>
                <div class="text-left space-y-2 relative z-10">
                    <h4 class="font-extrabold text-stone-900 text-lg md:text-xl">{{ $settings['partnership_cta_title'] ?? 'Tertarik Menjadi Mitra IZI Travel?' }}</h4>
                    <p class="text-stone-500 text-xs md:text-sm font-light">{{ $settings['partnership_cta_desc'] ?? 'Dapatkan proposal penawaran kemitraan resmi dan diskusikan peluang kerja sama bersama tim kami.' }}</p>
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
    <section class="py-16 md:py-24 relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800" data-purpose="faq-section">
        <!-- Stars/Pattern overlay -->
        <div class="absolute inset-0 bg-cover opacity-[0.03] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22%3E%3Cpath d=%22M30 0 l10 20 l20 10 l-20 10 l-10 20 l-10 -20 l-20 -10 l20 -10 z%22 fill=%22%23ffffff%22/%3E%3C/svg%3E'); background-size: 60px 60px;"></div>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="faq-header mb-12 text-center reveal">
                <div class="space-y-3 flex flex-col items-center">
                    <span class="faq-eyebrow inline-flex items-center px-4 py-1.5 rounded-full bg-white/5 border border-white/10 text-amber-400 text-xs font-bold tracking-[0.14em] uppercase">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <i data-lucide="help-circle" class="w-3.5 h-3.5"></i>
                        Tanya Jawab
                    </span>
                    <h2 class="faq-title text-3xl sm:text-4xl font-black text-white tracking-tight reveal-words">
                        {{ $settings['faq_section_title'] ?? 'Tanya Jawab (FAQ)' }}
                    </h2>
                    @include('partials.ornament')
                </div>
            </div>
            <div class="space-y-4 reveal">
                @foreach ($faqs as $faq)
                    <details class="faq-details group border border-white/[0.12] group-open:border-amber-400/35 rounded-2xl overflow-hidden transition-all duration-300 bg-white/[0.06] group-open:bg-white/[0.11] hover:bg-white/[0.09] backdrop-blur-md">
                        <summary class="flex items-center justify-between p-5 cursor-pointer list-none font-bold text-white transition">
                            {{ $faq->question }}
                            <svg class="w-5 h-5 text-amber-400/80 shrink-0 transition-transform duration-300 group-open:-rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="faq-content-wrapper">
                            <div class="overflow-hidden">
                                <div class="p-5 text-white/70 leading-relaxed text-sm font-light border-t border-white/10">
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
    <section class="section-soft-bg py-16 md:py-24 relative overflow-hidden bg-stone-50" data-purpose="cta-section" data-lazy-bg="">
        <!-- Background Image overlay -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-[center_center] scale-105" style="background-image: linear-gradient(to bottom, rgba(250, 250, 249, 0.75) 0%, rgba(250, 250, 249, 0.9) 100%), var(--deferred-bg, none);"></div>
        </div>
        <div class="absolute inset-0 bg-stone-100/10 pointer-events-none z-10"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 text-center">
            <div class="bg-gradient-to-br from-white/95 to-blue-600/[0.06] backdrop-blur-xl border border-blue-600/10 p-6 sm:p-12 md:p-16 rounded-[2rem] shadow-2xl shadow-blue-600/[0.03] reveal">
                <h2 class="text-3xl md:text-5xl font-black text-stone-900 mb-6 tracking-tight">
                    {{ $settings['cta_title'] ?? 'Siap Memulai Perjalanan Suci Anda?' }}
                </h2>
                <p class="text-lg text-stone-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                    {{ $settings['cta_description'] ?? 'Hubungi kami sekarang untuk mendapatkan rekomendasi paket terbaik sesuai kebutuhan dan budget perjalanan ibadah Anda.' }}
                </p>
                <a href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank" class="bg-gradient-to-r from-amber-500 to-amber-600 text-stone-950 hover:from-amber-400 hover:to-amber-500 px-10 py-4 rounded-full font-extrabold flex items-center gap-3 mx-auto shadow-lg shadow-amber-500/20 hover:shadow-amber-500/30 transition-all duration-300 transform active:scale-95 w-fit text-sm">
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
    @include('public.partials.footer')

    <!-- Lucide Icons: bundled via resources/js/app.js (window.lucide) -->
    <script>
        // Shared open/close panel animation helpers (lightbox, mobile drawer, video modal) —
        // Motion.animate() returns a promise that resolves when the animation actually finishes,
        // so callers don't need the old reflow-hack (`void el.offsetWidth`) + magic-number
        // setTimeout that just guessed how long the CSS transition would take.
        const panelIn = (elements, keyframes) => Motion.animate(elements, keyframes, { duration: 0.35, ease: [0.16, 1, 0.3, 1] });
        const panelOut = (elements, keyframes) => Motion.animate(elements, keyframes, { duration: 0.3, ease: [0.16, 1, 0.3, 1] });

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
                lightboxImg.setAttribute('alt', item.title || 'Foto perjalanan jemaah');
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
            lightboxTrigger = document.activeElement;

            lightbox.classList.remove('hidden');
            lightbox.classList.add('flex');

            // Body scroll lock
            document.body.style.overflow = 'hidden';

            panelIn(lightbox, { opacity: [0, 1] });
            panelIn(lightboxContentBox, { scale: [0.95, 1] });

            showLightboxItem(startIndex);
            lightboxClose.focus();

            // Pause testimonial autoplay when lightbox is opened
            if (typeof window.pauseTestimonialAutoplay === 'function') {
                window.pauseTestimonialAutoplay();
            }
        };

        const closeLightbox = async () => {
            if (lightbox.classList.contains('hidden')) return;
            // Unlock scroll
            document.body.style.overflow = '';

            await Promise.all([
                panelOut(lightbox, { opacity: [1, 0] }),
                panelOut(lightboxContentBox, { scale: [1, 0.95] }),
            ]);

            lightbox.classList.remove('flex');
            lightbox.classList.add('hidden');
            lightboxIframe.setAttribute('src', '');
            lightboxImg.setAttribute('src', '');
            lightboxTrigger?.focus();

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
                openLightbox(items, Number(card.dataset.startIndex) || 0);
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
            } else if (e.key === 'Tab') {
                const controls = [lightboxClose, lightboxPrev, lightboxNext].filter(button => !button.classList.contains('hidden'));
                const current = controls.indexOf(document.activeElement);
                if (e.shiftKey && current <= 0) {
                    e.preventDefault();
                    controls.at(-1).focus();
                } else if (!e.shiftKey && (current === controls.length - 1 || current < 0)) {
                    e.preventDefault();
                    controls[0].focus();
                }
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
            // Render Lucide Icons
            lucide.createIcons();



            // The shared public navigation manages its own transparent and scrolled states.
            const scrollProgressBar = document.getElementById('scroll-progress-bar');
            const onScrollHandler = () => {
                if (scrollProgressBar) {
                    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
                    const ratio = docHeight > 0 ? Math.min(window.scrollY / docHeight, 1) : 0;
                    scrollProgressBar.style.transform = `scaleX(${ratio})`;
                }
            };
            window.addEventListener("scroll", onScrollHandler, { passive: true });
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

            // 4. Stats Counter Count-up — Motion.animate() tweens the plain number,
            // replacing the hand-rolled requestAnimationFrame loop + manual ease-out-quad math.
            const runCounters = () => {
                document.querySelectorAll('.stat-counter').forEach(counter => {
                    const target = parseInt(counter.getAttribute('data-target'), 10);
                    const suffix = counter.getAttribute('data-suffix') || '';

                    Motion.animate(0, target, {
                        duration: 1.5,
                        ease: 'easeOut',
                        onUpdate: (value) => { counter.textContent = Math.floor(value) + suffix; },
                    });
                });
            };

            const statsSection = document.querySelector('#tentang-kami');
            if (statsSection) {
                const stop = Motion.inView(statsSection, () => {
                    runCounters();
                    stop();
                }, { amount: 0.1 });
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
                        dot.className = `w-2.5 h-2.5 rounded-full cursor-pointer transition-all duration-300 ${i === currentIndex ? 'bg-blue-600 w-6' : 'bg-stone-200 hover:bg-stone-400'}`;
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
                            dots[i].className = 'w-2.5 h-2.5 rounded-full cursor-pointer transition-all duration-300 bg-stone-200 hover:bg-stone-400';
                        }
                    }
                };

                const setPositionByIndex = () => {
                    const slideWidth = slides[0].getBoundingClientRect().width;
                    currentTranslate = currentIndex * -slideWidth;
                    prevTranslate = currentTranslate;
                    // Spring glide to the target slide (dragging itself still sets style.transform
                    // directly every frame in the animation() rAF loop below — only the "commit to
                    // nearest slide" snap uses Motion).
                    Motion.animate(track, { x: currentTranslate }, { type: Motion.spring, stiffness: 300, damping: 30 });
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

            // 12. Filter packages while keeping the visible count in sync.
            const packageTabBtns = document.querySelectorAll('.umrah-package-filter');
            const packageItems = document.querySelectorAll('.umrah-package-card');
            const packageCount = document.getElementById('umrah-package-count');
            const packageFilterEmpty = document.getElementById('umrah-package-filter-empty');

            packageTabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const filter = btn.dataset.packageFilter;
                    packageTabBtns.forEach(tab => {
                        const active = tab === btn;
                        tab.classList.toggle('is-active', active);
                        tab.setAttribute('aria-pressed', String(active));
                    });

                    let visible = 0;
                    packageItems.forEach(card => {
                        const show = filter === 'all' || card.dataset.category === filter;
                        card.hidden = !show;
                        if (show) visible++;
                    });
                    if (packageCount) packageCount.textContent = `Menampilkan ${visible} paket`;
                    if (packageFilterEmpty) packageFilterEmpty.hidden = visible !== 0;
                    btn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'nearest' });
                });
            });

            // Filter the six home articles without changing the article links.
            const articleSection = document.getElementById('artikel');
            if (articleSection) {
                const articleTabs = [...articleSection.querySelectorAll('.article-tab-btn')];
                const articleCards = [...articleSection.querySelectorAll('.article-card')];
                const articleCount = document.getElementById('article-filter-count');
                const articleEmpty = document.getElementById('article-filter-empty');

                articleTabs.forEach(tab => tab.addEventListener('click', () => {
                    const filter = tab.dataset.filter;
                    articleTabs.forEach(button => {
                        const active = button === tab;
                        button.classList.toggle('is-active', active);
                        button.setAttribute('aria-pressed', String(active));
                    });

                    let visible = 0;
                    articleCards.forEach(card => {
                        const show = filter === 'all' || card.dataset.category === filter;
                        card.hidden = !show;
                        if (show) visible++;
                    });
                    if (articleCount) articleCount.textContent = `${visible} artikel pilihan`;
                    if (articleEmpty) articleEmpty.hidden = visible !== 0;
                }));
            }
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

        });
    </script>

    @include('public.partials.home-haramain-script')





    <!-- BEGIN: Floating WhatsApp Button -->
    <a href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank" class="fixed bottom-20 md:bottom-6 right-6 z-50 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white p-4 rounded-full shadow-2xl shadow-emerald-500/30 flex items-center justify-center animate-pulse-glow hover:scale-110 active:scale-95 transition-all duration-300 group" aria-label="Hubungi WhatsApp">
        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
        </svg>
        <span class="max-w-0 overflow-hidden group-hover:max-w-xs group-hover:ml-2 transition-all duration-500 ease-out text-xs font-bold whitespace-nowrap">
            Chat WhatsApp
        </span>
    </a>

    <!-- Video Testimonial Modal (Premium & Smartphone-Adaptive for Instagram) -->
    <div id="video-testimonial-modal" class="fixed inset-0 z-[100] bg-stone-950/80 backdrop-blur-md hidden opacity-0 items-center justify-center p-4" role="dialog" aria-modal="true">
        <div class="absolute inset-0 cursor-pointer" onclick="window.closeVideoTestimonialModal()"></div>
        <div class="relative w-full max-w-4xl bg-black rounded-3xl overflow-hidden shadow-2xl scale-95 flex flex-col items-center border border-white/10" id="video-modal-content">
            <!-- Close Button -->
            <button onclick="window.closeVideoTestimonialModal()" class="absolute top-4 right-4 z-[110] bg-stone-900/60 hover:bg-stone-800/80 text-white p-2 rounded-full transition active:scale-95 border border-white/10" aria-label="Tutup Video">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <!-- Video Wrapper -->
            <div id="video-modal-iframe-container" class="w-full h-full aspect-video flex items-center justify-center">
                <!-- iframe will be injected here -->
            </div>
        </div>
    </div>

    <!-- Testimonial Full Text Modal -->
    <div id="testimonial-text-modal" class="fixed inset-0 z-[100] bg-stone-950/80 backdrop-blur-md hidden opacity-0 items-center justify-center p-4" role="dialog" aria-modal="true">
        <div class="absolute inset-0 cursor-pointer" onclick="window.closeTestimonialTextModal()"></div>
        <div class="relative w-full max-w-lg bg-white rounded-3xl overflow-hidden shadow-2xl scale-95 border border-stone-100 max-h-[85vh] flex flex-col" id="testimonial-text-modal-content">
            <button onclick="window.closeTestimonialTextModal()" class="absolute top-4 right-4 z-[110] bg-stone-100 hover:bg-stone-200 text-stone-600 p-2 rounded-full transition active:scale-95" aria-label="Tutup">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <div class="p-6 sm:p-8 overflow-y-auto">
                <div id="testimonial-text-modal-video" class="relative w-full aspect-video rounded-2xl overflow-hidden mb-5 hidden">
                    <div id="testimonial-text-modal-video-container" class="w-full h-full"></div>
                </div>
                <h3 id="testimonial-text-modal-name" class="font-extrabold text-stone-900 text-base sm:text-lg pr-8"></h3>
                <p id="testimonial-text-modal-location" class="text-xs text-stone-500 mb-2"></p>
                <div id="testimonial-text-modal-rating" class="flex text-amber-500 gap-0.5 mb-4"></div>
                <p id="testimonial-text-modal-message" class="text-stone-600 text-sm leading-relaxed italic whitespace-pre-line"></p>
            </div>
        </div>
    </div>

    <script>


        // Global Modal Logic for Video Testimonials
        window.closeVideoTestimonialModal = async () => {
            const modal = document.getElementById('video-testimonial-modal');
            const modalContent = document.getElementById('video-modal-content');
            const iframeContainer = document.getElementById('video-modal-iframe-container');

            await Promise.all([
                panelOut(modal, { opacity: [1, 0] }),
                panelOut(modalContent, { scale: [1, 0.95] }),
            ]);

            modal.classList.remove('flex');
            modal.classList.add('hidden');
            iframeContainer.innerHTML = '';

            // Resume autoplay
            if (typeof window.resumeTestimonialAutoplay === 'function') {
                window.resumeTestimonialAutoplay();
            }
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

            panelIn(modal, { opacity: [0, 1] });
            panelIn(modalContent, { scale: [0.95, 1] });

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

        // Global Modal Logic for Full Testimonial Text
        window.closeTestimonialTextModal = async () => {
            const modal = document.getElementById('testimonial-text-modal');
            const modalContent = document.getElementById('testimonial-text-modal-content');

            await Promise.all([
                panelOut(modal, { opacity: [1, 0] }),
                panelOut(modalContent, { scale: [1, 0.95] }),
            ]);

            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.getElementById('testimonial-text-modal-video-container').innerHTML = '';

            if (typeof window.resumeTestimonialAutoplay === 'function') {
                window.resumeTestimonialAutoplay();
            }
        };

        window.openTestimonialTextModal = (name, location, message, rating, embedUrl) => {
            const modal = document.getElementById('testimonial-text-modal');
            const modalContent = document.getElementById('testimonial-text-modal-content');

            document.getElementById('testimonial-text-modal-name').textContent = name;
            document.getElementById('testimonial-text-modal-location').textContent = location;
            document.getElementById('testimonial-text-modal-message').textContent = message;

            const ratingEl = document.getElementById('testimonial-text-modal-rating');
            ratingEl.innerHTML = '<i data-lucide="star" class="w-3.5 h-3.5 fill-current"></i>'.repeat(Math.max(0, Math.min(5, rating)));

            const videoWrap = document.getElementById('testimonial-text-modal-video');
            const videoContainer = document.getElementById('testimonial-text-modal-video-container');
            if (embedUrl) {
                videoContainer.innerHTML = `<iframe class="w-full h-full" title="Video testimoni" src="${embedUrl}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                videoWrap.classList.remove('hidden');
            } else {
                videoContainer.innerHTML = '';
                videoWrap.classList.add('hidden');
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            panelIn(modal, { opacity: [0, 1] });
            panelIn(modalContent, { scale: [0.95, 1] });

            if (typeof window.pauseTestimonialAutoplay === 'function') {
                window.pauseTestimonialAutoplay();
            }

            if (window.lucide) {
                window.lucide.createIcons();
            }
        };

        document.addEventListener('keydown', (e) => {
            const modal = document.getElementById('testimonial-text-modal');
            if (modal && !modal.classList.contains('hidden') && e.key === 'Escape') {
                window.closeTestimonialTextModal();
            }
        });
    </script>
</body>
</html>

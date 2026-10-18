@php
    $wa_phone = preg_replace('/[^0-9]/', '', $settings['contact_whatsapp'] ?? '6281112345678');
    if (str_starts_with($wa_phone, '0')) {
        $wa_phone = '62' . substr($wa_phone, 1);
    }

    $wa_cofounder = preg_replace('/[^0-9]/', '', $settings['contact_whatsapp_cofounder'] ?? '');
    if ($wa_cofounder && str_starts_with($wa_cofounder, '0')) {
        $wa_cofounder = '62' . substr($wa_cofounder, 1);
    }
    $brochure_link = $package->brochure
        ? $package->brochure_url
        : "https://wa.me/{$wa_phone}?text=Halo%20Admin%20IZI%20Travel%2C%20saya%20tertarik%20dengan%20paket%20" . urlencode($package->name) . "%20dan%20ingin%20meminta%20brosur%20PDF%20lengkapnya.";

    $badgeClasses = match ($package->badge_color) {
        'amber' => 'bg-amber-500 text-slate-950',
        'emerald' => 'bg-emerald-600 text-white',
        'indigo' => 'bg-indigo-600 text-white',
        'rose' => 'bg-rose-600 text-white',
        default => 'bg-slate-900 text-white',
    };
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ $package->name }} - {{ $settings['site_name'] ?? 'IZI Travel' }}</title>
    <link rel="icon" type="image/png" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <meta name="description" content="Paket Umrah {{ $package->name }} - {{ $package->duration_days }} Hari, berangkat {{ $package->departure_date->locale('id')->translatedFormat('d F Y') }}. Hotel {{ $package->hotel }}, maskapai {{ $package->airline }}. Mulai Rp {{ number_format($package->price, 0, ',', '.') }}">

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
    <link rel="canonical" href="{{ !empty($settings['seo_canonical_url']) ? rtrim($settings['seo_canonical_url'], '/') . '/paket/' . $package->slug : url()->current() }}" />

    <!-- Robots Meta -->
    <meta name="robots" content="index, follow" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="{{ $package->name }} - {{ $settings['site_name'] ?? 'IZI Travel' }}" />
    <meta property="og:description" content="Paket Umrah {{ $package->name }} - {{ $package->duration_days }} Hari, berangkat {{ $package->departure_date->locale('id')->translatedFormat('d F Y') }}. Hotel {{ $package->hotel }}, maskapai {{ $package->airline }}. Mulai Rp {{ number_format($package->price, 0, ',', '.') }}" />
    <meta property="og:image" content="{{ $package->image_url }}" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="{{ url()->current() }}" />
    <meta property="twitter:title" content="{{ $package->name }} - {{ $settings['site_name'] ?? 'IZI Travel' }}" />
    <meta property="twitter:description" content="Paket Umrah {{ $package->name }} - {{ $package->duration_days }} Hari, berangkat {{ $package->departure_date->locale('id')->translatedFormat('d F Y') }}. Hotel {{ $package->hotel }}, maskapai {{ $package->airline }}. Mulai Rp {{ number_format($package->price, 0, ',', '.') }}" />
    <meta property="twitter:image" content="{{ $package->image_url }}" />

    <!-- Structured Data: Product Schema for Package -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "Product",
      "name": "{{ str_replace('"', '\"', $package->name) }}",
      "image": "{{ $package->image_url }}",
      "description": "Paket Umrah {{ str_replace('"', '\"', $package->name) }} - {{ $package->duration_days }} Hari, berangkat {{ $package->departure_date->locale('id')->translatedFormat('d F Y') }}. Akomodasi hotel {{ str_replace('"', '\"', $package->hotel) }}, penerbangan dengan maskapai {{ str_replace('"', '\"', $package->airline) }}.",
      "brand": {
        "@@type": "Brand",
        "name": "{{ $settings['site_name'] ?? 'IZI Travel' }}"
      },
      "offers": {
        "@@type": "Offer",
        "url": "{{ url()->current() }}",
        "priceCurrency": "IDR",
        "price": "{{ $package->price }}",
        "priceValidUntil": "{{ $package->departure_date ? $package->departure_date->subWeeks(2)->toIso8601String() : '' }}",
        "availability": "https://schema.org/InStock",
        "itemCondition": "https://schema.org/NewCondition"
      }
    }
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Google Fonts: Plus Jakarta Sans, Inter, & El Messiri -->
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fafaf9;
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
            background-color: #fafaf9;
            background-image: radial-gradient(rgba(3, 136, 209, 0.03) 1.2px, transparent 0), radial-gradient(rgba(3, 136, 209, 0.03) 1.2px, transparent 0);
            background-size: 28px 28px;
            background-position: 0 0, 14px 14px;
        }

        /* Hide scrollbars for Chrome, Safari, Opera, Edge, Firefox, and IE */
        .scrollbar-none::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-none {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .soft-shadow {
            box-shadow: 0 10px 30px -10px rgba(7, 30, 61, 0.03), 0 1px 3px rgba(7, 30, 61, 0.01);
        }

        .soft-shadow-hover:hover {
            box-shadow: 0 20px 40px -10px rgba(7, 30, 61, 0.06), 0 1px 3px rgba(7, 30, 61, 0.01);
            transform: translateY(-2px);
        }

        @keyframes fade-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up {
            animation: fade-up 0.6s ease-out both;
        }
        .animate-fade-up-delay-1 { animation-delay: 0.1s; }
        .animate-fade-up-delay-2 { animation-delay: 0.2s; }
        .animate-fade-up-delay-3 { animation-delay: 0.3s; }
        .animate-fade-up-delay-4 { animation-delay: 0.4s; }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .shimmer-badge {
            background: linear-gradient(90deg, transparent 30%, rgba(255,255,255,0.25) 50%, transparent 70%);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-stone-50 text-slate-800 overflow-x-hidden antialiased izi-public">
    <!-- BEGIN: Floating Modern Header -->
    @include('public.partials.navigation')

    <!-- Header Section -->
    <div class="pt-32 pb-10 bg-gradient-to-b from-slate-50 to-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex text-[10px] font-bold text-slate-400 gap-2 items-center tracking-wider uppercase mb-5" aria-label="Breadcrumb">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition duration-150">Beranda</a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-slate-350"></i>
                <a href="{{ url('/#paket-umrah') }}" class="hover:text-blue-600 transition duration-150">Paket Umrah</a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-slate-350"></i>
                <span class="text-slate-600 font-extrabold truncate">{{ $package->name }}</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                <div class="space-y-3 max-w-4xl">
                    @if ($package->badge_label)
                        <span class="inline-flex items-center gap-1.5 {{ $badgeClasses }} text-[9px] font-black px-3.5 py-1.5 rounded-full uppercase tracking-widest shadow-sm">
                            <i data-lucide="sparkles" class="w-3 h-3"></i>
                            {{ $package->badge_label }}
                        </span>
                    @endif
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 leading-tight tracking-tight">
                        {{ $package->name }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-y-2 gap-x-4 text-slate-500 text-xs font-bold pt-1">
                        <span class="inline-flex items-center gap-1.5 bg-slate-100/80 px-3 py-1.5 rounded-lg">
                            <i data-lucide="clock" class="w-3.5 h-3.5 text-blue-600"></i>
                            {{ $package->duration_days }} Hari Perjalanan
                        </span>
                        <span class="inline-flex items-center gap-1.5 bg-slate-100/80 px-3 py-1.5 rounded-lg">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-blue-600"></i>
                            Keberangkatan: {{ $package->departure_date->locale('id')->translatedFormat('d F Y') }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 bg-slate-100/80 px-3 py-1.5 rounded-lg">
                            <i data-lucide="plane" class="w-3.5 h-3.5 text-blue-600"></i>
                            {{ $package->airline }}
                        </span>
                    </div>
                </div>

                <!-- Price Highlight for Mobile only -->
                <div class="lg:hidden w-full bg-blue-50/50 border border-blue-100/80 rounded-2xl p-5 mt-2 flex justify-between items-center">
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Estimasi Harga Paket</p>
                        <p class="text-2xl font-black text-blue-600 mt-1">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                        <p class="text-[10px] text-slate-400 font-medium">per jamaah · belum termasuk biaya tambahan</p>
                    </div>
                    <span class="bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl uppercase tracking-wider">
                        Harga Terbaik
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <main id="main-content" class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto islamic-pattern py-12 pb-24 relative z-10">

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">

            <!-- Left: Package Info (Col 8) -->
            <div class="lg:col-span-8 space-y-8 animate-fade-up">

                <!-- Main Image Card -->
                <div class="relative rounded-3xl overflow-hidden shadow-xl border border-slate-200 bg-white">
                    <img src="{{ $package->image_url }}" alt="{{ $package->name }}" class="w-full h-[300px] sm:h-[450px] md:h-[500px] object-cover" width="1200" height="500" fetchpriority="high" decoding="async" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/35 via-black/5 to-transparent"></div>
                    <div class="absolute bottom-5 left-5 right-5 text-white/90 text-xs font-semibold flex items-center justify-between pointer-events-none">
                        <span>Rute Perjalanan Resmi IZI Travel</span>
                        <span class="bg-black/40 backdrop-blur px-3 py-1.5 rounded-full flex items-center gap-1.5 text-[10px] uppercase font-bold tracking-wider">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-400"></i>
                            Pasti Berangkat
                        </span>
                    </div>
                </div>
                <!-- Informasi Perjalanan -->
                <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-xl shadow-slate-900/5 space-y-6">
                    <h3 class="text-slate-900 font-extrabold text-sm uppercase tracking-wider flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600 shadow-sm shadow-blue-500/50"></span> Informasi Perjalanan
                    </h3>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <!-- Tanggal -->
                        <div class="flex items-start gap-3 bg-slate-50/50 p-4 rounded-2xl border border-slate-100/50 min-w-0">
                            <span class="bg-blue-600/10 text-blue-600 p-2.5 rounded-xl border border-blue-600/10 flex items-center justify-center flex-shrink-0"><i data-lucide="calendar" class="w-5 h-5"></i></span>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider mb-1">Tanggal</p>
                                <p class="text-xs sm:text-sm font-extrabold text-slate-800 leading-tight">{{ $package->departure_date->locale('id')->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                        <!-- Durasi -->
                        <div class="flex items-start gap-3 bg-slate-50/50 p-4 rounded-2xl border border-slate-100/50 min-w-0">
                            <span class="bg-blue-600/10 text-blue-600 p-2.5 rounded-xl border border-blue-600/10 flex items-center justify-center flex-shrink-0"><i data-lucide="clock" class="w-5 h-5"></i></span>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider mb-1">Durasi</p>
                                <p class="text-xs sm:text-sm font-extrabold text-slate-800 leading-tight">{{ $package->duration_days }} Hari</p>
                            </div>
                        </div>
                        <!-- Maskapai -->
                        <div class="flex items-start gap-3 bg-slate-50/50 p-4 rounded-2xl border border-slate-100/50 min-w-0">
                            <span class="bg-blue-600/10 text-blue-600 p-2.5 rounded-xl border border-blue-600/10 flex items-center justify-center flex-shrink-0"><i data-lucide="plane" class="w-5 h-5"></i></span>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider mb-1">Maskapai</p>
                                <p class="text-xs sm:text-sm font-extrabold text-slate-800 leading-tight truncate">{{ $package->airline }}</p>
                            </div>
                        </div>
                        <!-- Start -->
                        <div class="flex items-start gap-3 bg-slate-50/50 p-4 rounded-2xl border border-slate-100/50 min-w-0">
                            <span class="bg-blue-600/10 text-blue-600 p-2.5 rounded-xl border border-blue-600/10 flex items-center justify-center flex-shrink-0"><i data-lucide="map-pin" class="w-5 h-5"></i></span>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider mb-1">Start</p>
                                <p class="text-xs sm:text-sm font-extrabold text-slate-800 leading-tight">{{ $package->start_city ?: 'Jakarta' }}</p>
                            </div>
                        </div>
                        <!-- Pembimbing -->
                        <div class="flex items-start gap-3 bg-slate-50/50 p-4 rounded-2xl border border-slate-100/50 min-w-0 col-span-2 sm:col-span-1">
                            <span class="bg-blue-600/10 text-blue-600 p-2.5 rounded-xl border border-blue-600/10 flex items-center justify-center flex-shrink-0"><i data-lucide="user-check" class="w-5 h-5"></i></span>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider mb-1">Pembimbing</p>
                                <p class="text-xs sm:text-sm font-extrabold text-slate-800 leading-tight">{{ $package->pembimbing ?: 'Muthawwif IZI Travel' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Itinerary Perjalanan -->
                @if (!empty($package->itinerary))
                <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-xl shadow-slate-900/5 space-y-6">
                    <h3 class="text-slate-900 font-extrabold text-sm uppercase tracking-wider flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <span class="w-2.5 h-2.5 rounded-full bg-teal-600 shadow-sm shadow-teal-500/50"></span> Itinerary Perjalanan
                    </h3>

                    <div class="space-y-5">
                        @foreach ($package->itinerary as $day)
                            <div class="flex items-start gap-4">
                                <div class="flex flex-col items-center shrink-0">
                                    <span class="w-10 h-10 rounded-full bg-teal-600 text-white flex items-center justify-center text-xs font-black shadow-sm">
                                        {{ $day['day'] ?? ($loop->iteration) }}
                                    </span>
                                    @if (!$loop->last)
                                        <span class="w-px flex-1 bg-teal-200 mt-2"></span>
                                    @endif
                                </div>
                                <div class="min-w-0 pb-1">
                                    <p class="text-[10px] text-teal-600 font-extrabold uppercase tracking-wider mb-1">
                                        Hari ke-{{ $day['day'] ?? $loop->iteration }}
                                        <span class="text-slate-400 normal-case font-bold">&middot; {{ $package->departure_date->copy()->addDays(($day['day'] ?? $loop->iteration) - 1)->locale('id')->translatedFormat('l, d F Y') }}</span>
                                    </p>
                                    <h4 class="text-sm font-black text-slate-800 leading-snug">{{ $day['title'] ?? '' }}</h4>
                                    @if (!empty($day['desc']))
                                        <p class="text-xs text-slate-500 font-medium mt-1 leading-relaxed">{{ $day['desc'] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Akomodasi Hotel -->
                <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-xl shadow-slate-900/5 space-y-6">
                    <h3 class="text-slate-900 font-extrabold text-sm uppercase tracking-wider flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-600 shadow-sm shadow-amber-500/50"></span> Akomodasi Hotel
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Hotel Makkah -->
                        <div class="flex flex-col gap-4 p-5 rounded-2xl border border-amber-100/80 bg-amber-50/20 relative overflow-hidden group hover:bg-amber-50/40 transition duration-300">
                            <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-amber-500/5 rounded-full blur-xl pointer-events-none"></div>
                            <div class="flex items-center gap-3">
                                <span class="bg-amber-100 text-amber-700 p-3 rounded-xl flex items-center justify-center shrink-0">
                                    <i data-lucide="hotel" class="w-5 h-5"></i>
                                </span>
                                <div>
                                    <h4 class="text-xs font-extrabold text-amber-800 uppercase tracking-widest leading-none mb-1">Hotel Makkah</h4>
                                    @if ($package->hotel_makkah_nights)
                                        <span class="text-[10px] font-bold text-slate-400">{{ $package->hotel_makkah_nights }} Malam</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <p class="text-base font-black text-slate-800 leading-snug">{{ $package->hotel_makkah ?: ($package->hotel ?: 'Hotel Bintang Pilihan') }}</p>                                @php
                                    $makkahDistance = $package->hotel_makkah_distance;
                                    if (empty($makkahDistance)) {
                                        preg_match('/(\d+)\s*m/i', $package->hotel_makkah, $makkahDist);
                                        $makkahDistance = isset($makkahDist[0]) ? '± ' . $makkahDist[0] . ' ke Masjidil Haram' : '± 680 m ke Masjidil Haram';
                                    } else {
                                        if (!str_contains($makkahDistance, 'Masjidil') && !str_contains($makkahDistance, 'Haram')) {
                                            $makkahDistance = '± ' . $makkahDistance . ' ke Masjidil Haram';
                                        }
                                    }
                                @endphp
                                <div class="flex items-center gap-1.5 mt-2 text-xs font-bold text-slate-500">
                                    <i data-lucide="footprints" class="w-4 h-4 text-amber-500"></i>
                                    <span>Estimasi Jarak: {{ $makkahDistance }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Hotel Madinah -->
                        <div class="flex flex-col gap-4 p-5 rounded-2xl border border-emerald-100/80 bg-emerald-50/20 relative overflow-hidden group hover:bg-emerald-50/40 transition duration-300">
                            <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-emerald-500/5 rounded-full blur-xl pointer-events-none"></div>
                            <div class="flex items-center gap-3">
                                <span class="bg-emerald-100 text-emerald-700 p-2.5 rounded-xl flex items-center justify-center shrink-0">
                                    <i data-lucide="building-2" class="w-5 h-5"></i>
                                </span>
                                <div>
                                    <h4 class="text-xs font-extrabold text-emerald-800 uppercase tracking-widest leading-none mb-1">Hotel Madinah</h4>
                                    @if ($package->hotel_madinah_nights)
                                        <span class="text-[10px] font-bold text-slate-400">{{ $package->hotel_madinah_nights }} Malam</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <p class="text-base font-black text-slate-800 leading-snug">{{ $package->hotel_madinah ?: ($package->hotel ?: 'Hotel Bintang Pilihan') }}</p>
                                @php
                                    $madinahDistance = $package->hotel_madinah_distance;
                                    if (empty($madinahDistance)) {
                                        preg_match('/(\d+)\s*m/i', $package->hotel_madinah, $madinahDist);
                                        $madinahDistance = isset($madinahDist[0]) ? '± ' . $madinahDist[0] . ' ke Masjid Nabawi' : '± 250 m ke Masjid Nabawi';
                                    } else {
                                        if (!str_contains($madinahDistance, 'Nabawi')) {
                                            $madinahDistance = '± ' . $madinahDistance . ' ke Masjid Nabawi';
                                        }
                                    }
                                @endphp
                                <div class="flex items-center gap-1.5 mt-2 text-xs font-bold text-slate-500">
                                    <i data-lucide="footprints" class="w-4 h-4 text-emerald-500"></i>
                                    <span>Estimasi Jarak: {{ $madinahDistance }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fitur Paket -->
                <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-xl shadow-slate-900/5 space-y-6">
                    <h3 class="text-slate-900 font-extrabold text-sm uppercase tracking-wider flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600 shadow-sm shadow-blue-500/50"></span> Fitur Paket
                    </h3>

                    <div class="flex flex-wrap gap-3">
                        @php
                            $features = $package->features ?? [
                                ['label' => 'Kereta Cepat', 'icon' => 'train-front', 'color' => 'indigo'],
                                ['label' => 'Direct Flight', 'icon' => 'plane-takeoff', 'color' => 'blue'],
                                ['label' => 'Free Perlengkapan', 'icon' => 'gift', 'color' => 'amber'],
                            ];
                        @endphp
                        @foreach ($features as $f)
                            @php
                                $color = $f['color'] ?? 'blue';
                                $colClass = match ($color) {
                                    'indigo' => 'border-indigo-100 bg-indigo-50/50 text-indigo-750',
                                    'blue' => 'border-blue-100 bg-blue-50/50 text-blue-750',
                                    'amber' => 'border-amber-100 bg-amber-50/50 text-amber-750',
                                    default => 'border-slate-100 bg-slate-50/50 text-slate-700',
                                };
                                $icoColor = "text-" . $color . "-600";
                            @endphp
                            <div class="inline-flex items-center gap-2.5 px-4.5 py-3 rounded-2xl border {{ $colClass }} text-xs font-black shadow-sm">
                                <i data-lucide="{{ $f['icon'] ?? 'sparkles' }}" class="w-4.5 h-4.5 {{ $icoColor }}"></i>
                                <span>{{ $f['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Rincian Biaya -->
                <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-xl shadow-slate-900/5 space-y-6">
                    <h3 class="text-slate-900 font-extrabold text-sm uppercase tracking-wider flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 shadow-sm shadow-emerald-500/50"></span> Rincian Biaya
                    </h3>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Biaya Termasuk -->
                        <div class="space-y-4">
                            <h4 class="text-xs sm:text-sm font-black text-emerald-700 flex items-center gap-2.5">
                                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-605"></i>
                                Biaya Termasuk
                            </h4>
                            <div class="grid grid-cols-1 gap-3">
                                @php
                                    $inclusions = $package->inclusions ?? [
                                        ['icon' => 'plane', 'label' => 'Tiket pesawat PP', 'desc' => 'Penerbangan pulang-pergi dengan maskapai terpercaya'],
                                        ['icon' => 'file-check', 'label' => 'Pengurusan visa resmi', 'desc' => 'Visa umrah resmi yang diproses langsung'],
                                        ['icon' => 'building-2', 'label' => 'Hotel pelataran masjid', 'desc' => 'Akomodasi dekat Masjidil Haram & Nabawi'],
                                        ['icon' => 'shield-check', 'label' => 'Asuransi perjalanan', 'desc' => 'Perlindungan selama perjalanan ibadah'],
                                        ['icon' => 'luggage', 'label' => 'Perlengkapan ibadah', 'desc' => 'Koper, kain ihram, buku doa, & lainnya'],
                                        ['icon' => 'compass', 'label' => 'Muthawwif bersertifikat', 'desc' => 'Pembimbing ibadah profesional & berpengalaman'],
                                        ['icon' => 'utensils', 'label' => 'Makan prasmanan 3x', 'desc' => 'Menu halal & beragam setiap hari'],
                                        ['icon' => 'book-open', 'label' => 'Handling & manasik', 'desc' => 'Bimbingan manasik pra-keberangkatan'],
                                    ];
                                @endphp
                                @foreach ($inclusions as $item)
                                    <div class="flex items-start gap-3.5 p-3.5 rounded-2xl border border-slate-100 bg-slate-50/40 hover:bg-emerald-50/30 hover:border-emerald-200 transition-all duration-300 group">
                                        <span class="bg-emerald-50 text-emerald-600 rounded-xl p-2.5 shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition duration-300 flex items-center justify-center">
                                            <i data-lucide="{{ $item['icon'] ?? 'check' }}" class="w-4.5 h-4.5"></i>
                                        </span>
                                        <div class="min-w-0">
                                            <h4 class="text-xs sm:text-sm font-black text-slate-800 transition leading-snug">{{ $item['label'] }}</h4>
                                            @if (!empty($item['desc']))
                                                <p class="text-[10px] sm:text-[11px] text-slate-400 font-semibold mt-1 leading-relaxed">{{ $item['desc'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Belum Termasuk -->
                        <div class="space-y-4">
                            <h4 class="text-xs sm:text-sm font-black text-rose-700 flex items-center gap-2.5">
                                <i data-lucide="x-circle" class="w-5 h-5 text-rose-605"></i>
                                Belum Termasuk
                            </h4>
                            <div class="grid grid-cols-1 gap-3">
                                @php
                                    $exclusions = $package->exclusions ?? [
                                        ['icon' => 'syringe', 'label' => 'Vaksin Meningitis', 'desc' => 'Persyaratan wajib kesehatan dari dinas terkait'],
                                        ['icon' => 'weight', 'label' => 'Kelebihan Bagasi', 'desc' => 'Sesuai dengan ketentuan berat maksimal dari maskapai'],
                                        ['icon' => 'wallet', 'label' => 'Pengeluaran Pribadi', 'desc' => 'Membeli oleh-oleh, laundry, telepon, dll.'],
                                        ['icon' => 'file-text', 'label' => 'Pembuatan Paspor', 'desc' => 'Dokumen resmi perjalanan luar negeri jemaah'],
                                    ];
                                @endphp
                                @foreach ($exclusions as $item)
                                    <div class="flex items-start gap-3.5 p-3.5 rounded-2xl border border-slate-100 bg-slate-50/40 hover:bg-rose-50/30 hover:border-rose-200 transition-all duration-300 group">
                                        <span class="bg-rose-50 text-rose-600 rounded-xl p-2.5 shrink-0 group-hover:bg-rose-600 group-hover:text-white transition duration-300 flex items-center justify-center">
                                            <i data-lucide="{{ $item['icon'] ?? 'x-circle' }}" class="w-4.5 h-4.5"></i>
                                        </span>
                                        <div class="min-w-0">
                                            <h4 class="text-xs sm:text-sm font-black text-slate-800 transition leading-snug">{{ $item['label'] }}</h4>
                                            @if (!empty($item['desc']))
                                                <p class="text-[10px] sm:text-[11px] text-slate-400 font-semibold mt-1 leading-relaxed">{{ $item['desc'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Opsi Harga -->
                <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-xl shadow-slate-900/5 space-y-6">
                    <h3 class="text-slate-900 font-extrabold text-sm uppercase tracking-wider flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 shadow-sm shadow-emerald-500/50"></span> Opsi Harga
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Quad -->
                        <div class="flex flex-col justify-between p-5 rounded-2xl border border-slate-200 bg-white shadow-sm relative overflow-hidden group hover:border-blue-600/30 transition duration-300">
                            <div>
                                <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-widest leading-none mb-2">Quad (Sekamar Berempat)</p>
                                <p class="text-xl font-black text-slate-900">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-3 font-medium">Kamar untuk 4 orang jamaah</p>
                        </div>

                        <!-- Triple -->
                        <div class="flex flex-col justify-between p-5 rounded-2xl border border-slate-200 bg-white shadow-sm relative overflow-hidden group hover:border-blue-600/30 transition duration-300">
                            <div>
                                <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-widest leading-none mb-2">Triple (Sekamar Bertiga)</p>
                                <p class="text-xl font-black text-slate-900">Rp {{ number_format($package->price_triple ?: ($package->price + 1500000), 0, ',', '.') }}</p>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-3 font-medium">Kamar untuk 3 orang jamaah</p>
                        </div>

                        <!-- Double -->
                        <div class="flex flex-col justify-between p-5 rounded-2xl border border-slate-200 bg-white shadow-sm relative overflow-hidden group hover:border-blue-600/30 transition duration-300">
                            <div>
                                <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-widest leading-none mb-2">Double (Sekamar Berdua)</p>
                                <p class="text-xl font-black text-slate-900">Rp {{ number_format($package->price_double ?: ($package->price + 3000000), 0, ',', '.') }}</p>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-3 font-medium">Kamar untuk 2 orang jamaah</p>
                        </div>
                    </div>
                </div>

                <!-- Brochure Image -->
                @if($package->brochure_image_url)
                <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-xl shadow-slate-900/5">
                    <h3 class="text-slate-900 font-extrabold text-sm uppercase tracking-wider flex items-center gap-2.5 mb-6 pb-4 border-b border-slate-100">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-600 shadow-sm shadow-amber-500/50"></span> Brosur Program Resmi
                    </h3>
                    <div class="flex justify-center">
                        <img src="{{ $package->brochure_image_url }}" alt="Brosur {{ $package->name }}" class="max-w-full h-auto rounded-2xl border border-slate-200 shadow-lg" style="max-height: 600px;" width="600" height="800" loading="lazy" decoding="async" />
                    </div>
                </div>
                @endif

                <!-- Important Info -->
                <div class="bg-amber-50/40 border border-amber-200/60 rounded-3xl p-6 sm:p-8 shadow-sm">
                    <h3 class="text-amber-800 font-extrabold text-sm uppercase tracking-wider flex items-center gap-2.5 mb-5">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shadow-sm shadow-amber-500/55"></span> Informasi Penting &amp; Ketentuan
                    </h3>
                    @php
                        $noteIcons = ['info', 'alert-circle', 'credit-card'];
                        $notes = [
                            $settings['package_note_1'] ?? 'Harga dapat berubah sewaktu-waktu mengikuti kurs & ketersediaan kuota. Hubungi tim kami untuk ketersediaan seat terbaru dan skema pembayaran.',
                            $settings['package_note_2'] ?? 'Pendaftaran ditutup paling lambat 3 minggu sebelum keberangkatan atau saat kuota habis.',
                            $settings['package_note_3'] ?? 'Tersedia skema cicilan hingga 12x tanpa bunga. Hubungi admin kami untuk info lebih lanjut.',
                        ];
                    @endphp
                    <div class="grid grid-cols-1 gap-3.5">
                        @foreach ($notes as $k => $note)
                            @if ($note)
                            <div class="flex items-start gap-4 p-4 bg-white border border-amber-100 rounded-2xl text-slate-700 hover:border-amber-300 transition duration-200">
                                <span class="bg-amber-50 text-amber-600 p-2.5 rounded-xl flex items-center justify-center shrink-0">
                                    <i data-lucide="{{ $noteIcons[$k] }}" class="w-5 h-5"></i>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-700 leading-relaxed">{{ $note }}</p>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- CTA Buttons (Mobile-only, visible below info block) -->
                <div class="lg:hidden bg-white rounded-3xl border border-slate-100 p-6 shadow-lg shadow-slate-900/5 space-y-3 relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-blue-500/5 rounded-full blur-xl pointer-events-none"></div>
                    <a href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel%2C%20saya%20tertarik%20dengan%20paket%20{{ urlencode($package->name) }}" target="_blank" rel="noopener" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-extrabold transition-all duration-200 text-xs text-center flex items-center justify-center gap-2 active:scale-95 shadow-md shadow-blue-500/10">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
                        </svg>
                        Pesan via WhatsApp
                    </a>
                    <a href="{{ $brochure_link }}" target="_blank" class="w-full bg-amber-50 hover:bg-amber-100 text-amber-800 py-3 rounded-xl font-extrabold border-2 border-amber-300 transition-all duration-200 text-xs text-center flex items-center justify-center gap-2 active:scale-95">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        <span>{{ $package->brochure ? 'Unduh Brosur PDF' : 'Minta Brosur PDF' }}</span>
                    </a>
                    <a href="{{ route('public.jemaah.tracking') }}" class="w-full bg-blue-50 hover:bg-blue-100 text-blue-800 py-3 rounded-xl font-extrabold border-2 border-blue-300 transition-all duration-200 text-xs text-center flex items-center justify-center gap-2 active:scale-95">
                        <i data-lucide="search-check" class="w-4 h-4"></i>
                        <span>Sudah Terdaftar? Cek Status Keberangkatan</span>
                    </a>
                </div>

            </div>

            <!-- Right: Sidebar (Col 4) -->
            <aside class="lg:col-span-4 space-y-6 animate-fade-up animate-fade-up-delay-2">

                <!-- Sticky Sidebar Wrapper -->
                <div class="lg:sticky lg:top-28 space-y-6">

                    <!-- Luxury Booking Widget Card -->
                    <div class="hidden lg:block bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-xl shadow-slate-900/5 relative overflow-hidden">
                        <!-- Bottom subtle glow backdrop -->
                        <div class="absolute -right-10 -bottom-10 w-36 h-36 bg-blue-500/5 rounded-full blur-2xl pointer-events-none"></div>

                        <div class="relative z-10 space-y-6">
                            <!-- Price Tag -->
                            <div class="space-y-1 pb-4 border-b border-slate-100">
                                <p class="text-[9px] text-slate-400 font-extrabold uppercase tracking-widest">Estimasi Harga Paket</p>
                                <div class="flex items-baseline gap-1 mt-1">
                                    <span class="text-3xl font-black text-slate-900 tracking-tight">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                    <span class="text-xs text-slate-400 font-bold">/ pax</span>
                                </div>
                                <p class="text-[10px] text-slate-400 font-medium">per jamaah · belum termasuk biaya tambahan</p>
                            </div>

                            <!-- Highlights checklist -->
                            <div class="space-y-3.5">
                                <div class="flex items-center gap-2.5 text-xs text-slate-600 font-bold">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-500 bg-emerald-50 rounded-full p-0.5"></i>
                                    <span>Pasti Berangkat &amp; Visa Terjamin</span>
                                </div>
                                <div class="flex items-center gap-2.5 text-xs text-slate-600 font-bold">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-500 bg-emerald-50 rounded-full p-0.5"></i>
                                    <span>Akomodasi Hotel Bintang Pilihan</span>
                                </div>
                                <div class="flex items-center gap-2.5 text-xs text-slate-600 font-bold">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-500 bg-emerald-50 rounded-full p-0.5"></i>
                                    <span>Bimbingan Manasik sesuai Sunnah</span>
                                </div>
                            </div>

                            <!-- Action CTA Buttons -->
                            <div class="space-y-3">
                                <a href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel%2C%20saya%20tertarik%20dengan%20paket%20{{ urlencode($package->name) }}" target="_blank" rel="noopener" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-extrabold transition-all duration-200 text-xs text-center flex items-center justify-center gap-2 active:scale-95 shadow-md shadow-blue-500/10">
                                    <svg class="w-5 h-5 fill-current flex-shrink-0" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
                                    </svg>
                                    Pesan via WhatsApp
                                </a>
                                <a href="{{ $brochure_link }}" target="_blank" class="w-full bg-amber-50 hover:bg-amber-100 text-amber-800 py-3 rounded-xl font-extrabold border-2 border-amber-300 transition-all duration-200 text-xs text-center flex items-center justify-center gap-2 active:scale-95">
                                    <i data-lucide="file-text" class="w-4 h-4 flex-shrink-0"></i>
                                    <span>{{ $package->brochure ? 'Unduh Brosur PDF' : 'Minta Brosur PDF' }}</span>
                                </a>
                                <a href="{{ route('public.jemaah.tracking') }}" class="w-full bg-blue-50 hover:bg-blue-100 text-blue-800 py-3 rounded-xl font-extrabold border-2 border-blue-300 transition-all duration-200 text-xs text-center flex items-center justify-center gap-2 active:scale-95">
                                    <i data-lucide="search-check" class="w-4 h-4 flex-shrink-0"></i>
                                    <span>Sudah Terdaftar? Cek Status Keberangkatan</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Summary Card -->
                    <div class="bg-white rounded-3xl border border-slate-150 p-6 shadow-xl shadow-slate-900/5">
                        <h3 class="font-extrabold text-slate-900 text-sm mb-5 flex items-center gap-2 border-b border-slate-50 pb-4">
                            <i data-lucide="list-checks" class="w-4 h-4 text-blue-600"></i>
                            Ringkasan Paket
                        </h3>
                        <div class="space-y-3.5">
                            <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                                <span class="text-xs text-slate-400 font-bold">Nama Paket</span>
                                <span class="text-xs font-bold text-slate-800 text-right max-w-[60%] truncate">{{ $package->name }}</span>
                            </div>
                            <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                                <span class="text-xs text-slate-400 font-bold">Durasi Perjalanan</span>
                                <span class="text-xs font-bold text-slate-800">{{ $package->duration_days }} Hari</span>
                            </div>
                            <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                                <span class="text-xs text-slate-400 font-bold">Keberangkatan</span>
                                <span class="text-xs font-bold text-slate-800">{{ $package->departure_date->locale('id')->translatedFormat('d M Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                                <span class="text-xs text-slate-400 font-bold">Maskapai</span>
                                <span class="text-xs font-bold text-slate-800">{{ $package->airline }}</span>
                            </div>
                            @if ($package->hotel_makkah)
                            <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                                <span class="text-xs text-slate-400 font-bold">Hotel Makkah</span>
                                <span class="text-xs font-bold text-slate-800 text-right max-w-[60%] truncate">
                                    {{ $package->hotel_makkah }}
                                    @if ($package->hotel_makkah_nights)
                                        <span class="text-amber-600">({{ $package->hotel_makkah_nights }}M)</span>
                                    @endif
                                </span>
                            </div>
                            @endif
                            @if ($package->hotel_madinah)
                            <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                                <span class="text-xs text-slate-400 font-bold">Hotel Madinah</span>
                                <span class="text-xs font-bold text-slate-800 text-right max-w-[60%] truncate">
                                    {{ $package->hotel_madinah }}
                                    @if ($package->hotel_madinah_nights)
                                        <span class="text-emerald-600">({{ $package->hotel_madinah_nights }}M)</span>
                                    @endif
                                </span>
                            </div>
                            @endif
                            @if (!$package->hotel_makkah && !$package->hotel_madinah && $package->hotel)
                            <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                                <span class="text-xs text-slate-400 font-bold">Hotel</span>
                                <span class="text-xs font-bold text-slate-800 text-right max-w-[60%] truncate">{{ $package->hotel }}</span>
                            </div>
                            @endif
                            <div class="flex items-center justify-between py-1.5">
                                <span class="text-xs text-slate-400 font-bold">Harga Mulai</span>
                                <span class="text-sm font-black text-blue-600">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Badges -->
                    <div class="bg-white rounded-3xl border border-slate-150 p-6 shadow-xl shadow-slate-900/5">
                        <h3 class="font-extrabold text-slate-900 text-sm mb-5 flex items-center gap-2 border-b border-slate-50 pb-4">
                            <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600"></i>
                            Jaminan Keamanan
                        </h3>
                        <div class="space-y-3.5">
                            @foreach ([
                                ['icon' => 'award', 'color' => 'emerald', 'text' => 'Izin PPIU Resmi Kemenag RI'],
                                ['icon' => 'lock', 'color' => 'blue', 'text' => 'Pembayaran Aman & Terjamin'],
                                ['icon' => 'headphones', 'color' => 'amber', 'text' => 'Support 24 Jam Non-Stop'],
                                ['icon' => 'heart-handshake', 'color' => 'rose', 'text' => 'Garansi Pelayanan Prima'],
                            ] as $badge)
                                <div class="flex items-center gap-3">
                                    <span class="bg-{{ $badge['color'] }}-50 text-{{ $badge['color'] }}-600 p-2 rounded-lg shrink-0">
                                        <i data-lucide="{{ $badge['icon'] }}" class="w-4.5 h-4.5"></i>
                                    </span>
                                    <p class="text-xs font-bold text-slate-700">{{ $badge['text'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </aside>
        </div>
    </main>

    <!-- BEGIN: Footer -->
    @include('public.partials.footer')





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
    <script>
        // Init Lucide
        if (window.lucide) {
            window.lucide.createIcons();
        }




    </script>
</body>

</html>

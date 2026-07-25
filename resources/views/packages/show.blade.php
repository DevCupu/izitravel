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
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/#beranda') }}">Beranda</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/#tentang-kami') }}">Tentang Kami</a>
                <a class="text-blue-600 bg-blue-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/#paket-umrah') }}">Paket Umrah</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/#galeri') }}">Galeri</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/#testimoni') }}">Testimoni</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/#artikel') }}">Artikel</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/#kemitraan') }}">Kemitraan</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/#kontak') }}">Kontak</a>
            </div>
            
            <div class="flex items-center gap-2">
                <a class="bg-blue-600 text-white px-3.5 py-2.5 sm:px-5 sm:py-2.5 rounded-full font-bold hover:bg-blue-700 transition-all duration-300 flex items-center gap-2 text-xs shadow-md shadow-blue-500/10 active:scale-95" href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
                    </svg>
                    <span class="hidden sm:inline">Konsultasi/WhatsApp</span>
                </a>
            </div>
        </nav>
    </header>
    <!-- END: Floating Header -->

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
    <main class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto islamic-pattern py-12 pb-24 relative z-10">
        
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

                <!-- Facilities Grid -->
                <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-xl shadow-slate-900/5">
                    <h3 class="text-slate-900 font-extrabold text-sm uppercase tracking-wider flex items-center gap-2.5 mb-6 pb-4 border-b border-slate-100">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600 shadow-sm shadow-blue-500/50"></span> Akomodasi &amp; Spesifikasi Penerbangan
                    </h3>

                    @php
                        $hasHotelDetail = $package->hotel_makkah || $package->hotel_madinah;
                        $gridCols = $hasHotelDetail ? 'grid-cols-1 sm:grid-cols-2' : 'grid-cols-1 sm:grid-cols-3';
                    @endphp
                    <div class="grid {{ $gridCols }} gap-4">
                        <!-- Departure Date -->
                        <div class="flex items-center gap-4 bg-slate-50 border border-slate-100 p-4.5 rounded-2xl hover:bg-slate-100/55 hover:border-blue-200 transition duration-200 group">
                            <span class="bg-blue-50 text-blue-600 p-3 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition duration-300">
                                <i data-lucide="calendar" class="w-5 h-5"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Tanggal Keberangkatan</p>
                                <p class="text-sm font-extrabold text-slate-800 mt-1 truncate">{{ $package->departure_date->locale('id')->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>

                        <!-- Airline -->
                        <div class="flex items-center gap-4 bg-slate-50 border border-slate-100 p-4.5 rounded-2xl hover:bg-slate-100/55 hover:border-blue-200 transition duration-200 group">
                            <span class="bg-blue-50 text-blue-600 p-3 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition duration-300">
                                <i data-lucide="plane" class="w-5 h-5"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Maskapai &amp; Penerbangan</p>
                                <p class="text-sm font-extrabold text-slate-800 mt-1 truncate">{{ $package->airline }}</p>
                            </div>
                        </div>

                        @if ($hasHotelDetail)
                            <!-- Hotel Makkah -->
                            @if ($package->hotel_makkah)
                            <div class="flex items-center gap-4 bg-amber-50/40 border border-amber-100/50 p-4.5 rounded-2xl hover:bg-amber-50/80 hover:border-amber-300 transition duration-200 group">
                                <span class="bg-amber-100 text-amber-700 p-3 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-amber-500 group-hover:text-white transition duration-300">
                                    <i data-lucide="hotel" class="w-5 h-5"></i>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-[9px] text-amber-600 font-bold uppercase tracking-wider flex items-center gap-1">
                                        Hotel Makkah
                                        @if ($package->hotel_makkah_nights)
                                            <span class="bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded text-[8px] font-black">{{ $package->hotel_makkah_nights }} Malam</span>
                                        @endif
                                    </p>
                                    <p class="text-sm font-extrabold text-slate-800 mt-1 truncate">{{ $package->hotel_makkah }}</p>
                                </div>
                            </div>
                            @endif

                            <!-- Hotel Madinah -->
                            @if ($package->hotel_madinah)
                            <div class="flex items-center gap-4 bg-emerald-50/40 border border-emerald-100/50 p-4.5 rounded-2xl hover:bg-emerald-50/80 hover:border-emerald-300 transition duration-200 group">
                                <span class="bg-emerald-100 text-emerald-700 p-3 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-emerald-500 group-hover:text-white transition duration-300">
                                    <i data-lucide="building-2" class="w-5 h-5"></i>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-[9px] text-emerald-600 font-bold uppercase tracking-wider flex items-center gap-1">
                                        Hotel Madinah
                                        @if ($package->hotel_madinah_nights)
                                            <span class="bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded text-[8px] font-black">{{ $package->hotel_madinah_nights }} Malam</span>
                                        @endif
                                    </p>
                                    <p class="text-sm font-extrabold text-slate-800 mt-1 truncate">{{ $package->hotel_madinah }}</p>
                                </div>
                            </div>
                            @endif
                        @else
                            <!-- Hotel (fallback single field) -->
                            @if ($package->hotel)
                            <div class="flex items-center gap-4 bg-slate-50 border border-slate-100 p-4.5 rounded-2xl hover:bg-slate-100/55 hover:border-blue-200 transition duration-200 group">
                                <span class="bg-blue-50 text-blue-600 p-3 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition duration-300">
                                    <i data-lucide="hotel" class="w-5 h-5"></i>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Hotel Pilihan</p>
                                    <p class="text-sm font-extrabold text-slate-800 mt-1 truncate">{{ $package->hotel }}</p>
                                </div>
                            </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- What's Included -->
                <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-8 shadow-xl shadow-slate-900/5">
                    <h3 class="text-slate-900 font-extrabold text-sm uppercase tracking-wider flex items-center gap-2.5 mb-6 pb-4 border-b border-slate-100">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 shadow-sm shadow-emerald-500/50"></span> Fasilitas &amp; Layanan yang Termasuk
                    </h3>

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
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($inclusions as $item)
                            <div class="flex items-start gap-4 p-4 rounded-2xl border border-slate-50 bg-slate-50/40 hover:bg-emerald-50/40 hover:border-emerald-250 transition-all duration-300 group">
                                <span class="bg-emerald-50 text-emerald-600 rounded-xl p-2.5 shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition duration-300">
                                    <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
                                </span>
                                <div>
                                    <h4 class="text-xs sm:text-sm font-black text-slate-800 transition">{{ $item['label'] }}</h4>
                                    @if (!empty($item['desc']))
                                        <p class="text-[11px] text-slate-400 font-semibold mt-1 leading-relaxed">{{ $item['desc'] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
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
                    <a href="{{ $brochure_link }}" target="_blank" class="w-full bg-amber-50 hover:bg-amber-100 text-amber-700 py-3 rounded-xl font-bold border border-amber-200 transition-all duration-200 text-xs text-center flex items-center justify-center gap-2 active:scale-95">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        <span>{{ $package->brochure ? 'Unduh Brosur PDF' : 'Minta Brosur PDF' }}</span>
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
                                <a href="{{ $brochure_link }}" target="_blank" class="w-full bg-amber-50 hover:bg-amber-100 text-amber-700 py-3 rounded-xl font-bold border border-amber-200/60 transition-all duration-200 text-xs text-center flex items-center justify-center gap-2 active:scale-95">
                                    <i data-lucide="file-text" class="w-4 h-4 flex-shrink-0"></i>
                                    <span>{{ $package->brochure ? 'Unduh Brosur PDF' : 'Minta Brosur PDF' }}</span>
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
    <footer class="bg-slate-950 text-white py-16 md:py-24 relative overflow-hidden islamic-pattern-blue-soft animate-fade-in mt-16" id="kontak" data-purpose="main-footer">
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

    <!-- BEGIN: Mobile Bottom Navigation Bar (App-Like) -->
    <div class="md:hidden fixed bottom-4 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-md bg-white/95 backdrop-blur-md border border-slate-100 rounded-full shadow-[0_-10px_35px_-10px_rgba(0,0,0,0.1),_0_10px_35px_-10px_rgba(0,0,0,0.05)] px-6 py-3 z-50">
        <div class="flex items-center justify-between text-slate-500 font-medium">
            <a href="{{ url('/') }}#beranda" class="flex flex-col items-center gap-1 hover:text-blue-600 transition active:scale-95 text-slate-500">
                <i data-lucide="home" class="w-5 h-5"></i>
                <span class="text-[9px] font-extrabold uppercase tracking-wider">Beranda</span>
            </a>
            <a href="{{ url('/') }}#paket-umrah" class="flex flex-col items-center gap-1 text-blue-600 transition active:scale-95">
                <i data-lucide="compass" class="w-5 h-5"></i>
                <span class="text-[9px] font-extrabold uppercase tracking-wider">Paket</span>
            </a>
            <a href="{{ url('/') }}#galeri" class="flex flex-col items-center gap-1 hover:text-blue-600 transition active:scale-95 text-slate-500">
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
                    <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO.webp') }}" alt="{{ $settings['site_name'] ?? 'IZI Travel' }}" class="h-7 w-auto object-contain" width="180" height="28" decoding="async" />
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
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-blue-600 bg-blue-50/55 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#paket-umrah">
                        <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100/50"><i data-lucide="compass" class="w-4 h-4"></i></span>
                        Paket Umrah
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#galeri">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="image" class="w-4 h-4"></i></span>
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
    <script src="https://unpkg.com/lucide@0.462.0"></script>
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
            
            // Re-run lucide on dynamic icons if needed
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>
</body>

</html>

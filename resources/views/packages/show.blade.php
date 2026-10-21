@php
    $wa_phone = preg_replace('/[^0-9]/', '', $settings['contact_whatsapp'] ?? '6281112345678');
    if (str_starts_with($wa_phone, '0')) {
        $wa_phone = '62' . substr($wa_phone, 1);
    }

    $brochure_link = $package->brochure
        ? $package->brochure_url
        : "https://wa.me/{$wa_phone}?text=Halo%20Admin%20IZI%20Travel%2C%20saya%20tertarik%20dengan%20paket%20" . urlencode($package->name) . "%20dan%20ingin%20meminta%20brosur%20PDF%20lengkapnya.";

@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ $package->name }} - {{ $settings['site_name'] ?? 'IZI Travel' }}</title>
    <link rel="icon" type="image/png" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <meta name="description" content="{{ $package->name }} - {{ $package->duration_days }} hari, berangkat {{ $package->departure_date->locale('id')->translatedFormat('d F Y') }} dengan {{ $package->airline }}. Harga mulai Rp {{ number_format($package->price, 0, ',', '.') }} per jemaah.">

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
    <meta property="og:description" content="{{ $package->name }} - {{ $package->duration_days }} hari, berangkat {{ $package->departure_date->locale('id')->translatedFormat('d F Y') }}. Harga mulai Rp {{ number_format($package->price, 0, ',', '.') }} per jemaah." />
    <meta property="og:image" content="{{ $package->image_url }}" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="{{ url()->current() }}" />
    <meta property="twitter:title" content="{{ $package->name }} - {{ $settings['site_name'] ?? 'IZI Travel' }}" />
    <meta property="twitter:description" content="{{ $package->name }} - {{ $package->duration_days }} hari, berangkat {{ $package->departure_date->locale('id')->translatedFormat('d F Y') }}. Harga mulai Rp {{ number_format($package->price, 0, ',', '.') }} per jemaah." />
    <meta property="twitter:image" content="{{ $package->image_url }}" />

    <!-- Structured Data: Product Schema for Package -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "Product",
      "name": "{{ str_replace('"', '\"', $package->name) }}",
      "image": "{{ $package->image_url }}",
      "description": "{{ str_replace('"', '\"', $package->name) }} - {{ $package->duration_days }} hari, berangkat {{ $package->departure_date->locale('id')->translatedFormat('d F Y') }} dengan {{ str_replace('"', '\"', $package->airline) }}.",
      "brand": {
        "@@type": "Brand",
        "name": "{{ $settings['site_name'] ?? 'IZI Travel' }}"
      },
      "offers": {
        "@@type": "Offer",
        "url": "{{ url()->current() }}",
        "priceCurrency": "IDR",
        "price": "{{ $package->price }}"
      }
    }
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

</head>

@include('packages._detail-content')
</html>

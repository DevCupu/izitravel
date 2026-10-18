@php
    $siteName = $settings['site_name'] ?? 'IZI Travel';
    $wa_phone = preg_replace('/[^0-9]/', '', $settings['contact_whatsapp'] ?? '6281112345678');
    if (str_starts_with($wa_phone, '0')) {
        $wa_phone = '62' . substr($wa_phone, 1);
    }
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Status Keberangkatan - {{ $siteName }}</title>
    <link rel="icon" type="image/png" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <meta name="description" content="Pantau status persiapan keberangkatan umrah Anda dengan nomor paspor dan tanggal lahir.">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; background-color: #fafaf9; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 700 !important; letter-spacing: -0.02em; }
        .font-islamic { font-family: 'El Messiri', serif !important; font-weight: 700 !important; letter-spacing: 0.01em; }
        .islamic-pattern {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cpath d='M30 0 l10 20 l20 10 l-20 10 l-10 20 l-10 -20 l-20 -10 l20 -10 z' fill='%23d97706' fill-opacity='0.02'/%3E%3C/svg%3E");
            background-size: 60px 60px;
        }
        .islamic-pattern-blue-soft {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cpath d='M30 0 l10 20 l20 10 l-20 10 l-10 20 l-10 -20 l-20 -10 l20 -10 z' fill='%233b82f6' fill-opacity='0.015'/%3E%3C/svg%3E");
            background-size: 60px 60px;
        }
        .soft-shadow { box-shadow: 0 10px 30px -10px rgba(7, 30, 61, 0.03), 0 1px 3px rgba(7, 30, 61, 0.01); }
        .scrollbar-none::-webkit-scrollbar { display: none; }
        .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-stone-50 text-stone-800 overflow-x-hidden antialiased izi-public">

    <!-- BEGIN: Floating Modern Header -->
    @include('public.partials.navigation')
    <main id="main-content">

    <!-- BEGIN: Banner Header -->
    <section class="relative pt-32 pb-16 bg-blue-950 overflow-hidden islamic-pattern">
        <div class="absolute inset-0 bg-stone-900/60 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <span class="bg-amber-500/10 text-amber-400 text-xs font-bold px-4 py-1.5 rounded-full w-fit uppercase tracking-wider border border-amber-500/20 inline-flex items-center gap-1.5">
                <i data-lucide="search-check" class="w-3.5 h-3.5"></i>
                Status Keberangkatan
            </span>
            <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight">Pantau Kesiapan Keberangkatan Anda</h1>
            <p class="text-stone-300 max-w-lg mx-auto text-xs md:text-sm">Cukup masukkan nomor paspor dan tanggal lahir Anda, lalu lihat detail paket, jadwal keberangkatan, hingga sejauh mana persiapan dokumen Anda sudah selesai.</p>
        </div>
    </section>
    <!-- END: Banner Header -->

    <!-- BEGIN: Content Section -->
    <section class="py-16 bg-stone-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 space-y-6">

            <!-- Search Card -->
            <div class="bg-white rounded-[2rem] border border-stone-100/80 p-6 sm:p-8 soft-shadow relative overflow-hidden">
                <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-amber-500/40 via-amber-500 to-amber-500/40"></div>
                <form method="GET" action="{{ route('public.jemaah.tracking.result') }}" class="space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="relative">
                            <i data-lucide="scan-search" class="w-4 h-4 text-stone-400 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input
                                type="text"
                                name="passport"
                                placeholder="Nomor Paspor, contoh: A1234567"
                                class="w-full pl-11 pr-4 py-3.5 text-sm border border-stone-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 rounded-2xl"
                                autofocus
                                required
                            >
                        </div>
                        <div class="relative">
                            <i data-lucide="calendar" class="w-4 h-4 text-stone-400 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input
                                type="date"
                                name="birth_date"
                                class="w-full pl-11 pr-4 py-3.5 text-sm border border-stone-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 rounded-2xl"
                                required
                            >
                        </div>
                    </div>
                    <button type="submit" class="group w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 active:scale-[0.97] rounded-2xl font-bold text-sm text-white transition-all duration-300 shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/30">
                        <i data-lucide="search-check" class="w-4.5 h-4.5 transition-transform group-hover:scale-110"></i>
                        Cari Status Keberangkatan
                        <i data-lucide="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-1"></i>
                    </button>
                </form>
            </div>

            <!-- Prompt state -->
            <div class="text-center py-10 space-y-3">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center mx-auto">
                    <i data-lucide="shield-check" class="w-8 h-8 text-blue-400"></i>
                </div>
                <p class="text-sm text-stone-400 max-w-xs mx-auto">Untuk menjaga privasi Anda, pencarian membutuhkan nomor paspor dan tanggal lahir sekaligus — sesuai data yang terdaftar di sistem kami.</p>
            </div>
        </div>
    </section>
    </main>
    <!-- END: Content Section -->

    <!-- BEGIN: Footer -->
    @include('public.partials.footer')





    </body>

</html>

@php
    $siteName = $settings['site_name'] ?? 'IZI Travel';
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

<body class="public-interior izi-public overflow-x-hidden antialiased">

    <!-- BEGIN: Floating Modern Header -->
    @include('public.partials.navigation')
    <main id="main-content">

    @include('public.partials.interior-hero', [
        'eyebrow' => 'Cek keberangkatan',
        'title' => 'Pantau persiapan perjalanan Anda',
        'description' => 'Lihat status pendaftaran, paket, dan jadwal keberangkatan dengan data yang Anda daftarkan.',
        'breadcrumb' => 'Cek keberangkatan',
    ])

    <section class="interior-section" aria-label="Pencarian status keberangkatan">
        <div class="tracking-layout">
            <div class="tracking-guide">
                <h2>Siapkan dua data ini</h2>
                <ol>
                    <li><span>1</span><div><strong>Nomor paspor</strong>Gunakan nomor yang tercatat saat pendaftaran.</div></li>
                    <li><span>2</span><div><strong>Tanggal lahir</strong>Pilih tanggal lahir sesuai data pendaftaran Anda.</div></li>
                </ol>
            </div>
            <div class="interior-card tracking-form">
                <h2>Cari status jemaah</h2>
                <p class="interior-help">Kedua data harus cocok untuk menampilkan hasil.</p>
                <form method="POST" action="{{ route('public.jemaah.tracking.search') }}">
                    @csrf
                    <div>
                        <label for="tracking-passport" class="interior-label">Nomor paspor</label>
                        <input id="tracking-passport" type="text" name="passport" class="interior-field" placeholder="Contoh: A1234567" autocomplete="off" autocapitalize="characters" spellcheck="false" maxlength="32" required autofocus>
                    </div>
                    <div>
                        <label for="tracking-birth-date" class="interior-label">Tanggal lahir</label>
                        <input id="tracking-birth-date" type="date" name="birth_date" class="interior-field" max="{{ now()->toDateString() }}" autocomplete="bday" required>
                    </div>
                    <button type="submit" class="interior-button"><i data-lucide="search-check" aria-hidden="true"></i>Cari status keberangkatan</button>
                </form>
                <div class="tracking-privacy"><i data-lucide="shield-check" aria-hidden="true"></i><p class="interior-help">Hasil hanya ditampilkan bila nomor paspor dan tanggal lahir sesuai. Jangan bagikan hasil pencarian kepada orang lain.</p></div>
            </div>
        </div>
    </section>
    </main>
    <!-- END: Content Section -->

    <!-- BEGIN: Footer -->
    @include('public.partials.footer')





    </body>

</html>

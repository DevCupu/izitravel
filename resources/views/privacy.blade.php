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
    <title>Kebijakan Privasi - {{ $siteName }}</title>
    <link rel="icon" type="image/png" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <meta name="description" content="Kebijakan privasi {{ $siteName }} terkait pengumpulan dan penggunaan data pengunjung.">

    @if(!empty($settings['seo_meta_keywords']))
    <meta name="keywords" content="{{ $settings['seo_meta_keywords'] }}" />
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

        .islamic-pattern-blue-soft {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cpath d='M30 0 l10 20 l20 10 l-20 10 l-10 20 l-10 -20 l-20 -10 l20 -10 z' fill='%233b82f6' fill-opacity='0.015'/%3E%3C/svg%3E");
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

<body class="izi-public bg-stone-50 text-slate-800 overflow-x-hidden antialiased">

    <!-- BEGIN: Floating Modern Header -->
    @include('public.partials.navigation')

    <main id="main-content">
    <!-- BEGIN: Banner Header -->
    <section class="relative pt-32 pb-16 bg-blue-950 overflow-hidden islamic-pattern">
        <div class="absolute inset-0 bg-slate-900/60 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <span class="bg-amber-500/10 text-amber-400 text-xs font-bold px-4 py-1.5 rounded-full w-fit uppercase tracking-wider border border-amber-500/20">
                Privasi Pengguna
            </span>
            <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight">Kebijakan Privasi</h1>
            <p class="text-slate-300 max-w-md mx-auto text-xs md:text-sm">Kebijakan privasi {{ $siteName }} terkait pengumpulan, penggunaan, dan pelindungan data pribadi pengunjung.</p>
        </div>
    </section>
    <!-- END: Banner Header -->

    <!-- BEGIN: Content Section -->
    <section class="py-16 bg-stone-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <div class="bg-white rounded-[2rem] border border-slate-100/80 p-8 sm:p-12 soft-shadow hover:shadow-lg transition-all duration-300 relative overflow-hidden">
                <!-- Decorative Top Mihrab Arch Indicator -->
                <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-amber-500/40 via-amber-500 to-amber-500/40"></div>

                <div class="prose prose-slate prose-sm sm:prose-base max-w-none space-y-8 text-slate-600 leading-relaxed">
                    @if(!empty($settings['privacy_content']))
                        <div class="whitespace-pre-line text-slate-600">
                            {!! $settings['privacy_content'] !!}
                        </div>
                    @else
                        <div class="flex items-center gap-2 text-slate-400 text-xs pb-4 border-b border-slate-100">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            <span>Terakhir diperbarui: {{ date('d F Y') }}</span>
                        </div>

                        <section class="space-y-3">
                            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center text-sm font-black">1</span>
                                Informasi yang Kami Kumpulkan
                            </h2>
                            <p class="pl-11 text-slate-600">Kami mengumpulkan informasi identitas pribadi secara sukarela saat Anda berinteraksi dengan situs kami. Data ini mencakup nama lengkap, alamat email aktif, nomor WhatsApp/telepon, serta rincian kebutuhan paket umrah/haji yang Anda isi melalui formulir pendaftaran maupun konsultasi online.</p>
                        </section>

                        <section class="space-y-3">
                            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center text-sm font-black">2</span>
                                Penggunaan Informasi
                            </h2>
                            <p class="pl-11 text-slate-600">Informasi yang kami kumpulkan dimanfaatkan secara eksklusif untuk kepentingan berikut:</p>
                            <ul class="pl-16 list-disc space-y-1 text-slate-600">
                                <li>Memproses transaksi, validasi administratif, dan reservasi paket perjalanan ibadah Anda.</li>
                                <li>Menghubungi Anda kembali terkait konsultasi syiar, informasi keberangkatan, atau penawaran produk yang relevan.</li>
                                <li>Meningkatkan kualitas tampilan, fitur, serta keamanan situs web kami demi kenyamanan pengguna.</li>
                            </ul>
                        </section>

                        <section class="space-y-3">
                            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center text-sm font-black">3</span>
                                Perlindungan Data Jemaah
                            </h2>
                            <p class="pl-11 text-slate-600">Kami menerapkan standar keamanan enkripsi data dan proteksi server demi melindungi kerahasiaan informasi pribadi Anda dari akses tidak sah, pengungkapan publik, modifikasi ilegal, maupun perusakan data. Seluruh berkas fisik dokumen umrah disimpan dalam sistem kearsipan kantor yang aman.</p>
                        </section>

                        <section class="space-y-3">
                            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center text-sm font-black">4</span>
                                Penggunaan Cookie Browser
                            </h2>
                            <p class="pl-11 text-slate-600">Situs web kami menggunakan teknologi cookie untuk melacak riwayat interaksi pengunjung secara anonim, mengingat preferensi menu Anda, serta mempercepat pemuatan halaman pada kunjungan berikutnya. Anda dapat mematikan fitur cookie kapan pun melalui pengaturan peramban internet Anda.</p>
                        </section>

                        <section class="space-y-3">
                            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center text-sm font-black">5</span>
                                Keterlibatan Pihak Ketiga
                            </h2>
                            <p class="pl-11 text-slate-600">Kami berkomitmen penuh untuk tidak menjual, memperdagangkan, menyewakan, atau menyebarluaskan data pribadi Anda kepada pihak luar tanpa izin tertulis Anda. Hal ini tidak berlaku untuk mitra tepercaya (seperti maskapai penerbangan, hotel, provider visa) yang secara teknis membantu operasional keberangkatan umrah Anda, sepanjang mereka berkomitmen menjaga kerahasiaan data tersebut.</p>
                        </section>

                        <section class="space-y-3">
                            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center text-sm font-black">6</span>
                                Perubahan Kebijakan
                            </h2>
                            <p class="pl-11 text-slate-600">Kebijakan privasi ini dapat disesuaikan secara berkala untuk mematuhi regulasi terbaru pemerintah Indonesia mengenai Perlindungan Data Pribadi (UU PDP). Jemaah disarankan untuk memeriksa halaman ini secara rutin untuk mengetahui pembaruan kebijakan keamanan kami.</p>
                        </section>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- END: Content Section -->
    </main>

    <!-- BEGIN: Footer -->
    @include('public.partials.footer')
    <script>document.addEventListener('DOMContentLoaded', () => window.lucide?.createIcons());</script>
</body>

</html>

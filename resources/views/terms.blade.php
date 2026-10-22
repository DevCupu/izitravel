@php
    $siteName = $settings['site_name'] ?? 'IZI Travel';
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Syarat &amp; Ketentuan - {{ $siteName }}</title>
    <link rel="icon" type="image/png" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <meta name="description" content="Syarat dan ketentuan penggunaan layanan {{ $siteName }}.">

    @if(!empty($settings['seo_meta_keywords']))
    <meta name="keywords" content="{{ $settings['seo_meta_keywords'] }}" />
    @endif

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Google Fonts: Plus Jakarta Sans, Inter, & El Messiri -->
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@305;400;500;600;700&display=swap" rel="stylesheet" />
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

<body class="public-interior izi-public overflow-x-hidden antialiased">

    <!-- BEGIN: Floating Modern Header -->
    @include('public.partials.navigation')

    <main id="main-content">
    @include('public.partials.interior-hero', [
        'eyebrow' => 'Informasi layanan',
        'title' => 'Syarat dan ketentuan',
        'description' => 'Ketahui aturan penggunaan layanan perjalanan ibadah IZI Travel sebelum mendaftar.',
        'breadcrumb' => 'Syarat & ketentuan',
    ])

    <!-- BEGIN: Content Section -->
    <section class="interior-section">
        <div class="max-w-4xl mx-auto">
            <div class="interior-card p-7 sm:p-12">
                <div class="prose prose-slate prose-sm sm:prose-base max-w-none space-y-8 text-slate-600 leading-relaxed">
                    @if(!empty($settings['terms_content']))
                        <div class="whitespace-pre-line text-slate-600">
                            {!! $settings['terms_content'] !!}
                        </div>
                    @else
                        <div class="flex items-center gap-2 text-slate-400 text-xs pb-4 border-b border-slate-100">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            <span>Terakhir diperbarui: {{ date('d F Y') }}</span>
                        </div>

                        <section class="space-y-3">
                            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center text-sm font-black">1</span>
                                Penerimaan Ketentuan
                            </h2>
                            <p class="pl-11 text-slate-600">Dengan mengakses dan menggunakan situs web {{ $siteName }}, Anda menyetujui untuk terikat oleh syarat dan ketentuan yang tercantum di halaman ini. Jika Anda tidak setuju dengan bagian mana pun dari ketentuan ini, Anda tidak diperkenankan menggunakan layanan kami.</p>
                        </section>

                        <section class="space-y-3">
                            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center text-sm font-black">2</span>
                                Layanan Perjalanan
                            </h2>
                            <p class="pl-11 text-slate-600">{{ $siteName }} menyediakan informasi lengkap, jadwal keberangkatan, dan fasilitasi pendaftaran paket perjalanan ibadah Umrah dan Haji Khusus. Kami berupaya menyajikan data seakurat mungkin, namun ketersediaan kuota, akomodasi, dan jadwal penerbangan dapat berubah sewaktu-waktu sesuai kondisi riil di lapangan.</p>
                        </section>

                        <section class="space-y-3">
                            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center text-sm font-black">3</span>
                                Pendaftaran &amp; Pembayaran
                            </h2>
                            <p class="pl-11 text-slate-600">Proses pendaftaran dianggap sah setelah calon jemaah menyetujui syarat paket, melengkapi dokumen administrasi yang diminta, dan melakukan pembayaran uang muka (*down payment*) sesuai ketentuan. Pelunasan wajib dilakukan sesuai dengan batas waktu yang ditentukan sebelum tanggal keberangkatan.</p>
                        </section>

                        <section class="space-y-3">
                            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center text-sm font-black">4</span>
                                Kekayaan Intelektual
                            </h2>
                            <p class="pl-11 text-slate-600">Seluruh materi yang ditampilkan di situs ini, termasuk namun tidak terbatas pada desain grafis, logo perusahaan, teks dokumentasi, foto orisinal, serta kode pemrograman, merupakan hak cipta milik {{ $siteName }} dan dilindungi undang-undang kekayaan intelektual.</p>
                        </section>

                        <section class="space-y-3">
                            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center text-sm font-black">5</span>
                                Perubahan Ketentuan
                            </h2>
                            <p class="pl-11 text-slate-600">Kami berhak mutlak untuk memperbarui, mengubah, atau mengganti bagian mana pun dari Syarat &amp; Ketentuan ini demi meningkatkan kualitas pelayanan. Perubahan tersebut bersifat mengikat dan akan berlaku efektif segera setelah dipublikasikan secara terbuka di halaman ini.</p>
                        </section>

                        <section class="space-y-3">
                            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center text-sm font-black">6</span>
                                Hubungi Layanan Pelanggan
                            </h2>
                            <p class="pl-11 text-slate-600">Apabila Anda memiliki pertanyaan, keluhan, atau memerlukan klarifikasi lebih lanjut mengenai Syarat &amp; Ketentuan di atas, silakan hubungi kami secara langsung melalui kontak WhatsApp atau email resmi kantor pelayanan kami.</p>
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

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

<body class="bg-stone-50 text-slate-800 overflow-x-hidden antialiased">

    <!-- BEGIN: Floating Modern Header -->
    <header class="fixed top-4 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-7xl z-50">
        <nav class="bg-white border border-slate-100/80 rounded-full shadow-[0_10px_35px_-10px_rgba(0,0,0,0.05)] px-6 py-3 flex items-center justify-between transition-all duration-300">
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}">
                    <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO.webp') }}" alt="{{ $siteName }}" class="h-8 w-auto object-contain" width="687" height="240" fetchpriority="high" decoding="async" />
                </a>
            </div>
            
            <!-- Desktop Navigation Links -->
            <div class="hidden md:flex items-center space-x-1 font-medium text-slate-600">
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#beranda">Beranda</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#tentang-kami">Tentang Kami</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#paket-umrah">Paket Umrah</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ route('public.gallery') }}">Galeri</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#testimoni">Testimoni</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#artikel">Artikel</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#kemitraan">Kemitraan</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#kontak">Kontak</a>
            </div>
            
            <div class="flex items-center gap-2">
                <a class="bg-blue-600 text-white px-3.5 py-2.5 sm:px-5 sm:py-2.5 rounded-full font-bold hover:bg-blue-700 transition-all duration-300 flex items-center gap-2 text-xs shadow-md active:scale-95" href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
                    </svg>
                    <span class="hidden sm:inline">Konsultasi/WhatsApp</span>
                </a>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-bottom-menu-trigger" class="md:hidden w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-600 hover:bg-slate-100 transition active:scale-95 shadow-sm">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
            </div>
        </nav>
    </header>
    <!-- END: Floating Header -->

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
                    <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO WHITE.webp') }}" alt="{{ $siteName }}" class="h-11 w-auto object-contain" width="531" height="240" loading="lazy" decoding="async" />
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
                            <a href="{{ route('public.gallery') }}" class="hover:text-amber-400 transition duration-200 flex items-center gap-2 group/link">
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
                    <h4 class="text-xs font-black uppercase tracking-widest text-amber-500 border-l-2 border-amber-500 pl-3">{{ $settings['footer_contact_heading'] ?? 'Hubungi Kami' }}</h4>
                    <ul class="space-y-4 text-xs text-slate-400">
                        <li class="flex items-start gap-3.5 group/item">
                            <span class="w-8 h-8 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center flex-shrink-0 text-amber-500 group-hover/item:bg-amber-500 group-hover/item:text-slate-950 group-hover/item:border-amber-500 transition duration-300">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                            </span>
                            <span class="leading-relaxed group-hover/item:text-slate-200 transition duration-200">{{ $settings['contact_address'] ?? 'Gedung IZI Travel, Jl. Urip Sumoharjo No. 12, Makassar, Sulawesi Selatan' }}</span>
                        </li>
                        <li class="flex items-center gap-3.5 group/item">
                            <span class="w-8 h-8 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center flex-shrink-0 text-amber-500 group-hover/item:bg-amber-500 group-hover/item:text-slate-950 group-hover/item:border-amber-500 transition duration-300">
                                <i data-lucide="phone" class="w-4 h-4"></i>
                            </span>
                            <span class="group-hover/item:text-slate-200 transition duration-200">{{ $settings['contact_phone'] ?? '+62 811-1234-5678' }}</span>
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
                            <div class="w-full h-28 bg-slate-900 flex items-center justify-center">
                                <span class="text-slate-500 text-xs">Peta Lokasi</span>
                            </div>
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
                <p>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
                <div class="flex gap-6 font-light">
                    <a href="{{ route('public.terms') }}" class="hover:text-amber-500 transition duration-200">Syarat &amp; Ketentuan</a>
                    <a href="{{ route('public.privacy') }}" class="text-amber-500 font-medium">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>
    <!-- END: Footer -->

    <!-- BEGIN: Mobile Navigation Drawer -->
    <div id="mobile-drawer" class="fixed inset-0 z-50 invisible flex justify-end transition-all duration-300" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div id="mobile-drawer-backdrop" class="fixed inset-0 bg-slate-950/40 opacity-0 transition-opacity duration-300 backdrop-blur-sm"></div>
        
        <!-- Drawer content -->
        <div id="mobile-drawer-content" class="relative w-full max-w-xs bg-white h-full shadow-2xl p-6 flex flex-col gap-6 transform translate-x-full transition-transform duration-300 z-10">
            <!-- Scrollable content area -->
            <div class="flex-1 overflow-y-auto scrollbar-none space-y-6 pr-1">
                <!-- Header inside drawer -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO.webp') }}" alt="{{ $siteName }}" class="h-7 w-auto object-contain" width="180" height="28" decoding="async" />
                    <button id="mobile-drawer-close" class="w-8 h-8 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-800 transition active:scale-95 shadow-sm">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <!-- Navigation Links -->
                <div class="flex flex-col space-y-1">
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#beranda">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="home" class="w-4 h-4"></i></span>
                        Beranda
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#tentang-kami">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="users" class="w-4 h-4"></i></span>
                        Tentang Kami
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#paket-umrah">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="compass" class="w-4 h-4"></i></span>
                        Paket Umrah
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="{{ route('public.gallery') }}">
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

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@0.462.0"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize Lucide Icons
            if (window.lucide) {
                window.lucide.createIcons();
            }

            // Drawer Script
            const mobileBottomMenuTrigger = document.getElementById('mobile-bottom-menu-trigger');
            const mobileDrawer = document.getElementById('mobile-drawer');
            const mobileDrawerBackdrop = document.getElementById('mobile-drawer-backdrop');
            const mobileDrawerContent = document.getElementById('mobile-drawer-content');
            const mobileDrawerClose = document.getElementById('mobile-drawer-close');
            const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

            const openDrawer = () => {
                mobileDrawer.classList.remove('invisible');
                mobileDrawer.classList.add('flex');
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
    </script>
</body>

</html>

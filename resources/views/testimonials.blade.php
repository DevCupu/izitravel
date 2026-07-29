@php
    $wa_phone = preg_replace('/[^0-9]/', '', $settings['contact_whatsapp'] ?? '6281112345678');
    if (str_starts_with($wa_phone, '0')) {
        $wa_phone = '62' . substr($wa_phone, 1);
    }

    $wa_cofounder = preg_replace('/[^0-9]/', '', $settings['contact_whatsapp_cofounder'] ?? '');
    if ($wa_cofounder && str_starts_with($wa_cofounder, '0')) {
        $wa_cofounder = '62' . substr($wa_cofounder, 1);
    }
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ $settings['site_name'] ?? 'IZI Travel' }} - Testimoni &amp; Cerita Jamaah</title>
    <link rel="icon" type="image/png" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <meta name="description" content="Cerita dan testimoni nyata ulasan jamaah yang telah mempercayakan perjalanan ibadah Umrah dan Haji Premium bersama IZI Travel.">
    
    @if(!empty($settings['seo_meta_keywords']))
    <meta name="keywords" content="{{ $settings['seo_meta_keywords'] }}" />
    @endif

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Google Fonts: Plus Jakarta Sans, Inter, Amiri & El Messiri -->
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=El+Messiri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
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

        .font-arabic {
            font-family: 'Amiri', serif !important;
            font-weight: 400 !important;
        }

        .islamic-pattern {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cpath d='M30 0 l10 20 l20 10 l-20 10 l-10 20 l-10 -20 l-20 -10 l20 -10 z' fill='%23d97706' fill-opacity='0.025'/%3E%3C/svg%3E");
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
                    <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO.webp') }}" alt="{{ $settings['site_name'] ?? 'IZI Travel' }}" class="h-8 w-auto object-contain" width="687" height="240" fetchpriority="high" decoding="async" />
                </a>
            </div>
            
            <!-- Desktop Navigation Links -->
            <div class="hidden md:flex items-center space-x-1 font-medium text-slate-600">
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#beranda">Beranda</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#tentang-kami">Tentang Kami</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#paket-umrah">Paket Umrah</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ route('public.gallery') }}">Galeri</a>
                <a class="text-blue-600 bg-blue-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ route('public.testimonials') }}">Testimoni</a>
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
            </div>
        </nav>
    </header>
    <!-- END: Floating Header -->

    <!-- BEGIN: Banner Header -->
    <section class="relative pt-36 pb-20 bg-blue-950 overflow-hidden islamic-pattern">
        <div class="absolute inset-0 bg-slate-950/70 pointer-events-none"></div>
        
        <!-- Glowing background accent -->
        <div class="absolute left-1/2 -translate-x-1/2 top-1/2 -translate-y-1/2 w-[500px] h-[250px] bg-blue-500/10 rounded-full blur-[120px] pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-5">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-black tracking-widest uppercase shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Kisah &amp; Ulasan Jemaah
            </span>
            <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight">Apa Kata Mereka Tentang IZI Travel?</h1>
            <p class="text-slate-300 max-w-xl mx-auto text-xs md:text-sm font-medium leading-relaxed">
                Dengarkan penuturan jujur, rasa khusyuk, dan pengalaman beribadah yang berkesan langsung dari para jamaah yang telah menyelesaikan perjalanan ibadah mereka bersama kami.
            </p>
            <div class="flex justify-center pt-2">
                <span class="font-arabic text-xl md:text-2xl text-amber-400/60 leading-none">﷽</span>
            </div>
        </div>
    </section>
    <!-- END: Banner Header -->

    <!-- BEGIN: Testimonials Grid Section -->
    <section class="py-20 bg-stone-50 overflow-hidden relative">
        <!-- Ambient Glowing Orbs -->
        <div class="absolute left-1/4 top-1/4 w-[500px] h-[250px] bg-blue-400/5 rounded-full blur-[100px] pointer-events-none -z-10 animate-aurora-1"></div>
        <div class="absolute right-1/4 bottom-1/4 w-[450px] h-[220px] bg-emerald-400/5 rounded-full blur-[100px] pointer-events-none -z-10 animate-aurora-2"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Interactive Client-Side Filters -->
            <div class="w-full overflow-x-auto scrollbar-none py-2 px-4 flex justify-center mb-12">
                <div class="relative flex items-center gap-1 bg-slate-100/90 p-1.5 rounded-full border border-slate-200/50 z-10 whitespace-nowrap">
                    <button class="testimonial-filter-btn active px-5 py-2 rounded-full text-xs font-bold transition-all duration-200 text-blue-600 bg-white shadow-sm border border-slate-200/10" data-filter="all">
                        Semua Ulasan
                    </button>
                    <button class="testimonial-filter-btn px-5 py-2 rounded-full text-xs font-bold transition-all duration-200 text-slate-500 hover:text-blue-600" data-filter="video">
                        Dengan Video
                    </button>
                    <button class="testimonial-filter-btn px-5 py-2 rounded-full text-xs font-bold transition-all duration-200 text-slate-500 hover:text-blue-600" data-filter="photo">
                        Dengan Foto
                    </button>
                    <button class="testimonial-filter-btn px-5 py-2 rounded-full text-xs font-bold transition-all duration-200 text-slate-500 hover:text-blue-600" data-filter="star5">
                        Bintang 5
                    </button>
                </div>
            </div>

            @if(isset($testimonials) && $testimonials->count() > 0)
                <!-- Testimonials Responsive Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8" id="testimonials-wrapper">
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
                                    $parsedEmbedUrl = "https://www.instagram.com/reel/" . $matches[1] . "/embed/captioned/";
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
                        
                        <!-- Testimonial Grid Card (Subtle/Simple Hover State) -->
                        <div class="testimonial-card-item p-2 bg-slate-100/40 border border-slate-200/40 rounded-[2.2rem] hover:bg-slate-100/70 hover:border-blue-500/25 hover:shadow-md transition-all duration-300 flex flex-col justify-between"
                             data-has-video="{{ !empty($parsedEmbedUrl) ? 'true' : 'false' }}"
                             data-has-photo="{{ !empty($testimonial->photo) ? 'true' : 'false' }}"
                             data-rating="{{ $testimonial->rating }}">
                            
                            <!-- Inner Core -->
                            <div class="bg-white rounded-[1.8rem] p-6 sm:p-8 flex flex-col justify-between h-full border border-slate-100/80 shadow-sm relative overflow-hidden flex-1">
                                <div>
                                    <div class="flex items-center justify-between gap-3 sm:gap-4 mb-4 sm:mb-6">
                                        <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                                            @if(!empty($testimonial->photo))
                                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full overflow-hidden border-2 border-white shadow-md flex-shrink-0 relative">
                                                    <img src="{{ asset('storage/' . $testimonial->photo) }}" alt="{{ $testimonial->name }}" class="w-full h-full object-cover">
                                                </div>
                                            @else
                                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br {{ $gradient }} flex items-center justify-center text-white font-black text-sm sm:text-base border-2 border-white shadow-md flex-shrink-0 relative">
                                                    {{ $initial }}
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <h4 class="font-extrabold text-slate-900 text-sm md:text-base truncate sm:whitespace-normal transition duration-300">{{ $testimonial->name }}</h4>
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
                                        <!-- Sleek responsive video player thumbnail -->
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
                                        <!-- Translucent graphic quote mark -->
                                        <span class="absolute -top-6 -left-3 text-blue-600/5 text-8xl font-serif pointer-events-none select-none">“</span>
                                        <p class="text-slate-650 text-xs sm:text-sm leading-relaxed mb-4 sm:mb-6 font-medium italic relative z-10 pl-3">
                                            {{ $testimonial->message }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- No results fallback under filters -->
                <div class="max-w-md mx-auto text-center py-16 hidden" id="no-filter-results">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mx-auto mb-4 border border-slate-200/40">
                        <i data-lucide="filter" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Tidak Ada Hasil</h3>
                    <p class="text-sm text-slate-500 mt-1">Tidak ada ulasan aktif yang cocok dengan kriteria filter pilihan Anda.</p>
                </div>

                <!-- Pagination Links -->
                @if ($testimonials->hasPages())
                    <div class="mt-12 bg-white rounded-3xl border border-slate-100 p-4 shadow-sm flex justify-center">
                        {{ $testimonials->links() }}
                    </div>
                @endif
            @else
                <div class="max-w-md mx-auto text-center py-20">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mx-auto mb-4">
                        <i data-lucide="message-square" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Testimoni Belum Tersedia</h3>
                    <p class="text-sm text-slate-500 mt-1">Belum ada testimoni aktif yang tersedia untuk ditampilkan saat ini.</p>
                </div>
            @endif
        </div>
    </section>
    <!-- END: Testimonials Grid Section -->

    <!-- BEGIN: Footer -->
    <footer class="bg-slate-950 text-white py-16 md:py-24 relative overflow-hidden islamic-pattern-blue-soft animate-fade-in" id="kontak" data-purpose="main-footer">
        <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-blue-500/30 to-transparent"></div>
        <div class="absolute -right-20 -bottom-20 w-[600px] h-[600px] bg-blue-600/5 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute -left-20 -top-20 w-[500px] h-[500px] bg-blue-600/3 rounded-full blur-[100px] pointer-events-none"></div>
        
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none overflow-hidden z-0">
            <span class="text-[12vw] font-black text-white/[0.06] uppercase tracking-[0.2em] leading-none whitespace-nowrap font-heading">
                IZI TRAVEL
            </span>
        </div>
        
        <div class="max-w-[85rem] mx-auto px-6 sm:px-10 lg:px-16 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-12 mb-16">
                <!-- Col 1: Branding & Socials -->
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

                <!-- Col 2: Quick Links -->
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
                            <a href="{{ route('public.testimonials') }}" class="hover:text-amber-400 transition duration-200 flex items-center gap-2 group/link">
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

                <!-- Col 3: Hubungi Kami -->
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

                <!-- Col 4: Maps & Legalitas -->
                <div class="lg:col-span-3 space-y-6">
                    <h4 class="text-xs font-black uppercase tracking-widest text-amber-500 border-l-2 border-amber-500 pl-3">Legalitas &amp; Lokasi</h4>
                    <div class="rounded-2xl overflow-hidden border border-white/10 shadow-2xl h-28 relative group transition duration-300 hover:border-blue-500/30">
                        @if(isset($settings['contact_gmaps']) && !empty($settings['contact_gmaps']))
                            <iframe src="{{ $settings['contact_gmaps'] }}" class="w-full h-full border-0 grayscale opacity-80 group-hover:grayscale-0 group-hover:opacity-100 transition duration-500" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        @else
                            <div class="w-full h-28 bg-slate-900 flex items-center justify-center text-slate-600">
                                <i data-lucide="map" class="w-8 h-8"></i>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-slate-950/40 group-hover:bg-slate-950/10 transition duration-300 pointer-events-none flex items-end p-2.5">
                            <span class="text-[9px] font-bold text-white/80 bg-slate-900/90 backdrop-blur-sm px-2 py-1 rounded-md border border-white/5 flex items-center gap-1 shadow-sm">
                                <i data-lucide="map" class="w-2.5 h-2.5 text-amber-500"></i>
                                Lokasi Kantor Pusat
                            </span>
                        </div>
                    </div>
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

            <!-- Bottom Copyright Bar -->
            <div class="border-t border-white/5 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ $settings['site_name'] ?? 'IZI Travel' }}. All rights reserved.</p>
                <div class="flex gap-6 font-light">
                    <a href="{{ route('public.privacy') }}" class="hover:text-amber-500 transition duration-200">Syarat &amp; Ketentuan</a>
                    <a href="{{ route('public.privacy') }}" class="hover:text-amber-500 transition duration-200">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>
    <!-- END: Footer -->

    <!-- BEGIN: Mobile Bottom Navigation Bar -->
    <div class="md:hidden fixed bottom-4 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-md bg-white/95 backdrop-blur-md border border-slate-100 rounded-full shadow-[0_-10px_35px_-10px_rgba(0,0,0,0.1),_0_10px_35px_-10px_rgba(0,0,0,0.05)] px-6 py-3 z-50">
        <div class="flex items-center justify-between text-slate-500 font-medium">
            <a href="{{ url('/') }}#beranda" class="flex flex-col items-center gap-1 hover:text-blue-600 transition active:scale-95 text-slate-500">
                <i data-lucide="home" class="w-5 h-5"></i>
                <span class="text-[9px] font-extrabold uppercase tracking-wider">Beranda</span>
            </a>
            <a href="{{ url('/') }}#paket-umrah" class="flex flex-col items-center gap-1 hover:text-blue-600 transition active:scale-95 text-slate-500">
                <i data-lucide="compass" class="w-5 h-5"></i>
                <span class="text-[9px] font-extrabold uppercase tracking-wider">Paket</span>
            </a>
            <a href="{{ route('public.gallery') }}" class="flex flex-col items-center gap-1 hover:text-blue-600 transition active:scale-95 text-slate-500">
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
        <div id="mobile-drawer-backdrop" class="absolute inset-0 bg-slate-900/40 backdrop-blur-md opacity-0 transition-opacity duration-300"></div>
        <div id="mobile-drawer-content" class="absolute top-0 right-0 h-full w-[290px] sm:w-[340px] bg-white/95 backdrop-blur-xl border-l border-slate-100 shadow-2xl p-6 flex flex-col justify-between translate-x-full transition-transform duration-300 ease-out rounded-l-[2rem]">
            <div class="flex-1 overflow-y-auto scrollbar-none space-y-6 pr-1">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO.webp') }}" alt="{{ $settings['site_name'] ?? 'IZI Travel' }}" class="h-7 w-auto object-contain" width="180" height="28" decoding="async" />
                    <button id="mobile-drawer-close" class="w-8 h-8 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-800 transition active:scale-95 shadow-sm">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
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
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-blue-600 bg-blue-50/55 transition duration-200 font-semibold text-xs" href="{{ route('public.testimonials') }}">
                        <span class="w-8 h-8 rounded-xl bg-blue-55 text-blue-600 flex items-center justify-center border border-blue-100/50"><i data-lucide="message-square" class="w-4 h-4"></i></span>
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
    <a href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank" class="fixed bottom-20 md:bottom-6 right-6 z-50 bg-gradient-to-r from-emerald-500 to-green-600 text-white p-4 rounded-full shadow-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all duration-300 group" aria-label="Hubungi WhatsApp">
        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
        </svg>
        <span class="max-w-0 overflow-hidden group-hover:max-w-xs group-hover:ml-2 transition-all duration-500 ease-out text-xs font-bold whitespace-nowrap">Chat Kami</span>
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

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@0.462.0"></script>
    <script>
        lucide.createIcons();

        // Mobile drawer menu logic
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

        // Client-side filtering logic
        document.addEventListener('DOMContentLoaded', () => {
            const filterBtns = document.querySelectorAll('.testimonial-filter-btn');
            const cards = document.querySelectorAll('.testimonial-card-item');
            const noResults = document.getElementById('no-filter-results');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Update active button state
                    filterBtns.forEach(b => {
                        b.classList.remove('text-blue-600', 'bg-white', 'shadow-sm', 'border', 'border-slate-200/10');
                        b.classList.add('text-slate-500');
                    });
                    btn.classList.add('text-blue-600', 'bg-white', 'shadow-sm', 'border', 'border-slate-200/10');
                    btn.classList.remove('text-slate-500');

                    const filter = btn.getAttribute('data-filter');
                    let visibleCount = 0;

                    cards.forEach(card => {
                        const hasVideo = card.getAttribute('data-has-video') === 'true';
                        const hasPhoto = card.getAttribute('data-has-photo') === 'true';
                        const rating = card.getAttribute('data-rating');

                        let show = false;
                        if (filter === 'all') {
                            show = true;
                        } else if (filter === 'video') {
                            show = hasVideo;
                        } else if (filter === 'photo') {
                            show = hasPhoto;
                        } else if (filter === 'star5') {
                            show = rating === '5';
                        }

                        if (show) {
                            card.style.display = 'flex';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    // Show no results fallback if count is 0
                    if (visibleCount === 0) {
                        noResults.classList.remove('hidden');
                    } else {
                        noResults.classList.add('hidden');
                    }
                });
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
            }, 300);
        };

        window.playTestimonialVideo = (embedUrl) => {
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
            
            iframeContainer.innerHTML = `<iframe class="w-full h-full" src="${embedUrl}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
            
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

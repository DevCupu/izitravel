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
    <title>Artikel Bertag #{{ $tag }} - {{ $siteName }}</title>
    <link rel="icon" type="image/png" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <meta name="description" content="Temukan artikel-artikel bertag #{{ $tag }} mengenai panduan ibadah, tips, serta kabar haramain terbaru bersama IZI Travel.">

    @if(!empty($settings['seo_meta_keywords']))
    <meta name="keywords" content="{{ $tag }}, {{ $settings['seo_meta_keywords'] }}" />
    @endif

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #fafaf9; /* Stone 50 */
            color: #1e293b;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 700 !important;
            letter-spacing: -0.02em;
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

<body class="bg-[#fafaf9] text-slate-800 overflow-x-hidden antialiased izi-public">

    <!-- BEGIN: Floating Modern Header -->
    @include('public.partials.navigation')

    <!-- Main Container (Editorial Layout) -->
    <main id="main-content" class="pt-32 pb-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <!-- Breadcrumbs -->
        <nav class="flex text-[10px] font-bold text-slate-400 gap-2 items-center tracking-widest uppercase mb-6" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Beranda</a>
            <span>/</span>
            <a href="{{ url('/#artikel') }}" class="hover:text-blue-600 transition">Artikel</a>
            <span>/</span>
            <span class="text-slate-650">#{{ $tag }}</span>
        </nav>

        <!-- Minimalist Page Title -->
        <div class="border-b border-slate-200/70 pb-8 mb-12 text-left">
            <span class="text-[10px] font-black uppercase text-amber-600 tracking-widest block mb-2.5">Arsip Kategori</span>
            <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">Topik: #{{ $tag }}</h1>
            <p class="text-slate-500 text-xs md:text-sm font-light mt-3 max-w-2xl leading-relaxed">
                Menampilkan kumpulan tulisan pembimbing, berita terbaru, dan tips ibadah yang dikelompokkan dalam topik #{{ $tag }}.
            </p>
        </div>

        @if($articles->count() > 0)
            <!-- Clean Magazine Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($articles as $article)
                    <article class="flex flex-col justify-between group bg-white rounded-3xl p-4 border border-slate-200/40 shadow-sm hover:shadow-md transition-all duration-300">
                        <div>
                            <!-- Aspect Ratio Image -->
                            <div class="relative aspect-[16/10] rounded-2xl overflow-hidden bg-slate-100 mb-5 border border-slate-200/20">
                                <a href="{{ route('articles.show', $article->slug) }}">
                                    <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover transition duration-550 group-hover:scale-102" width="480" height="300" loading="lazy" decoding="async" />
                                </a>
                            </div>

                            <!-- Card Content Info -->
                            <div class="space-y-2 px-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-extrabold text-blue-600 uppercase tracking-widest">{{ $article->category }}</span>
                                    <span class="text-slate-300 text-xs">•</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $article->published_at }}</span>
                                </div>
                                <h3 class="text-base md:text-lg font-bold text-slate-900 leading-snug group-hover:text-blue-600 transition duration-150">
                                    <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                                </h3>
                                <p class="text-slate-505 text-xs font-light leading-relaxed line-clamp-3">
                                    {{ $article->excerpt }}
                                </p>
                            </div>
                        </div>

                        <!-- Mini Tags & Read Time footer -->
                        <div class="mt-5 pt-4 border-t border-slate-100 flex flex-col gap-2 px-1">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                                Oleh {{ $article->author }} • {{ $article->read_time }} Baca
                            </p>
                            @if(!empty($article->tags))
                                @php
                                    $cardTags = array_filter(array_map('trim', explode(',', $article->tags)));
                                @endphp
                                @if(!empty($cardTags))
                                    <div class="flex flex-wrap gap-1.5 mt-1">
                                        @foreach($cardTags as $t)
                                            <a href="{{ route('public.articles.tag', str_replace('#', '', $t)) }}" class="text-[10px] font-semibold {{ strtolower(trim($t)) === strtolower('#'.$tag) ? 'text-amber-600 font-black' : 'text-slate-400 hover:text-blue-600' }} transition">
                                                {{ str_starts_with($t, '#') ? $t : '#' . $t }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination Links -->
            @if ($articles->hasPages())
                <div class="mt-16 pt-8 border-t border-slate-200/50 flex justify-center">
                    {{ $articles->links() }}
                </div>
            @endif
        @else
            <!-- No Results Clean State -->
            <div class="text-center py-24 bg-white border border-slate-200/40 rounded-3xl max-w-md mx-auto space-y-4 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/5 border border-amber-500/20 text-amber-600 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="font-bold text-slate-900 text-base">Belum Ada Artikel</h3>
                <p class="text-slate-500 text-xs leading-relaxed max-w-xs mx-auto">Tidak ada artikel aktif yang memiliki kategori tag #{{ $tag }} saat ini.</p>
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:underline pt-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path></svg>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        @endif

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

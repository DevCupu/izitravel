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
    <title>{{ $article->title }} - {{ $settings['site_name'] ?? 'IZI Travel' }}</title>
    <link rel="icon" type="image/png" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <meta name="description" content="{{ $article->excerpt ?? ($settings['site_description'] ?? '') }}">

    @if(!empty($article->tags))
    <meta name="keywords" content="{{ str_replace('#', '', $article->tags) }}{{ !empty($settings['seo_meta_keywords']) ? ', ' . $settings['seo_meta_keywords'] : '' }}" />
    @elseif(!empty($settings['seo_meta_keywords']))
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
    <link rel="canonical" href="{{ !empty($settings['seo_canonical_url']) ? rtrim($settings['seo_canonical_url'], '/') . '/artikel/' . $article->slug : url()->current() }}" />

    <!-- Robots Meta -->
    <meta name="robots" content="index, follow" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="{{ $article->title }} - {{ $settings['site_name'] ?? 'IZI Travel' }}" />
    <meta property="og:description" content="{{ $article->excerpt ?? ($settings['site_description'] ?? '') }}" />
    <meta property="og:image" content="{{ $article->image_url }}" />
    @if(!empty($article->tags))
        @foreach(explode(',', $article->tags) as $tag)
            <meta property="article:tag" content="{{ trim(str_replace('#', '', $tag)) }}" />
        @endforeach
    @endif

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="{{ url()->current() }}" />
    <meta property="twitter:title" content="{{ $article->title }} - {{ $settings['site_name'] ?? 'IZI Travel' }}" />
    <meta property="twitter:description" content="{{ $article->excerpt ?? ($settings['site_description'] ?? '') }}" />
    <meta property="twitter:image" content="{{ $article->image_url }}" />

    <!-- Structured Data: Article Schema -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "Article",
      "mainEntityOfPage": {
        "@@type": "WebPage",
        "@@id": "{{ url()->current() }}"
      },
      "headline": "{{ str_replace('"', '\"', $article->title) }}",
      "description": "{{ str_replace('"', '\"', $article->excerpt ?? '') }}",
      "image": "{{ $article->image_url }}",
      "author": {
        "@@type": "Person",
        "name": "{{ $article->author ?? 'IZI Travel' }}",
        "jobTitle": "{{ $article->author_role ?? '' }}"
      },
      "publisher": {
        "@@type": "Organization",
        "name": "{{ $settings['site_name'] ?? 'IZI Travel' }}",
        "logo": {
          "@@type": "ImageObject",
          "url": "{{ asset($settings['site_logo'] ?? 'images/Izi LOGO.webp') }}"
        }
      },
      "datePublished": "{{ $article->created_at ? $article->created_at->toIso8601String() : '' }}",
      "dateModified": "{{ $article->updated_at ? $article->updated_at->toIso8601String() : '' }}"{{ !empty($article->tags) ? ",\n      \"keywords\": \"" . str_replace('"', '\"', str_replace('#', '', $article->tags)) . "\"" : "" }}
    }
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Google Fonts: Plus Jakarta Sans, Inter, & El Messiri -->
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <style>
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

        .prose p {
            margin-bottom: 1.5rem;
            color: #4b5563; /* text-gray-650 style */
            line-height: 1.8;
            font-size: 1.05rem;
            font-weight: 300;
        }

        .prose h2 {
            font-size: 1.625rem;
            color: #0f172a;
            margin-top: 2.25rem;
            margin-bottom: 1rem;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 800 !important;
        }

        .prose h3 {
            font-size: 1.375rem;
            color: #0f172a;
            margin-top: 2rem;
            margin-bottom: 0.75rem;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 800 !important;
        }

        .prose h4 {
            font-size: 1.125rem;
            color: #0f172a;
            margin-top: 1.75rem;
            margin-bottom: 0.75rem;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 700 !important;
        }

        .prose blockquote {
            border-left: 4px solid #0388d1;
            padding-left: 1.25rem;
            font-style: italic;
            color: #374151;
            margin: 1.75rem 0;
            background-color: #f0f7fc;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            border-radius: 0 12px 12px 0;
        }

        /* HTML formatting overrides inside .prose */
        .prose strong, .prose b {
            font-weight: 700 !important;
            color: #0f172a;
        }

        .prose em, .prose i {
            font-style: italic !important;
        }

        .prose ul {
            list-style-type: disc !important;
            padding-left: 1.75rem !important;
            margin-top: 1rem !important;
            margin-bottom: 1.5rem !important;
        }

        .prose ol {
            list-style-type: decimal !important;
            padding-left: 1.75rem !important;
            margin-top: 1rem !important;
            margin-bottom: 1.5rem !important;
        }

        .prose li {
            margin-bottom: 0.5rem !important;
            color: #4b5563;
            line-height: 1.7;
            font-size: 1.05rem;
            font-weight: 300;
            list-style: inherit !important;
        }

        .prose a {
            color: #2563eb !important;
            text-decoration: underline !important;
            font-weight: 500;
            transition: color 0.2s;
        }

        .prose a:hover {
            color: #1d4ed8 !important;
        }

        .prose img {
            max-width: 100% !important;
            height: auto !important;
            border-radius: 1rem !important;
            margin: 2rem auto !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            display: block;
        }

        .prose figure.image {
            margin: 2rem auto !important;
            max-width: 100% !important;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .prose figure.image img {
            border-radius: 1rem !important;
            max-width: 100% !important;
            height: auto !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }

        .prose figure.media {
            margin: 2rem auto !important;
            max-width: 100% !important;
        }

        .prose hr {
            border: 0;
            border-top: 1px solid #e2e8f0;
            margin: 2rem 0 !important;
        }
    </style>
</head>

<body class="public-interior izi-public overflow-x-hidden antialiased">
    <!-- BEGIN: Floating Modern Header -->
    @include('public.partials.navigation')

    <!-- Main Container -->
    <main id="main-content" class="article-detail mx-auto max-w-[1248px] px-4 pb-24 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex text-xs font-bold text-slate-400 gap-2 items-center tracking-wide uppercase mb-6" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Beranda</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('public.articles.index') }}" class="hover:text-blue-600 transition">Artikel</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-slate-600 truncate">{{ $article->category }}</span>
        </nav>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-12 mt-4">

            <!-- Left: Article Content (Col 8) -->
            <article class="interior-card lg:col-span-8 p-6 md:p-10">

                <!-- Category Badge -->
                <span class="bg-blue-50 text-blue-600 text-[10px] font-black px-3.5 py-1.5 rounded-full uppercase tracking-wider border border-blue-100/30 w-fit">
                    {{ $article->category }}
                </span>

                <!-- Article Title -->
                <h1 class="text-3xl md:text-5xl font-black text-[#102b52] leading-[1.16] tracking-tight mt-6 mb-6">
                    {{ $article->title }}
                </h1>

                <!-- Article Metadata -->
                <div class="flex items-center gap-4 text-xs text-slate-400 font-bold flex-wrap border-b border-slate-100 pb-6 mb-8">
                    <span class="flex items-center gap-1.5">
                        <i data-lucide="calendar" class="w-4 h-4 text-blue-500"></i>
                        {{ $article->published_at }}
                    </span>
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-350"></span>
                    <span class="flex items-center gap-1.5">
                        <i data-lucide="clock" class="w-4 h-4 text-blue-500"></i>
                        {{ $article->read_time }} Baca
                    </span>
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-350"></span>
                    <span class="flex items-center gap-1.5">
                        <i data-lucide="user-check" class="w-4 h-4 text-blue-500"></i>
                        {{ $article->author }}
                    </span>
                </div>

                <!-- Featured Image -->
                <div class="h-64 sm:h-96 md:h-[450px] w-full rounded-2xl md:rounded-3xl overflow-hidden bg-slate-100 relative shadow-lg mb-8">
                    <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover" width="800" height="450" fetchpriority="high" decoding="async" />
                </div>

                <!-- Main Body Content -->
                <div class="prose max-w-none select-text mb-12">
                    {!! $article->content !!}
                </div>

                <!-- Article Tags -->
                @if(!empty($article->tags))
                    @php
                        $tagsList = array_filter(array_map('trim', explode(',', $article->tags)));
                    @endphp
                    @if(!empty($tagsList))
                        <div class="flex flex-wrap items-center gap-2 mb-10 pt-5 border-t border-slate-100 dark:border-slate-800">
                            <span class="text-xs text-slate-400 font-extrabold uppercase tracking-wider mr-1">Tags:</span>
                            @foreach($tagsList as $tag)
                                <a href="{{ route('public.articles.tag', str_replace('#', '', $tag)) }}" class="inline-flex items-center text-xs font-semibold text-blue-600 hover:text-white bg-blue-50/50 hover:bg-blue-600 border border-blue-100/40 hover:border-transparent px-3 py-1 rounded-xl transition duration-200">
                                    {{ str_starts_with($tag, '#') ? $tag : '#' . $tag }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                @endif

            </article>

            <!-- Right: Sidebar (Col 4) -->
            <aside class="lg:col-span-4 space-y-8">

                <!-- Recommended Articles Widget -->
                <div class="interior-card p-6 md:p-8">
                    <h3 class="font-extrabold text-slate-900 text-lg mb-6 flex items-center gap-2 border-b border-slate-50 pb-4">
                        <i data-lucide="sparkles" class="w-5 h-5 text-amber-500"></i>
                        Baca Juga
                    </h3>
                    <div class="space-y-6">
                        @foreach ($recommendedArticles as $rec)
                            <div class="group flex items-start gap-4 hover:-translate-y-0.5 transition duration-200">
                                <!-- Thumbnail -->
                                <div class="w-20 h-20 rounded-2xl overflow-hidden bg-slate-100 flex-shrink-0 relative shadow-sm border border-slate-50">
                                    <img src="{{ $rec->image_url }}" alt="{{ $rec->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" width="300" height="200" loading="lazy" decoding="async" />
                                </div>
                                <!-- Title & Category -->
                                <div class="space-y-1">
                                    <span class="text-blue-600 text-[9px] font-extrabold uppercase tracking-wide block">
                                        {{ $rec->category }}
                                    </span>
                                    <h4 class="font-extrabold text-xs md:text-sm text-slate-900 leading-snug group-hover:text-blue-600 transition line-clamp-2">
                                        <a href="{{ route('articles.show', $rec->slug) }}">{{ $rec->title }}</a>
                                    </h4>
                                    <span class="text-[10px] text-slate-400 font-bold block mt-1 flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-3 h-3"></i> {{ $rec->published_at }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Sidebar CTA Package -->
                <div class="bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 text-white rounded-3xl p-8 shadow-xl shadow-blue-950/20 relative overflow-hidden group">
                    <!-- Glow backdrops -->
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-amber-500/10 rounded-full blur-3xl group-hover:bg-amber-500/20 transition duration-500"></div>
                    <div class="absolute -left-10 -top-10 w-40 h-40 bg-blue-500/10 rounded-full blur-3xl"></div>

                    <div class="relative z-10 space-y-6">
                        <div class="bg-amber-500 text-slate-950 p-3.5 rounded-2xl w-fit font-bold shadow-lg shadow-amber-500/20">
                            <i data-lucide="compass" class="w-6 h-6"></i>
                        </div>
                        <div class="space-y-2">
                            <h3 class="font-extrabold text-amber-400 text-lg leading-snug">Wujudkan Umrah Impian Anda</h3>
                            <p class="text-slate-350 text-xs leading-relaxed font-light">
                                Nikmati kemudahan beribadah di Ring 1 pelataran Masjidil Haram & Nabawi bersama IZI Travel.
                            </p>
                        </div>

                        <div class="h-px bg-white/10"></div>

                        <a href="https://wa.me/{{ $wa_phone }}?text=Halo%20Admin%20IZI%20Travel,%20saya%20tertarik%20bertanya%20mengenai%20paket%20umrah." target="_blank" class="w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-3.5 px-6 rounded-2xl font-bold shadow-lg shadow-blue-500/20 active:scale-95 transition-all duration-300 flex items-center justify-center gap-2 text-xs">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
                            </svg>
                            Konsultasi Sekarang
                        </a>
                    </div>
                </div>

            </aside>
        </div>
    </main>

    <!-- BEGIN: Footer -->
    @include('public.partials.footer')





    <!-- Lucide Icons -->
    <script>
        // Init Lucide
        if (window.lucide) {
            window.lucide.createIcons();
        }




    </script>
    <!-- Resolve oembed (video elements) for CKEditor 5 -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('oembed[url]').forEach(element => {
                const url = element.getAttribute('url');
                if (!url) return;

                let embedUrl = null;

                // YouTube
                if (url.includes('youtube.com') || url.includes('youtu.be')) {
                    let videoId = '';
                    if (url.includes('youtube.com/watch')) {
                        const urlParams = new URLSearchParams(new URL(url).search);
                        videoId = urlParams.get('v');
                    } else if (url.includes('youtu.be/')) {
                        videoId = url.split('youtu.be/')[1].split('?')[0];
                    } else if (url.includes('youtube.com/embed/')) {
                        videoId = url.split('youtube.com/embed/')[1].split('?')[0];
                    }
                    if (videoId) {
                        embedUrl = `https://www.youtube.com/embed/${videoId}`;
                    }
                }
                // Vimeo
                else if (url.includes('vimeo.com')) {
                    const videoId = url.split('/').pop();
                    if (videoId) {
                        embedUrl = `https://player.vimeo.com/video/${videoId}`;
                    }
                }

                if (embedUrl) {
                    const iframe = document.createElement('iframe');
                    iframe.setAttribute('src', embedUrl);
                    iframe.setAttribute('frameborder', '0');
                    iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
                    iframe.setAttribute('allowfullscreen', 'true');
                    iframe.className = 'w-full aspect-video rounded-2xl my-6 shadow-lg border border-slate-100';

                    element.parentNode.replaceChild(iframe, element);
                }
            });
        });
    </script>
</body>

</html>

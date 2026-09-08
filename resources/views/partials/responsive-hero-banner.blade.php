@php
    // Responsive Hero Banner partial.
    // Usage: @include('partials.responsive-hero-banner', [...overrides])
    $rhb = [
        // Navigation
        'logoUrl'       => $logoUrl ?? '',
        'navLinks'      => $navLinks ?? [
            ['label' => 'Home', 'href' => '#', 'isActive' => true],
            ['label' => 'Missions', 'href' => '#'],
            ['label' => 'Destinations', 'href' => '#'],
            ['label' => 'Technology', 'href' => '#'],
            ['label' => 'Book Flight', 'href' => '#'],
        ],
        'ctaButtonText'     => $ctaButtonText ?? 'Reserve Seat',
        'ctaButtonHref'     => $ctaButtonHref ?? '#',
        // Hero content
        'badgeLabel'        => $badgeLabel ?? 'New',
        'badgeText'         => $badgeText ?? 'First Commercial Flight to Mars 2026',
        'title'             => $title ?? 'Journey Beyond Earth',
        'titleLine2'        => $titleLine2 ?? 'Into the Cosmos',
        'description'       => $description ?? 'Experience the cosmos like never before. Our advanced spacecraft and cutting-edge technology make interplanetary travel accessible, safe, and unforgettable.',
        'primaryButtonText' => $primaryButtonText ?? 'Book Your Journey',
        'primaryButtonHref' => $primaryButtonHref ?? '#',
        'secondaryButtonText' => $secondaryButtonText ?? 'Watch Launch',
        'secondaryButtonHref' => $secondaryButtonHref ?? '#',
        'partnersTitle' => $partnersTitle ?? 'Partnering with leading space agencies worldwide',
        'partners'      => $partners ?? [],
    ];

    // Background image: allow override, otherwise fall back to the admin hero image
    // or the default 4K Kaaba background already used by the site's welcome page.
    $rhb['backgroundImageUrl'] = $backgroundImageUrl ?? (
        !empty($settings['hero_image'])
            ? (str_starts_with($settings['hero_image'], 'images/') ? asset($settings['hero_image']) : asset('storage/' . $settings['hero_image']))
            : asset('images/hero_kaaba_4k.webp')
    );
@endphp

<section
    class="w-full isolate min-h-screen overflow-hidden relative"
    x-data="{ rhbMenuOpen: false }"
    data-purpose="responsive-hero-banner"
>
    <img
        src="{{ $rhb['backgroundImageUrl'] }}"
        alt=""
        class="w-full h-full object-cover absolute top-0 right-0 bottom-0 left-0"
        fetchpriority="high"
        decoding="async"
    />
    <div class="pointer-events-none absolute inset-0 ring-1 ring-black/30"></div>

    <header class="z-10 xl:top-4 relative">
        <div class="mx-6">
            <div class="flex items-center justify-between pt-4">
                @if(!empty($rhb['logoUrl']))
                    <a
                        href="#"
                        class="inline-flex items-center justify-center bg-center w-[100px] h-[40px] bg-cover rounded"
                        style="background-image: url('{{ $rhb['logoUrl'] }}');"
                        aria-label="Home"
                    ></a>
                @else
                    <a href="#" class="text-white text-lg font-bold tracking-tight font-heading">IZI Travel</a>
                @endif

                <nav class="hidden md:flex items-center gap-2">
                    <div class="flex items-center gap-1 rounded-full bg-white/5 px-1 py-1 ring-1 ring-white/10 backdrop-blur">
                        @foreach($rhb['navLinks'] as $link)
                            <a
                                href="{{ $link['href'] }}"
                                class="px-3 py-2 text-sm font-medium hover:text-white transition-colors {{ ($link['isActive'] ?? false) ? 'text-white/90' : 'text-white/80' }}"
                            >
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                        <a
                            href="{{ $rhb['ctaButtonHref'] }}"
                            class="ml-1 inline-flex items-center gap-2 rounded-full bg-white px-3.5 py-2 text-sm font-medium text-neutral-900 hover:bg-white/90 transition-colors"
                        >
                            {{ $rhb['ctaButtonText'] }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                <path d="M7 7h10v10" />
                                <path d="M7 17 17 7" />
                            </svg>
                        </a>
                    </div>
                </nav>

                <button
                    @click="rhbMenuOpen = !rhbMenuOpen"
                    class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/10 ring-1 ring-white/15 backdrop-blur"
                    :aria-expanded="rhbMenuOpen.toString()"
                    aria-label="Toggle menu"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-white/90">
                        <path d="M4 5h16" />
                        <path d="M4 12h16" />
                        <path d="M4 19h16" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    @if(count($rhb['navLinks']) > 0)
        {{-- Mobile dropdown menu (overlay, floats above hero content) --}}
        <nav
            class="md:hidden absolute left-0 right-0 top-4 z-30 px-6" x-cloak
            x-show="rhbMenuOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            @click.outside="rhbMenuOpen = false"
        >
            <div class="rounded-2xl bg-neutral-900/95 backdrop-blur ring-1 ring-white/10 shadow-xl shadow-black/30 p-2 space-y-1">
                @foreach($rhb['navLinks'] as $link)
                    <a
                        href="{{ $link['href'] }}"
                        @click="rhbMenuOpen = false"
                        class="block px-3 py-2 text-sm font-medium {{ ($link['isActive'] ?? false) ? 'text-white' : 'text-white/75' }} hover:bg-white/10 rounded-lg transition-colors"
                    >
                        {{ $link['label'] }}
                    </a>
                @endforeach
                <a
                    href="{{ $rhb['ctaButtonHref'] }}"
                    @click="rhbMenuOpen = false"
                    class="flex items-center justify-center gap-2 mt-1 rounded-full bg-white px-3.5 py-2 text-sm font-medium text-neutral-900 hover:bg-white/90 transition-colors"
                >
                    {{ $rhb['ctaButtonText'] }}
                </a>
            </div>
        </nav>
    @endif

    <div class="z-10 relative">
        <div class="sm:pt-28 md:pt-32 lg:pt-40 max-w-7xl mx-auto pt-28 px-6 pb-16">
            <div class="mx-auto max-w-3xl text-center flex flex-col items-center justify-center">
                <div class="mb-6 inline-flex items-center gap-3 rounded-full bg-white/10 px-2.5 py-2 ring-1 ring-white/15 backdrop-blur animate-fade-slide-in">
                    <span class="inline-flex items-center text-xs font-medium text-neutral-900 bg-white/90 rounded-full py-0.5 px-2">{{ $rhb['badgeLabel'] }}</span>
                    <span class="text-sm font-medium text-white/90">{{ $rhb['badgeText'] }}</span>
                </div>

                <h1 class="sm:text-5xl md:text-6xl lg:text-7xl leading-tight text-4xl text-white tracking-tight font-serif font-normal animate-fade-slide-in">
                    {{ $rhb['title'] }}
                    <br class="hidden sm:block" />
                    {{ $rhb['titleLine2'] }}
                </h1>

                <p class="sm:text-lg animate-fade-slide-in text-base text-white/80 max-w-2xl mt-6 mx-auto">
                    {{ $rhb['description'] }}
                </p>

                <div class="flex flex-col sm:flex-row sm:gap-4 mt-10 gap-3 items-center justify-center animate-fade-slide-in">
                    <a
                        href="{{ $rhb['primaryButtonHref'] }}"
                        class="inline-flex items-center gap-2 hover:bg-white/15 text-sm font-medium text-white bg-white/10 ring-white/15 ring-1 rounded-full py-3 px-5 transition-colors"
                    >
                        {{ $rhb['primaryButtonText'] }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </a>
                    <a
                        href="{{ $rhb['secondaryButtonHref'] }}"
                        class="inline-flex items-center gap-2 rounded-full bg-transparent px-5 py-3 text-sm font-medium text-white/90 hover:text-white transition-colors"
                    >
                        {{ $rhb['secondaryButtonText'] }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                            <path d="M5 5a2 2 0 0 1 3.008-1.728l11.997 6.998a2 2 0 0 1 .003 3.458l-12 7A2 2 0 0 1 5 19z" />
                        </svg>
                    </a>
                </div>
            </div>

            @if(!empty($rhb['partnersTitle']) || count($rhb['partners']) > 0)
                <div class="mx-auto mt-20 max-w-5xl">
                    <p class="animate-fade-slide-in text-sm text-white/70 text-center">{{ $rhb['partnersTitle'] }}</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 animate-fade-slide-in text-white/70 mt-6 items-center justify-items-center gap-4">
                        @foreach($rhb['partners'] as $partner)
                            <a
                                href="{{ $partner['href'] }}"
                                class="inline-flex items-center justify-center bg-center w-[120px] h-[36px] bg-cover rounded-full opacity-80 hover:opacity-100 transition-opacity"
                                style="background-image: url('{{ $partner['logoUrl'] }}');"
                            ></a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

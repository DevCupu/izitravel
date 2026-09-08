@php
    // Promo carousel — auto-sliding promotion banner.
    // Slides come from admin (App\Models\Promo via $promos); fall back to defaults when empty.
    $promoSlides = ($promos ?? collect())
        ->map(
            fn($p) => [
                'image' => $p->image_url ?? asset('images/package_kaaba.webp'),
                'label' => $p->label ?: 'Promo',
                'title' => $p->title,
                'description' => $p->description,
                'cta_text' => $p->cta_text ?: 'Lihat Detail',
                'cta_href' => $p->action_url ?: '#',
            ],
        )
        ->values()
        ->all();

    if (empty($promoSlides)) {
        $promoSlides = [
            [
                'image' => asset('images/package_kaaba.webp'),
                'label' => 'Promo Umrah',
                'title' => 'Paket Umrah Reguler 2026',
                'description' =>
                    'Keberangkatan rutin dengan harga terjangkau, hotel bintang lima di Ring 1 Masjidil Haram & Nabawi, serta pendampingan mutawwif berpengalaman.',
                'cta_text' => 'Lihat Paket',
                'cta_href' => '#paket-umrah',
            ],
            [
                'image' => asset('images/package_nabawi.webp'),
                'label' => 'Promo Spesial',
                'title' => 'Umrah Ramadhan & Idul Fitri',
                'description' =>
                    'Maksimalkan ibadah di bulan penuh berkah bersama keluarga. Visa, tiket, hotel, dan perlengkapan ibadah sudah termasuk.',
                'cta_text' => 'Konsultasi Gratis',
                'cta_href' => '#kontak',
            ],
            [
                'image' => asset('images/section_makkah_wide.webp'),
                'label' => 'Kuota Terbatas',
                'title' => 'Promo Rombongan & Keluarga',
                'description' =>
                    'Diskon spesial untuk keberangkatan rombongan serta kebutuhan masa tunggu tercepat untuk jamaah. Mulai rencanakan perjalanan ibadah Anda.',
                'cta_text' => 'Hubungi Kami',
                'cta_href' => '#kontak',
            ],
        ];
    }
    $promoInterval = $promoInterval ?? 5000;
@endphp

<section x-data="{
    active: 0,
    count: {{ count($promoSlides) }},
    timer: null,
    touchStartX: 0,
    start() {
        this.stop();
        this.timer = setInterval(() => {
            this.active = (this.active + 1) % this.count;
        }, {{ $promoInterval }});
    },
    stop() {
        if (this.timer) {
            clearInterval(this.timer);
            this.timer = null;
        }
    },
    go(index) {
        this.active = (index + this.count) % this.count;
    },
    touchStart(e) {
        this.touchStartX = e.changedTouches[0].clientX;
    },
    touchEnd(e) {
        const dx = e.changedTouches[0].clientX - this.touchStartX;
        if (Math.abs(dx) > 48) {
            this.go(this.active + (dx < 0 ? 1 : -1));
        }
    }
}" x-init="start()" @mouseenter="stop()" @mouseleave="start()"
    class="relative z-10 mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 mt-8 sm:mt-10 lg:mt-12 mb-10 sm:mb-12 lg:mb-16"
    aria-label="Promosi" data-purpose="promo-carousel">
    <div
        class="relative select-none overflow-hidden rounded-2xl sm:rounded-3xl ring-1 ring-white/15 shadow-xl shadow-blue-950/30">
        {{-- Sliding track --}}
        <div class="flex h-[430px] sm:h-[460px] md:h-[480px] lg:h-[520px] transition-transform duration-700 ease-out will-change-transform"
            :style="`transform: translateX(-${active * 100}%)`" @touchstart.passive="touchStart($event)"
            @touchend.passive="touchEnd($event)">
            @foreach ($promoSlides as $slide)
                <div class="relative h-full w-full shrink-0">
                    {{-- Background image --}}
                    <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}"
                        class="absolute inset-0 h-full w-full object-cover" loading="lazy" decoding="async"
                        draggable="false" />
                    {{-- Scrims: bottom-up for text, soft left fade for composition --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-950/95 via-blue-950/45 to-blue-950/10">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-950/60 via-blue-950/10 to-transparent">
                    </div>

                    {{-- Content: bottom-left, no floating box --}}
                    <div
                        class="absolute inset-0 z-10 flex flex-col items-start justify-end px-6 pb-14 pt-20 sm:px-10 sm:pb-16 lg:px-14">
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-[#c89e2b] px-3.5 py-1.5 text-[11px] font-bold uppercase tracking-[0.14em] text-[#113a6b] shadow-md shadow-black/25">
                            <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                            {{ $slide['label'] }}
                        </span>

                        <h3
                            class="mt-4 max-w-xl text-2xl font-extrabold leading-tight text-white text-shadow-hero sm:text-3xl lg:text-4xl font-poppins">
                            {{ $slide['title'] }}
                        </h3>

                        <p
                            class="mt-3 max-w-xl text-sm leading-relaxed text-white/85 text-shadow-hero sm:text-base font-poppins line-clamp-3 sm:line-clamp-none">
                            {{ $slide['description'] }}
                        </p>

                        <a href="{{ $slide['cta_href'] }}"
                            class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#c89e2b] px-6 py-3 text-sm font-bold text-[#113a6b] shadow-lg shadow-black/25 transition hover:bg-[#f5b953] active:scale-95 sm:mt-6 sm:w-auto font-poppins">
                            {{ $slide['cta_text'] }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="h-4 w-4">
                                <path d="M5 12h14" />
                                <path d="m12 5 7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Prev / Next arrows (desktop only; mobile uses swipe) --}}
        <button type="button" @click="go(active - 1)"
            class="absolute left-3 top-1/2 z-20 hidden -translate-y-1/2 h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white ring-1 ring-white/25 backdrop-blur transition hover:bg-white/20 md:inline-flex"
            aria-label="Promo sebelumnya">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <path d="m15 18-6-6 6-6" />
            </svg>
        </button>
        <button type="button" @click="go(active + 1)"
            class="absolute right-3 top-1/2 z-20 hidden -translate-y-1/2 h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white ring-1 ring-white/25 backdrop-blur transition hover:bg-white/20 md:inline-flex"
            aria-label="Promo selanjutnya">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <path d="m9 18 6-6-6-6" />
            </svg>
        </button>

        {{-- Dots with autoplay progress fill --}}
        <div class="absolute bottom-5 left-1/2 z-20 flex -translate-x-1/2 items-center gap-2.5">
            @foreach ($promoSlides as $i => $slide)
                <button type="button" @click="go({{ $loop->index }})"
                    class="relative h-1.5 overflow-hidden rounded-full transition-all duration-300"
                    :class="active === {{ $loop->index }} ? 'w-8 bg-white/25' : 'w-2 bg-white/35 hover:bg-white/60'"
                    :aria-label="'Ke promo ' + ({{ $loop->index }} + 1)">
                    <div x-show="active === {{ $loop->index }}" class="absolute inset-y-0 left-0 h-full bg-[#e8c96a]"
                        style="animation: promoBar {{ $promoInterval }}ms linear forwards;"></div>
                </button>
            @endforeach
        </div>
    </div>
</section>

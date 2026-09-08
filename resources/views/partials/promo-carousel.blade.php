@php
    // Promo carousel — auto-sliding promotion banner.
    // Slides come from admin (App\Models\Promo via $promos); fall back to defaults when empty.
    $promoSlides = ($promos ?? collect())
        ->map(fn ($p) => [
            'image'       => $p->image_url ?? asset('images/package_kaaba.webp'),
            'label'       => $p->label ?: 'Promo',
            'title'       => $p->title,
            'description' => $p->description,
            'cta_text'    => $p->cta_text ?: 'Lihat Detail',
            'cta_href'    => $p->action_url ?: '#',
        ])
        ->values()
        ->all();

    if (empty($promoSlides)) {
        $promoSlides = [
            [
                'image'       => asset('images/package_kaaba.webp'),
                'label'       => 'Promo Umrah',
                'title'       => 'Paket Umrah Reguler 2026',
                'description' => 'Keberangkatan rutin dengan harga terjangkau, hotel bintang lima di Ring 1 Masjidil Haram & Nabawi, serta pendampingan mutawwif berpengalaman.',
                'cta_text'    => 'Lihat Paket',
                'cta_href'    => '#paket-umrah',
            ],
            [
                'image'       => asset('images/package_nabawi.webp'),
                'label'       => 'Promo Spesial',
                'title'       => 'Umrah Ramadhan & Idul Fitri',
                'description' => 'Maksimalkan ibadah di bulan penuh berkah bersama keluarga. Visa, tiket, hotel, dan perlengkapan ibadah sudah termasuk.',
                'cta_text'    => 'Konsultasi Gratis',
                'cta_href'    => '#kontak',
            ],
            [
                'image'       => asset('images/section_makkah_wide.webp'),
                'label'       => 'Kuota Terbatas',
                'title'       => 'Promo Rombongan & Keluarga',
                'description' => 'Diskon spesial untuk keberangkatan rombongan serta kebutuhan masa tunggu tercepat untuk jamaah. Mulai rencanakan perjalanan ibadah Anda.',
                'cta_text'    => 'Hubungi Kami',
                'cta_href'    => '#kontak',
            ],
        ];
    }
    $promoInterval = $promoInterval ?? 5000;
@endphp

<section
    x-data="{
        active: 0,
        count: {{ count($promoSlides) }},
        timer: null,
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
        }
    }"
    x-init="start()"
    @mouseenter="stop()"
    @mouseleave="start()"
    class="relative z-10 mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 mt-10 sm:mt-12 lg:mt-14 mb-10 sm:mb-12 lg:mb-14"
    aria-label="Promosi"
    data-purpose="promo-carousel"
>
    <div class="relative overflow-hidden rounded-3xl ring-1 ring-white/15 shadow-xl shadow-blue-950/30">
        {{-- Sliding track --}}
        <div
            class="flex transition-transform duration-700 ease-out will-change-transform"
            :style="`transform: translateX(-${active * 100}%)`"
        >
            @foreach($promoSlides as $slide)
                <div class="relative w-full shrink-0">
                    {{-- Background image --}}
                    <img
                        src="{{ $slide['image'] }}"
                        alt="{{ $slide['title'] }}"
                        class="absolute inset-0 h-full w-full object-cover"
                        loading="lazy"
                        decoding="async"
                    />
                    {{-- Gradient overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-950/95 via-blue-900/70 to-blue-900/25"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-950/75 via-transparent to-blue-950/45"></div>

                    {{-- Content (glassmorphism panel) --}}
                    <div class="relative z-10 flex min-h-[260px] md:min-h-[320px] lg:min-h-[360px] flex-col items-start justify-center px-6 py-10 sm:px-10 lg:px-14">
                        <div class="relative w-full sm:max-w-xl overflow-hidden rounded-2xl sm:rounded-3xl border border-white/15 bg-blue-950/45 p-6 sm:p-8 shadow-2xl shadow-blue-950/40 backdrop-blur-2xl">
                            <div class="pointer-events-none absolute inset-x-5 top-0 h-px bg-gradient-to-r from-transparent via-white/40 to-transparent"></div>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#c89e2b]/15 ring-1 ring-[#c89e2b]/40 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.14em] text-[#e8c96a] font-poppins">
                                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                                {{ $slide['label'] }}
                            </span>

                            <h3 class="mt-4 text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight text-white font-poppins text-shadow-hero">
                                {{ $slide['title'] }}
                            </h3>

                            <p class="mt-3 text-sm sm:text-base leading-relaxed text-white/85 font-poppins">
                                {{ $slide['description'] }}
                            </p>

                            <a
                                href="{{ $slide['cta_href'] }}"
                                class="mt-6 inline-flex items-center gap-2 rounded-full bg-[#c89e2b] px-6 py-3 text-sm font-bold text-[#113a6b] shadow-md shadow-[#c89e2b]/20 transition hover:bg-[#f5b953] active:scale-95 font-poppins"
                            >
                                {{ $slide['cta_text'] }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                    <path d="M5 12h14" />
                                    <path d="m12 5 7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Prev / Next arrows --}}
        <button
            type="button"
            @click="go(active - 1)"
            class="absolute left-3 top-1/2 z-20 -translate-y-1/2 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white ring-1 ring-white/25 backdrop-blur transition hover:bg-white/20"
            aria-label="Promo sebelumnya"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <path d="m15 18-6-6 6-6" />
            </svg>
        </button>
        <button
            type="button"
            @click="go(active + 1)"
            class="absolute right-3 top-1/2 z-20 -translate-y-1/2 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white ring-1 ring-white/25 backdrop-blur transition hover:bg-white/20"
            aria-label="Promo selanjutnya"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <path d="m9 18 6-6-6-6" />
            </svg>
        </button>

        {{-- Autoplay progress bar --}}
        <div class="absolute bottom-0 left-0 z-20 h-1 w-full bg-white/10">
            <div
                class="h-full bg-[#c89e2b]"
                :key="active"
                style="animation: promoBar {{ $promoInterval }}ms linear forwards;"
            ></div>
        </div>

        {{-- Dots indicator --}}
        <div class="absolute bottom-4 left-1/2 z-20 flex -translate-x-1/2 items-center gap-2">
            @foreach($promoSlides as $i => $slide)
                <button
                    type="button"
                    @click="go({{ $loop->index }})"
                    class="h-2 rounded-full transition-all duration-300"
                    :class="active === {{ $loop->index }} ? 'w-6 bg-[#c89e2b]' : 'w-2 bg-white/40 hover:bg-white/70'"
                    :aria-label="'Ke promo ' + ({{ $loop->index }} + 1)"
                ></button>
            @endforeach
        </div>
    </div>
</section>
@php
    $groupedPackages = $packages->groupBy(fn ($package) => $package->category ?: 'Lainnya');
    $categoryOrder = \App\Models\Category::query()->where('is_active', true)->orderBy('order')->pluck('name')
        ->filter(fn ($name) => in_array($name, ['Premium', 'Ekonomi', 'VVIP', 'Lainnya'], true) || $groupedPackages->has($name))
        ->merge($groupedPackages->keys())->unique();
@endphp

<section class="umrah-packages" id="paket-umrah" data-purpose="packages-grid" aria-labelledby="umrah-packages-heading">
    <div class="umrah-packages__shell">
        <header class="umrah-packages__header">
            <div class="umrah-packages__heading">
                <span class="umrah-packages__eyebrow">{{ $settings['packages_label'] ?? 'Paket Pilihan' }}</span>
                <h2 id="umrah-packages-heading">{{ $settings['packages_section_title'] ?? 'Paket Umrah Kami' }}</h2>
                <p>{{ $settings['packages_section_subtitle'] ?? 'Pilih tanggal, durasi, dan akomodasi yang sesuai untuk perjalanan ibadah Anda.' }}</p>
            </div>
            <div class="umrah-packages__guide" aria-label="Panduan memilih paket">
                <i data-lucide="compass" aria-hidden="true"></i>
                <span>Bandingkan jadwal, hotel di Tanah Suci, dan harga sebelum memilih.</span>
            </div>
        </header>

        @if ($packages->isNotEmpty())
            <div class="umrah-packages__toolbar">
                <div class="umrah-packages__filter-scroll" aria-label="Filter kategori paket">
                    <div class="umrah-packages__filters" role="group" aria-label="Kategori paket umrah">
                        <button type="button" class="umrah-package-filter is-active" data-package-filter="all" aria-pressed="true">
                            {{ $settings['packages_filter_all'] ?? 'Semua Paket' }} <span>{{ $packages->count() }}</span>
                        </button>
                        @foreach ($categoryOrder as $categoryName)
                            <button type="button" class="umrah-package-filter" data-package-filter="{{ Str::slug($categoryName) }}" aria-pressed="false">
                                {{ $categoryName }} <span>{{ $groupedPackages->get($categoryName, collect())->count() }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
                <p class="umrah-packages__count" id="umrah-package-count" aria-live="polite">Menampilkan {{ $packages->count() }} paket</p>
            </div>

            <div class="umrah-packages__grid" id="packages-grid">
                @foreach ($packages as $package)
                    @php
                        $categoryName = $package->category ?: 'Lainnya';
                        $hotelMakkah = trim($package->hotel_makkah ?: '');
                        $hotelMadinah = trim($package->hotel_madinah ?: '');
                        $hotelGeneral = trim($package->hotel ?: '');
                        $departure = $package->departure_date?->locale('id')->translatedFormat('d F Y');
                    @endphp
                    <article class="umrah-package-card" data-category="{{ Str::slug($categoryName) }}" data-purpose="package-item">
                        <div class="umrah-package-card__media">
                            <img src="{{ $package->image_url ?: asset('images/package_kaaba.webp') }}" alt="{{ $package->name }}" width="640" height="400" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='{{ asset('images/package_kaaba.webp') }}';">
                            <div class="umrah-package-card__tags">
                                <span class="umrah-package-card__category">{{ $categoryName }}</span>
                                @if ($package->badge_label)
                                    <span class="umrah-package-card__badge">{{ $package->badge_label }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="umrah-package-card__content">
                            <div class="umrah-package-card__meta">
                                @if ($departure)
                                    <span><i data-lucide="calendar-days" aria-hidden="true"></i>{{ $departure }}</span>
                                @endif
                                @if ($package->duration_days)
                                    <span><i data-lucide="clock-3" aria-hidden="true"></i>{{ $package->duration_days }} hari</span>
                                @endif
                            </div>
                            <h3><a href="{{ route('packages.show', $package->slug) }}">{{ $package->name }}</a></h3>

                            @if ($package->start_city || $package->airline)
                                <div class="umrah-package-card__flight">
                                    <i data-lucide="plane" aria-hidden="true"></i>
                                    <span>
                                        @if ($package->start_city)Dari {{ $package->start_city }}@endif
                                        @if ($package->start_city && $package->airline)<span aria-hidden="true"> · </span>@endif
                                        @if ($package->airline){{ $package->airline }}@endif
                                    </span>
                                </div>
                            @endif

                            @if ($hotelMakkah || $hotelMadinah || $hotelGeneral)
                                <div class="umrah-package-card__stay" aria-label="Akomodasi paket">
                                    <span class="umrah-package-card__stay-title">Akomodasi di Tanah Suci</span>
                                    @if ($hotelMakkah || $hotelMadinah)
                                        @if ($hotelMakkah)
                                            <div class="umrah-package-card__stay-row">
                                                <span class="umrah-package-card__city">Makkah</span>
                                                <span class="umrah-package-card__hotel" title="{{ $hotelMakkah }}">{{ $hotelMakkah }}</span>
                                                @if ($package->hotel_makkah_nights)<small>{{ $package->hotel_makkah_nights }} malam</small>@endif
                                            </div>
                                        @endif
                                        @if ($hotelMadinah)
                                            <div class="umrah-package-card__stay-row">
                                                <span class="umrah-package-card__city">Madinah</span>
                                                <span class="umrah-package-card__hotel" title="{{ $hotelMadinah }}">{{ $hotelMadinah }}</span>
                                                @if ($package->hotel_madinah_nights)<small>{{ $package->hotel_madinah_nights }} malam</small>@endif
                                            </div>
                                        @endif
                                    @else
                                        <div class="umrah-package-card__stay-row">
                                            <span class="umrah-package-card__city">Hotel</span>
                                            <span class="umrah-package-card__hotel" title="{{ $hotelGeneral }}">{{ $hotelGeneral }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="umrah-package-card__footer">
                            <div class="umrah-package-card__price">
                                <span>{{ $settings['packages_price_label'] ?? 'Mulai dari' }} · per jamaah</span>
                                <strong>Rp {{ number_format($package->price, 0, ',', '.') }}</strong>
                            </div>
                            <a href="{{ route('packages.show', $package->slug) }}" aria-label="Lihat detail paket {{ $package->name }}">
                                <span>{{ $settings['packages_detail_btn'] ?? 'Detail Paket' }}</span>
                                <i data-lucide="arrow-up-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="umrah-packages__filtered-empty" id="umrah-package-filter-empty" role="status" hidden>
                Belum ada keberangkatan untuk kategori ini. Pilih kategori lain atau <a href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel%2C%20saya%20ingin%20tanya%20jadwal%20umrah" target="_blank" rel="noopener noreferrer">tanya jadwal berikutnya</a>.
            </div>
        @else
            <div class="umrah-packages__empty">
                <i data-lucide="calendar-search" aria-hidden="true"></i>
                <h3>Belum ada paket yang ditampilkan</h3>
                <p>Hubungi tim IZI Travel untuk informasi jadwal keberangkatan terbaru.</p>
                <a href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel%2C%20saya%20ingin%20tanya%20jadwal%20umrah" target="_blank" rel="noopener noreferrer">Hubungi kami <i data-lucide="arrow-right" aria-hidden="true"></i></a>
            </div>
        @endif
    </div>
</section>

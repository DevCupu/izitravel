@php
    $departure = $package->departure_date->copy()->locale('id');
    $bookingUrl = "https://wa.me/{$wa_phone}?text=" . rawurlencode("Assalamu'alaikum IZI Travel, saya ingin bertanya tentang paket {$package->name}.");
    $prices = [
        ['name' => 'Quad', 'occupancy' => '4 orang sekamar', 'price' => $package->price],
        ['name' => 'Triple', 'occupancy' => '3 orang sekamar', 'price' => $package->price_triple ?: $package->price + 1500000],
        ['name' => 'Double', 'occupancy' => '2 orang sekamar', 'price' => $package->price_double ?: $package->price + 3000000],
    ];
    $inclusions = $package->inclusions ?? [];
    $exclusions = $package->exclusions ?? [];
    $features = $package->features ?? [];
    $notes = array_values(array_filter([
        $settings['package_note_1'] ?? 'Harga dan ketersediaan kursi dapat berubah. Konfirmasi harga akhir sebelum pembayaran.',
        $settings['package_note_2'] ?? 'Pendaftaran mengikuti ketersediaan kuota.',
        $settings['package_note_3'] ?? null,
    ]));
@endphp
<body class="izi-public package-detail antialiased">
    @include('public.partials.navigation')

    <main id="main-content" class="package-main">
        <div class="package-shell">
            <nav aria-label="Breadcrumb" class="package-breadcrumb">
                <a href="{{ url('/') }}">Beranda</a>
                <i data-lucide="chevron-right" aria-hidden="true"></i>
                <a href="{{ url('/#paket-umrah') }}">Paket Umrah</a>
                <i data-lucide="chevron-right" aria-hidden="true"></i>
                <span aria-current="page">{{ $package->name }}</span>
            </nav>

            <section class="package-hero" aria-labelledby="package-title">
                <div class="package-hero-copy">
                    <div class="package-kicker">
                        <span>Program Umrah {{ $departure->format('Y') }}</span>
                        @if ($package->badge_label)
                            <span class="package-badge">{{ $package->badge_label }}</span>
                        @endif
                    </div>
                    <h1 id="package-title">{{ $package->name }}</h1>
                    <p class="package-lead">Informasi perjalanan, hotel, dan pilihan biaya dalam satu halaman untuk membantu Anda merencanakan ibadah.</p>

                    <div class="package-departure" aria-label="Jadwal keberangkatan">
                        <div class="package-date">
                            <span class="package-date-day">{{ $departure->format('d') }}</span>
                            <span class="package-date-month">{{ $departure->translatedFormat('F') }}<br>{{ $departure->format('Y') }}</span>
                        </div>
                        <div class="package-departure-detail">
                            <span>Keberangkatan dari {{ $package->start_city ?: 'kota asal' }}</span>
                            <strong>{{ $package->duration_days }} hari perjalanan</strong>
                        </div>
                        <i data-lucide="plane-takeoff" aria-hidden="true"></i>
                    </div>

                    <div class="package-hero-facts">
                        <span><i data-lucide="plane" aria-hidden="true"></i>{{ $package->airline }}</span>
                        <span><i data-lucide="user-round-check" aria-hidden="true"></i>{{ $package->pembimbing ?: 'Muthawwif IZI Travel' }}</span>
                    </div>
                </div>
                <div class="package-hero-visual">
                    @if ($package->image_url)
                        <img src="{{ $package->image_url }}" alt="Gambar paket {{ $package->name }}" width="960" height="720" fetchpriority="high" decoding="async">
                    @else
                        <div class="package-image-fallback"><i data-lucide="landmark" aria-hidden="true"></i><span>Perjalanan ibadah bersama IZI Travel</span></div>
                    @endif
                </div>
            </section>

            <div class="package-layout">
                <div class="package-content">
                    <section id="pilihan-harga" class="package-section" aria-labelledby="price-title">
                        <div class="package-section-heading">
                            <div><span class="package-section-label">Pilih sesuai kebutuhan</span><h2 id="price-title">Pilihan kamar &amp; harga</h2></div>
                            <p>Harga per jemaah. Perbedaan harga mengikuti jumlah orang dalam satu kamar.</p>
                        </div>
                        <div class="package-price-grid">
                            @foreach ($prices as $option)
                                @php $optionUrl = "https://wa.me/{$wa_phone}?text=" . rawurlencode("Assalamu'alaikum IZI Travel, saya ingin bertanya tentang paket {$package->name}, kamar {$option['name']} ({$option['occupancy']})."); @endphp
                                <a class="package-price-option {{ $loop->first ? 'is-featured' : '' }}" href="{{ $optionUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Tanya kamar {{ $option['name'] }}, Rp {{ number_format($option['price'], 0, ',', '.') }} per jemaah melalui WhatsApp">
                                    <span class="package-price-name">{{ $option['name'] }}</span>
                                    <span class="package-price-occupancy">{{ $option['occupancy'] }}</span>
                                    <strong>Rp {{ number_format($option['price'], 0, ',', '.') }}</strong>
                                    <span class="package-price-action">Tanya kamar ini <i data-lucide="arrow-up-right" aria-hidden="true"></i></span>
                                </a>
                            @endforeach
                        </div>
                        <p class="package-price-footnote">Harga dapat berubah mengikuti kurs dan ketersediaan kursi. Lihat biaya di luar paket sebelum memesan.</p>
                    </section>

                    <section id="perjalanan-hotel" class="package-section" aria-labelledby="hotel-title">
                        <div class="package-section-heading"><div><span class="package-section-label">Rencana perjalanan</span><h2 id="hotel-title">Hotel &amp; fasilitas</h2></div></div>
                        <div class="package-hotel-grid">
                            <article class="package-hotel">
                                <div class="package-hotel-top"><span>Makkah</span>@if ($package->hotel_makkah_nights)<strong>{{ $package->hotel_makkah_nights }} malam</strong>@endif</div>
                                <h3>{{ $package->hotel_makkah ?: ($package->hotel ?: 'Hotel pilihan IZI Travel') }}</h3>
                                @if ($package->hotel_makkah_distance)<p><i data-lucide="map-pin" aria-hidden="true"></i>Estimasi jarak: {{ $package->hotel_makkah_distance }}</p>@endif
                            </article>
                            <article class="package-hotel">
                                <div class="package-hotel-top"><span>Madinah</span>@if ($package->hotel_madinah_nights)<strong>{{ $package->hotel_madinah_nights }} malam</strong>@endif</div>
                                <h3>{{ $package->hotel_madinah ?: ($package->hotel ?: 'Hotel pilihan IZI Travel') }}</h3>
                                @if ($package->hotel_madinah_distance)<p><i data-lucide="map-pin" aria-hidden="true"></i>Estimasi jarak: {{ $package->hotel_madinah_distance }}</p>@endif
                            </article>
                        </div>
                        @if (count($features))
                            <div class="package-features" aria-label="Fasilitas paket">
                                @foreach ($features as $feature)
                                    <span><i data-lucide="check" aria-hidden="true"></i>{{ $feature['label'] ?? '' }}</span>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    @if (!empty($package->itinerary))
                        <section class="package-section" aria-labelledby="itinerary-title">
                            <div class="package-section-heading"><div><span class="package-section-label">Agenda ibadah</span><h2 id="itinerary-title">Rencana perjalanan</h2></div></div>
                            <ol class="package-itinerary">
                                @foreach ($package->itinerary as $day)
                                    <li><span>Hari {{ $day['day'] ?? $loop->iteration }}</span><div><h3>{{ $day['title'] ?? '' }}</h3>@if (!empty($day['desc']))<p>{{ $day['desc'] }}</p>@endif</div></li>
                                @endforeach
                            </ol>
                        </section>
                    @endif

                    <section id="rincian-biaya" class="package-section" aria-labelledby="cost-title">
                        <div class="package-section-heading"><div><span class="package-section-label">Transparansi biaya</span><h2 id="cost-title">Apa saja yang dibayar?</h2></div><p>Periksa layanan yang sudah masuk harga paket dan pengeluaran yang perlu disiapkan terpisah.</p></div>
                        <div class="package-cost-grid">
                            <div class="package-cost-list">
                                <h3><i data-lucide="circle-check" aria-hidden="true"></i>Sudah termasuk</h3>
                                @forelse ($inclusions as $item)
                                    <div class="package-cost-item"><i data-lucide="check" aria-hidden="true"></i><div><strong>{{ $item['label'] ?? '' }}</strong>@if (!empty($item['desc']))<p>{{ $item['desc'] }}</p>@endif</div></div>
                                @empty
                                    <p class="package-empty">Hubungi admin untuk rincian layanan yang termasuk.</p>
                                @endforelse
                            </div>
                            <div class="package-cost-list package-cost-extra">
                                <h3><i data-lucide="circle-minus" aria-hidden="true"></i>Di luar harga paket</h3>
                                @forelse ($exclusions as $item)
                                    <div class="package-cost-item"><i data-lucide="plus" aria-hidden="true"></i><div><strong>{{ $item['label'] ?? '' }}</strong>@if (!empty($item['desc']))<p>{{ $item['desc'] }}</p>@endif</div></div>
                                @empty
                                    <p class="package-empty">Konfirmasi potensi biaya tambahan kepada admin.</p>
                                @endforelse
                            </div>
                        </div>
                    </section>

                    @if ($package->brochure_image_url)
                        <section class="package-section package-brochure" aria-labelledby="brochure-title">
                            <div class="package-section-heading"><div><span class="package-section-label">Dokumen program</span><h2 id="brochure-title">Brosur resmi</h2></div><a href="{{ $brochure_link }}" target="_blank" rel="noopener noreferrer">{{ $package->brochure ? 'Unduh PDF' : 'Minta PDF' }} <i data-lucide="arrow-up-right" aria-hidden="true"></i></a></div>
                            <details><summary>Lihat brosur di halaman ini <i data-lucide="chevron-down" aria-hidden="true"></i></summary><img src="{{ $package->brochure_image_url }}" alt="Brosur {{ $package->name }}" width="600" height="800" loading="lazy" decoding="async"></details>
                        </section>
                    @endif

                    @if (count($notes))
                        <section class="package-notes" aria-labelledby="notes-title">
                            <div><i data-lucide="info" aria-hidden="true"></i><h2 id="notes-title">Sebelum mendaftar</h2></div>
                            <ul>@foreach ($notes as $note)<li>{{ $note }}</li>@endforeach</ul>
                        </section>
                    @endif
                </div>

                <aside class="package-sidebar" aria-label="Pemesanan dan bantuan">
                    <div class="package-booking">
                        <span class="package-booking-label">Harga mulai</span>
                        <div class="package-booking-price">Rp {{ number_format($package->price, 0, ',', '.') }}</div>
                        <p>per jemaah · kamar Quad (4 orang)</p>
                        <a class="package-button-primary" href="{{ $bookingUrl }}" target="_blank" rel="noopener noreferrer"><i data-lucide="message-circle" aria-hidden="true"></i>Tanya ketersediaan kursi</a>
                        <a class="package-button-secondary" href="{{ $brochure_link }}" target="_blank" rel="noopener noreferrer"><i data-lucide="file-down" aria-hidden="true"></i>{{ $package->brochure ? 'Unduh brosur PDF' : 'Minta brosur PDF' }}</a>
                        <p class="package-booking-helper">Admin akan membantu konfirmasi harga akhir, kuota, dan alur pendaftaran.</p>
                        <div class="package-booking-links"><a href="#rincian-biaya">Lihat rincian biaya</a><a href="{{ route('public.jemaah.tracking') }}">Sudah terdaftar? Cek keberangkatan</a></div>
                    </div>
                    @if (!empty($settings['footer_ppiu_number']))
                        <div class="package-legal"><i data-lucide="shield-check" aria-hidden="true"></i><div><strong>Izin PPIU Kemenag RI</strong><span>No. {{ $settings['footer_ppiu_number'] }}</span></div></div>
                    @endif
                </aside>
            </div>
        </div>
    </main>

    @include('public.partials.footer')
    <script>if (window.lucide) window.lucide.createIcons();</script>
</body>

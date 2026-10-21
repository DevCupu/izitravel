@php
    $featureDefaults = [
        1 => ['Legalitas Resmi Kemenag', 'Memiliki izin PPIU resmi dari Kementerian Agama RI untuk kepastian keamanan hukum perjalanan Anda.', 'award'],
        2 => ['Jaminan Visa Umrah', 'Proses penerbitan visa yang aman, transparan, dan terkonfirmasi langsung ke sistem kedutaan.', 'file-check'],
        3 => ['Hotel Dekat Pelataran', 'Akomodasi hotel bintang pilihan dengan jarak yang dekat memudahkan Anda beribadah di Masjidil Haram & Nabawi.', 'building-2'],
        4 => ['Muthawwif Khas Nusantara', 'Muthawwif & pembimbing ibadah bersertifikasi, membimbing sesuai sunnah dengan keramahan khas Indonesia.', 'compass'],
        5 => ['Layanan Siaga & Peduli', 'Customer support dan tim handling operasional siaga melayani Anda 24 jam dengan asas kekeluargaan.', 'phone-call'],
        6 => ['Kepastian Tiket Terbang', 'Kepastian tanggal keberangkatan dengan tiket pesawat premium (PP) yang telah issued sejak pendaftaran.', 'plane-takeoff'],
    ];
@endphp

<section class="why-izi" id="keunggulan" data-purpose="features-grid" data-lazy-bg="{{ asset('images/section_kaaba_detail.webp') }}" aria-labelledby="why-izi-title">
    <div class="why-izi__backdrop" aria-hidden="true" style="background-image: linear-gradient(90deg, #102e53 0%, rgba(16,46,83,.98) 48%, rgba(16,46,83,.83) 100%), var(--deferred-bg, none);"></div>
    <div class="why-izi__shell">
        <header class="why-izi__intro">
            <span class="why-izi__eyebrow">{{ $settings['features_badge'] ?? 'Kenapa Kami' }}</span>
            <h2 id="why-izi-title">{{ $settings['features_section_title'] ?? 'Keunggulan Layanan Kami' }}</h2>
            <p>{{ $settings['features_section_subtitle'] ?? 'Mitra tepercaya perjalanan ibadah Anda dengan standar pelayanan tinggi dan kekeluargaan.' }}</p>
            <a class="why-izi__link" href="#paket-umrah">Lihat paket umrah <i data-lucide="arrow-up-right" aria-hidden="true"></i></a>
        </header>

        <div class="why-izi__grid">
            @for ($number = 1; $number <= 6; $number++)
                @php
                    [$fallbackTitle, $fallbackDescription, $fallbackIcon] = $featureDefaults[$number];
                    $title = $settings['feature_'.$number.'_title'] ?? $fallbackTitle;
                    $description = $settings['feature_'.$number.'_desc'] ?? $fallbackDescription;
                    $description = str_replace(', and ', ', dan ', $description);
                    $icon = $settings['feature_'.$number.'_icon'] ?? $fallbackIcon;
                    $image = $settings['feature_'.$number.'_image'] ?? null;
                    $imageUrl = $image ? (str_starts_with($image, 'images/') ? asset($image) : asset('storage/'.$image)) : null;
                @endphp
                <article class="why-izi__card {{ $number === 1 ? 'why-izi__card--legal' : '' }}">
                    <div class="why-izi__icon" aria-hidden="true">
                        @if ($imageUrl)
                            <img src="{{ $imageUrl }}" alt="" width="28" height="28" loading="lazy" decoding="async">
                        @else
                            <i data-lucide="{{ $icon }}"></i>
                        @endif
                    </div>
                    <div class="why-izi__card-copy">
                        <h3>{{ $title }}</h3>
                        <p>{{ $description }}</p>
                    </div>
                </article>
            @endfor
        </div>
    </div>
</section>

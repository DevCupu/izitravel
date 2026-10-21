<section class="haramain-section" id="haramain-info" aria-labelledby="haramain-heading">
    <div class="haramain-shell">
        <div class="haramain-heading">
            <span class="haramain-kicker">{{ $settings['haramain_badge'] ?? 'Info Haramain Live & Waktu Shalat' }}</span>
            <h2 id="haramain-heading">{{ $settings['haramain_title'] ?? 'Kabar Tanah Suci & Jadwal Shalat' }}</h2>
            <p>{{ $settings['haramain_subtitle'] ?? 'Pantau siaran Makkah dan Madinah, jadwal shalat hari ini, serta prakiraan cuaca Makkah.' }}</p>
        </div>

        <div class="haramain-layout">
            <div class="haramain-prayer-card">
                <div class="haramain-prayer-head">
                    <div>
                        <span class="haramain-card-kicker">Waktu setempat</span>
                        <h3 id="clock-location-title">Makkah Al-Mukarramah</h3>
                    </div>
                    <span class="haramain-status" id="prayer-status" role="status">Memuat jadwal</span>
                </div>

                <div class="haramain-clock">
                    <time id="clock-time">--:--:--</time>
                    <span id="clock-date">Memuat tanggal</span>
                </div>

                <div class="haramain-tabs" role="group" aria-label="Pilih kota untuk jadwal shalat">
                    <button type="button" id="tab-btn-makkah" data-prayer-location="makkah" aria-pressed="true" class="is-active">Makkah</button>
                    <button type="button" id="tab-btn-madinah" data-prayer-location="madinah" aria-pressed="false">Madinah</button>
                    <button type="button" id="tab-btn-wib" data-prayer-location="wib" aria-pressed="false">WIB</button>
                    <button type="button" id="tab-btn-wita" data-prayer-location="wita" aria-pressed="false">WITA</button>
                </div>

                <div class="haramain-next-prayer">
                    <span>Shalat berikutnya</span>
                    <div><strong id="next-prayer-name">Menunggu jadwal</strong><time id="next-prayer-countdown">--:--:--</time></div>
                </div>

                <div class="haramain-prayer-list" aria-label="Jadwal shalat hari ini">
                    <div><span>Subuh</span><time id="prayer-time-subuh">--:--</time></div>
                    <div><span>Dzuhur</span><time id="prayer-time-dzuhur">--:--</time></div>
                    <div><span>Ashar</span><time id="prayer-time-ashar">--:--</time></div>
                    <div><span>Maghrib</span><time id="prayer-time-maghrib">--:--</time></div>
                    <div><span>Isya</span><time id="prayer-time-isya">--:--</time></div>
                </div>
                <p class="haramain-source">Sumber perhitungan: <a href="https://aladhan.com/prayer-times-api" target="_blank" rel="noopener noreferrer">AlAdhan</a>. Jadwal dapat berbeda dari pengumuman masjid setempat.</p>
            </div>

            <div class="haramain-side">
                <div class="haramain-stream-card">
                    <div class="haramain-stream-head">
                        <div><span class="haramain-card-kicker">Siaran Tanah Suci</span><h3>Ikuti suasana dua masjid suci</h3></div>
                        <span class="haramain-stream-provider"><i data-lucide="youtube" aria-hidden="true"></i>YouTube</span>
                    </div>
                    <div class="haramain-stream-tabs" role="group" aria-label="Pilih siaran Haramain">
                        <button type="button" id="stream-btn-makkah" data-stream-location="makkah" aria-pressed="true" class="is-active">Masjidil Haram</button>
                        <button type="button" id="stream-btn-madinah" data-stream-location="madinah" aria-pressed="false">Masjid Nabawi</button>
                    </div>
                    <div class="haramain-player">
                        <iframe id="haramain-iframe" src="about:blank" title="Siaran Masjidil Haram" allow="autoplay; encrypted-media; picture-in-picture; web-share" allowfullscreen hidden></iframe>
                        <button type="button" id="haramain-facade" aria-label="Putar siaran Masjidil Haram">
                            <img id="haramain-cover" src="{{ asset('images/package_kaaba.webp') }}" alt="" width="800" height="450" loading="lazy" decoding="async">
                            <span class="haramain-player-shade" aria-hidden="true"></span>
                            <span class="haramain-player-play" aria-hidden="true"><i data-lucide="play"></i></span>
                            <span class="haramain-player-caption">Putar siaran <i data-lucide="arrow-up-right" aria-hidden="true"></i></span>
                        </button>
                    </div>
                    <div class="haramain-stream-actions">
                        <span>Siaran dimuat saat Anda menekan putar.</span>
                        <div>
                            <button type="button" id="haramain-refresh" aria-label="Muat ulang siaran"><i data-lucide="rotate-cw" aria-hidden="true"></i></button>
                            <a id="youtube-direct-link" href="https://www.youtube.com/channel/UCos52azQNBgW63_9uDJoPDA" target="_blank" rel="noopener noreferrer">Buka di YouTube <i data-lucide="arrow-up-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>

                <div class="haramain-weather-card">
                    <div class="haramain-weather-icon"><i id="weather-icon" data-lucide="cloud-sun" aria-hidden="true"></i></div>
                    <div class="haramain-weather-copy">
                        <span class="haramain-card-kicker">Prakiraan cuaca Makkah</span>
                        <strong id="weather-temp">—</strong>
                        <span id="weather-desc">Memuat data cuaca</span>
                    </div>
                    <p id="weather-updated">Sumber: <a href="https://open-meteo.com/en/docs" target="_blank" rel="noopener noreferrer">Open-Meteo</a><span id="weather-updated-time"></span></p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const section = document.getElementById('haramain-info');
        if (!section) return;

        const locations = {
            makkah: { name: 'Makkah Al-Mukarramah', zone: 'Asia/Riyadh', latitude: 21.3891, longitude: 39.8579, method: 4 },
            madinah: { name: 'Madinah Al-Munawwarah', zone: 'Asia/Riyadh', latitude: 24.4672, longitude: 39.6111, method: 4 },
            wib: { name: 'Jakarta · WIB', zone: 'Asia/Jakarta', latitude: -6.2088, longitude: 106.8456, method: 20 },
            wita: { name: 'Makassar · WITA', zone: 'Asia/Makassar', latitude: -5.1476, longitude: 119.4327, method: 20 },
        };
        const prayerNames = ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'];
        const apiNames = ['Fajr', 'Dhuhr', 'Asr', 'Maghrib', 'Isha'];
        const clockFormatters = Object.fromEntries(Object.entries(locations).map(([key, location]) => [key,
            new Intl.DateTimeFormat('en-GB', { timeZone: location.zone, year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit', hourCycle: 'h23' })
        ]));
        const dateFormatters = Object.fromEntries(Object.entries(locations).map(([key, location]) => [key,
            new Intl.DateTimeFormat('id-ID', { timeZone: location.zone, weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })
        ]));
        const schedules = {};
        const prayerRequests = {};
        const lastPrayerAttempt = {};
        const tomorrowSchedules = {};
        const tomorrowRequests = {};
        const lastTomorrowAttempt = {};
        let currentLocation = 'makkah';
        let isVisible = false;
        let lastShownDate = '';
        let clockTimer = null;

        const zonedParts = (key, date = new Date()) => {
            const parts = Object.fromEntries(clockFormatters[key].formatToParts(date).filter(part => part.type !== 'literal').map(part => [part.type, Number(part.value)]));
            return { ...parts, dateKey: `${parts.year}-${String(parts.month).padStart(2, '0')}-${String(parts.day).padStart(2, '0')}` };
        };
        const followingDate = parts => {
            const date = new Date(Date.UTC(parts.year, parts.month - 1, parts.day + 1));
            const year = date.getUTCFullYear(), month = date.getUTCMonth() + 1, day = date.getUTCDate();
            return { year, month, day, dateKey: `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}` };
        };
        const formatCountdown = seconds => `${String(Math.floor(seconds / 3600)).padStart(2, '0')}:${String(Math.floor(seconds % 3600 / 60)).padStart(2, '0')}:${String(seconds % 60).padStart(2, '0')}`;

        const fetchTimings = async (key, parts, signal) => {
            const date = `${String(parts.day).padStart(2, '0')}-${String(parts.month).padStart(2, '0')}-${parts.year}`;
            const location = locations[key];
            const params = new URLSearchParams({ latitude: String(location.latitude), longitude: String(location.longitude), method: String(location.method), timezonestring: location.zone });
            const response = await fetch(`https://api.aladhan.com/v1/timings/${date}?${params}`, { signal });
            if (!response.ok) throw new Error('Jadwal tidak tersedia');
            const data = await response.json();
            const times = {};
            prayerNames.forEach((name, index) => {
                const match = String(data?.data?.timings?.[apiNames[index]] || '').match(/^([01]\d|2[0-3]):[0-5]\d/);
                if (!match) throw new Error('Format jadwal tidak valid');
                times[name] = match[0];
            });
            return times;
        };

        const showPrayerStatus = (message, state = '') => {
            const element = document.getElementById('prayer-status');
            element.textContent = message;
            element.className = `haramain-status ${state}`.trim();
        };

        const renderSchedule = () => {
            const today = zonedParts(currentLocation).dateKey;
            const schedule = schedules[currentLocation]?.dateKey === today ? schedules[currentLocation].times : null;
            prayerNames.forEach(name => {
                document.getElementById(`prayer-time-${name.toLowerCase()}`).textContent = schedule?.[name] || '--:--';
            });
            if (schedule) showPrayerStatus('Jadwal hari ini', 'is-ready');
            else if (prayerRequests[currentLocation]) showPrayerStatus('Memuat jadwal');
            else if (lastPrayerAttempt[currentLocation]) showPrayerStatus('Jadwal belum tersedia', 'is-error');
            else showPrayerStatus('Memuat jadwal');
        };

        const renderClock = () => {
            const now = new Date();
            const parts = zonedParts(currentLocation, now);
            document.getElementById('clock-time').textContent = `${String(parts.hour).padStart(2, '0')}:${String(parts.minute).padStart(2, '0')}:${String(parts.second).padStart(2, '0')}`;
            document.getElementById('clock-date').textContent = dateFormatters[currentLocation].format(now);

            if (lastShownDate !== `${currentLocation}:${parts.dateKey}`) {
                lastShownDate = `${currentLocation}:${parts.dateKey}`;
                renderSchedule();
                if (isVisible) loadPrayer(currentLocation);
            }

            const schedule = schedules[currentLocation]?.dateKey === parts.dateKey ? schedules[currentLocation].times : null;
            const nameElement = document.getElementById('next-prayer-name');
            const countdownElement = document.getElementById('next-prayer-countdown');
            if (!schedule) {
                nameElement.textContent = 'Menunggu jadwal';
                countdownElement.textContent = '--:--:--';
                return;
            }

            const currentSeconds = parts.hour * 3600 + parts.minute * 60 + parts.second;
            const next = prayerNames.map(name => ({ name, seconds: Number(schedule[name].slice(0, 2)) * 3600 + Number(schedule[name].slice(3, 5)) * 60 }))
                .find(prayer => prayer.seconds > currentSeconds);
            if (!next) {
                const tomorrow = followingDate(parts);
                const subuh = tomorrowSchedules[currentLocation]?.dateKey === tomorrow.dateKey ? tomorrowSchedules[currentLocation].times.Subuh : null;
                nameElement.textContent = 'Subuh besok';
                countdownElement.textContent = subuh ? formatCountdown(24 * 3600 - currentSeconds + Number(subuh.slice(0, 2)) * 3600 + Number(subuh.slice(3, 5)) * 60) : '--:--:--';
                if (isVisible) loadTomorrow(currentLocation, tomorrow);
                return;
            }
            const remaining = next.seconds - currentSeconds;
            nameElement.textContent = next.name;
            countdownElement.textContent = formatCountdown(remaining);
        };

        const loadPrayer = async (key) => {
            const parts = zonedParts(key);
            if (schedules[key]?.dateKey === parts.dateKey || prayerRequests[key]) return;
            if (lastPrayerAttempt[key]?.dateKey === parts.dateKey && Date.now() - lastPrayerAttempt[key].at < 30 * 60 * 1000) return;
            lastPrayerAttempt[key] = { dateKey: parts.dateKey, at: Date.now() };
            prayerRequests[key] = true;
            if (key === currentLocation) renderSchedule();

            const controller = new AbortController();
            const timeout = setTimeout(() => controller.abort(), 10000);
            prayerRequests[key] = (async () => {
                try {
                    schedules[key] = { dateKey: parts.dateKey, times: await fetchTimings(key, parts, controller.signal) };
                } catch (error) {
                    // Leave the schedule empty rather than showing an old or invented time.
                } finally {
                    clearTimeout(timeout);
                    delete prayerRequests[key];
                    if (key === currentLocation) { renderSchedule(); renderClock(); }
                }
            })();
        };

        const loadTomorrow = (key, parts) => {
            if (tomorrowSchedules[key]?.dateKey === parts.dateKey || tomorrowRequests[key]) return;
            if (lastTomorrowAttempt[key]?.dateKey === parts.dateKey && Date.now() - lastTomorrowAttempt[key].at < 30 * 60 * 1000) return;
            lastTomorrowAttempt[key] = { dateKey: parts.dateKey, at: Date.now() };
            const controller = new AbortController();
            const timeout = setTimeout(() => controller.abort(), 10000);
            tomorrowRequests[key] = (async () => {
                try {
                    tomorrowSchedules[key] = { dateKey: parts.dateKey, times: await fetchTimings(key, parts, controller.signal) };
                } catch (error) {
                    // The next-day countdown stays unavailable until its schedule is fetched.
                } finally {
                    clearTimeout(timeout);
                    delete tomorrowRequests[key];
                    if (key === currentLocation) renderClock();
                }
            })();
        };

        section.querySelectorAll('[data-prayer-location]').forEach(button => button.addEventListener('click', () => {
            currentLocation = button.dataset.prayerLocation;
            section.querySelectorAll('[data-prayer-location]').forEach(tab => {
                const active = tab === button;
                tab.classList.toggle('is-active', active);
                tab.setAttribute('aria-pressed', String(active));
            });
            document.getElementById('clock-location-title').textContent = locations[currentLocation].name;
            lastShownDate = '';
            renderClock();
            loadPrayer(currentLocation);
        }));

        const weatherDescriptions = code => {
            if (code === 0) return ['Cerah', 'sun'];
            if (code <= 3) return ['Cerah berawan', 'cloud-sun'];
            if (code <= 48) return ['Berkabut', 'cloud'];
            if (code <= 67) return ['Gerimis atau hujan', 'cloud-drizzle'];
            if (code <= 77) return ['Bersalju', 'cloud-snow'];
            if (code <= 82) return ['Hujan', 'cloud-rain'];
            if (code >= 95) return ['Badai petir', 'cloud-lightning'];
            return ['Berawan', 'cloud'];
        };
        let lastWeatherAttempt = 0;
        const loadWeather = async () => {
            if (Date.now() - lastWeatherAttempt < 30 * 60 * 1000) return;
            lastWeatherAttempt = Date.now();
            const controller = new AbortController();
            const timeout = setTimeout(() => controller.abort(), 10000);
            try {
                const params = new URLSearchParams({ latitude: '21.3891', longitude: '39.8579', current: 'temperature_2m,weather_code', timezone: 'Asia/Riyadh' });
                const response = await fetch(`https://api.open-meteo.com/v1/forecast?${params}`, { signal: controller.signal });
                if (!response.ok) throw new Error('Cuaca tidak tersedia');
                const current = (await response.json()).current;
                if (!Number.isFinite(current?.temperature_2m)) throw new Error('Format cuaca tidak valid');
                const [description, icon] = weatherDescriptions(Number(current.weather_code));
                document.getElementById('weather-temp').textContent = `${Math.round(current.temperature_2m)}°C`;
                document.getElementById('weather-desc').textContent = description;
                const modelTime = String(current.time || '').split('T')[1]?.slice(0, 5);
                document.getElementById('weather-updated-time').textContent = /^\d{2}:\d{2}$/.test(modelTime) ? ` · Data pukul ${modelTime} waktu Makkah` : '';
                const iconElement = document.getElementById('weather-icon');
                iconElement?.setAttribute('data-lucide', icon);
                window.lucide?.createIcons();
            } catch (error) {
                document.getElementById('weather-temp').textContent = '—';
                document.getElementById('weather-desc').textContent = 'Cuaca belum tersedia';
                document.getElementById('weather-updated-time').textContent = '';
            } finally {
                clearTimeout(timeout);
            }
        };

        const streamSettings = {
            makkah: @json($settings['haramain_youtube_makkah'] ?? 'UCos52azQNBgW63_9uDJoPDA'),
            madinah: @json($settings['haramain_youtube_madinah'] ?? 'UCROKYPep-UuODNwyipe6JMw'),
        };
        const fallbackChannels = { makkah: 'UCos52azQNBgW63_9uDJoPDA', madinah: 'UCROKYPep-UuODNwyipe6JMw' };
        const parseStream = (input, fallback) => {
            const value = String(input || '').trim();
            const video = value.match(/(?:youtube\.com\/(?:watch\?[^#]*?v=|live\/|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/i);
            if (video) return { embed: `https://www.youtube.com/embed/${video[1]}`, direct: `https://www.youtube.com/watch?v=${video[1]}` };
            if (/^[a-zA-Z0-9_-]{11}$/.test(value)) return { embed: `https://www.youtube.com/embed/${value}`, direct: `https://www.youtube.com/watch?v=${value}` };
            const channel = value.match(/(?:^|channel\/)(UC[a-zA-Z0-9_-]{22})(?:[/?#]|$)/) || [null, fallback];
            return { embed: `https://www.youtube.com/embed/live_stream?channel=${channel[1]}`, direct: `https://www.youtube.com/channel/${channel[1]}` };
        };
        const streams = Object.fromEntries(Object.entries(streamSettings).map(([key, setting]) => [key, parseStream(setting, fallbackChannels[key])]));
        const facade = document.getElementById('haramain-facade');
        const iframe = document.getElementById('haramain-iframe');
        const cover = document.getElementById('haramain-cover');
        const directLink = document.getElementById('youtube-direct-link');
        let currentStream = 'makkah';
        let refreshTimer = null;
        const stopStream = () => {
            clearTimeout(refreshTimer);
            iframe.src = 'about:blank';
            iframe.hidden = true;
            facade.hidden = false;
        };
        const selectStream = key => {
            currentStream = key;
            stopStream();
            cover.src = key === 'makkah' ? @json(asset('images/package_kaaba.webp')) : @json(asset('images/section_madinah_nabawi.webp'));
            facade.setAttribute('aria-label', `Putar siaran ${key === 'makkah' ? 'Masjidil Haram' : 'Masjid Nabawi'}`);
            iframe.title = `Siaran ${key === 'makkah' ? 'Masjidil Haram' : 'Masjid Nabawi'}`;
            directLink.href = streams[key].direct;
            section.querySelectorAll('[data-stream-location]').forEach(button => {
                const active = button.dataset.streamLocation === key;
                button.classList.toggle('is-active', active);
                button.setAttribute('aria-pressed', String(active));
            });
        };
        const playStream = () => {
            const separator = streams[currentStream].embed.includes('?') ? '&' : '?';
            iframe.src = `${streams[currentStream].embed}${separator}autoplay=1&mute=1`;
            iframe.hidden = false;
            facade.hidden = true;
        };
        section.querySelectorAll('[data-stream-location]').forEach(button => button.addEventListener('click', () => selectStream(button.dataset.streamLocation)));
        facade.addEventListener('click', playStream);
        document.getElementById('haramain-refresh').addEventListener('click', () => {
            if (iframe.hidden) { playStream(); return; }
            const key = currentStream;
            iframe.src = 'about:blank';
            refreshTimer = setTimeout(() => { if (currentStream === key) playStream(); }, 100);
        });
        selectStream('makkah');

        const startClock = () => {
            renderClock();
            if (!clockTimer) clockTimer = setInterval(renderClock, 1000);
        };
        const stopClock = () => {
            clearInterval(clockTimer);
            clockTimer = null;
        };
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(entries => {
                isVisible = entries[0].isIntersecting;
                if (isVisible) { startClock(); loadPrayer(currentLocation); loadWeather(); }
                else { stopClock(); if (!iframe.hidden) stopStream(); }
            }, { rootMargin: '250px 0px' });
            observer.observe(section);
        } else {
            isVisible = true;
            startClock();
            loadPrayer(currentLocation);
            loadWeather();
        }
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) { stopClock(); if (!iframe.hidden) stopStream(); }
            else if (isVisible) { startClock(); loadPrayer(currentLocation); loadWeather(); }
        });
    });
</script>

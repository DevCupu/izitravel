<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menghubungkan Anda - {{ config('app.name', 'IZI Travel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <style>
        :root {
            --navy: #0b1f3a;
            --navy-2: #10294d;
            --gold: #c8a24b;
            --gold-soft: #e0c177;
            --cream: #fbf8f1;
            --ink: #1f2937;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(120% 120% at 100% 0%, rgba(200,162,75,.16) 0%, transparent 55%),
                radial-gradient(120% 130% at 0% 100%, rgba(200,162,75,.10) 0%, transparent 50%),
                linear-gradient(160deg, var(--navy) 0%, var(--navy-2) 60%, #123056 100%);
            padding: 1.5rem;
            color: var(--ink);
        }
        .frame {
            width: 100%;
            max-width: 440px;
            background: var(--cream);
            border-radius: 1.4rem;
            padding: 2.6rem 2.1rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 28px 64px -18px rgba(4,10,22,.6);
            animation: rise .5s cubic-bezier(.22,.9,.3,1.15) both;
        }
        .frame::before {
            content: "";
            position: absolute;
            inset: 0 0 auto 0;
            height: 5px;
            background: linear-gradient(90deg, var(--gold) 0%, var(--gold-soft) 50%, var(--gold) 100%);
        }
        .brand {
            font-size: .66rem;
            font-weight: 800;
            letter-spacing: .34em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 1.4rem;
        }
        .emblem {
            width: 84px;
            height: 84px;
            margin: 0 auto 1.4rem;
            border-radius: 50%;
            background:
                radial-gradient(circle at 50% 40%, #e9ddb9 0%, transparent 42%),
                linear-gradient(145deg, var(--navy) 0%, var(--navy-2) 100%);
            border: 2px solid var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: inset 0 0 12px rgba(200,162,75,.35), 0 14px 30px -12px rgba(4,14,28,.6);
        }
        .emblem::before {
            content: "";
            position: absolute;
            inset: -8px;
            border-radius: 50%;
            border: 1px solid rgba(200,162,75,.4);
            animation: ring 2.2s cubic-bezier(.2,.7,.3,1) infinite;
        }
        .emblem img { width: 58px; height: 38px; object-fit: contain; }
        @keyframes ring {
            0% { transform: scale(.85); opacity: .9; }
            70% { transform: scale(1.12); opacity: 0; }
            100% { transform: scale(1.12); opacity: 0; }
        }
        h1 { font-size: 1.3rem; font-weight: 800; color: var(--navy); letter-spacing: -.01em; margin-bottom: .5rem; }
        .sub { font-size: .875rem; color: #4b5a6d; line-height: 1.6; }
        .sub strong { color: var(--navy); }
        .coat {
            margin-top: 1.3rem;
            padding: .8rem .95rem;
            background: #fffdf6;
            border: 1px solid #eadfb9;
            border-radius: .8rem;
            font-size: .78rem;
            color: #7a6a3c;
            font-weight: 600;
        }
        .countdown {
            margin-top: 1.6rem;
            font-size: .8rem;
            font-weight: 700;
            color: #8296ad;
            letter-spacing: .03em;
        }
        .countdown b { color: var(--navy); }
        .btn {
            display: inline-block;
            margin-top: .9rem;
            padding: .82rem 1.55rem;
            border-radius: .8rem;
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-2) 100%);
            color: #fff;
            font-weight: 800;
            font-size: .875rem;
            text-decoration: none;
            letter-spacing: .02em;
            box-shadow: 0 12px 26px -12px rgba(4,14,28,.7);
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 18px 34px -14px rgba(4,14,28,.75); }
        .btn:active { transform: translateY(0) scale(.98); }
        .error-box {
            margin-top: 1.25rem;
            padding: .9rem 1rem;
            background: #fff4ed;
            border: 1px solid #fdd3ad;
            border-radius: .8rem;
            font-size: .8125rem;
            color: #9a3412;
            line-height: 1.5;
        }
        .footer { margin-top: 2rem; font-size: .7rem; color: #a9b6c6; letter-spacing: .04em; }
    </style>
</head>
<body>
    <div class="frame">
        <div class="brand">{{ config('app.name', 'IZI Travel') }}</div>
        <div class="emblem">
            <img src="{{ asset('images/Izi LOGO WHITE.webp') }}" alt="IZI Travel" width="116" height="76">
        </div>

        @if ($wa_url)
            <h1>Menghubungkan Anda...</h1>
            <p class="sub">
                @if ($cs['name'])
                    Dalam sekejap, percakapan Anda akan dilanjutkan oleh <strong>{{ $cs['name'] }}</strong> dari tim
                    {{ config('app.name', 'IZI Travel') }}.
                @else
                    Anda akan segera terhubung dengan tim kami.
                @endif
            </p>
            @if ($cs['fallback'])
                <div class="coat">Tim kami sedang sedikit ramai, Anda akan dihubungkan ke nomor utama kami.</div>
            @endif
            @if ($utm_campaign)
                <div class="coat">Paket: <strong>{{ $utm_campaign }}</strong></div>
            @endif
            <div class="countdown">Membuka WhatsApp dalam <b>1</b> detik...</div>
            <a class="btn" href="{{ $wa_url }}" target="_blank" rel="noopener">Lanjut ke WhatsApp</a>
        @else
            <h1>CS Sedang Tidak Tersedia</h1>
            <p class="sub">Mohon maaf, tim kami sedang tidak dapat dihubungi saat ini.</p>
            <div class="error-box">Silakan coba lagi nanti, atau kembali ke website utama kami.</div>
            <a class="btn" href="{{ url('/') }}" style="background:#0f172a;">Kembali ke Website</a>
        @endif

        <p class="footer">&copy; {{ date('Y') }} {{ config('app.name', 'IZI Travel') }}</p>
    </div>

    @if ($wa_url)
    <script>
        (function () {
            var webTarget = @json($wa_url);
            var appTarget = @json($wa_app_url);
            var campaignKey = @json($utm_campaign ?: 'default');
            var count;
            var el = document.querySelector('.countdown b');
            var beaconSent = false;
            var tokenMeta = document.querySelector('meta[name="csrf-token"]');
            var isMobile = /Android|iPhone|iPad|iPod|Windows Phone/i.test(navigator.userAgent);
            var isAdsInAppBrowser = /FBAN|FBAV|FB_IAB|Instagram|Messenger|TikTok|BytedanceWebview|Twitter|Line|Snapchat|Pinterest/i.test(navigator.userAgent);
            var hasOpened = false;
            var wasHiddenAfterOpen = false;
            var appFallbackTimer = null;
            var alreadyRedirected = false;

            count = isAdsInAppBrowser ? 3 : 1;
            if (el) el.textContent = count;

            try {
                alreadyRedirected = !!sessionStorage.getItem('izi_wa_redirected:' + campaignKey);
            } catch (e) {}

            function sendClickBeacon() {
                if (beaconSent || !tokenMeta) return;
                beaconSent = true;
                var token = @json($token);
                if (!token) return;
                var csrf = tokenMeta.getAttribute('content');
                var form = new FormData();
                form.append('token', token);
                try {
                    navigator.sendBeacon(@json(route('public.chat.click')), form);
                } catch (e) {}
            }

            function markRedirected() {
                try {
                    sessionStorage.setItem('izi_wa_redirected:' + campaignKey, '1');
                } catch (e) {}
            }

            function openWhatsApp() {
                if (hasOpened) return;
                hasOpened = true;
                markRedirected();
                sendClickBeacon();

                if (isMobile && appTarget) {
                    appFallbackTimer = setTimeout(function () {
                        window.location.replace(webTarget);
                    }, 2200);
                    window.location.href = appTarget;
                } else {
                    window.location.replace(webTarget);
                }
            }

            document.querySelector('.btn').addEventListener('click', function (event) {
                event.preventDefault();
                openWhatsApp();
            });

            window.addEventListener('pageshow', function (event) {
                if (event.persisted) window.location.reload();
            });

            document.addEventListener('visibilitychange', function () {
                if (document.hidden) {
                    if (appFallbackTimer) {
                        clearTimeout(appFallbackTimer);
                        appFallbackTimer = null;
                    }
                    if (isAdsInAppBrowser && hasOpened) wasHiddenAfterOpen = true;
                    return;
                }

                if (isAdsInAppBrowser && wasHiddenAfterOpen) window.location.reload();
            });

            if (alreadyRedirected) {
                var countdownBox = document.querySelector('.countdown');
                if (countdownBox) countdownBox.textContent = 'Klik tombol di bawah untuk membuka WhatsApp lagi.';
            } else {
                var go = function () {
                    clearInterval(timer);
                    openWhatsApp();
                };

                var timer = setInterval(function () {
                    count--;
                    if (el) el.textContent = count;
                    if (count <= 0) {
                        go();
                    }
                }, 1000);

                setTimeout(function () {
                    if (count > 0) clearInterval(timer);
                    go();
                }, isAdsInAppBrowser ? 3500 : (isMobile ? 50 : 500));
            }
        })();
    </script>
    @endif
</body>
</html>

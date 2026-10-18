<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $code }} — {{ $heading }} | IZI Travel</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { color-scheme: light; font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        * { box-sizing: border-box; }
        body { min-height: 100vh; min-height: 100dvh; margin: 0; display: grid; place-items: center; padding: 24px; color: #102b52; background: radial-gradient(circle at 85% 10%, #dce9fa, transparent 33%), #f5f7fb; }
        main { width: min(100%, 530px); padding: clamp(28px, 6vw, 56px); border: 1px solid #d9e3ef; border-radius: 28px; background: #fff; box-shadow: 0 22px 64px rgba(16,43,82,.09); text-align: center; }
        .logo { display: inline-flex; min-height: 44px; align-items: center; }
        .logo img { display: block; width: 145px; height: auto; }
        .code { margin: 32px 0 4px; color: #3166b1; font-size: clamp(64px, 14vw, 112px); font-weight: 800; letter-spacing: -.09em; line-height: 1; }
        h1 { margin: 12px 0; font-family: 'El Messiri', 'Plus Jakarta Sans', sans-serif; font-size: clamp(25px, 6vw, 34px); line-height: 1.25; }
        p { max-width: 38ch; margin: 0 auto; color: #475569; font-size: 15px; line-height: 1.75; }
        .actions { display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 30px; }
        a.button { display: inline-flex; min-height: 48px; align-items: center; justify-content: center; padding: 12px 19px; border-radius: 12px; background: #3166b1; color: #fff; font-size: 14px; font-weight: 700; text-decoration: none; }
        a.button:hover { background: #245392; }
        a.secondary { border: 1px solid #cbd5e1; background: #fff; color: #102b52; }
        a.secondary:hover { background: #f1f5f9; }
        a:focus-visible { outline: 3px solid #3166b1; outline-offset: 3px; }
    </style>
</head>
<body>
    <main>
        <a href="{{ url('/') }}" class="logo" aria-label="IZI Travel, kembali ke beranda"><img src="{{ asset('images/Izi LOGO.webp') }}" alt="IZI Travel"></a>
        <div class="code" aria-hidden="true">{{ $code }}</div>
        <h1>{{ $heading }}</h1>
        <p>{{ $description }}</p>
        <div class="actions">
            <a class="button" href="{{ $actionUrl }}">{{ $actionLabel }}</a>
            @if ($actionUrl !== url('/')) <a class="button secondary" href="{{ url('/') }}">Ke beranda</a> @endif
        </div>
    </main>
</body>
</html>

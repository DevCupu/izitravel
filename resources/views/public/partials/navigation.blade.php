@php
    $hasCustomLogo = !empty($settings['site_logo']);
    $publicLogo = $hasCustomLogo
        ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/'.$settings['site_logo']))
        : asset('images/Izi LOGO.webp');
    $publicWa = preg_replace('/[^0-9]/', '', $settings['contact_whatsapp'] ?? '6281112345678');
    if (str_starts_with($publicWa, '0')) $publicWa = '62'.substr($publicWa, 1);
    $transparentOnHero = request()->is('/');
    $publicLinks = [
        ['label' => 'Beranda', 'href' => url('/'), 'icon' => 'home', 'active' => request()->is('/')],
        ['label' => 'Paket Umrah', 'href' => url('/#paket-umrah'), 'icon' => 'compass', 'active' => request()->is('paket/*')],
        ['label' => 'Galeri', 'href' => route('public.gallery'), 'icon' => 'image', 'active' => request()->routeIs('public.gallery')],
        ['label' => 'Testimoni', 'href' => route('public.testimonials'), 'icon' => 'message-square-quote', 'active' => request()->routeIs('public.testimonials')],
        ['label' => 'Artikel', 'href' => route('public.articles.index'), 'icon' => 'book-open', 'active' => request()->is('artikel*')],
        ['label' => 'Cek Keberangkatan', 'href' => route('public.jemaah.tracking'), 'icon' => 'search-check', 'active' => request()->is('lacak-jemaah*')],
    ];
    $desktopLinks = $transparentOnHero ? [
        ['label' => 'Beranda', 'href' => '#beranda', 'active' => true],
        ['label' => 'Tentang Kami', 'href' => '#tentang-kami', 'active' => false],
        ['label' => 'Paket Umrah', 'href' => '#paket-umrah', 'active' => false],
        ['label' => 'Galeri', 'href' => '#galeri', 'active' => false],
        ['label' => 'Testimoni', 'href' => '#testimoni', 'active' => false],
        ['label' => 'Artikel', 'href' => '#artikel', 'active' => false],
        ['label' => 'Kemitraan', 'href' => '#kemitraan', 'active' => false],
        ['label' => 'Kontak', 'href' => '#kontak', 'active' => false],
    ] : $publicLinks;
@endphp
<div x-data="{ menuOpen: false }" @keydown.escape.window="if (menuOpen) { menuOpen = false; $nextTick(() => document.querySelector('[aria-controls=public-mobile-menu]').focus()) }">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[120] focus:rounded-xl focus:bg-white focus:px-4 focus:py-3 focus:text-[#102b52]">Lewati navigasi</a>
    <header class="public-header fixed left-1/2 top-3 z-50 w-[calc(100%-1.5rem)] max-w-7xl -translate-x-1/2 sm:top-4" style="font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;">
        <nav aria-label="Navigasi utama" class="flex min-h-[66px] items-center justify-between gap-3 rounded-2xl border px-4 transition-[background-color,border-color,box-shadow] duration-300 sm:rounded-full sm:px-6 xl:min-h-[56px] {{ $transparentOnHero ? 'public-nav-hero' : 'border-white/75 bg-white/95 shadow-[0_12px_32px_rgba(16,43,82,.14)] backdrop-blur-lg' }}">
            <a href="{{ url('/') }}" aria-label="IZI Travel, kembali ke beranda" class="shrink-0"><img src="{{ $publicLogo }}" alt="IZI Travel" class="h-8 w-auto object-contain transition-[filter] duration-300 {{ $transparentOnHero ? 'public-nav-logo' : '' }}" width="480" height="168" fetchpriority="high"></a>
            <div class="hidden min-w-0 items-center gap-0.5 xl:flex">
                @foreach ($desktopLinks as $link)
                    <a href="{{ $link['href'] }}" @if ($link['active']) aria-current="page" @endif class="rounded-full px-2.5 py-2 text-[14px] font-bold tracking-tight transition {{ $transparentOnHero ? 'public-nav-link'.($link['active'] ? ' is-active' : '') : ($link['active'] ? 'bg-blue-50 text-[#245392]' : 'text-slate-600 hover:bg-blue-50 hover:text-[#3166b1]') }}" @if ($transparentOnHero && $link['active']) style="text-decoration-line:underline;text-decoration-thickness:2px;text-underline-offset:9px" @endif>{{ $link['label'] }}</a>
                @endforeach
            </div>
            <div class="flex shrink-0 items-center gap-2">
                <a href="{{ route('public.jemaah.tracking') }}" class="inline-flex min-h-11 min-w-11 items-center justify-center rounded-full border border-blue-100 bg-white px-3 text-xs font-bold text-[#245392] transition hover:bg-blue-50 sm:gap-2 xl:min-h-9" aria-label="Cek keberangkatan"><i data-lucide="search-check" class="size-4" aria-hidden="true"></i><span class="hidden sm:inline">Cek Keberangkatan</span></a>
                <a href="https://wa.me/{{ $publicWa }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-11 min-w-11 items-center justify-center rounded-full px-3 text-xs font-bold transition sm:gap-2 xl:min-h-9 {{ $transparentOnHero ? 'public-nav-consult' : 'bg-[#fcde05] text-[#102b52] hover:bg-[#f2cf00]' }}" aria-label="Konsultasi melalui WhatsApp, buka tab baru"><svg class="size-4 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path></svg><span class="hidden sm:inline">Konsultasi</span></a>
            </div>
        </nav>
    </header>

    @if ($transparentOnHero)
        <script>
            (() => {
                const nav = document.querySelector('.public-nav-hero');
                if (!nav) return;

                const syncNav = () => nav.classList.toggle('is-scrolled', window.scrollY > 30);
                syncNav();
                window.addEventListener('scroll', syncNav, { passive: true });
                window.addEventListener('pageshow', syncNav);
            })();
        </script>
    @endif

    <nav aria-label="Navigasi seluler" class="public-mobile-nav fixed inset-x-3 bottom-3 z-50 rounded-2xl border border-slate-200 bg-white/95 p-1.5 shadow-[0_12px_36px_rgba(16,43,82,.18)] backdrop-blur-lg md:hidden">
        <div class="grid grid-cols-5">
            @foreach (array_slice($publicLinks, 0, 1) as $link)
                <a href="{{ $link['href'] }}" @if ($link['active']) aria-current="page" @endif class="flex min-h-[60px] flex-col items-center justify-center gap-1 rounded-xl text-[10px] font-bold {{ $link['active'] ? 'bg-blue-50 text-[#3166b1]' : 'text-slate-600' }}"><i data-lucide="{{ $link['icon'] }}" class="size-5" aria-hidden="true"></i>{{ $link['label'] }}</a>
            @endforeach
            @foreach ([$publicLinks[1], $publicLinks[2], $publicLinks[4]] as $link)
                <a href="{{ $link['href'] }}" @if ($link['active']) aria-current="page" @endif class="flex min-h-[60px] flex-col items-center justify-center gap-1 rounded-xl text-[10px] font-bold {{ $link['active'] ? 'bg-blue-50 text-[#3166b1]' : 'text-slate-600' }}"><i data-lucide="{{ $link['icon'] }}" class="size-5" aria-hidden="true"></i>{{ $link['label'] === 'Paket Umrah' ? 'Paket' : $link['label'] }}</a>
            @endforeach
            <button type="button" @click="menuOpen = true; setTimeout(() => $refs.menuClose.focus(), 0)" :aria-expanded="menuOpen.toString()" aria-controls="public-mobile-menu" class="flex min-h-[60px] flex-col items-center justify-center gap-1 rounded-xl text-[10px] font-bold text-slate-600"><i data-lucide="menu" class="size-5" aria-hidden="true"></i>Menu</button>
        </div>
    </nav>

    <div id="public-mobile-menu" x-show="menuOpen" x-cloak @keydown.tab="if ($event.shiftKey && document.activeElement === $refs.menuClose) { $event.preventDefault(); $refs.menuLast.focus() } else if (!$event.shiftKey && document.activeElement === $refs.menuLast) { $event.preventDefault(); $refs.menuClose.focus() }" class="fixed inset-0 z-[100] md:hidden" role="dialog" aria-modal="true" aria-label="Menu navigasi">
        <button type="button" @click="menuOpen = false; $nextTick(() => document.querySelector('[aria-controls=public-mobile-menu]').focus())" class="absolute inset-0 w-full bg-slate-950/55" aria-label="Tutup menu navigasi"></button>
        <div class="absolute inset-y-0 right-0 flex w-[min(340px,88vw)] flex-col bg-white p-5 shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4"><span><img src="{{ $publicLogo }}" alt="IZI Travel" class="h-7 w-auto object-contain"></span><button x-ref="menuClose" type="button" @click="menuOpen = false; $nextTick(() => document.querySelector('[aria-controls=public-mobile-menu]').focus())" class="inline-flex size-11 items-center justify-center rounded-xl border border-slate-200 text-slate-700" aria-label="Tutup menu"><i data-lucide="x" class="size-5" aria-hidden="true"></i></button></div>
            <div class="mt-4 flex-1 space-y-1 overflow-y-auto">@foreach ($publicLinks as $link)<a href="{{ $link['href'] }}" @if ($link['active']) aria-current="page" @endif class="flex min-h-12 items-center gap-3 rounded-xl px-3 text-sm font-semibold {{ $link['active'] ? 'bg-blue-50 text-[#3166b1]' : 'text-slate-700 hover:bg-slate-50' }}"><i data-lucide="{{ $link['icon'] }}" class="size-5" aria-hidden="true"></i>{{ $link['label'] }}</a>@endforeach</div>
            <a x-ref="menuLast" href="https://wa.me/{{ $publicWa }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-[#3166b1] px-4 text-sm font-bold text-white"><i data-lucide="message-circle" class="size-5" aria-hidden="true"></i>Konsultasi WhatsApp</a>
        </div>
    </div>
</div>

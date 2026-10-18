@php
    $csNavigation = [
        'dashboard' => ['Beranda', 'layout-dashboard'],
        'leads.index' => ['Leads', 'users-round'],
        'tasks' => ['Tugas', 'calendar-check'],
        'pipeline' => ['Pipeline', 'columns-3'],
        'guide' => ['Panduan', 'book-open'],
    ];
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Area CS' }} · IZI Travel</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body{font-family:'Plus Jakarta Sans',sans-serif}h1,h2,h3{font-family:'El Messiri',sans-serif}</style>
</head>
<body class="cs-body min-h-screen bg-[#f5f7fb] text-slate-900 antialiased">
    <a href="#cs-main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[70] focus:rounded-lg focus:bg-white focus:px-4 focus:py-3 focus:text-[#102b52]">Lewati navigasi</a>
    <div x-data="{ sidebarCollapsed: localStorage.getItem('csSidebarCollapsed') === 'true' }" class="min-h-screen lg:flex">
        <aside class="hidden bg-[#102b52] px-5 py-7 text-white transition-all duration-200 lg:sticky lg:top-0 lg:flex lg:h-screen lg:w-64 lg:shrink-0 lg:flex-col" :style="sidebarCollapsed ? 'width:5rem;padding-left:.75rem;padding-right:.75rem' : 'width:16rem;padding-left:1.25rem;padding-right:1.25rem'">
            <div class="mb-9 flex items-center justify-between gap-2" :style="sidebarCollapsed ? 'justify-content:center' : ''">
                <a x-show="!sidebarCollapsed" href="{{ route('cs.dashboard') }}" class="min-w-0 rounded-lg px-2 py-1" aria-label="IZI Travel, ke beranda CS"><img src="{{ asset('images/Izi LOGO WHITE.webp') }}" alt="IZI Travel" class="h-8 w-auto"></a>
                <button type="button" @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('csSidebarCollapsed', sidebarCollapsed)" :aria-label="sidebarCollapsed ? 'Perluas sidebar' : 'Ciutkan sidebar'" :title="sidebarCollapsed ? 'Perluas sidebar' : 'Ciutkan sidebar'" :aria-expanded="(!sidebarCollapsed).toString()" aria-controls="cs-sidebar-navigation" class="inline-flex size-10 shrink-0 items-center justify-center rounded-xl border border-white/20 text-xl leading-none text-blue-100 transition hover:bg-white/10 hover:text-white" x-text="sidebarCollapsed ? '›' : '‹'"></button>
            </div>
            <p x-show="!sidebarCollapsed" class="mb-3 px-3 text-[11px] font-extrabold uppercase tracking-[.15em] text-blue-200">Area kerja CS</p>
            <nav id="cs-sidebar-navigation" aria-label="Navigasi utama CS" class="space-y-1">
                @foreach ($csNavigation as $key => [$label, $icon])
                    @php($active = request()->routeIs('cs.'.$key) || ($key === 'leads.index' && request()->routeIs('cs.leads.*')))
                    <a href="{{ route('cs.'.$key) }}" @if ($active) aria-current="page" @endif :aria-label="sidebarCollapsed ? '{{ $label }}' : null" :title="sidebarCollapsed ? '{{ $label }}' : null" :style="sidebarCollapsed ? 'justify-content:center;padding-left:0;padding-right:0' : ''" class="flex min-h-11 items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ $active ? 'bg-white text-[#102b52] shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}"><i data-lucide="{{ $icon }}" class="size-5 shrink-0" aria-hidden="true"></i><span x-show="!sidebarCollapsed">{{ $label }}</span></a>
                @endforeach
            </nav>
            <div class="mt-auto border-t border-white/15 pt-5" :style="sidebarCollapsed ? 'padding-left:0;padding-right:0' : 'padding-left:.75rem;padding-right:.75rem'">
                <div x-show="!sidebarCollapsed">
                    <p class="text-sm font-bold text-white">{{ auth()->user()->name }}</p>
                    <p class="mt-1 text-xs text-blue-200">Waktu kerja · WITA</p>
                </div>
                <a href="{{ route('cs.password.edit') }}" :aria-label="sidebarCollapsed ? 'Ubah kata sandi' : null" :title="sidebarCollapsed ? 'Ubah kata sandi' : null" :style="sidebarCollapsed ? 'justify-content:center;width:100%' : ''" class="mt-4 inline-flex min-h-11 items-center gap-2 text-xs font-bold text-blue-100 hover:text-white"><i data-lucide="key-round" class="size-4 shrink-0" aria-hidden="true"></i><span x-show="!sidebarCollapsed">Ubah kata sandi</span></a>
            </div>
        </aside>
        <div class="min-w-0 flex-1 pb-[calc(5.5rem+env(safe-area-inset-bottom))] lg:pb-0">
            <header class="border-b border-slate-200 bg-white px-4 py-3 sm:px-8 sm:py-4">
                <div class="{{ request()->routeIs('cs.pipeline') ? 'max-w-[1600px]' : 'max-w-6xl' }} mx-auto flex min-w-0 items-center justify-between gap-3">
                    <div class="min-w-0"><p class="mb-0.5 text-[10px] font-extrabold uppercase tracking-[.14em] text-[#3166b1] lg:hidden">IZI Travel · CS</p>{{ $header ?? '' }}</div>
                    <div class="flex shrink-0 items-center gap-2">
                        <a href="{{ route('cs.password.edit') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-200 px-3 text-slate-600 hover:bg-slate-50 lg:hidden" aria-label="Ubah kata sandi"><i data-lucide="key-round" class="size-4" aria-hidden="true"></i></a>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="inline-flex min-h-11 items-center rounded-xl border border-slate-200 px-3.5 text-xs font-bold text-slate-700 hover:bg-slate-50">Keluar</button></form>
                    </div>
                </div>
            </header>
            <main id="cs-main" class="{{ request()->routeIs('cs.pipeline') ? 'max-w-[1600px]' : 'max-w-6xl' }} mx-auto min-w-0 px-4 py-6 sm:px-8 sm:py-8">
                @if (session('status'))<x-ui.status tone="success" class="mb-5">{{ session('status') }}</x-ui.status>@endif
                @if ($errors->any())<x-ui.status tone="error" class="mb-5"><p class="font-bold">Periksa kembali data berikut:</p><ul class="mt-1 list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></x-ui.status>@endif
                {{ $slot }}
            </main>
        </div>
    </div>
    <nav aria-label="Navigasi seluler CS" class="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white/95 px-1 pb-[env(safe-area-inset-bottom)] shadow-[0_-6px_24px_rgba(16,43,82,.08)] backdrop-blur lg:hidden">
        <div class="mx-auto grid max-w-xl grid-cols-5">
            @foreach ($csNavigation as $key => [$label, $icon])
                @php($active = request()->routeIs('cs.'.$key) || ($key === 'leads.index' && request()->routeIs('cs.leads.*')))
                <a href="{{ route('cs.'.$key) }}" @if ($active) aria-current="page" @endif class="relative flex min-h-[70px] flex-col items-center justify-center gap-1 rounded-lg px-1 text-[10px] font-bold {{ $active ? 'text-[#3166b1]' : 'text-slate-500' }}"><i data-lucide="{{ $icon }}" class="size-5" aria-hidden="true"></i>{{ $label }}@if ($active)<span class="absolute inset-x-5 top-0 h-[3px] rounded-b-full bg-[#3166b1]" aria-hidden="true"></span>@endif</a>
            @endforeach
        </div>
    </nav>
    <script>document.addEventListener('DOMContentLoaded', () => window.lucide?.createIcons());</script>
</body>
</html>

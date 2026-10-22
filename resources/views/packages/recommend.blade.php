<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, follow">
    <meta name="description" content="Temukan paket umrah IZI Travel sesuai anggaran, bulan keberangkatan, dan kota asal Anda.">
    <title>Cari Paket Umrah yang Cocok - {{ $settings['site_name'] ?? 'IZI Travel' }}</title>
    <link rel="icon" type="image/png" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/'.$settings['site_favicon'])) : asset('images/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .recommend-page { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; color: #172b45; }
        .recommend-page h1, .recommend-page h2, .recommend-page h3 { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; letter-spacing: -.035em; }
        .recommend-hero { background: radial-gradient(circle at 82% 20%, rgba(70,128,200,.42), transparent 32%), linear-gradient(125deg, #102b52, #173b70 72%, #245392); }
        .recommend-orbit { width: 340px; height: 340px; border: 1px solid rgba(255,255,255,.16); border-radius: 50%; transform: rotate(-20deg) scaleX(1.45); }
        .recommend-field:focus { outline: 2px solid #3166b1; outline-offset: 2px; border-color: #3166b1; }
        .recommend-ticket { box-shadow: 0 18px 45px rgba(16,43,82,.08); }
        @media (prefers-reduced-motion: reduce) { html { scroll-behavior: auto; } }
    </style>
</head>
<body class="recommend-page overflow-x-hidden bg-[#f5f8fc] antialiased izi-public">
    @include('public.partials.navigation')

    <main id="main-content">
        <section class="recommend-hero relative overflow-hidden px-4 pb-24 pt-36 text-white sm:px-6 sm:pb-28">
            <div class="recommend-orbit pointer-events-none absolute -right-32 top-8 hidden lg:block" aria-hidden="true"></div>
            <div class="recommend-orbit pointer-events-none absolute -right-8 top-28 hidden lg:block" aria-hidden="true"></div>
            <div class="relative mx-auto max-w-7xl">
                <a href="{{ url('/#paket-umrah') }}" class="inline-flex min-h-10 items-center gap-2 text-sm font-semibold text-blue-100 hover:text-white"><i data-lucide="arrow-left" class="size-4" aria-hidden="true"></i>Kembali ke semua paket</a>
                <div class="mt-10 max-w-2xl">
                    <span class="inline-flex items-center gap-2 rounded-full border border-[#fcde05]/50 bg-[#fcde05]/10 px-4 py-2 text-xs font-bold text-[#fcde05]"><i data-lucide="compass" class="size-4" aria-hidden="true"></i>Asisten Pilih Paket</span>
                    <h1 class="mt-5 text-4xl font-extrabold leading-[1.12] sm:text-5xl">Temukan keberangkatan yang pas untuk Anda.</h1>
                    <p class="mt-5 max-w-xl text-sm leading-7 text-blue-100 sm:text-base">Ceritakan rencana umrah Anda. Kami cocokkan dengan jadwal dan harga paket IZI Travel yang sedang tersedia.</p>
                </div>
            </div>
        </section>

        <section class="relative mx-auto -mt-12 max-w-7xl px-4 pb-20 sm:px-6 sm:pb-28" aria-label="Pencarian rekomendasi paket">
            <div class="grid items-start gap-7 lg:grid-cols-[minmax(0,.9fr)_minmax(0,1.1fr)]">
                <div class="rounded-[1.75rem] border border-[#dce4ee] bg-white p-6 shadow-[0_18px_45px_rgba(16,43,82,.08)] sm:p-8">
                    <div class="flex items-start gap-3">
                        <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-[#eaf2fd] text-[#3166b1]"><i data-lucide="sliders-horizontal" class="size-5" aria-hidden="true"></i></div>
                        <div><h2 class="text-xl font-extrabold text-[#102b52]">Rencana perjalanan Anda</h2><p class="mt-1 text-sm leading-6 text-slate-500">Isi yang sudah Anda tentukan. Kolom lain boleh dikosongkan.</p></div>
                    </div>

                    <form method="POST" action="{{ route('public.packages.recommend.submit') }}" class="mt-7 space-y-5">
                        @csrf
                        <div>
                            <label for="budget" class="mb-2 block text-sm font-bold text-[#172b45]">Anggaran maksimal per jemaah</label>
                            <div class="relative"><span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-500">Rp</span><input id="budget" name="budget" type="number" min="1000000" max="1000000000" step="1000000" inputmode="numeric" value="{{ old('budget', $preferences['budget'] ?? '') }}" placeholder="Contoh: 35000000" class="recommend-field min-h-12 w-full rounded-xl border border-slate-200 bg-white pl-12 pr-4 text-sm text-[#172b45] placeholder:text-slate-400"></div>
                            @error('budget')<p class="mt-2 text-xs font-semibold text-red-700">{{ $message }}</p>@enderror
                        </div>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="month" class="mb-2 block text-sm font-bold text-[#172b45]">Bulan berangkat</label>
                                <input id="month" name="month" type="month" min="{{ today()->format('Y-m') }}" value="{{ old('month', $preferences['month'] ?? '') }}" class="recommend-field min-h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-[#172b45]">
                                @error('month')<p class="mt-2 text-xs font-semibold text-red-700">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="city" class="mb-2 block text-sm font-bold text-[#172b45]">Kota keberangkatan</label>
                                <select id="city" name="city" class="recommend-field min-h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-[#172b45]">
                                    <option value="">Semua kota</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city }}" @selected(old('city', $preferences['city'] ?? '') === $city)>{{ $city }}</option>
                                    @endforeach
                                </select>
                                @error('city')<p class="mt-2 text-xs font-semibold text-red-700">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div>
                            <label for="notes" class="mb-2 block text-sm font-bold text-[#172b45]">Hal lain yang penting bagi Anda <span class="font-normal text-slate-500">(opsional)</span></label>
                            <textarea id="notes" name="notes" rows="3" maxlength="300" placeholder="Misalnya ingin hotel dekat Masjidil Haram atau perjalanan lebih singkat" class="recommend-field w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm leading-6 text-[#172b45] placeholder:text-slate-400">{{ old('notes', $preferences['notes'] ?? '') }}</textarea>
                            <p class="mt-1 text-xs leading-5 text-slate-500">Catatan ini ikut terkirim ke CS jika Anda memilih konsultasi. Jangan masukkan nomor paspor atau data sensitif.</p>
                            @error('notes')<p class="mt-2 text-xs font-semibold text-red-700">{{ $message }}</p>@enderror
                        </div>
                        @if ($ai['enabled'] && $ai['key_verified'])
                            <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-[#dce4ee] bg-[#f5f8fc] p-4 text-xs leading-5 text-slate-600">
                                <input type="checkbox" name="ai_consent" value="1" @checked(old('ai_consent', $preferences['ai_consent'] ?? false)) class="mt-0.5 rounded border-slate-300 text-[#3166b1] focus:ring-[#3166b1]">
                                <span>Saya setuju anggaran, jadwal, kota, dan catatan saya dikirim ke OpenAI untuk mengurutkan paket. Tanpa persetujuan, saya tetap mendapat hasil dari filter situs.</span>
                            </label>
                        @endif
                        <button type="submit" class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#fcde05] px-5 text-sm font-extrabold text-[#102b52] transition hover:bg-[#f2cf00] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#3166b1]"><i data-lucide="sparkles" class="size-4" aria-hidden="true"></i>Cari paket yang cocok</button>
                    </form>
                </div>

                <div id="hasil" tabindex="-1" class="min-w-0 scroll-mt-24">
                    @if ($result === null)
                        <div class="rounded-[1.75rem] border border-dashed border-[#b7cbe5] bg-white/75 p-8 sm:p-10">
                            <div class="flex size-14 items-center justify-center rounded-2xl bg-[#eaf2fd] text-[#3166b1]"><i data-lucide="route" class="size-7" aria-hidden="true"></i></div>
                            <h2 class="mt-6 text-2xl font-extrabold text-[#102b52]">Mulai dari rencana Anda</h2>
                            <p class="mt-3 max-w-md text-sm leading-7 text-slate-600">Kami akan menampilkan hingga {{ $ai['result_limit'] }} paket yang sesuai. Anda bisa melihat detailnya dulu atau langsung membicarakannya dengan tim kami.</p>
                            <div class="mt-7 grid gap-3 border-t border-slate-200 pt-6 text-sm font-semibold text-[#3166b1] sm:grid-cols-3">
                                <span class="flex items-center gap-2"><i data-lucide="wallet" class="size-4" aria-hidden="true"></i>Sesuai anggaran</span>
                                <span class="flex items-center gap-2"><i data-lucide="calendar-days" class="size-4" aria-hidden="true"></i>Jadwal tersedia</span>
                                <span class="flex items-center gap-2"><i data-lucide="map-pin" class="size-4" aria-hidden="true"></i>Kota pilihan</span>
                            </div>
                        </div>
                    @elseif ($result['packages']->isEmpty())
                        <div class="rounded-[1.75rem] border border-[#dce4ee] bg-white p-8 sm:p-10" role="status">
                            <div class="flex size-14 items-center justify-center rounded-2xl bg-[#eaf2fd] text-[#3166b1]"><i data-lucide="calendar-search" class="size-7" aria-hidden="true"></i></div>
                            <h2 class="mt-6 text-2xl font-extrabold text-[#102b52]">Belum ada paket untuk pilihan ini</h2>
                            <p class="mt-3 text-sm leading-7 text-slate-600">Coba longgarkan anggaran, pilih bulan lain, atau tampilkan semua kota keberangkatan.</p>
                            <a href="{{ url('/#paket-umrah') }}" class="mt-6 inline-flex min-h-11 items-center gap-2 rounded-xl bg-[#3166b1] px-5 text-sm font-bold text-white hover:bg-[#245392]">Lihat semua paket<i data-lucide="arrow-right" class="size-4" aria-hidden="true"></i></a>
                        </div>
                    @else
                        <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                            <div><span class="text-xs font-extrabold uppercase tracking-[.14em] text-[#3166b1]">Hasil pilihan</span><h2 class="mt-2 text-2xl font-extrabold text-[#102b52]">{{ $result['packages']->count() }} paket untuk Anda</h2></div>
                            <span class="text-xs font-semibold text-slate-500">Harga dan kursi dikonfirmasi oleh CS</span>
                        </div>
                        @if (! $result['ai_used'] && ! empty($preferences['notes']))
                            <p class="mb-4 rounded-xl bg-blue-50 px-4 py-3 text-xs leading-5 text-[#245392]">Catatan tambahan Anda akan diteruskan ke CS saat konsultasi.</p>
                        @endif
                        <div class="space-y-4">
                            @foreach ($result['packages'] as $package)
                                <article class="recommend-ticket overflow-hidden rounded-[1.5rem] border border-[#dce4ee] bg-white">
                                    <div class="grid sm:grid-cols-[155px_minmax(0,1fr)]">
                                        <img src="{{ $package->image_url ?: asset('images/package_kaaba.webp') }}" alt="" class="h-40 w-full object-cover sm:h-full" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('images/package_kaaba.webp') }}'">
                                        <div class="min-w-0 p-5 sm:p-6">
                                            <div class="flex flex-wrap items-center gap-2 text-[11px] font-bold uppercase tracking-wide text-[#3166b1]"><span>{{ $package->category ?: 'Umrah' }}</span><span aria-hidden="true">·</span><span>{{ $package->duration_days }} hari</span></div>
                                            <h3 class="mt-2 text-lg font-extrabold leading-snug text-[#102b52]"><a href="{{ route('packages.show', $package->slug) }}" class="hover:text-[#3166b1]">{{ $package->name }}</a></h3>
                                            <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-xs font-semibold text-slate-600"><span class="inline-flex items-center gap-1.5"><i data-lucide="calendar-days" class="size-4 text-[#3166b1]" aria-hidden="true"></i>{{ $package->departure_date->locale('id')->translatedFormat('d F Y') }}</span>@if ($package->start_city)<span class="inline-flex items-center gap-1.5"><i data-lucide="map-pin" class="size-4 text-[#3166b1]" aria-hidden="true"></i>Dari {{ $package->start_city }}</span>@endif</div>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap items-center justify-between gap-4 border-t border-dashed border-[#cbd8ea] bg-[#fbfcff] px-5 py-4 sm:px-6"><div><span class="block text-xs text-slate-500">Mulai dari · per jemaah</span><strong class="text-lg font-extrabold text-[#102b52]">Rp {{ number_format($package->price, 0, ',', '.') }}</strong></div><div class="flex flex-wrap gap-2"><a href="{{ route('packages.show', $package->slug) }}" class="inline-flex min-h-10 items-center rounded-lg border border-[#b7cbe5] px-4 text-xs font-bold text-[#245392] hover:bg-blue-50">Detail paket</a><a href="{{ route('public.chat', ['recommended_package_id' => $package->id, 'utm_source' => 'website', 'utm_medium' => 'rekomendasi_paket']) }}" class="inline-flex min-h-10 items-center gap-1.5 rounded-lg bg-[#fcde05] px-4 text-xs font-extrabold text-[#102b52] hover:bg-[#f2cf00]"><i data-lucide="message-circle" class="size-4" aria-hidden="true"></i>Konsultasi CS</a></div></div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </main>
    @include('public.partials.footer')
    @if ($result !== null)
        <script>window.addEventListener('load', () => document.getElementById('hasil')?.scrollIntoView({block: 'start'}), {once: true});</script>
    @endif
</body>
</html>

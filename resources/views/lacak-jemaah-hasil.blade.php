@php
    $siteName = $settings['site_name'] ?? 'IZI Travel';
    $wa_phone = preg_replace('/[^0-9]/', '', $settings['contact_whatsapp'] ?? '6281112345678');
    if (str_starts_with($wa_phone, '0')) {
        $wa_phone = '62' . substr($wa_phone, 1);
    }

    $statusMeta = [
        'not_started' => ['icon' => 'file-clock', 'badge' => 'bg-slate-100 text-slate-500'],
        'in_progress' => ['icon' => 'loader', 'badge' => 'bg-blue-50 text-blue-600'],
        'ready' => ['icon' => 'check-circle-2', 'badge' => 'bg-emerald-50 text-emerald-600'],
        'attention' => ['icon' => 'alert-triangle', 'badge' => 'bg-amber-50 text-amber-700'],
    ];

    $checklistStepMeta = [
        'completed' => ['icon' => 'check', 'ring' => 'bg-emerald-600 text-white', 'text' => 'text-emerald-600'],
        'in_progress' => ['icon' => 'loader', 'ring' => 'bg-blue-600 text-white', 'text' => 'text-blue-600'],
        'problem' => ['icon' => 'x', 'ring' => 'bg-red-600 text-white', 'text' => 'text-red-600'],
        'missing' => ['icon' => 'circle', 'ring' => 'bg-slate-100 text-slate-400 border border-slate-200', 'text' => 'text-slate-400'],
    ];

    $checklistKinds = ['document' => 'Dokumen', 'operational' => 'Operasional'];
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Hasil Lacak Jemaah - {{ $siteName }}</title>
    <link rel="icon" type="image/png" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ isset($settings['site_favicon']) ? (str_starts_with($settings['site_favicon'], 'images/') ? asset($settings['site_favicon']) : asset('storage/' . $settings['site_favicon'])) : asset('images/favicon.png') }}">
    <meta name="description" content="Hasil pencarian status persiapan keberangkatan umrah Anda.">
    <meta name="robots" content="noindex, nofollow">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; background-color: #fafaf9; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 700 !important; letter-spacing: -0.02em; }
        .font-islamic { font-family: 'El Messiri', serif !important; font-weight: 700 !important; letter-spacing: 0.01em; }
        .islamic-pattern {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cpath d='M30 0 l10 20 l20 10 l-20 10 l-10 20 l-10 -20 l-20 -10 l20 -10 z' fill='%23d97706' fill-opacity='0.02'/%3E%3C/svg%3E");
            background-size: 60px 60px;
        }
        .islamic-pattern-blue-soft {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cpath d='M30 0 l10 20 l20 10 l-20 10 l-10 20 l-10 -20 l-20 -10 l20 -10 z' fill='%233b82f6' fill-opacity='0.015'/%3E%3C/svg%3E");
            background-size: 60px 60px;
        }
        .soft-shadow { box-shadow: 0 10px 30px -10px rgba(7, 30, 61, 0.03), 0 1px 3px rgba(7, 30, 61, 0.01); }
        .scrollbar-none::-webkit-scrollbar { display: none; }
        .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-stone-50 text-slate-800 overflow-x-hidden antialiased">

    <!-- BEGIN: Floating Modern Header -->
    <header class="fixed top-4 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-7xl z-50">
        <nav class="bg-white border border-slate-100/80 rounded-full shadow-[0_10px_35px_-10px_rgba(0,0,0,0.05)] px-6 py-3 flex items-center justify-between transition-all duration-300">
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}">
                    <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO.webp') }}" alt="{{ $siteName }}" class="h-8 w-auto object-contain" width="687" height="240" fetchpriority="high" decoding="async" />
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-1 font-medium text-slate-600">
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#beranda">Beranda</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#paket-umrah">Paket Umrah</a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ route('public.gallery') }}">Galeri</a>
                <a class="text-blue-600 bg-blue-50/80 px-3.5 py-2 rounded-full transition duration-200 flex items-center gap-1.5" href="{{ route('public.jemaah.tracking') }}">
                    <i data-lucide="search-check" class="w-3.5 h-3.5"></i>
                    Lacak Jemaah
                </a>
                <a class="hover:text-blue-600 hover:bg-slate-50/80 px-3.5 py-2 rounded-full transition duration-200" href="{{ url('/') }}#kontak">Kontak</a>
            </div>

            <div class="flex items-center gap-2">
                <a class="bg-blue-600 text-white px-3.5 py-2.5 sm:px-5 sm:py-2.5 rounded-full font-bold hover:bg-blue-700 transition-all duration-300 flex items-center gap-2 text-xs shadow-md active:scale-95" href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path></svg>
                    <span class="hidden sm:inline">Konsultasi/WhatsApp</span>
                </a>
                <button id="mobile-bottom-menu-trigger" class="md:hidden w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-600 hover:bg-slate-100 transition active:scale-95 shadow-sm">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
            </div>
        </nav>
    </header>
    <!-- END: Floating Header -->

    <!-- BEGIN: Banner Header -->
    <section class="relative pt-32 pb-12 bg-blue-950 overflow-hidden islamic-pattern">
        <div class="absolute inset-0 bg-slate-900/60 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-center sm:text-left space-y-2">
                <span class="bg-amber-500/10 text-amber-400 text-xs font-bold px-4 py-1.5 rounded-full w-fit uppercase tracking-wider border border-amber-500/20 inline-flex items-center gap-1.5">
                    <i data-lucide="search-check" class="w-3.5 h-3.5"></i>
                    Hasil Pencarian
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Status Persiapan Keberangkatan</h1>
            </div>
            <a href="{{ route('public.jemaah.tracking') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/15 border border-white/10 rounded-full font-bold text-xs text-white transition shrink-0">
                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                Cari Nomor Lain
            </a>
        </div>
    </section>
    <!-- END: Banner Header -->

    <!-- BEGIN: Content Section -->
    <section class="py-16 bg-stone-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 space-y-8">

            @if (! $jemaah)
                <!-- Not found -->
                <div class="max-w-lg mx-auto bg-white rounded-[2rem] border border-slate-100/80 p-8 text-center soft-shadow space-y-3">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 flex items-center justify-center mx-auto">
                        <i data-lucide="user-x" class="w-7 h-7 text-rose-400"></i>
                    </div>
                    <p class="font-bold text-slate-800">Data Tidak Ditemukan</p>
                    <p class="text-xs text-slate-400 max-w-xs mx-auto">Nomor paspor dan tanggal lahir yang Anda masukkan belum sesuai dengan catatan kami. Pastikan keduanya benar, atau hubungi tim kami jika Anda baru saja mendaftar.</p>
                    <a href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%2C%20saya%20ingin%20menanyakan%20status%20pendaftaran%20saya" target="_blank" class="inline-flex items-center gap-2 mt-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 rounded-full font-bold text-xs text-white transition shadow-md">
                        <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                        Hubungi Kami
                    </a>
                </div>
            @else
                <!-- Found -->
                <div class="bg-gradient-to-br from-blue-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white relative overflow-hidden" x-data="{ showPersonal: false }">
                    <div class="relative z-10 space-y-5">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center shrink-0">
                                    <i data-lucide="user-round" class="w-6 h-6 text-amber-400"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Assalamu'alaikum</p>
                                    <p class="text-lg font-bold">{{ $jemaah->name }}</p>
                                </div>
                            </div>
                            <button type="button" @click="showPersonal = !showPersonal"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-full font-bold text-xs text-slate-200 hover:text-white transition shrink-0">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                <span x-text="showPersonal ? 'Sembunyikan Data Pribadi' : 'Lihat Data Pribadi'"></span>
                            </button>
                        </div>

                        <div x-show="showPersonal" x-cloak x-transition class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-5 border-t border-white/10">
                            @php
                                $personalFields = [
                                    ['label' => 'No. Paspor', 'value' => $jemaah->passport_number],
                                    ['label' => 'Tanggal Lahir', 'value' => optional($jemaah->birth_date)->translatedFormat('d F Y')],
                                    ['label' => 'Jenis Kelamin', 'value' => \App\Models\Jemaah::GENDERS[$jemaah->gender] ?? null],
                                    ['label' => 'Alamat', 'value' => $jemaah->address],
                                ];
                            @endphp
                            @foreach ($personalFields as $field)
                                <div class="min-w-0 {{ $field['label'] === 'Alamat' ? 'col-span-2 sm:col-span-4' : '' }}">
                                    <p class="text-[9px] uppercase tracking-widest text-slate-400 font-bold mb-1">{{ $field['label'] }}</p>
                                    @if (!empty($field['value']))
                                        <p class="text-sm font-bold text-white break-words">{{ $field['value'] }}</p>
                                    @else
                                        <p class="text-sm font-semibold text-slate-500 italic flex items-center gap-1.5">
                                            <i data-lucide="circle-slash" class="w-3.5 h-3.5"></i>
                                            Belum diisi
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @forelse ($jemaah->registrations as $registration)
                    @php
                        $meta = $statusMeta[$registration->overall_status];
                        $package = $registration->package;
                        $companions = max(0, ($package->registrations_count ?? 1) - 1);

                        $today = now()->startOfDay();
                        $returnDate = $package->departure_date->copy()->addDays($package->duration_days);
                        $journeySteps = [
                            ['label' => 'Pendaftaran', 'icon' => 'clipboard-check', 'done' => true],
                            ['label' => 'Persiapan Dokumen', 'icon' => 'file-check-2', 'done' => collect($registration->checklist)->every(fn ($item) => $item['status'] === 'completed')],
                            ['label' => 'Manasik', 'icon' => 'graduation-cap', 'done' => $package->manasik_date && $today->gte($package->manasik_date)],
                            ['label' => 'Keberangkatan', 'icon' => 'plane-takeoff', 'done' => $today->gte($package->departure_date)],
                            ['label' => 'Kepulangan', 'icon' => 'plane-landing', 'done' => $today->gte($returnDate)],
                        ];
                        $journeyDoneCount = collect($journeySteps)->where('done', true)->count();
                        $journeyCurrentIndex = collect($journeySteps)->search(fn ($step) => ! $step['done']);
                        $journeyCurrentIndex = $journeyCurrentIndex === false ? count($journeySteps) - 1 : $journeyCurrentIndex;
                        $journeyProgressPercent = ($journeyDoneCount / count($journeySteps)) * 80;
                    @endphp

                    @if ($registration->overall_status === 'attention')
                        <!-- Perlu Tindak Lanjut -->
                        <div class="flex flex-col gap-4 p-5 sm:p-6 rounded-3xl border border-amber-100/80 bg-amber-50/20">
                            <div class="flex items-center gap-3">
                                <span class="bg-amber-100 text-amber-700 p-3 rounded-xl flex items-center justify-center shrink-0"><i data-lucide="alert-triangle" class="w-5 h-5"></i></span>
                                <h4 class="text-sm font-extrabold text-amber-800">Perlu Konfirmasi Ulang</h4>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed">Ada bagian dari persiapan keberangkatan Anda yang perlu dikonfirmasi ulang oleh tim kami agar tidak mengganggu jadwal keberangkatan. Silakan hubungi kami segera untuk informasi lebih lanjut.</p>
                            <a href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%2C%20saya%20ingin%20menanyakan%20status%20persiapan%20keberangkatan%20saya" target="_blank" class="inline-flex items-center gap-2 self-start px-5 py-2.5 bg-amber-600 hover:bg-amber-700 rounded-full font-bold text-xs text-white transition shadow-md">
                                <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                                Hubungi Kami Sekarang
                            </a>
                        </div>
                    @elseif ($registration->overall_status === 'ready')
                        <!-- Persiapan Lengkap -->
                        <div class="flex flex-col gap-3 p-5 sm:p-6 rounded-3xl border border-emerald-100/80 bg-emerald-50/20">
                            <div class="flex items-center gap-3">
                                <span class="bg-emerald-100 text-emerald-700 p-3 rounded-xl flex items-center justify-center shrink-0"><i data-lucide="check-circle-2" class="w-5 h-5"></i></span>
                                <h4 class="text-sm font-extrabold text-emerald-800">Alhamdulillah, Persiapan Anda Sudah Lengkap</h4>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed">Seluruh dokumen dan perlengkapan Anda telah diverifikasi oleh tim kami. Tidak ada lagi yang perlu disiapkan — cukup jaga kesehatan dan pastikan hadir sesuai jadwal manasik serta keberangkatan. Sampai jumpa di Tanah Suci.</p>
                        </div>
                    @endif

                    <!-- Progres Perjalanan -->
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xl shadow-slate-900/10 hover:shadow-2xl transition-shadow duration-300">
                        <h3 class="text-slate-900 font-extrabold text-sm uppercase tracking-wider flex items-center gap-2.5 pb-5 border-b border-slate-200 mb-8">
                            <span class="w-2.5 h-2.5 rounded-full bg-teal-600 shadow-sm shadow-teal-500/50"></span>
                            Progres Perjalanan
                        </h3>
                        <div class="relative">
                            <div class="absolute top-5 left-[10%] right-[10%] h-0.5 bg-slate-200"></div>
                            <div class="absolute top-5 left-[10%] h-0.5 bg-emerald-500 transition-all" style="width: {{ $journeyProgressPercent }}%"></div>
                            <div class="relative grid grid-cols-5 gap-1">
                                @foreach ($journeySteps as $index => $step)
                                    <div class="flex flex-col items-center text-center gap-2">
                                        <span class="w-10 h-10 rounded-full flex items-center justify-center border-2 shrink-0
                                            {{ $step['done']
                                                ? 'bg-emerald-600 border-emerald-600 text-white'
                                                : ($index === $journeyCurrentIndex
                                                    ? 'bg-white border-blue-600 text-blue-600 ring-4 ring-blue-100'
                                                    : 'bg-white border-slate-200 text-slate-300') }}">
                                            <i data-lucide="{{ $step['done'] ? 'check' : $step['icon'] }}" class="w-4.5 h-4.5"></i>
                                        </span>
                                        <p class="text-[10px] sm:text-[11px] font-bold leading-tight {{ $step['done'] || $index === $journeyCurrentIndex ? 'text-slate-700' : 'text-slate-400' }}">{{ $step['label'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Bento grid: package hero + all detail cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 md:grid-flow-row-dense gap-6">

                        <!-- Package hero image -->
                        <div class="relative rounded-3xl overflow-hidden shadow-xl border border-slate-200 bg-white h-56 md:h-64 md:col-span-2">
                            @if ($package->image_url)
                                <img src="{{ $package->image_url }}" alt="{{ $package->name }}" class="w-full h-full object-cover" width="1200" height="500" fetchpriority="high" decoding="async" />
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-900 to-slate-900"></div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                            <div class="absolute bottom-5 left-5 right-5 text-white">
                                <p class="font-black text-lg sm:text-xl">{{ $package->name }}</p>
                                <p class="text-xs text-slate-200 mt-1 flex items-center gap-1.5">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                    Keberangkatan {{ $package->departure_date->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Kesiapan Berangkat -->
                        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xl shadow-slate-900/10 hover:shadow-2xl transition-shadow duration-300 space-y-5 md:row-span-2">
                            <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-200">
                                <h3 class="text-slate-900 font-extrabold text-sm uppercase tracking-wider flex items-center gap-2.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 shadow-sm shadow-emerald-500/50"></span>
                                    Kesiapan Berangkat
                                </h3>
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold px-3 py-1.5 rounded-full {{ $meta['badge'] }}">
                                    <i data-lucide="{{ $meta['icon'] }}" class="w-3.5 h-3.5"></i>
                                    {{ \App\Models\Registration::OVERALL_STATUSES[$registration->overall_status] }}
                                </span>
                            </div>

                            <div class="space-y-5">
                                @foreach ($checklistKinds as $kind => $kindLabel)
                                    <div>
                                        <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-widest mb-1">{{ $kindLabel }}</p>
                                        <div class="divide-y divide-slate-50">
                                            @foreach (collect($registration->checklist)->where('kind', $kind) as $item)
                                                @php
                                                    $stepMeta = $checklistStepMeta[$item['status']];
                                                @endphp
                                                <div class="flex items-center gap-3 py-3">
                                                    <span class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 {{ $stepMeta['ring'] }}">
                                                        <i data-lucide="{{ $stepMeta['icon'] }}" class="w-4 h-4"></i>
                                                    </span>
                                                    <p class="flex-1 text-sm font-bold text-slate-700">{{ $item['label'] }}</p>
                                                    <span class="text-xs font-extrabold {{ $stepMeta['text'] }}">{{ \App\Models\Registration::STATUSES[$item['status']] }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Detail Paket Umroh -->
                        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xl shadow-slate-900/10 hover:shadow-2xl transition-shadow duration-300 space-y-5 md:col-span-2">
                            <h3 class="text-slate-900 font-extrabold text-sm uppercase tracking-wider flex items-center gap-2.5 pb-4 border-b border-slate-200">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600 shadow-sm shadow-indigo-500/50"></span>
                                Detail Paket Umroh
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @php
                                    $inclusions = $package->inclusions ?: [
                                        ['icon' => 'plane', 'label' => 'Tiket pesawat PP', 'desc' => 'Penerbangan pulang-pergi dengan maskapai terpercaya'],
                                        ['icon' => 'file-check', 'label' => 'Pengurusan visa resmi', 'desc' => 'Visa umrah resmi yang diproses langsung'],
                                        ['icon' => 'building-2', 'label' => 'Hotel pelataran masjid', 'desc' => 'Akomodasi dekat Masjidil Haram & Nabawi'],
                                        ['icon' => 'shield-check', 'label' => 'Asuransi perjalanan', 'desc' => 'Perlindungan selama perjalanan ibadah'],
                                        ['icon' => 'luggage', 'label' => 'Perlengkapan ibadah', 'desc' => 'Koper, kain ihram, buku doa, & lainnya'],
                                        ['icon' => 'compass', 'label' => 'Muthawwif bersertifikat', 'desc' => 'Pembimbing ibadah profesional & berpengalaman'],
                                        ['icon' => 'utensils', 'label' => 'Makan prasmanan 3x', 'desc' => 'Menu halal & beragam setiap hari'],
                                        ['icon' => 'book-open', 'label' => 'Handling & manasik', 'desc' => 'Bimbingan manasik pra-keberangkatan'],
                                    ];
                                @endphp
                                @foreach ($inclusions as $item)
                                    <div class="flex items-start gap-3.5 p-3.5 rounded-2xl border border-slate-200 bg-slate-50/40">
                                        <span class="bg-indigo-50 text-indigo-600 rounded-xl p-2.5 shrink-0 flex items-center justify-center"><i data-lucide="{{ $item['icon'] ?? 'check' }}" class="w-4.5 h-4.5"></i></span>
                                        <div class="min-w-0">
                                            <h4 class="text-xs sm:text-sm font-black text-slate-800 leading-snug">{{ $item['label'] }}</h4>
                                            @if (!empty($item['desc']))
                                                <p class="text-[10px] sm:text-[11px] text-slate-400 font-semibold mt-1 leading-relaxed">{{ $item['desc'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Tim Lapangan & Jadwal Manasik -->
                        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xl shadow-slate-900/10 hover:shadow-2xl transition-shadow duration-300 space-y-5">
                            <h3 class="text-slate-900 font-extrabold text-sm uppercase tracking-wider flex items-center gap-2.5 pb-4 border-b border-slate-200">
                                <span class="w-2.5 h-2.5 rounded-full bg-purple-600 shadow-sm shadow-purple-500/50"></span>
                                Tim & Manasik
                            </h3>

                            <div class="space-y-4">
                                <div class="flex items-start gap-3 bg-slate-50/50 p-4 rounded-2xl border border-slate-200 min-w-0">
                                    <span class="bg-purple-600/10 text-purple-600 p-2.5 rounded-xl border border-purple-600/10 flex items-center justify-center flex-shrink-0"><i data-lucide="user-check" class="w-5 h-5"></i></span>
                                    <div class="min-w-0">
                                        <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider mb-1">Tim Lapangan Bertugas</p>
                                        <p class="text-xs sm:text-sm font-extrabold text-slate-800 leading-tight">{{ $package->pembimbing ?: 'Muthawwif IZI Travel' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 bg-slate-50/50 p-4 rounded-2xl border border-slate-200 min-w-0">
                                    <span class="bg-purple-600/10 text-purple-600 p-2.5 rounded-xl border border-purple-600/10 flex items-center justify-center flex-shrink-0"><i data-lucide="calendar-check" class="w-5 h-5"></i></span>
                                    <div class="min-w-0">
                                        <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider mb-1">Jadwal Manasik</p>
                                        @if ($package->manasik_date)
                                            <p class="text-xs sm:text-sm font-extrabold text-slate-800 leading-tight">{{ $package->manasik_date->translatedFormat('d F Y') }}</p>
                                            @if ($package->manasik_location)
                                                <p class="text-[11px] text-slate-400 font-semibold mt-0.5">{{ $package->manasik_location }}</p>
                                            @endif
                                        @else
                                            <p class="text-xs sm:text-sm font-extrabold text-slate-400 leading-tight">Akan diinformasikan tim kami</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Perjalanan -->
                        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xl shadow-slate-900/10 hover:shadow-2xl transition-shadow duration-300 space-y-5 md:col-span-2">
                            <h3 class="text-slate-900 font-extrabold text-sm uppercase tracking-wider flex items-center gap-2.5 pb-4 border-b border-slate-200">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-600 shadow-sm shadow-blue-500/50"></span>
                                Informasi Perjalanan
                            </h3>

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <div class="flex items-start gap-3 bg-slate-50/50 p-4 rounded-2xl border border-slate-200 min-w-0">
                                    <span class="bg-blue-600/10 text-blue-600 p-2.5 rounded-xl border border-blue-600/10 flex items-center justify-center flex-shrink-0"><i data-lucide="calendar" class="w-5 h-5"></i></span>
                                    <div class="min-w-0">
                                        <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider mb-1">Keberangkatan</p>
                                        <p class="text-xs sm:text-sm font-extrabold text-slate-800 leading-tight">{{ $package->departure_date->translatedFormat('d F Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 bg-slate-50/50 p-4 rounded-2xl border border-slate-200 min-w-0">
                                    <span class="bg-blue-600/10 text-blue-600 p-2.5 rounded-xl border border-blue-600/10 flex items-center justify-center flex-shrink-0"><i data-lucide="clock" class="w-5 h-5"></i></span>
                                    <div class="min-w-0">
                                        <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider mb-1">Durasi</p>
                                        <p class="text-xs sm:text-sm font-extrabold text-slate-800 leading-tight">{{ $package->duration_days }} Hari</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 bg-slate-50/50 p-4 rounded-2xl border border-slate-200 min-w-0">
                                    <span class="bg-blue-600/10 text-blue-600 p-2.5 rounded-xl border border-blue-600/10 flex items-center justify-center flex-shrink-0"><i data-lucide="plane" class="w-5 h-5"></i></span>
                                    <div class="min-w-0">
                                        <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider mb-1">Maskapai</p>
                                        <p class="text-xs sm:text-sm font-extrabold text-slate-800 leading-tight truncate">{{ $package->airline ?: '—' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 bg-slate-50/50 p-4 rounded-2xl border border-slate-200 min-w-0">
                                    <span class="bg-blue-600/10 text-blue-600 p-2.5 rounded-xl border border-blue-600/10 flex items-center justify-center flex-shrink-0"><i data-lucide="users" class="w-5 h-5"></i></span>
                                    <div class="min-w-0">
                                        <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider mb-1">Rombongan</p>
                                        <p class="text-xs sm:text-sm font-extrabold text-slate-800 leading-tight">{{ $companions > 0 ? "Bersama {$companions} jemaah lain" : 'Belum ada jemaah lain' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Akomodasi Hotel -->
                        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xl shadow-slate-900/10 hover:shadow-2xl transition-shadow duration-300 space-y-5 md:col-span-3">
                            <h3 class="text-slate-900 font-extrabold text-sm uppercase tracking-wider flex items-center gap-2.5 pb-4 border-b border-slate-200">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-600 shadow-sm shadow-amber-500/50"></span> Akomodasi Hotel
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="flex flex-col gap-4 p-5 rounded-2xl border border-amber-100/80 bg-amber-50/20">
                                    <div class="flex items-center gap-3">
                                        <span class="bg-amber-100 text-amber-700 p-3 rounded-xl flex items-center justify-center shrink-0"><i data-lucide="hotel" class="w-5 h-5"></i></span>
                                        <div>
                                            <h4 class="text-xs font-extrabold text-amber-800 uppercase tracking-widest leading-none mb-1">Hotel Makkah</h4>
                                            @if ($package->hotel_makkah_nights)
                                                <span class="text-[10px] font-bold text-slate-400">{{ $package->hotel_makkah_nights }} Malam</span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-base font-black text-slate-800 leading-snug">{{ $package->hotel_makkah ?: ($package->hotel ?: 'Hotel Bintang Pilihan') }}</p>
                                </div>
                                <div class="flex flex-col gap-4 p-5 rounded-2xl border border-amber-100/80 bg-amber-50/20">
                                    <div class="flex items-center gap-3">
                                        <span class="bg-amber-100 text-amber-700 p-3 rounded-xl flex items-center justify-center shrink-0"><i data-lucide="hotel" class="w-5 h-5"></i></span>
                                        <div>
                                            <h4 class="text-xs font-extrabold text-amber-800 uppercase tracking-widest leading-none mb-1">Hotel Madinah</h4>
                                            @if ($package->hotel_madinah_nights)
                                                <span class="text-[10px] font-bold text-slate-400">{{ $package->hotel_madinah_nights }} Malam</span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-base font-black text-slate-800 leading-snug">{{ $package->hotel_madinah ?: ($package->hotel ?: 'Hotel Bintang Pilihan') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="max-w-lg mx-auto bg-white rounded-3xl border border-slate-100/80 p-8 text-center soft-shadow">
                        <p class="text-sm text-slate-400">Anda belum terdaftar pada keberangkatan manapun.</p>
                    </div>
                @endforelse

                <!-- Contact CTA -->
                <div class="rounded-3xl border border-blue-100 bg-blue-50/50 p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3 text-center sm:text-left">
                        <span class="w-10 h-10 rounded-xl bg-blue-600/10 text-blue-600 flex items-center justify-center shrink-0 hidden sm:flex">
                            <i data-lucide="message-circle-question" class="w-5 h-5"></i>
                        </span>
                        <p class="text-xs text-slate-500">Untuk detail kelengkapan dokumen atau pertanyaan lain, silakan hubungi tim kami langsung.</p>
                    </div>
                    <a href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%2C%20saya%20ingin%20menanyakan%20status%20pendaftaran%20saya" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 rounded-full font-bold text-xs text-white transition shadow-md shrink-0">
                        <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                        Hubungi Kami
                    </a>
                </div>
            @endif
        </div>
    </section>
    <!-- END: Content Section -->

    <!-- BEGIN: Footer -->
    <footer class="bg-slate-950 text-white py-16 md:py-24 relative overflow-hidden islamic-pattern-blue-soft" id="kontak">
        <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-blue-500/30 to-transparent"></div>
        <div class="absolute -right-20 -bottom-20 w-[600px] h-[600px] bg-blue-600/5 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="max-w-[85rem] mx-auto px-6 sm:px-10 lg:px-16 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-10">
                <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO WHITE.webp') }}" alt="{{ $siteName }}" class="h-11 w-auto object-contain" width="531" height="240" loading="lazy" decoding="async" />
                <div class="flex items-center gap-4 text-xs text-slate-400">
                    <span class="flex items-center gap-1.5"><i data-lucide="phone" class="w-3.5 h-3.5 text-amber-500"></i>{{ $settings['contact_phone'] ?? '+62 811-1234-5678' }}</span>
                    <span class="flex items-center gap-1.5"><i data-lucide="mail" class="w-3.5 h-3.5 text-amber-500"></i>{{ $settings['contact_email'] ?? 'info@izitravel.co.id' }}</span>
                </div>
            </div>
            <div class="border-t border-white/5 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
                <div class="flex gap-6 font-light">
                    <a href="{{ route('public.terms') }}" class="hover:text-amber-500 transition duration-200">Syarat &amp; Ketentuan</a>
                    <a href="{{ route('public.privacy') }}" class="hover:text-amber-500 transition duration-200">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>
    <!-- END: Footer -->

    <!-- BEGIN: Mobile Navigation Drawer -->
    <div id="mobile-drawer" class="fixed inset-0 z-50 invisible flex justify-end transition-all duration-300" role="dialog" aria-modal="true">
        <div id="mobile-drawer-backdrop" class="fixed inset-0 bg-slate-950/40 opacity-0 transition-opacity duration-300 backdrop-blur-sm"></div>
        <div id="mobile-drawer-content" class="relative w-full max-w-xs bg-white h-full shadow-2xl p-6 flex flex-col gap-6 transform translate-x-full transition-transform duration-300 z-10">
            <div class="flex-1 overflow-y-auto scrollbar-none space-y-6 pr-1">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <img src="{{ isset($settings['site_logo']) ? (str_starts_with($settings['site_logo'], 'images/') ? asset($settings['site_logo']) : asset('storage/' . $settings['site_logo'])) : asset('images/Izi LOGO.webp') }}" alt="{{ $siteName }}" class="h-7 w-auto object-contain" width="180" height="28" decoding="async" />
                    <button id="mobile-drawer-close" class="w-8 h-8 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-800 transition active:scale-95 shadow-sm">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div class="flex flex-col space-y-1">
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#beranda">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="home" class="w-4 h-4"></i></span>
                        Beranda
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#paket-umrah">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="compass" class="w-4 h-4"></i></span>
                        Paket Umrah
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-blue-600 bg-blue-50/50 transition duration-200 font-semibold text-xs" href="{{ route('public.jemaah.tracking') }}">
                        <span class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center"><i data-lucide="search-check" class="w-4 h-4"></i></span>
                        Lacak Jemaah
                    </a>
                    <a class="mobile-nav-link flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition duration-200 font-semibold text-xs" href="{{ url('/') }}#kontak">
                        <span class="w-8 h-8 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center border border-slate-200/50"><i data-lucide="phone-call" class="w-4 h-4"></i></span>
                        Kontak
                    </a>
                </div>
            </div>
            <div class="pt-4 border-t border-slate-100 flex flex-col gap-3 mt-4 shrink-0">
                <a class="w-full bg-blue-600 text-white py-2.5 rounded-xl font-bold hover:bg-blue-700 transition duration-200 flex items-center justify-center gap-2 text-xs shadow-lg shadow-blue-500/10 active:scale-95" href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%20IZI%20Travel" target="_blank">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path></svg>
                    <span>Konsultasi Syiar</span>
                </a>
            </div>
        </div>
    </div>
    <!-- END: Mobile Navigation Drawer -->

    <script src="https://unpkg.com/lucide@0.462.0"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) window.lucide.createIcons();

            const mobileBottomMenuTrigger = document.getElementById('mobile-bottom-menu-trigger');
            const mobileDrawer = document.getElementById('mobile-drawer');
            const mobileDrawerBackdrop = document.getElementById('mobile-drawer-backdrop');
            const mobileDrawerContent = document.getElementById('mobile-drawer-content');
            const mobileDrawerClose = document.getElementById('mobile-drawer-close');
            const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

            const openDrawer = () => {
                mobileDrawer.classList.remove('invisible');
                mobileDrawer.classList.add('flex');
                void mobileDrawer.offsetWidth;
                mobileDrawerBackdrop.classList.remove('opacity-0');
                mobileDrawerBackdrop.classList.add('opacity-100');
                mobileDrawerContent.classList.remove('translate-x-full');
                mobileDrawerContent.classList.add('translate-x-0');
            };

            const closeDrawer = () => {
                mobileDrawerBackdrop.classList.remove('opacity-100');
                mobileDrawerBackdrop.classList.add('opacity-0');
                mobileDrawerContent.classList.remove('translate-x-0');
                mobileDrawerContent.classList.add('translate-x-full');
                setTimeout(() => {
                    mobileDrawer.classList.add('invisible');
                    mobileDrawer.classList.remove('flex');
                }, 300);
            };

            if (mobileBottomMenuTrigger) mobileBottomMenuTrigger.addEventListener('click', openDrawer);
            if (mobileDrawerClose) mobileDrawerClose.addEventListener('click', closeDrawer);
            if (mobileDrawerBackdrop) mobileDrawerBackdrop.addEventListener('click', closeDrawer);
            mobileNavLinks.forEach(link => link.addEventListener('click', closeDrawer));
        });
    </script>
</body>

</html>

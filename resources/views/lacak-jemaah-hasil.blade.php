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
    <title>Hasil Status Keberangkatan - {{ $siteName }}</title>
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

<body class="public-interior izi-public overflow-x-hidden antialiased">

    @include('public.partials.navigation')
    <main id="main-content">

    @include('public.partials.interior-hero', [
        'eyebrow' => 'Hasil pencarian',
        'title' => 'Status persiapan keberangkatan',
        'description' => 'Periksa ringkasan persiapan perjalanan Anda di bawah ini.',
        'breadcrumb' => 'Hasil pencarian',
        'actionHref' => route('public.jemaah.tracking'),
        'actionLabel' => 'Cari nomor lain',
    ])

    <!-- BEGIN: Content Section -->
    <section class="interior-section">
        <div class="max-w-6xl mx-auto space-y-8">

            @if (! $jemaah)
                <!-- Not found -->
                <div class="interior-card interior-empty">
                    <i data-lucide="user-round-x" aria-hidden="true"></i>
                    <h2>Data Tidak Ditemukan</h2>
                    <p>Periksa kembali nomor paspor dan tanggal lahir sesuai data pendaftaran. Jika Anda baru mendaftar, tim kami dapat membantu memeriksanya.</p>
                    <div class="flex flex-wrap justify-center gap-3">
                        <a href="{{ route('public.jemaah.tracking') }}" class="interior-button">Coba lagi</a>
                        <a href="https://wa.me/{{ $wa_phone }}?text=Assalamu%27alaikum%2C%20saya%20ingin%20menanyakan%20status%20pendaftaran%20saya" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-12 items-center px-4 text-sm font-bold text-[#3166b1]">Hubungi tim kami</a>
                    </div>
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

    </main>
    @include('public.partials.footer')
</body>
</html>

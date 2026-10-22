@php
    $nowWita = now('Asia/Makassar');
    $periodDays = (int) \Carbon\Carbon::parse($from)->diffInDays(\Carbon\Carbon::parse($to)) + 1;
    $presets = [
        ['label' => '7 hari', 'from' => $nowWita->copy()->subDays(6)->toDateString(), 'to' => $nowWita->toDateString()],
        ['label' => '30 hari', 'from' => $nowWita->copy()->subDays(29)->toDateString(), 'to' => $nowWita->toDateString()],
        ['label' => 'Bulan ini', 'from' => $nowWita->copy()->startOfMonth()->toDateString(), 'to' => $nowWita->toDateString()],
        ['label' => '90 hari', 'from' => $nowWita->copy()->subDays(89)->toDateString(), 'to' => $nowWita->toDateString()],
    ];
    $stageColors = $stageOptions->pluck('color', 'key')->all();
    $picRows = $csList->map(fn ($cs) => ['label' => $cs->name, 'value' => (int) ($byCs[$cs->id] ?? 0), 'detail' => (int) ($wonByCs[$cs->id] ?? 0).' berhasil'])->sortByDesc('value')->values();
    $campaignRows = $campaigns->map(fn ($item) => ['label' => $item->name, 'value' => (int) ($byCampaign[$item->id] ?? 0)])->filter(fn ($row) => $row['value'] > 0)->sortByDesc('value')->values();
    $adRows = $ads->map(fn ($item) => ['label' => $item->name, 'value' => (int) ($byAd[$item->id] ?? 0)])->filter(fn ($row) => $row['value'] > 0)->sortByDesc('value')->values();
    $packageRows = $packages->map(fn ($item) => ['label' => $item->name, 'value' => (int) ($byPackage[$item->id] ?? 0)])->filter(fn ($row) => $row['value'] > 0)->sortByDesc('value')->values();
    $needRows = collect(\App\Models\Lead::NEED_TYPES)->map(fn ($label, $key) => ['label' => $label, 'value' => (int) ($byNeed[$key] ?? 0)])->filter(fn ($row) => $row['value'] > 0)->sortByDesc('value')->values();
    $cityRows = $byCity->map(fn ($count, $city) => ['label' => $city, 'value' => (int) $count])->sortByDesc('value')->values();
    $monthRows = $byMonth->map(fn ($count, $month) => ['label' => \Carbon\Carbon::parse($month.'-01')->locale('id')->translatedFormat('F Y'), 'value' => (int) $count])->sortByDesc('value')->values();
    $metrics = [
        ['label' => 'Kunjungan /chat', 'value' => $visits, 'icon' => 'eye', 'note' => 'Tanggal kunjungan'],
        ['label' => 'Ke WhatsApp', 'value' => $redirects, 'icon' => 'message-circle', 'note' => 'Tanggal pengalihan'],
        ['label' => 'Lead tercatat', 'value' => $leadCount, 'icon' => 'users-round', 'note' => 'Tanggal lead dibuat'],
        ['label' => 'Booking berhasil', 'value' => $wonCount, 'icon' => 'badge-check', 'note' => 'Tanggal konfirmasi'],
    ];
@endphp
<x-admin-layout title="Laporan CRM">
    <x-slot name="header"><div><h1 class="text-xl font-bold text-slate-900 dark:text-white">Laporan CRM</h1><p class="text-xs text-slate-500 dark:text-slate-400">Aktivitas dan hasil calon jemaah</p></div></x-slot>
    <div class="crm-page mx-auto max-w-[1600px] space-y-6 pb-6">
        <section class="relative overflow-hidden rounded-[1.5rem] bg-[#102b52] p-5 text-white shadow-[0_16px_40px_rgba(16,43,82,.12)] sm:p-7">
            <div class="pointer-events-none absolute -right-12 -top-24 size-72 rounded-full border-[36px] border-white/[.05]" aria-hidden="true"></div>
            <div class="relative flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
                <div class="max-w-2xl"><p class="text-[11px] font-extrabold uppercase tracking-[.18em] text-[#f2c454]">Ringkasan operasional</p><h2 class="mt-2 text-2xl font-bold leading-tight sm:text-3xl">Lihat alur kontak, lead, dan hasil.</h2><p class="mt-2 text-sm leading-relaxed text-blue-100">Setiap angka mengikuti tanggal kejadiannya dalam WITA. Pilih periode untuk melihat tren dan kondisi lead yang tercatat.</p></div>
                <div class="shrink-0 rounded-xl border border-white/15 bg-white/10 px-4 py-3"><p class="text-[11px] font-bold uppercase tracking-wider text-blue-200">Periode aktif</p><p class="mt-1 text-base font-extrabold tabular-nums">{{ \Carbon\Carbon::parse($from)->locale('id')->translatedFormat('d M Y') }} – {{ \Carbon\Carbon::parse($to)->locale('id')->translatedFormat('d M Y') }}</p><p class="mt-0.5 text-xs text-blue-200">{{ number_format($periodDays, 0, ',', '.') }} hari · WITA</p></div>
            </div>
        </section>
        <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-5" aria-labelledby="report-filter-title">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                <div><h2 id="report-filter-title" class="text-sm font-extrabold text-[#102b52] dark:text-white">Pilih periode laporan</h2><nav class="mt-3 flex flex-wrap gap-2" aria-label="Periode cepat">@foreach ($presets as $preset)<a href="{{ route('admin.crm.report', ['from' => $preset['from'], 'to' => $preset['to']]) }}" @class(['inline-flex min-h-10 items-center rounded-lg border px-3.5 text-xs font-bold transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#3166b1]', 'border-[#3166b1] bg-blue-50 text-[#245392] dark:bg-blue-900/30 dark:text-blue-200' => $from === $preset['from'] && $to === $preset['to'], 'border-slate-200 text-slate-600 hover:border-[#3166b1] hover:text-[#3166b1] dark:border-slate-600 dark:text-slate-300' => $from !== $preset['from'] || $to !== $preset['to']])>{{ $preset['label'] }}</a>@endforeach</nav></div>
                <form method="GET" action="{{ route('admin.crm.report') }}" class="grid grid-cols-2 items-end gap-2 sm:flex sm:flex-wrap sm:gap-3">
                    <div><label for="from" class="mb-1 block text-xs font-bold text-slate-600 dark:text-slate-300">Dari · WITA</label><input id="from" type="date" name="from" value="{{ $from }}" required class="min-h-11 w-full rounded-xl border-slate-300 bg-white text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-white"></div>
                    <div><label for="to" class="mb-1 block text-xs font-bold text-slate-600 dark:text-slate-300">Sampai · WITA</label><input id="to" type="date" name="to" value="{{ $to }}" required class="min-h-11 w-full rounded-xl border-slate-300 bg-white text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-white"></div>
                    <button type="submit" class="col-span-2 inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-[#3166b1] px-5 text-sm font-bold text-white transition hover:bg-[#245392] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#3166b1] sm:col-span-1"><i data-lucide="sliders-horizontal" class="size-4" aria-hidden="true"></i>Tampilkan</button>
                </form>
            </div>
        </section>
        <div class="grid grid-cols-2 gap-3 lg:grid-cols-4 lg:gap-4">@foreach ($metrics as $metric)
            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-5">
                <div class="flex items-start justify-between gap-2"><p class="text-xs font-bold leading-snug text-slate-600 dark:text-slate-300 sm:text-sm">{{ $metric['label'] }}</p><span class="hidden size-9 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-[#3166b1] dark:bg-blue-900/30 sm:inline-flex"><i data-lucide="{{ $metric['icon'] }}" class="size-4" aria-hidden="true"></i></span></div>
                <p class="mt-3 text-3xl font-extrabold tabular-nums tracking-tight text-[#102b52] dark:text-white sm:text-4xl">{{ number_format($metric['value'], 0, ',', '.') }}</p><p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">{{ $metric['note'] }}</p>
            </section>@endforeach
        </div>
        <div class="grid gap-4 xl:grid-cols-2">
            @include('crm.partials.report-trend', ['title' => 'Kunjungan & WhatsApp', 'subtitle' => 'Aktivitas pada /chat, menurut waktu masing-masing', 'firstKey' => 'visits', 'firstLabel' => 'Kunjungan', 'firstColor' => '#3166b1', 'secondKey' => 'redirects', 'secondLabel' => 'Ke WhatsApp', 'secondColor' => '#f2c454', 'chartId' => 'traffic'])
            @include('crm.partials.report-trend', ['title' => 'Lead & booking', 'subtitle' => 'Lead baru dan booking yang dikonfirmasi dalam periode', 'firstKey' => 'leads', 'firstLabel' => 'Lead tercatat', 'firstColor' => '#8b6cb1', 'secondKey' => 'wins', 'secondLabel' => 'Booking berhasil', 'secondColor' => '#27856d', 'chartId' => 'crm'])
        </div>
        <div class="grid gap-4 xl:grid-cols-[1.15fr_.85fr]">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-6">
                <div class="mb-5"><p class="text-[11px] font-extrabold uppercase tracking-[.14em] text-[#3166b1] dark:text-blue-300">Posisi terkini</p><h2 class="mt-1 text-lg font-bold text-[#102b52] dark:text-white">Tahap lead</h2><p class="text-xs text-slate-500 dark:text-slate-400">Lead yang dibuat dalam periode terpilih</p></div>
                <div class="space-y-3.5">@foreach ($stageLabels as $stage => $label)
                    @php($value = (int) ($byStage[$stage] ?? 0))
                    <div class="grid grid-cols-[92px_1fr_35px] items-center gap-3 text-xs sm:grid-cols-[108px_1fr_42px]"><span class="font-semibold text-slate-700 dark:text-slate-200">{{ $label }}</span><div class="h-2.5 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-700"><div class="h-full rounded-full" style="width: {{ $leadCount ? min(100, $value / $leadCount * 100) : 0 }}%; background-color: {{ $stageColors[$stage] }}"></div></div><span class="text-right font-extrabold tabular-nums text-slate-700 dark:text-slate-200">{{ $value }}</span></div>
                @endforeach</div>@if (!$leadCount)<p class="mt-4 text-xs text-slate-500 dark:text-slate-400">Belum ada lead yang dibuat dalam periode ini.</p>@endif
            </section>
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-6"><div class="mb-5"><p class="text-[11px] font-extrabold uppercase tracking-[.14em] text-[#3166b1] dark:text-blue-300">Penanggung jawab</p><h2 class="mt-1 text-lg font-bold text-[#102b52] dark:text-white">Lead per PIC</h2><p class="text-xs text-slate-500 dark:text-slate-400">PIC saat ini; berhasil adalah status saat ini</p></div>@include('crm.partials.report-bars', ['rows' => $picRows, 'color' => '#3166b1', 'empty' => 'Belum ada PIC yang tercatat.', 'limit' => 8])</section>
        </div>
        <div class="grid gap-4 lg:grid-cols-3">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-6"><p class="text-[11px] font-extrabold uppercase tracking-[.14em] text-[#3166b1] dark:text-blue-300">Asal informasi</p><h2 class="mt-1 text-lg font-bold text-[#102b52] dark:text-white">Sumber lead</h2><p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Pencatatan sumber untuk lead dalam periode</p><div class="mt-5 flex h-3 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-700" aria-hidden="true"><span class="bg-[#3166b1]" style="width: {{ $leadCount ? $manualCount / $leadCount * 100 : 0 }}%"></span><span class="bg-slate-400" style="width: {{ $leadCount ? $unknownCount / $leadCount * 100 : 0 }}%"></span></div><dl class="mt-4 space-y-3 text-sm"><div class="flex items-center justify-between gap-3"><dt class="flex items-center gap-2 text-slate-600 dark:text-slate-300"><span class="size-2.5 rounded-full bg-[#3166b1]"></span>Dipilih manual</dt><dd class="font-extrabold tabular-nums text-[#102b52] dark:text-white">{{ number_format($manualCount, 0, ',', '.') }}</dd></div><div class="flex items-center justify-between gap-3"><dt class="flex items-center gap-2 text-slate-600 dark:text-slate-300"><span class="size-2.5 rounded-full bg-slate-400"></span>Tidak diketahui</dt><dd class="font-extrabold tabular-nums text-[#102b52] dark:text-white">{{ number_format($unknownCount, 0, ',', '.') }}</dd></div></dl><p class="mt-5 rounded-xl bg-amber-50 px-3 py-2.5 text-xs leading-relaxed text-amber-900 dark:bg-amber-900/20 dark:text-amber-200">Campaign dan Ads dipilih manual oleh tim. Angka ini bukan atribusi iklan terverifikasi.</p></section>
            @include('crm.partials.report-section', ['eyebrow' => 'Dipilih manual', 'title' => 'Campaign', 'description' => 'Campaign yang dicatat pada lead', 'rows' => $campaignRows, 'color' => '#3166b1', 'empty' => 'Belum ada Campaign yang dicatat.'])
            @include('crm.partials.report-section', ['eyebrow' => 'Dipilih manual', 'title' => 'Ads', 'description' => 'Ads yang dicatat pada lead', 'rows' => $adRows, 'color' => '#5a7fc4', 'empty' => 'Belum ada Ads yang dicatat.'])
        </div>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @include('crm.partials.report-section', ['eyebrow' => 'Minat perjalanan', 'title' => 'Paket diminati', 'description' => 'Paket pada lead yang dibuat di periode', 'rows' => $packageRows, 'color' => '#8b6cb1', 'empty' => 'Belum ada paket yang dipilih.'])
            @include('crm.partials.report-section', ['eyebrow' => 'Pertanyaan calon jemaah', 'title' => 'Kebutuhan', 'description' => 'Kebutuhan yang dipilih saat pencatatan', 'rows' => $needRows, 'color' => '#bf8846', 'empty' => 'Belum ada kebutuhan yang dicatat.'])
            @include('crm.partials.report-section', ['eyebrow' => 'Asal calon jemaah', 'title' => 'Kota', 'description' => 'Kota yang dicatat pada kontak', 'rows' => $cityRows, 'color' => '#27856d', 'empty' => 'Belum ada kota yang dicatat.'])
            @include('crm.partials.report-section', ['eyebrow' => 'Rencana perjalanan', 'title' => 'Bulan umroh', 'description' => 'Bulan keberangkatan yang direncanakan', 'rows' => $monthRows, 'color' => '#c49c34', 'empty' => 'Belum ada bulan umroh yang dicatat.'])
        </div>
        <aside class="rounded-2xl border border-blue-200 bg-blue-50/70 p-5 text-xs leading-relaxed text-slate-700 dark:border-blue-900/60 dark:bg-blue-950/30 dark:text-slate-300 sm:p-6" aria-labelledby="report-method-title"><h2 id="report-method-title" class="text-sm font-extrabold text-[#102b52] dark:text-white">Cara membaca laporan</h2><p class="mt-2">Kunjungan dan pengalihan WhatsApp berasal dari aktivitas <strong>/chat</strong>. Lead dibuat setelah CS atau admin benar-benar menerima kontak. Booking dihitung pada tanggal konfirmasinya. Keempat angka merupakan kejadian yang berbeda dan tidak saling dianggap sebagai konversi otomatis.</p><p class="mt-2">Grafik mengikuti waktu kejadian dalam <strong>WITA (Asia/Makassar)</strong>. Rincian tahap, PIC, sumber, paket, kebutuhan, kota, dan bulan umroh menunjukkan kondisi saat ini dari <strong>lead yang dibuat dalam periode terpilih</strong>. Booking pada grafik bisa berasal dari lead yang dibuat sebelum periode tersebut.</p></aside>
    </div>
</x-admin-layout>

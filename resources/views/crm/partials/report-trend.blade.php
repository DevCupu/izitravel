@php
    $peak = max(1, collect($trend)->max(fn ($bucket) => max($bucket[$firstKey], $bucket[$secondKey])));
    $hasActivity = collect($trend)->contains(fn ($bucket) => $bucket[$firstKey] > 0 || $bucket[$secondKey] > 0);
    $chartWidth = max(480, count($trend) * 35);
    $labelEvery = max(1, (int) ceil(count($trend) / 8));
    $granularityLabel = ['day' => 'harian', 'week' => 'per 7 hari', 'month' => 'bulanan'][$granularity];
@endphp
<section class="min-w-0 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-6" aria-labelledby="chart-{{ $chartId }}-title">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div><p class="text-[11px] font-extrabold uppercase tracking-[.14em] text-[#3166b1] dark:text-blue-300">Tren {{ $granularityLabel }}</p><h2 id="chart-{{ $chartId }}-title" class="mt-1 text-lg font-bold text-[#102b52] dark:text-white">{{ $title }}</h2><p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $subtitle }}</p></div>
        <div class="flex flex-wrap gap-x-4 gap-y-1 text-[11px] font-semibold text-slate-600 dark:text-slate-300"><span class="inline-flex items-center gap-1.5"><span class="size-2.5 rounded-sm" style="background-color: {{ $firstColor }}"></span>{{ $firstLabel }}</span><span class="inline-flex items-center gap-1.5"><span class="size-2.5 rounded-sm" style="background-color: {{ $secondColor }}"></span>{{ $secondLabel }}</span></div>
    </div>
    @if ($hasActivity)
        <div class="mt-6 overflow-x-auto pb-1 focus-visible:rounded-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#3166b1]" role="img" tabindex="0" aria-label="Grafik batang {{ $title }}. Geser untuk melihat periode lain. Data lengkap tersedia pada tabel di bawah grafik.">
            <div class="relative" style="width: {{ $chartWidth }}px; min-width: 100%;">
                <div class="pointer-events-none absolute inset-x-0 top-0 bottom-7 flex flex-col justify-between" aria-hidden="true">@foreach (range(0, 4) as $line)<span class="border-t border-dashed border-slate-200 dark:border-slate-700"></span>@endforeach</div>
                <div class="relative grid h-52 items-end gap-0" style="grid-template-columns: repeat({{ count($trend) }}, minmax(0, 1fr));">
                    @foreach ($trend as $bucket)
                        <div class="flex h-full items-end justify-center gap-[2px] px-[2px]" title="{{ $bucket['start'] }}{{ $bucket['end'] !== $bucket['start'] ? ' – '.$bucket['end'] : '' }}: {{ $firstLabel }} {{ $bucket[$firstKey] }}, {{ $secondLabel }} {{ $bucket[$secondKey] }}">
                            <span class="w-2.5 min-w-[3px] rounded-t-sm sm:w-3" style="height: {{ $bucket[$firstKey] ? max(2, $bucket[$firstKey] / $peak * 100) : 0 }}%; background-color: {{ $firstColor }}"></span>
                            <span class="w-2.5 min-w-[3px] rounded-t-sm sm:w-3" style="height: {{ $bucket[$secondKey] ? max(2, $bucket[$secondKey] / $peak * 100) : 0 }}%; background-color: {{ $secondColor }}"></span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-2 grid h-5 gap-0 border-t border-slate-200 pt-1 dark:border-slate-700" style="grid-template-columns: repeat({{ count($trend) }}, minmax(0, 1fr));">
                    @foreach ($trend as $index => $bucket)<span class="overflow-visible whitespace-nowrap text-center text-[10px] tabular-nums text-slate-500 dark:text-slate-400">{{ $index % $labelEvery === 0 || $index === count($trend) - 1 ? $bucket['label'] : '' }}</span>@endforeach
                </div>
            </div>
        </div>
        <p class="mt-2 text-[11px] text-slate-500 dark:text-slate-400">Skala tertinggi: {{ number_format($peak, 0, ',', '.') }} kejadian per {{ $granularity === 'month' ? 'bulan' : ($granularity === 'week' ? '7 hari' : 'hari') }}. Geser grafik untuk melihat periode lain.</p>
    @else
        <div class="mt-6 flex min-h-52 flex-col items-center justify-center rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 text-center dark:border-slate-700 dark:bg-slate-900/40"><i data-lucide="chart-no-axes-combined" class="size-7 text-slate-400" aria-hidden="true"></i><p class="mt-3 text-sm font-bold text-slate-700 dark:text-slate-200">Belum ada aktivitas</p><p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Coba pilih periode yang lebih panjang.</p></div>
    @endif
    <details class="mt-5 border-t border-slate-100 pt-3 dark:border-slate-700"><summary class="cursor-pointer rounded-md text-xs font-bold text-[#3166b1] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#3166b1] dark:text-blue-300">Lihat angka per periode</summary><div class="mt-3 max-h-72 overflow-auto rounded-lg border border-slate-200 dark:border-slate-700"><table class="w-full min-w-[360px] text-left text-xs"><thead class="sticky top-0 bg-slate-50 text-slate-600 dark:bg-slate-900 dark:text-slate-300"><tr><th scope="col" class="px-3 py-2">Periode WITA</th><th scope="col" class="px-3 py-2 text-right">{{ $firstLabel }}</th><th scope="col" class="px-3 py-2 text-right">{{ $secondLabel }}</th></tr></thead><tbody class="divide-y divide-slate-100 dark:divide-slate-700">@foreach ($trend as $bucket)<tr><th scope="row" class="px-3 py-2 font-medium text-slate-700 dark:text-slate-200">{{ $bucket['start'] }}{{ $bucket['end'] !== $bucket['start'] ? ' – '.$bucket['end'] : '' }}</th><td class="px-3 py-2 text-right tabular-nums">{{ $bucket[$firstKey] }}</td><td class="px-3 py-2 text-right tabular-nums">{{ $bucket[$secondKey] }}</td></tr>@endforeach</tbody></table></div></details>
</section>

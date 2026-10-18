@php
    $visibleRows = $rows->take($limit ?? 6);
    $maxValue = max(1, (int) ($rows->max('value') ?? 0));
@endphp
@if ($rows->sum('value') > 0)
    <div class="space-y-4">
        @foreach ($visibleRows as $row)
            <div><div class="mb-1.5 flex items-start justify-between gap-3 text-xs"><span class="min-w-0 break-words font-semibold text-slate-700 dark:text-slate-200">{{ $row['label'] }}</span><span class="shrink-0 text-right font-extrabold tabular-nums text-[#102b52] dark:text-white">{{ number_format($row['value'], 0, ',', '.') }}@isset($row['detail'])<span class="ml-1 font-medium text-slate-500 dark:text-slate-400">· {{ $row['detail'] }}</span>@endisset</span></div><div class="h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-700"><div class="h-full rounded-full" style="width: {{ $row['value'] / $maxValue * 100 }}%; background-color: {{ $color }}"></div></div></div>
        @endforeach
    </div>
    @if ($rows->count() > $visibleRows->count())<p class="mt-4 text-xs text-slate-500 dark:text-slate-400">Menampilkan {{ $visibleRows->count() }} dari {{ $rows->count() }} kategori dengan jumlah tertinggi.</p>@endif
@else
    <p class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-6 text-center text-xs text-slate-500 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-400">{{ $empty }}</p>
@endif

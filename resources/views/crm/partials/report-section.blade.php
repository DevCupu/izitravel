<section class="min-w-0 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-6">
    <div class="mb-5"><p class="text-[11px] font-extrabold uppercase tracking-[.14em] text-[#3166b1] dark:text-blue-300">{{ $eyebrow }}</p><h2 class="mt-1 text-lg font-bold text-[#102b52] dark:text-white">{{ $title }}</h2><p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $description }}</p></div>
    @include('crm.partials.report-bars', ['rows' => $rows, 'color' => $color, 'empty' => $empty])
</section>

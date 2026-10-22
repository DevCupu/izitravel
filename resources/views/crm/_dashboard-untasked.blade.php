<div class="flex flex-col gap-2 border-t border-slate-100 py-3 first:border-t-0 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
    <div class="min-w-0">
        <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400">Dicatat {{ $lead->created_at->timezone('Asia/Makassar')->locale('id')->translatedFormat('d M Y') }} · {{ $stageLabels[$lead->stage] ?? $lead->stage }}</p>
        <a href="{{ route($routePrefix.'leads.show', $lead) }}" class="mt-1 block truncate text-sm font-bold text-[#102b52] hover:text-[#3166b1] dark:text-white">{{ $lead->display_name }}</a>
        @if ($isAdmin)<p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">PIC {{ $lead->cs?->name ?? 'Belum ditentukan' }}</p>@endif
    </div>
    <a href="{{ route($routePrefix.'leads.show', $lead) }}#tindak-lanjut" class="inline-flex min-h-10 shrink-0 items-center gap-1.5 self-start rounded-lg border border-blue-200 px-3 text-xs font-bold text-[#245392] hover:bg-blue-50 dark:border-blue-800 dark:text-blue-200 dark:hover:bg-blue-950/40 sm:self-auto"><i data-lucide="calendar-plus" class="size-3.5" aria-hidden="true"></i>Jadwalkan</a>
</div>

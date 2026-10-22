<div class="border-t border-slate-100 py-3 first:border-t-0 dark:border-slate-700">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <time datetime="{{ $task->due_at->toIso8601String() }}" class="text-[11px] font-extrabold uppercase tracking-wide {{ $isOverdue ? 'text-red-700 dark:text-red-400' : 'text-amber-700 dark:text-amber-300' }}">{{ $task->due_at->timezone('Asia/Makassar')->locale('id')->translatedFormat('d M, H:i') }} WITA</time>
            <a href="{{ route($routePrefix.'leads.show', $task->lead_id) }}" class="mt-1 block truncate text-sm font-bold text-[#102b52] hover:text-[#3166b1] dark:text-white">{{ $task->lead?->display_name ?? 'Lead tidak tersedia' }}</a>
            <p class="mt-0.5 line-clamp-2 text-xs leading-relaxed text-slate-600 dark:text-slate-300">{{ $task->description }}@if ($isAdmin && $task->assignee) · {{ $task->assignee->name }}@endif</p>
        </div>
        <form method="POST" action="{{ route($routePrefix.'followups.complete', $task) }}" class="shrink-0 self-start sm:self-auto">
            @csrf @method('PATCH')
            <button type="submit" aria-label="Tandai selesai tugas {{ $task->description }} untuk {{ $task->lead?->display_name }}" class="inline-flex min-h-10 items-center justify-center gap-1.5 rounded-lg border border-slate-300 px-3 text-xs font-bold text-[#102b52] hover:border-[#3166b1] hover:bg-blue-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-[#3166b1] dark:border-slate-600 dark:text-white dark:hover:bg-slate-700"><i data-lucide="check" class="size-3.5" aria-hidden="true"></i>Tandai selesai</button>
        </form>
    </div>
    @include('crm._complete-next-form', ['context' => 'dashboard'])
</div>

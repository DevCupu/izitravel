<div class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-start">
    @if ($bulkSelect ?? false)
        <label class="shrink-0"><input type="checkbox" value="{{ $task->id }}" x-model="selected" aria-label="Pilih tugas {{ $task->description }}" class="size-5 rounded border-slate-300 text-[#3166b1] focus:ring-[#3166b1]"></label>
    @endif
    <div class="min-w-0 flex-1">
        <div class="flex flex-wrap items-center gap-2"><span class="text-[11px] font-extrabold uppercase tracking-wide {{ $task->due_at->lt(now()) ? 'text-red-700' : 'text-[#3166b1]' }}">{{ $task->due_at->timezone('Asia/Makassar')->translatedFormat('d M Y, H:i') }} WITA</span>@if ($isAdmin)<span class="text-xs text-slate-500">· {{ $task->assignee?->name }}</span>@endif</div>
        <a href="{{ route($routePrefix.'leads.show', $task->lead_id) }}" class="mt-1 block font-bold text-slate-900 hover:text-[#3166b1]">{{ $task->lead?->display_name }}</a>
        <p class="mt-1 text-sm text-slate-600">{{ $task->description }}</p>
        @if ($taskControls ?? false)
            <details class="mt-3"><summary class="cursor-pointer text-xs font-bold text-[#3166b1]">Ubah tugas</summary>
                <form method="POST" action="{{ route($routePrefix.'followups.update', $task) }}" class="mt-3 grid gap-3 sm:grid-cols-[180px_minmax(0,1fr)_auto]">@csrf @method('PATCH')
                    <div><label for="task-due-{{ $task->id }}" class="ui-label">Jadwal WITA</label><input id="task-due-{{ $task->id }}" type="datetime-local" name="due_at" value="{{ $task->due_at->timezone('Asia/Makassar')->format('Y-m-d\TH:i') }}" required class="ui-input"></div>
                    <div><label for="task-description-{{ $task->id }}" class="ui-label">Yang perlu dilakukan</label><input id="task-description-{{ $task->id }}" name="description" value="{{ $task->description }}" required maxlength="255" class="ui-input"></div>
                    <button class="ui-button-primary self-end">Simpan</button>
                </form>
            </details>
        @endif
    </div>
    <div class="flex flex-wrap gap-2 sm:justify-end">
        <form method="POST" action="{{ route($routePrefix.'followups.complete', $task) }}">@csrf @method('PATCH')<button class="rounded-xl border border-[#3166b1] px-4 py-2.5 text-sm font-bold text-[#3166b1] hover:bg-blue-50">Tandai selesai</button></form>
        @if ($taskControls ?? false)
            <form method="POST" action="{{ route($routePrefix.'followups.archive', $task) }}" onsubmit="return confirm('Arsipkan tugas ini?')">@csrf @method('DELETE')<button class="rounded-xl border border-red-200 px-4 py-2.5 text-sm font-bold text-red-700 hover:bg-red-50">Arsipkan</button></form>
        @endif
    </div>
</div>

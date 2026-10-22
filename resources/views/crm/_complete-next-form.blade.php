@php($formId = ($context ?? 'task').'-next-'.$task->id)
<details class="group mt-2 rounded-xl border border-blue-100 bg-blue-50/50 p-3 dark:border-blue-900/50 dark:bg-blue-950/20" @if (old('next_task_id') == $task->id) open @endif>
    <summary class="flex min-h-10 cursor-pointer items-center text-xs font-bold text-[#245392] marker:content-none dark:text-blue-200">Selesaikan dan jadwalkan berikutnya</summary>
    <form method="POST" action="{{ route($routePrefix.'followups.complete', $task) }}" class="mt-3 space-y-3">
        @csrf @method('PATCH')
        <input type="hidden" name="next_task_id" value="{{ $task->id }}">
        <div>
            <label for="{{ $formId }}-due" class="ui-label text-xs">Jadwal · WITA</label>
            <input id="{{ $formId }}-due" type="datetime-local" name="next_due_at" value="{{ old('next_task_id') == $task->id ? old('next_due_at') : '' }}" required class="ui-input">
            @if (old('next_task_id') == $task->id) @error('next_due_at')<p class="ui-error" role="alert">{{ $message }}</p>@enderror @endif
        </div>
        <div>
            <label for="{{ $formId }}-description" class="ui-label text-xs">Yang perlu dilakukan</label>
            <input id="{{ $formId }}-description" name="next_description" value="{{ old('next_task_id') == $task->id ? old('next_description') : '' }}" maxlength="255" required placeholder="Contoh: Konfirmasi pilihan paket" class="ui-input">
            @if (old('next_task_id') == $task->id) @error('next_description')<p class="ui-error" role="alert">{{ $message }}</p>@enderror @endif
        </div>
        <button type="submit" class="ui-button-primary w-full">Selesaikan dan jadwalkan</button>
    </form>
</details>

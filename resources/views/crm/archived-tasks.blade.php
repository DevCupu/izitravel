<x-admin-layout title="Arsip tugas">
    <x-slot name="header"><div><a href="{{ route('admin.crm.tasks') }}" class="text-xs font-bold text-[#3166b1]">← Tugas terbuka</a><h1 class="mt-1 text-xl font-bold text-slate-900 dark:text-white">Arsip tugas</h1><p class="text-xs text-slate-500">Tugas pada lead yang sedang diarsipkan muncul kembali setelah lead dipulihkan.</p></div></x-slot>
    <div class="space-y-5" x-data="{ selected: [], allIds: {{ \Illuminate\Support\Js::from($tasks->pluck('id')->map(fn ($id) => (string) $id)->all()) }} }">
        <p class="text-sm font-semibold text-slate-700">{{ number_format($tasks->total(), 0, ',', '.') }} tugas diarsipkan pada lead aktif</p>
        @if ($tasks->count())
            <div class="flex flex-wrap items-center gap-3 rounded-xl border border-blue-100 bg-blue-50 p-3">
                <button type="button" @click="selected = selected.length === allIds.length ? [] : [...allIds]" class="text-sm font-bold text-[#245392]" x-text="selected.length === allIds.length ? 'Batalkan pilihan' : 'Pilih semua di halaman ini'"></button>
                <span class="text-xs text-slate-600" x-text="selected.length + ' dipilih'"></span>
                <form method="POST" action="{{ route('admin.crm.tasks.bulk-restore') }}" class="ml-auto">@csrf
                    <template x-for="id in selected" :key="id"><input type="hidden" name="ids[]" :value="id"></template>
                    <button :disabled="!selected.length" class="ui-button-primary disabled:cursor-not-allowed disabled:opacity-50">Pulihkan terpilih</button>
                </form>
            </div>
        @endif
        <div data-record-views data-default-view="card">
        <div data-record-cards class="space-y-3">
            @forelse ($tasks as $task)
                <div class="flex flex-wrap items-start gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                    <input type="checkbox" value="{{ $task->id }}" x-model="selected" aria-label="Pilih tugas {{ $task->description }}" class="mt-1 size-5 shrink-0 rounded border-slate-300 text-[#3166b1] focus:ring-[#3166b1]">
                    <div class="min-w-0 flex-1"><a href="{{ route('admin.crm.leads.show', $task->lead_id) }}" class="font-bold text-slate-900 hover:text-[#3166b1]">{{ $task->lead?->display_name }}</a><p class="mt-1 text-sm text-slate-600">{{ $task->description }}</p><p class="mt-1 text-xs text-slate-500">PIC {{ $task->assignee?->name ?? '—' }} · Jadwal {{ $task->due_at->timezone('Asia/Makassar')->translatedFormat('d M Y, H:i') }} WITA</p></div>
                    <form method="POST" action="{{ route('admin.crm.followups.restore', $task) }}">@csrf @method('PATCH')<button class="text-sm font-bold text-[#3166b1]">Pulihkan tugas</button></form>
                </div>
            @empty
                <x-ui.empty-state title="Arsip tugas kosong" description="Tugas yang diarsipkan akan muncul di sini." icon="archive" />
            @endforelse
        </div>
        <div data-record-table hidden class="overflow-x-auto rounded-xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead class="border-b border-slate-200 bg-slate-50 text-xs text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300"><tr><th scope="col" class="px-4 py-3">Pilih</th><th scope="col" class="px-4 py-3">Lead</th><th scope="col" class="px-4 py-3">Tugas</th><th scope="col" class="px-4 py-3">PIC</th><th scope="col" class="px-4 py-3">Jadwal WITA</th><th scope="col" class="px-4 py-3">Aksi</th></tr></thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($tasks as $task)
                        <tr class="text-slate-700 dark:text-slate-200"><td class="px-4 py-3"><input type="checkbox" value="{{ $task->id }}" x-model="selected" aria-label="Pilih tugas {{ $task->description }}" class="size-5 rounded border-slate-300 text-[#3166b1]"></td><th scope="row" class="px-4 py-3 font-bold"><a href="{{ route('admin.crm.leads.show', $task->lead_id) }}" class="text-[#102b52] hover:underline dark:text-white">{{ $task->lead?->display_name }}</a></th><td class="px-4 py-3">{{ $task->description }}</td><td class="px-4 py-3">{{ $task->assignee?->name ?? '—' }}</td><td class="whitespace-nowrap px-4 py-3">{{ $task->due_at->timezone('Asia/Makassar')->translatedFormat('d M Y, H:i') }}</td><td class="px-4 py-3"><form method="POST" action="{{ route('admin.crm.followups.restore', $task) }}">@csrf @method('PATCH')<button class="font-bold text-[#3166b1]">Pulihkan tugas</button></form></td></tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">Arsip tugas kosong.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>
        {{ $tasks->links() }}
    </div>
</x-admin-layout>

<x-dynamic-component :component="$isAdmin ? 'admin-layout' : 'cs-layout'" title="Tugas follow-up">
    <x-slot name="header"><div><h1 class="text-xl font-bold text-slate-900 dark:text-white">Tugas follow-up</h1><p class="text-xs text-slate-500 dark:text-slate-400">Urut dari jadwal paling awal · WITA</p></div></x-slot>
    <div class="crm-page space-y-5" x-data="{ selected: [], allIds: {{ \Illuminate\Support\Js::from($tasks->pluck('id')->map(fn ($id) => (string) $id)->all()) }} }">
        @include('crm._page-intro', ['eyebrow' => 'Agenda tindak lanjut', 'title' => $isAdmin ? 'Jaga ritme follow-up tim' : 'Kerjakan yang paling mendesak', 'description' => 'Tugas diurutkan menurut waktu WITA. Selesaikan kontak dan jadwalkan langkah berikutnya dalam satu tindakan.', 'href' => route($routePrefix.'tasks', ['period' => 'overdue']), 'linkLabel' => 'Lihat terlambat', 'icon' => 'alarm-clock'])
        <div class="flex flex-wrap items-end justify-between gap-3"><div><p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ number_format($tasks->total(), 0, ',', '.') }} tugas terbuka ditemukan</p><p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Selesaikan tugas setelah menghubungi calon jemaah.</p></div><div class="flex flex-wrap gap-2">@if ($isAdmin)<a href="{{ route('admin.crm.tasks.archived') }}" class="ui-button-secondary">Arsip tugas</a>@endif<a href="{{ route($routePrefix.'leads.index') }}" class="ui-button-primary"><i data-lucide="users-round" class="size-4" aria-hidden="true"></i>Buka leads</a></div></div>
        <div class="ui-card ui-card-pad">
            <nav class="flex flex-wrap gap-2" aria-label="Jadwal tugas">
                @foreach (['' => 'Semua', 'overdue' => 'Terlambat', 'today' => 'Hari ini', 'upcoming' => 'Mendatang'] as $key => $label)
                    <a href="{{ route($routePrefix.'tasks', array_filter(['period' => $key, 'search' => $filters['search'] ?? null, 'cs_id' => $isAdmin ? ($filters['cs_id'] ?? null) : null], fn ($value) => $value !== null && $value !== '')) }}" @if (($filters['period'] ?? '') === $key) aria-current="page" @endif class="inline-flex min-h-10 items-center rounded-full border px-3.5 text-xs font-bold {{ ($filters['period'] ?? '') === $key ? 'border-[#3166b1] bg-blue-50 text-[#245392] dark:bg-blue-900/30 dark:text-blue-200' : 'border-slate-200 text-slate-600 hover:border-[#3166b1] dark:border-slate-600 dark:text-slate-300' }}">{{ $label }}</a>
                @endforeach
            </nav>
            <form method="GET" action="{{ route($routePrefix.'tasks') }}" class="mt-4 grid gap-3 border-t border-slate-100 pt-4 dark:border-slate-700 sm:grid-cols-[minmax(0,1fr)_180px_auto_auto]">
                @if (!empty($filters['period']))<input type="hidden" name="period" value="{{ $filters['period'] }}">@endif
                <div><label for="task-search" class="ui-label">Cari tugas atau calon jemaah</label><input id="task-search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nama, nomor, atau isi tugas" class="ui-input"></div>
                @if ($isAdmin)<div><label for="task-cs" class="ui-label">PIC</label><select id="task-cs" name="cs_id" class="ui-input"><option value="">Semua PIC</option>@foreach ($csList as $cs)<option value="{{ $cs->id }}" @selected(($filters['cs_id'] ?? '') == $cs->id)>{{ $cs->name }}</option>@endforeach</select></div>@endif
                <button type="submit" class="ui-button-primary self-end">Terapkan</button>
                <a href="{{ route($routePrefix.'tasks') }}" class="ui-button-secondary self-end">Reset</a>
            </form>
        </div>
        @if ($tasks->count())
            <div class="flex flex-wrap items-center gap-3 rounded-xl border border-blue-100 bg-blue-50 p-3">
                <button type="button" @click="selected = selected.length === allIds.length ? [] : [...allIds]" class="text-sm font-bold text-[#245392]" x-text="selected.length === allIds.length ? 'Batalkan pilihan' : 'Pilih semua di halaman ini'"></button>
                <span class="text-xs text-slate-600" x-text="selected.length + ' dipilih'"></span>
                <form method="POST" action="{{ route($routePrefix.'tasks.bulk-complete') }}" class="ml-auto flex flex-wrap gap-2">@csrf
                    <template x-for="id in selected" :key="id"><input type="hidden" name="ids[]" :value="id"></template>
                    <button type="submit" :disabled="!selected.length" @click="if (!confirm('Selesaikan ' + selected.length + ' tugas terpilih?')) $event.preventDefault()" class="rounded-xl bg-[#3166b1] px-4 py-2.5 text-xs font-bold text-white disabled:cursor-not-allowed disabled:opacity-50">Selesaikan terpilih</button>
                    <button type="submit" formaction="{{ route($routePrefix.'tasks.bulk-archive') }}" :disabled="!selected.length" @click="if (!confirm('Arsipkan ' + selected.length + ' tugas terpilih?')) $event.preventDefault()" class="rounded-xl bg-red-700 px-4 py-2.5 text-xs font-bold text-white disabled:cursor-not-allowed disabled:opacity-50">Arsipkan terpilih</button>
                </form>
            </div>
        @endif
        <div data-record-views data-default-view="card">
            <div data-record-cards class="space-y-3">@forelse ($tasks as $task) @include('crm._task-row', ['bulkSelect' => true, 'taskControls' => true]) @empty <x-ui.empty-state title="Tidak ada tugas yang cocok" description="Ubah pencarian atau jadwalkan tindak lanjut dari detail lead." icon="calendar-check"><a href="{{ route($routePrefix.'leads.index') }}" class="ui-button-primary">Lihat leads</a></x-ui.empty-state> @endforelse</div>
            <div data-record-table hidden class="overflow-x-auto rounded-xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900">
                <table class="w-full min-w-[850px] text-left text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 text-xs text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300"><tr><th scope="col" class="px-4 py-3">Pilih</th><th scope="col" class="px-4 py-3">Jadwal WITA</th><th scope="col" class="px-4 py-3">Lead</th><th scope="col" class="px-4 py-3">Tugas</th>@if ($isAdmin)<th scope="col" class="px-4 py-3">PIC</th>@endif<th scope="col" class="px-4 py-3">Aksi</th></tr></thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($tasks as $task)
                            <tr class="align-top text-slate-700 dark:text-slate-200">
                                <td class="px-4 py-3"><input type="checkbox" value="{{ $task->id }}" x-model="selected" aria-label="Pilih tugas {{ $task->description }}" class="size-5 rounded border-slate-300 text-[#3166b1]"></td>
                                <td class="whitespace-nowrap px-4 py-3 {{ $task->due_at->lt(now()) ? 'font-bold text-red-700' : '' }}">{{ $task->due_at->timezone('Asia/Makassar')->translatedFormat('d M Y, H:i') }}</td>
                                <th scope="row" class="px-4 py-3 font-bold"><a href="{{ route($routePrefix.'leads.show', $task->lead_id) }}" class="text-[#102b52] hover:underline dark:text-white">{{ $task->lead?->display_name }}</a></th>
                                <td class="max-w-64 px-4 py-3">{{ $task->description }}</td>
                                @if ($isAdmin)<td class="px-4 py-3">{{ $task->assignee?->name ?? '—' }}</td>@endif
                                <td class="space-y-2 px-4 py-3">
                                    <details><summary class="cursor-pointer font-bold text-[#3166b1]">Ubah tugas</summary><form method="POST" action="{{ route($routePrefix.'followups.update', $task) }}" class="mt-2 min-w-56 space-y-2">@csrf @method('PATCH')<label class="block text-xs font-bold" for="table-task-due-{{ $task->id }}">Jadwal WITA</label><input id="table-task-due-{{ $task->id }}" type="datetime-local" name="due_at" value="{{ $task->due_at->timezone('Asia/Makassar')->format('Y-m-d\TH:i') }}" required class="ui-input"><label class="block text-xs font-bold" for="table-task-description-{{ $task->id }}">Yang perlu dilakukan</label><input id="table-task-description-{{ $task->id }}" name="description" value="{{ $task->description }}" required maxlength="255" class="ui-input"><button class="ui-button-primary">Simpan</button></form></details>
                                    @include('crm._complete-next-form', ['context' => 'table'])
                                    <form method="POST" action="{{ route($routePrefix.'followups.complete', $task) }}">@csrf @method('PATCH')<button class="font-bold text-[#3166b1]">Tandai selesai</button></form>
                                    <form method="POST" action="{{ route($routePrefix.'followups.archive', $task) }}" onsubmit="return confirm('Arsipkan tugas ini?')">@csrf @method('DELETE')<button class="font-bold text-red-700">Arsipkan</button></form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="{{ $isAdmin ? 6 : 5 }}" class="px-4 py-8 text-center text-slate-500">Tidak ada tugas yang cocok.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{ $tasks->links() }}
    </div>
</x-dynamic-component>

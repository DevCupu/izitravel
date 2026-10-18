<x-admin-layout title="Arsip lead">
    <x-slot name="header"><div><a href="{{ route('admin.crm.leads.index') }}" class="text-xs font-bold text-[#3166b1]">← Semua lead</a><h1 class="mt-1 text-xl font-bold text-slate-900 dark:text-white">Arsip lead</h1><p class="text-xs text-slate-500">Lead yang diarsipkan dapat dipulihkan beserta riwayatnya.</p></div></x-slot>
    <div class="space-y-5" x-data="{ selected: [], allIds: {{ \Illuminate\Support\Js::from($leads->pluck('id')->map(fn ($id) => (string) $id)->all()) }} }">
        <form method="GET" action="{{ route('admin.crm.leads.archived') }}" class="ui-card ui-card-pad flex flex-wrap items-end gap-3">
            <div class="min-w-[220px] flex-1"><label for="archived-lead-search" class="ui-label">Cari nama lead</label><input id="archived-lead-search" name="search" value="{{ $filters['search'] ?? '' }}" class="ui-input"></div>
            <button class="ui-button-primary">Cari</button><a href="{{ route('admin.crm.leads.archived') }}" class="ui-button-secondary">Reset</a>
        </form>
        <p class="text-sm font-semibold text-slate-700">{{ number_format($leads->total(), 0, ',', '.') }} lead diarsipkan</p>
        @if ($leads->count())
            <div class="flex flex-wrap items-center gap-3 rounded-xl border border-blue-100 bg-blue-50 p-3">
                <button type="button" @click="selected = selected.length === allIds.length ? [] : [...allIds]" class="text-sm font-bold text-[#245392]" x-text="selected.length === allIds.length ? 'Batalkan pilihan' : 'Pilih semua di halaman ini'"></button>
                <span class="text-xs text-slate-600" x-text="selected.length + ' dipilih'"></span>
                <form method="POST" action="{{ route('admin.crm.leads.bulk-restore') }}" class="ml-auto">@csrf
                    <template x-for="id in selected" :key="id"><input type="hidden" name="ids[]" :value="id"></template>
                    <button :disabled="!selected.length" class="ui-button-primary disabled:cursor-not-allowed disabled:opacity-50">Pulihkan terpilih</button>
                </form>
            </div>
        @endif
        <div data-record-views data-default-view="card">
        <div data-record-cards class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($leads as $lead)
                <div class="flex gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                    <input type="checkbox" value="{{ $lead->id }}" x-model="selected" aria-label="Pilih {{ $lead->display_name }}" class="mt-1 size-5 shrink-0 rounded border-slate-300 text-[#3166b1] focus:ring-[#3166b1]">
                    <div class="min-w-0 flex-1"><p class="font-bold text-slate-900">{{ $lead->display_name }}</p><p class="mt-1 text-xs text-slate-500">{{ $stageLabels[$lead->stage] ?? $lead->stage }} · PIC {{ $lead->cs?->name ?? '—' }}</p><p class="mt-1 text-xs text-slate-500">Diarsipkan {{ $lead->deleted_at->timezone('Asia/Makassar')->translatedFormat('d M Y, H:i') }} WITA</p>
                        <form method="POST" action="{{ route('admin.crm.leads.restore', $lead) }}" class="mt-3">@csrf @method('PATCH')<button class="text-sm font-bold text-[#3166b1]">Pulihkan lead</button></form>
                    </div>
                </div>
            @empty
                <div class="sm:col-span-2 xl:col-span-3"><x-ui.empty-state title="Arsip masih kosong" description="Lead yang diarsipkan admin akan muncul di sini." icon="archive" /></div>
            @endforelse
        </div>
        <div data-record-table hidden class="overflow-x-auto rounded-xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900">
            <table class="w-full min-w-[700px] text-left text-sm">
                <thead class="border-b border-slate-200 bg-slate-50 text-xs text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300"><tr><th scope="col" class="px-4 py-3">Pilih</th><th scope="col" class="px-4 py-3">Lead</th><th scope="col" class="px-4 py-3">Tahap</th><th scope="col" class="px-4 py-3">PIC</th><th scope="col" class="px-4 py-3">Diarsipkan</th><th scope="col" class="px-4 py-3">Aksi</th></tr></thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($leads as $lead)
                        <tr class="text-slate-700 dark:text-slate-200">
                            <td class="px-4 py-3"><input type="checkbox" value="{{ $lead->id }}" x-model="selected" aria-label="Pilih {{ $lead->display_name }}" class="size-5 rounded border-slate-300 text-[#3166b1]"></td>
                            <th scope="row" class="px-4 py-3 font-bold">{{ $lead->display_name }}</th><td class="px-4 py-3">{{ $stageLabels[$lead->stage] ?? $lead->stage }}</td><td class="px-4 py-3">{{ $lead->cs?->name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3">{{ $lead->deleted_at->timezone('Asia/Makassar')->translatedFormat('d M Y, H:i') }} WITA</td>
                            <td class="px-4 py-3"><form method="POST" action="{{ route('admin.crm.leads.restore', $lead) }}">@csrf @method('PATCH')<button class="font-bold text-[#3166b1]">Pulihkan lead</button></form></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">Arsip masih kosong.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>
        {{ $leads->links() }}
    </div>
</x-admin-layout>

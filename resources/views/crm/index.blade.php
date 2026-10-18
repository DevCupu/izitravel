<x-dynamic-component :component="$isAdmin ? 'admin-layout' : 'cs-layout'" title="Leads">
    <x-slot name="header"><div><h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ $isAdmin ? 'Semua leads' : 'Leads saya' }}</h1><p class="text-xs text-slate-500 dark:text-slate-400">Calon jemaah yang sudah menghubungi tim</p></div></x-slot>
    @php($failedFollowupLead = ! $isAdmin && old('followup_lead_id') ? $leads->firstWhere('id', (int) old('followup_lead_id')) : null)
    <div class="space-y-5"
         x-data="{ selected: [], allIds: {{ \Illuminate\Support\Js::from($leads->pluck('id')->map(fn ($id) => (string) $id)->all()) }}, followupLead: null, openFollowup(id, name, url) { this.followupLead = { id, name, url }; this.$nextTick(() => { this.$refs.followupDialog.showModal(); this.$refs.followupDue.focus(); }); } }"
         @if ($failedFollowupLead) x-init="openFollowup({{ $failedFollowupLead->id }}, @js($failedFollowupLead->display_name), @js(route('cs.leads.followups', $failedFollowupLead)))" @endif>
        <div class="flex flex-wrap items-end justify-between gap-3"><div><p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ number_format($leads->total(), 0, ',', '.') }} lead ditemukan</p><p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $isAdmin ? 'Buka lead untuk melihat percakapan, tahap, dan tindak lanjut.' : 'Hubungi calon jemaah dan jadwalkan langkah berikutnya dari daftar ini.' }}</p></div><div class="flex flex-wrap gap-2">@if ($isAdmin)<a href="{{ route('admin.crm.leads.archived') }}" class="ui-button-secondary">Arsip lead</a>@else<a href="{{ route('cs.tasks') }}" class="ui-button-secondary"><i data-lucide="calendar-check" class="size-4" aria-hidden="true"></i>Tugas saya</a>@endif<a href="{{ route($routePrefix.'leads.create') }}" class="ui-button-primary"><i data-lucide="plus" class="size-4" aria-hidden="true"></i>Tambah lead</a></div></div>
        <form method="GET" action="{{ route($routePrefix.'leads.index') }}" class="ui-card ui-card-pad space-y-4" aria-label="Filter lead">
            <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_180px_auto_auto]">
                <div><label for="lead-search" class="ui-label">Cari nama atau nomor WhatsApp</label><input id="lead-search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Contoh: Ibu Siti atau 0812..." class="ui-input"></div>
                <div><label for="lead-stage" class="ui-label">Tahap</label><select id="lead-stage" name="stage" class="ui-input"><option value="">Semua tahap</option>@foreach ($stageLabels as $key => $label)<option value="{{ $key }}" @selected(($filters['stage'] ?? '') === $key)>{{ $label }}</option>@endforeach</select></div>
                <button type="submit" class="ui-button-primary self-end">Terapkan</button>
                <a href="{{ route($routePrefix.'leads.index') }}" class="ui-button-secondary self-end">Reset</a>
            </div>
            <details @if (array_intersect_key($filters, array_flip(['cs_id', 'campaign_id', 'campaign_ad_id', 'package_id', 'from', 'to']))) open @endif class="border-t border-slate-100 pt-3 dark:border-slate-700">
                <summary class="cursor-pointer rounded-lg py-1 text-xs font-bold text-[#3166b1] dark:text-blue-300">Filter lanjutan: PIC, sumber, paket, tanggal</summary>
                <div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @if ($isAdmin)<div><label for="lead-cs" class="ui-label">PIC</label><select id="lead-cs" name="cs_id" class="ui-input"><option value="">Semua PIC</option>@foreach ($csList as $cs)<option value="{{ $cs->id }}" @selected(($filters['cs_id'] ?? '') == $cs->id)>{{ $cs->name }}</option>@endforeach</select></div>@endif
                    <div><label for="lead-campaign" class="ui-label">Campaign</label><select id="lead-campaign" name="campaign_id" class="ui-input"><option value="">Semua Campaign</option>@foreach ($campaigns as $campaign)<option value="{{ $campaign->id }}" @selected(($filters['campaign_id'] ?? '') == $campaign->id)>{{ $campaign->name }}</option>@endforeach</select></div>
                    <div><label for="lead-ad" class="ui-label">Ads</label><select id="lead-ad" name="campaign_ad_id" class="ui-input"><option value="">Semua Ads</option>@foreach ($ads as $ad)<option value="{{ $ad->id }}" @selected(($filters['campaign_ad_id'] ?? '') == $ad->id)>{{ $ad->name }} · {{ $campaigns->firstWhere('id', $ad->campaign_id)?->name }}</option>@endforeach</select></div>
                    <div><label for="lead-package" class="ui-label">Paket</label><select id="lead-package" name="package_id" class="ui-input"><option value="">Semua paket</option>@foreach ($packages as $package)<option value="{{ $package->id }}" @selected(($filters['package_id'] ?? '') == $package->id)>{{ $package->name }}</option>@endforeach</select></div>
                    <div><label for="lead-from" class="ui-label">Dibuat dari · WITA</label><input id="lead-from" name="from" type="date" value="{{ $filters['from'] ?? '' }}" class="ui-input"></div>
                    <div><label for="lead-to" class="ui-label">Sampai · WITA</label><input id="lead-to" name="to" type="date" value="{{ $filters['to'] ?? '' }}" class="ui-input"></div>
                </div>
            </details>
        </form>
        @if ($isAdmin && $leads->count())
            <div class="flex flex-wrap items-center gap-3 rounded-xl border border-blue-100 bg-blue-50 p-3">
                <button type="button" @click="selected = selected.length === allIds.length ? [] : [...allIds]" class="text-sm font-bold text-[#245392]" x-text="selected.length === allIds.length ? 'Batalkan pilihan' : 'Pilih semua di halaman ini'"></button>
                <span class="text-xs text-slate-600" x-text="selected.length + ' dipilih'"></span>
                <form method="POST" action="{{ route('admin.crm.leads.bulk-archive') }}" class="ml-auto">@csrf
                    <template x-for="id in selected" :key="id"><input type="hidden" name="ids[]" :value="id"></template>
                    <button type="submit" :disabled="!selected.length" @click="if (!confirm('Arsipkan ' + selected.length + ' lead terpilih?')) $event.preventDefault()" class="rounded-xl bg-red-700 px-4 py-2.5 text-xs font-bold text-white hover:bg-red-800 disabled:cursor-not-allowed disabled:opacity-50">Arsipkan terpilih</button>
                </form>
            </div>
        @endif
        <div data-record-views data-default-view="card">
            <div data-record-cards class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">@forelse ($leads as $lead) @include('crm._lead-card', ['bulkSelect' => $isAdmin]) @empty <div class="sm:col-span-2 xl:col-span-3"><x-ui.empty-state title="Tidak ada lead yang cocok" description="Ubah filter atau catat calon jemaah setelah mereka menghubungi CS." icon="users-round"><a href="{{ route($routePrefix.'leads.create') }}" class="ui-button-primary">Tambah lead</a></x-ui.empty-state></div> @endforelse</div>
            <div data-record-table hidden class="overflow-x-auto rounded-xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900">
                <table class="w-full min-w-[800px] text-left text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 text-xs text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300"><tr>@if ($isAdmin)<th scope="col" class="px-4 py-3">Pilih</th>@endif<th scope="col" class="px-4 py-3">Lead</th><th scope="col" class="px-4 py-3">Paket</th><th scope="col" class="px-4 py-3">Tahap</th><th scope="col" class="px-4 py-3">Kota</th><th scope="col" class="px-4 py-3">Rencana umrah</th>@if ($isAdmin)<th scope="col" class="px-4 py-3">PIC</th>@endif<th scope="col" class="px-4 py-3">Diperbarui</th>@unless ($isAdmin)<th scope="col" class="px-4 py-3">Aksi CS</th>@endunless</tr></thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($leads as $lead)
                            <tr class="text-slate-700 hover:bg-blue-50/50 dark:text-slate-200 dark:hover:bg-slate-800/60">
                                @if ($isAdmin)<td class="px-4 py-3"><input type="checkbox" value="{{ $lead->id }}" x-model="selected" aria-label="Pilih {{ $lead->display_name }}" class="size-5 rounded border-slate-300 text-[#3166b1]"></td>@endif
                                <th scope="row" class="px-4 py-3 font-bold"><a href="{{ route($routePrefix.'leads.show', $lead) }}" class="text-[#102b52] hover:underline dark:text-white">{{ $lead->display_name }}</a></th>
                                <td class="px-4 py-3">{{ $lead->package?->name ?? 'Paket belum dipilih' }}</td><td class="px-4 py-3">{{ $stageLabels[$lead->stage] ?? $lead->stage }}</td><td class="px-4 py-3">{{ $lead->city ?: '—' }}</td>
                                <td class="whitespace-nowrap px-4 py-3">{{ $lead->planned_departure_month ? \Carbon\Carbon::parse($lead->planned_departure_month.'-01')->locale('id')->translatedFormat('M Y') : '—' }}</td>
                                @if ($isAdmin)<td class="px-4 py-3">{{ $lead->cs?->name ?? '—' }}</td>@endif
                                <td class="whitespace-nowrap px-4 py-3">{{ $lead->updated_at->timezone('Asia/Makassar')->locale('id')->translatedFormat('d M Y') }}</td>
                                @unless ($isAdmin)<td class="px-4 py-3">@include('crm._cs-lead-actions', ['compact' => true])</td>@endunless
                            </tr>
                        @empty
                            <tr><td colspan="{{ $isAdmin ? 8 : 7 }}" class="px-4 py-8 text-center text-slate-500">Tidak ada lead yang cocok.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{ $leads->links() }}
        @unless ($isAdmin)
            <dialog x-ref="followupDialog" @close="followupLead = null" @click="if ($event.target === $el) $el.close()"
                    aria-labelledby="followup-dialog-title" class="w-[calc(100%-2rem)] max-w-lg rounded-2xl border border-slate-200 bg-white p-0 text-slate-900 shadow-2xl backdrop:bg-slate-900/60">
                <form method="POST" :action="followupLead?.url || '#'" class="p-5 sm:p-6">
                    @csrf
                    <input type="hidden" name="followup_lead_id" :value="followupLead?.id || ''">
                    <div class="flex items-start justify-between gap-4">
                        <div><h2 id="followup-dialog-title" class="text-lg font-bold">Jadwalkan follow-up</h2><p class="mt-1 text-sm text-slate-600" x-text="followupLead?.name || ''"></p></div>
                        <button type="button" @click="$refs.followupDialog.close()" class="inline-flex size-9 shrink-0 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100" aria-label="Tutup"><i data-lucide="x" class="size-5" aria-hidden="true"></i></button>
                    </div>
                    <div class="mt-5 space-y-4">
                        <div><label for="quick-followup-due" class="ui-label">Tanggal dan waktu · WITA</label><input x-ref="followupDue" id="quick-followup-due" type="datetime-local" name="due_at" value="{{ old('due_at') }}" required class="ui-input">@error('due_at')<p class="mt-1 text-xs font-semibold text-red-700">{{ $message }}</p>@enderror</div>
                        <div><label for="quick-followup-description" class="ui-label">Yang perlu dilakukan</label><input id="quick-followup-description" name="description" value="{{ old('description') }}" maxlength="255" required placeholder="Contoh: Kirim rincian paket" class="ui-input">@error('description')<p class="mt-1 text-xs font-semibold text-red-700">{{ $message }}</p>@enderror</div>
                    </div>
                    <div class="mt-6 flex justify-end gap-2"><button type="button" @click="$refs.followupDialog.close()" class="ui-button-secondary">Batal</button><button type="submit" class="ui-button-primary">Jadwalkan</button></div>
                </form>
            </dialog>
        @endunless
    </div>
</x-dynamic-component>

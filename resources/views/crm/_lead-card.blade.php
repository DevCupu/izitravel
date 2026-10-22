<div class="min-w-0 rounded-xl border border-slate-200 bg-white shadow-sm transition hover:border-[#3166b1] hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
    <div class="flex min-w-0">
        @if ($bulkSelect ?? false)
            <label class="flex shrink-0 items-start px-3 py-4" title="Pilih {{ $lead->display_name }}">
                <input type="checkbox" value="{{ $lead->id }}" x-model="selected" aria-label="Pilih {{ $lead->display_name }}" class="mt-1 size-5 rounded border-slate-300 text-[#3166b1] focus:ring-[#3166b1]">
            </label>
        @endif
        <a href="{{ route($routePrefix.'leads.show', $lead) }}" class="block min-w-0 flex-1 rounded-xl p-4 focus-visible:outline-[#3166b1]">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0"><p class="truncate font-bold text-slate-900 dark:text-white">{{ $lead->display_name }}</p><p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $lead->package?->name ?? 'Paket belum dipilih' }}</p></div>
                <span class="shrink-0 rounded-full px-2.5 py-1 text-[11px] font-bold {{ $lead->stage === 'berhasil' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300' : ($lead->stage === 'gagal' ? 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300' : 'bg-blue-50 text-[#3166b1] dark:bg-blue-900/30 dark:text-blue-300') }}">{{ $stageLabels[$lead->stage] ?? $lead->stage }}</span>
            </div>
            @if ($lead->city || $lead->planned_departure_month)
                <p class="mt-2 text-xs text-slate-600 dark:text-slate-300">@if ($lead->city){{ $lead->city }}@endif @if ($lead->city && $lead->planned_departure_month) · @endif @if ($lead->planned_departure_month)Umrah {{ \Carbon\Carbon::parse($lead->planned_departure_month.'-01')->locale('id')->translatedFormat('F Y') }}@endif</p>
            @endif
            @if ($isAdmin)<p class="mt-3 border-t border-slate-100 pt-2 text-xs text-slate-500 dark:border-slate-700 dark:text-slate-400">PIC: {{ $lead->cs?->name ?? '—' }}</p>@endif
        </a>
    </div>
    @if ($isAdmin)
        <div class="flex justify-end border-t border-slate-100 px-4 py-2 dark:border-slate-700">
            @include('crm._admin-stage-action')
        </div>
    @else
        @include('crm._cs-lead-actions')
    @endif
</div>

<div class="{{ $isAdmin ? 'flex' : 'flex flex-col' }} min-w-0 rounded-xl border border-slate-200 bg-white shadow-sm transition hover:border-[#3166b1] hover:shadow-md">
    @if ($bulkSelect ?? false)
        <label class="flex shrink-0 items-start px-3 py-4" title="Pilih {{ $lead->display_name }}">
            <input type="checkbox" value="{{ $lead->id }}" x-model="selected" aria-label="Pilih {{ $lead->display_name }}" class="mt-1 size-5 rounded border-slate-300 text-[#3166b1] focus:ring-[#3166b1]">
        </label>
    @endif
    <a href="{{ route($routePrefix.'leads.show', $lead) }}" class="block min-w-0 flex-1 rounded-xl p-4 focus-visible:outline-[#3166b1]">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0"><p class="truncate font-bold text-slate-900">{{ $lead->display_name }}</p><p class="mt-1 text-xs text-slate-500">{{ $lead->package?->name ?? 'Paket belum dipilih' }}</p></div>
            <span class="shrink-0 rounded-full px-2.5 py-1 text-[11px] font-bold {{ $lead->stage === 'berhasil' ? 'bg-emerald-100 text-emerald-800' : ($lead->stage === 'gagal' ? 'bg-slate-100 text-slate-700' : 'bg-blue-50 text-[#3166b1]') }}">{{ $stageLabels[$lead->stage] ?? $lead->stage }}</span>
        </div>
        @if ($lead->city || $lead->planned_departure_month)
            <p class="mt-2 text-xs text-slate-600">@if ($lead->city){{ $lead->city }}@endif @if ($lead->city && $lead->planned_departure_month) · @endif @if ($lead->planned_departure_month)Umrah {{ \Carbon\Carbon::parse($lead->planned_departure_month.'-01')->locale('id')->translatedFormat('F Y') }}@endif</p>
        @endif
        @if ($isAdmin)<p class="mt-3 border-t border-slate-100 pt-2 text-xs text-slate-500">PIC: {{ $lead->cs?->name ?? '—' }}</p>@endif
    </a>
    @unless ($isAdmin)
        @include('crm._cs-lead-actions')
    @endunless
</div>

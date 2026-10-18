@php($compact = $compact ?? false)
<div class="flex flex-wrap items-center gap-2 {{ $compact ? '' : 'border-t border-slate-100 px-4 py-3' }}">
    <a href="https://wa.me/{{ $lead->contact->phone_normalized }}" target="_blank" rel="noopener noreferrer"
       class="inline-flex min-h-10 items-center gap-1.5 rounded-lg bg-[#e9f7f0] px-3 text-xs font-bold text-[#176b4d] transition hover:bg-[#d5efdf] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#176b4d]"
       aria-label="Buka WhatsApp {{ $lead->display_name }}">
        <i data-lucide="message-circle" class="size-4" aria-hidden="true"></i>WhatsApp
    </a>
    @unless (in_array($lead->stage, ['berhasil', 'gagal']))
        <button type="button" @click="openFollowup({{ $lead->id }}, @js($lead->display_name), @js(route('cs.leads.followups', $lead)))"
                class="inline-flex min-h-10 items-center gap-1.5 rounded-lg border border-[#b9cdec] px-3 text-xs font-bold text-[#245392] transition hover:bg-blue-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#3166b1]"
                aria-label="Jadwalkan follow-up {{ $lead->display_name }}">
            <i data-lucide="calendar-plus" class="size-4" aria-hidden="true"></i>Follow-up
        </button>
        <a href="{{ route('cs.leads.show', $lead) }}#tahap-penjualan"
           class="inline-flex min-h-10 items-center gap-1.5 rounded-lg px-2 text-xs font-bold text-[#245392] transition hover:bg-blue-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#3166b1]">
            Ubah tahap <i data-lucide="arrow-up-right" class="size-3.5" aria-hidden="true"></i>
        </a>
    @endunless
</div>

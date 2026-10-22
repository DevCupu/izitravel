@php
    $stageActionPayload = [
        'id' => $lead->id,
        'name' => $lead->display_name,
        'url' => route('admin.crm.leads.stage', $lead),
        'stage' => $lead->stage,
        'confirmedOn' => $lead->won_at?->timezone('Asia/Makassar')->toDateString(),
        'confirmationNote' => $lead->confirmation_note,
        'bookingReference' => $lead->booking_reference,
        'lostReason' => $lead->lost_reason,
    ];
@endphp
<a href="{{ route('admin.crm.leads.show', $lead) }}#tahap-penjualan"
   @click.prevent="openStage(@js($stageActionPayload))"
   class="inline-flex min-h-10 items-center gap-1 rounded-lg px-2 text-xs font-bold text-[#3166b1] hover:bg-blue-50 dark:text-blue-300 dark:hover:bg-slate-700"
   aria-label="Ubah tahap {{ $lead->display_name }}">
    Ubah tahap <i data-lucide="arrow-up-right" class="size-3.5" aria-hidden="true"></i>
</a>

{{-- Vars: $status (one of Registration::STATUSES keys) --}}
@php
    $colors = [
        'missing' => 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400',
        'in_progress' => 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
        'completed' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400',
        'problem' => 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400',
    ];
    $icons = [
        'missing' => 'circle-dashed',
        'in_progress' => 'loader',
        'completed' => 'check-circle-2',
        'problem' => 'alert-triangle',
    ];
@endphp
<span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-md {{ $colors[$status] ?? $colors['missing'] }}">
    <i data-lucide="{{ $icons[$status] ?? $icons['missing'] }}" class="w-3 h-3"></i>
    {{ \App\Models\Registration::STATUSES[$status] ?? $status }}
</span>

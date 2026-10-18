@props(['tone' => 'info'])
@php
    $classes = [
        'info' => 'border-blue-200 bg-blue-50 text-blue-900 dark:border-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-900 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-950 dark:border-amber-800 dark:bg-amber-900/30 dark:text-amber-200',
        'error' => 'border-red-200 bg-red-50 text-red-900 dark:border-red-800 dark:bg-red-900/30 dark:text-red-200',
    ];
@endphp
<div {{ $attributes->class(['rounded-xl border px-4 py-3 text-sm leading-relaxed', $classes[$tone] ?? $classes['info']]) }} role="{{ $tone === 'error' ? 'alert' : 'status' }}">{{ $slot }}</div>

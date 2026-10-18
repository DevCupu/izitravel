@props(['title', 'description' => null])
<div {{ $attributes->class(['flex flex-wrap items-end justify-between gap-3']) }}>
    <div><h2 class="ui-section-title">{{ $title }}</h2>@if ($description)<p class="mt-1 text-xs leading-relaxed text-slate-500 dark:text-slate-400">{{ $description }}</p>@endif</div>
    @if (trim((string) $slot))<div class="flex flex-wrap items-center gap-2">{{ $slot }}</div>@endif
</div>

@props(['title', 'description' => null, 'icon' => 'inbox'])
<div {{ $attributes->class(['ui-empty']) }}>
    <span class="mx-auto mb-3 flex size-11 items-center justify-center rounded-xl bg-blue-50 text-[#3166b1] dark:bg-blue-900/30 dark:text-blue-300"><i data-lucide="{{ $icon }}" class="size-5" aria-hidden="true"></i></span>
    <p class="font-bold text-[#102b52] dark:text-white">{{ $title }}</p>
    @if ($description)<p class="mx-auto mt-1 max-w-md text-xs leading-relaxed">{{ $description }}</p>@endif
    @if (trim((string) $slot))<div class="mt-4">{{ $slot }}</div>@endif
</div>

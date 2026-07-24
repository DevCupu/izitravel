@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-sm text-slate-700 mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>

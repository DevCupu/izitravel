<nav aria-label="Bagian formulir paket" class="mb-6 rounded-2xl border border-blue-100 bg-blue-50/60 p-3 dark:border-blue-900/50 dark:bg-blue-950/20">
    <p class="px-2 pb-2 text-xs font-bold text-[#102b52] dark:text-blue-100">Isi paket per bagian</p>
    <div class="flex gap-2 overflow-x-auto pb-1">
        @foreach ([
            ['info-paket', 'Informasi'],
            ['harga-paket', 'Harga'],
            ['fasilitas-paket', 'Fasilitas'],
            ['biaya-paket', 'Biaya lain'],
            ['fitur-paket', 'Fitur'],
            ['itinerary-paket', 'Itinerary'],
            ['media-paket', 'Media & status'],
        ] as [$id, $label])
            <a href="#{{ $id }}" class="inline-flex min-h-11 shrink-0 items-center rounded-xl border border-blue-100 bg-white px-3 text-xs font-bold text-[#245392] transition hover:bg-blue-100 dark:border-slate-700 dark:bg-slate-800 dark:text-blue-200 dark:hover:bg-slate-700">{{ $label }}</a>
        @endforeach
    </div>
</nav>

@if ($errors->any())
    <div role="alert" class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-900 dark:bg-red-950/30 dark:text-red-200">
        <p class="font-bold">Periksa {{ $errors->count() }} isian berikut:</p>
        <ul class="mt-2 list-inside list-disc space-y-1">
            @foreach ($errors->all() as $message)<li>{{ $message }}</li>@endforeach
        </ul>
    </div>
@endif

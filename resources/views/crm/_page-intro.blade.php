<section class="crm-intro relative overflow-hidden rounded-[1.5rem] bg-[#102b52] px-5 py-5 text-white shadow-[0_14px_35px_rgba(16,43,82,.12)] sm:px-7 sm:py-6" aria-label="{{ $title }}">
    <div aria-hidden="true" class="pointer-events-none absolute -right-12 -top-24 size-64 rounded-full border-[32px] border-white/[.05]"></div>
    <div class="relative flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="min-w-0 border-l-[3px] border-[#fcde05] pl-4">
            <p class="text-[11px] font-extrabold uppercase tracking-[.16em] text-[#fcde05]">{{ $eyebrow }}</p>
            <h2 class="mt-1 text-xl font-bold leading-tight sm:text-2xl">{{ $title }}</h2>
            <p class="mt-1 max-w-2xl text-xs leading-relaxed text-blue-100 sm:text-sm">{{ $description }}</p>
        </div>
        @isset($href)
            <a href="{{ $href }}" class="inline-flex min-h-11 shrink-0 items-center justify-center gap-2 self-start rounded-xl bg-[#fcde05] px-4 py-2.5 text-sm font-extrabold text-[#102b52] transition hover:bg-[#ffe845] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white sm:self-auto">
                <i data-lucide="{{ $icon ?? 'arrow-up-right' }}" class="size-4" aria-hidden="true"></i>{{ $linkLabel }}
            </a>
        @endisset
    </div>
</section>

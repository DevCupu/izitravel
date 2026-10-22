<header class="interior-hero">
    <div class="interior-hero__shell">
        <nav class="interior-hero__breadcrumb" aria-label="Breadcrumb">
            <a href="{{ url('/') }}">Beranda</a>
            <i data-lucide="chevron-right" aria-hidden="true"></i>
            <span aria-current="page">{{ $breadcrumb ?? $title }}</span>
        </nav>
        <div class="interior-hero__content">
            <div>
                <span class="interior-hero__eyebrow"><span aria-hidden="true"></span>{{ $eyebrow }}</span>
                <h1>{{ $title }}</h1>
                <p>{{ $description }}</p>
            </div>
            @if (!empty($actionHref))
                <a class="interior-hero__action" href="{{ $actionHref }}">
                    <i data-lucide="arrow-left" aria-hidden="true"></i>{{ $actionLabel }}
                </a>
            @endif
        </div>
    </div>
</header>

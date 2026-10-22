<section class="journal-home" id="artikel" data-purpose="articles-section" aria-labelledby="journal-home-title">
    <div class="journal-home__shell">
        <div class="journal-home__heading">
            <div>
                <span class="journal-home__eyebrow">{{ $settings['articles_label'] ?? 'Artikel & Inspirasi' }}</span>
                <h2 id="journal-home-title">{{ $settings['articles_section_title'] ?? 'Kabar & Tips Umrah Terbaru' }}</h2>
            </div>
            <p>{{ $settings['articles_section_subtitle'] ?? 'Dapatkan panduan ibadah terpercaya, informasi destinasi, serta tips kesehatan untuk kelancaran umrah Anda.' }}</p>
        </div>

        <div class="journal-home__toolbar">
            <div class="journal-home__filters" role="group" aria-label="Filter kategori artikel">
                <button type="button" class="article-tab-btn is-active" data-filter="all" aria-pressed="true">{{ $settings['articles_filter_all'] ?? 'Semua' }}</button>
                <button type="button" class="article-tab-btn" data-filter="panduan-umrah" aria-pressed="false">{{ $settings['articles_filter_panduan'] ?? 'Panduan Umrah' }}</button>
                <button type="button" class="article-tab-btn" data-filter="tips-doa" aria-pressed="false">{{ $settings['articles_filter_tips'] ?? 'Tips & Doa' }}</button>
                <button type="button" class="article-tab-btn" data-filter="info-haramain" aria-pressed="false">{{ $settings['articles_filter_haramain'] ?? 'Info Haramain' }}</button>
            </div>
            <p class="journal-home__count" id="article-filter-count" aria-live="polite">{{ $articles->count() }} artikel pilihan</p>
        </div>

        <div class="journal-home__grid" id="articles-grid">
            @foreach ($articles as $article)
                @php
                    $categorySlug = \Illuminate\Support\Str::slug($article->category);
                    $filterCategory = str_contains($categorySlug, 'haramain') ? 'info-haramain' : (str_contains($categorySlug, 'tips') || str_contains($categorySlug, 'doa') ? 'tips-doa' : (str_contains($categorySlug, 'panduan') || str_contains($categorySlug, 'umroh') ? 'panduan-umrah' : $categorySlug));
                    $cardTags = array_values(array_filter(array_map('trim', explode(',', (string) $article->tags))));
                    $publishedDate = str_ireplace(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'October', 'November', 'December'], ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'Oktober', 'November', 'Desember'], (string) $article->published_at);
                    $readTime = preg_replace('/\bmin(?:ute)?s?\b/i', 'menit', trim((string) $article->read_time));
                    $readSuffix = mb_strtolower((string) ($settings['articles_read_suffix'] ?? 'Baca'));
                    $readLabel = $readTime === '' ? null : (preg_match('/^\d+$/', $readTime) ? $readTime.' menit '.$readSuffix : (str_contains(mb_strtolower($readTime), $readSuffix) ? $readTime : $readTime.' '.$readSuffix));
                    $authorInitials = collect(explode(' ', trim((string) $article->author)))->filter()->map(fn ($word) => strtoupper(mb_substr($word, 0, 1)))->take(2)->join('');
                @endphp
                <article class="article-card journal-card" data-category="{{ $filterCategory }}" data-purpose="article-item">
                    <a class="journal-card__image" href="{{ route('articles.show', $article->slug) }}" aria-label="Baca artikel {{ $article->title }}">
                        <img src="{{ $article->image_url ?: asset('images/package_kaaba.webp') }}" alt="" width="600" height="375" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='{{ asset('images/package_kaaba.webp') }}';">
                        <span class="journal-card__category">{{ $article->category }}</span>
                    </a>
                    <div class="journal-card__body">
                        <div class="journal-card__meta">
                            @if ($publishedDate)<span>{{ $publishedDate }}</span>@endif
                            @if ($readLabel)<span class="journal-card__read-time">{{ $readLabel }}</span>@endif
                        </div>
                        <h3><a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a></h3>
                        @if ($article->excerpt)<p class="journal-card__excerpt">{{ $article->excerpt }}</p>@endif
                        @if (count($cardTags))
                            <div class="journal-card__tags" aria-label="Topik artikel">
                                @foreach (array_slice($cardTags, 0, 2) as $tag)
                                    <a href="{{ route('public.articles.tag', ltrim($tag, '#')) }}">{{ str_starts_with($tag, '#') ? $tag : '#'.$tag }}</a>
                                @endforeach
                                @if (count($cardTags) > 2)<span>+{{ count($cardTags) - 2 }}</span>@endif
                            </div>
                        @endif
                    </div>
                    <div class="journal-card__footer">
                        <div class="journal-card__author">
                            <span class="journal-card__avatar" aria-hidden="true">{{ $authorInitials ?: 'IZ' }}</span>
                            <span class="journal-card__author-text"><strong>{{ $article->author }}</strong>@if ($article->author_role)<small>{{ $article->author_role }}</small>@endif</span>
                        </div>
                        <a class="journal-card__open" href="{{ route('articles.show', $article->slug) }}" aria-label="{{ $settings['articles_read_more'] ?? 'Baca' }} {{ $article->title }}"><span>{{ $settings['articles_read_more'] ?? 'Baca' }}</span><i data-lucide="arrow-up-right" aria-hidden="true"></i></a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="journal-home__empty" id="article-filter-empty" @if ($articles->isNotEmpty()) hidden @endif>
            <p>Belum ada artikel dalam kategori ini.</p>
            <span>Pilih kategori lain untuk menemukan bacaan umrah.</span>
        </div>

        @if ($articles->count() >= 6)
            <div class="journal-home__more">
                <a href="{{ route('public.articles.index') }}">{{ $settings['articles_view_all'] ?? 'Lihat Semua Artikel' }} <i data-lucide="arrow-up-right" aria-hidden="true"></i></a>
            </div>
        @endif
    </div>
</section>

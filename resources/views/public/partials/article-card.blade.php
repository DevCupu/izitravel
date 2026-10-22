<article class="article-card interior-card">
    <a class="article-card__image" href="{{ route('articles.show', $article->slug) }}" aria-label="Baca {{ $article->title }}">
        <img src="{{ $article->image_url }}" alt="" width="480" height="300" loading="{{ $loop->index < 3 ? 'eager' : 'lazy' }}" decoding="async">
    </a>
    <div class="article-card__body">
        <div class="article-card__meta"><span>{{ $article->category }}</span><span>{{ $article->published_at }}</span></div>
        <h2><a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a></h2>
        <p>{{ $article->excerpt }}</p>
        <div class="article-card__footer">
            <span>{{ $article->read_time }} baca</span>
            <a href="{{ route('articles.show', $article->slug) }}" aria-label="Baca artikel {{ $article->title }}">Baca artikel &rarr;</a>
        </div>
    </div>
</article>

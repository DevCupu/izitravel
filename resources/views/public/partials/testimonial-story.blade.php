@php
    $storyContext = $context ?? 'page';
    $videoUrl = $testimonial->video_url ?? '';
    $embedUrl = null;
    $thumbnailUrl = null;
    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|live|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $videoUrl, $matches)) {
        $embedUrl = 'https://www.youtube.com/embed/'.$matches[1].'?autoplay=1';
        $thumbnailUrl = 'https://img.youtube.com/vi/'.$matches[1].'/hqdefault.jpg';
    } elseif (preg_match('/instagram\.com\/(?:reel|p)\/([a-zA-Z0-9_-]+)/i', $videoUrl, $matches)) {
        $embedUrl = 'https://www.instagram.com/reel/'.$matches[1].'/embed/';
    } elseif (preg_match('/(?:vimeo\.com\/(?:channels\/[^\/]+\/|groups\/[^\/]+\/video\/|album\/[^\/]+\/video\/|video\/|)|player\.vimeo\.com\/video\/)([0-9]+)/i', $videoUrl, $matches)) {
        $embedUrl = 'https://player.vimeo.com/video/'.$matches[1].'?autoplay=1';
    }
    $displayName = trim((string) $testimonial->name);
    $avatarName = preg_replace('/^((h|hj|dr|drs|prof|ustadz|ust)\.?\s+)+/i', '', $displayName) ?: $displayName;
    $initial = mb_strtoupper(mb_substr($avatarName ?: 'J', 0, 1));
    $rating = max(0, min(5, (int) $testimonial->rating));
    $coverUrl = $thumbnailUrl ?: (!empty($testimonial->photo) ? asset('storage/'.$testimonial->photo) : asset('images/section_makkah_wide.webp'));
@endphp
<article class="story-card {{ $storyContext === 'home' ? 'story-card-home' : 'story-card-page' }} {{ !empty($featured) ? 'story-card-featured' : '' }}" @if($storyContext === 'page') id="cerita-{{ $testimonial->id }}" @endif>
    @if ($embedUrl)
        <button type="button" class="story-video" data-story-video="{{ $embedUrl }}" aria-label="Putar video testimoni {{ $displayName }}">
            <img src="{{ $coverUrl }}" alt="" loading="lazy" decoding="async">
            <span class="story-video-shade" aria-hidden="true"></span>
            <span class="story-play" aria-hidden="true"><i data-lucide="play"></i></span>
            <span class="story-video-caption">Tonton cerita jemaah <i data-lucide="arrow-up-right" aria-hidden="true"></i></span>
        </button>
    @endif
    <div class="story-card-body">
        <div class="story-card-meta">
            <span>{{ $embedUrl ? 'Cerita video' : 'Cerita tertulis' }}</span>
            @if($rating)
                <span class="story-rating" aria-label="Penilaian {{ $rating }} dari 5"><i data-lucide="star" aria-hidden="true"></i>{{ $rating }}/5</span>
            @endif
        </div>
        <blockquote class="story-quote">
            <p>{{ $testimonial->message }}</p>
        </blockquote>
        @if ($storyContext === 'home')
            <a class="story-read-more" href="{{ route('public.testimonials') }}#cerita-{{ $testimonial->id }}">Baca cerita lengkap <i data-lucide="arrow-right" aria-hidden="true"></i></a>
        @else
            <button type="button" class="story-read-more" data-story-expand aria-expanded="false" hidden>Baca selengkapnya <i data-lucide="chevron-down" aria-hidden="true"></i></button>
        @endif
        <div class="story-author">
            @if (!empty($testimonial->photo))
                <img class="story-avatar" src="{{ asset('storage/'.$testimonial->photo) }}" alt="" width="44" height="44" loading="lazy" decoding="async">
            @else
                <span class="story-avatar story-avatar-initial" aria-hidden="true">{{ $initial }}</span>
            @endif
            <div><h3>{{ $displayName }}</h3>@if($testimonial->location)<p>{{ $testimonial->location }}</p>@endif</div>
        </div>
    </div>
</article>

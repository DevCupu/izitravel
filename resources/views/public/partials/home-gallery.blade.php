@php
    $albumGroups = $galleries->groupBy(fn ($item) => trim((string) $item->category_label) ?: 'Umum')
        ->sortByDesc(fn ($items) => $items->max('updated_at'));
    $totalAlbums = $albumGroups->count();
    $displayAlbums = $albumGroups->take(5)->map(function ($items, $name) {
            $cover = $items->firstWhere('type', 'photo') ?? $items->first();
            $coverUrl = $cover?->image_url;
            if (!$coverUrl && $cover?->video_platform === 'youtube' && $cover?->video_id) {
                $coverUrl = 'https://img.youtube.com/vi/'.$cover->video_id.'/hqdefault.jpg';
            }

            return (object) [
                'name' => $name,
                'cover_url' => $coverUrl,
                'items' => $items->map(fn ($item) => [
                    'type' => $item->type,
                    'src' => $item->image_url,
                    'title' => $item->title,
                    'category' => $item->type === 'video' ? ($item->video_platform === 'youtube' ? 'YouTube' : 'Video') : ($item->category_label ?? 'Foto'),
                    'video_id' => $item->video_id,
                    'video_platform' => $item->video_platform,
                ])->values(),
                'count' => $items->count(),
            ];
        })->values();
    $seenCovers = [];
    $displayAlbums->each(function ($album) use (&$seenCovers) {
        $photos = $album->items->where('type', 'photo')->pluck('src')->filter();
        $album->cover_url = $photos->first(fn ($src) => !in_array($src, $seenCovers, true)) ?: $photos->first() ?: $album->cover_url;
        $album->cover_index = $album->items->search(fn ($item) => $item['src'] === $album->cover_url) ?: 0;
        if ($album->cover_url) $seenCovers[] = $album->cover_url;
    });
@endphp

<section class="journey-gallery" id="galeri" data-purpose="gallery-section" aria-labelledby="home-gallery-title">
    <div class="journey-gallery-shell">
        <div class="journey-gallery-heading">
            <div>
                <span class="journey-gallery-kicker">{{ $settings['gallery_label'] ?? 'Galeri & Dokumentasi' }}</span>
                <h2 id="home-gallery-title">{{ $settings['gallery_section_title'] ?? 'Galeri Kegiatan & Testimoni' }}</h2>
                <p>{{ $settings['gallery_section_subtitle'] ?? 'Dokumentasi perjalanan jemaah IZI Travel, dari persiapan hingga ibadah di Tanah Suci.' }}</p>
            </div>
            @if ($totalAlbums > 0)
                <div class="journey-gallery-count" aria-label="{{ $totalAlbums }} album, {{ $galleries->count() }} foto dan video">
                    <strong>{{ $totalAlbums }}</strong>
                    <span>album perjalanan<small>{{ $galleries->count() }} foto &amp; video</small></span>
                </div>
            @endif
        </div>

        @if ($displayAlbums->isNotEmpty())
            <div class="journey-gallery-layout">
                @php $featuredAlbum = $displayAlbums->first(); @endphp
                <button type="button" class="album-folder-card journey-gallery-card journey-gallery-featured"
                    data-album-name="{{ $featuredAlbum->name }}" data-items="{{ $featuredAlbum->items->toJson() }}" data-start-index="{{ $featuredAlbum->cover_index }}"
                    aria-label="Buka album {{ $featuredAlbum->name }}, {{ $featuredAlbum->count }} foto dan video">
                    <img src="{{ $featuredAlbum->cover_url ?: asset('images/section_makkah_wide.webp') }}" alt="" width="720" height="640" loading="lazy" decoding="async" fetchpriority="low">
                    <span class="journey-gallery-shade" aria-hidden="true"></span>
                    <span class="journey-gallery-card-content">
                        <span class="journey-gallery-card-type">Album utama <span>·</span> {{ $featuredAlbum->count }} media</span>
                        <span class="journey-gallery-card-title">{{ $featuredAlbum->name }}</span>
                        <span class="journey-gallery-card-action">Buka album <i data-lucide="arrow-up-right" aria-hidden="true"></i></span>
                    </span>
                </button>

                <div class="journey-gallery-support" aria-label="Album lainnya">
                    @foreach ($displayAlbums->skip(1) as $album)
                        <button type="button" class="album-folder-card journey-gallery-card journey-gallery-small"
                            data-album-name="{{ $album->name }}" data-items="{{ $album->items->toJson() }}" data-start-index="{{ $album->cover_index }}"
                            aria-label="Buka album {{ $album->name }}, {{ $album->count }} foto dan video">
                            <img src="{{ $album->cover_url ?: asset('images/section_makkah_wide.webp') }}" alt="" width="400" height="300" loading="lazy" decoding="async" fetchpriority="low">
                            <span class="journey-gallery-shade" aria-hidden="true"></span>
                            <span class="journey-gallery-card-content">
                                <span class="journey-gallery-card-type">{{ $album->count }} media</span>
                                <span class="journey-gallery-card-title">{{ $album->name }}</span>
                                <span class="journey-gallery-card-action">Buka album <i data-lucide="arrow-up-right" aria-hidden="true"></i></span>
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="journey-gallery-footer">
                <span class="journey-gallery-swipe" aria-hidden="true">Geser untuk melihat album lain <i data-lucide="arrow-right"></i></span>
                <a href="{{ route('public.gallery') }}">Jelajahi semua album <i data-lucide="arrow-up-right" aria-hidden="true"></i></a>
            </div>
        @else
            <div class="journey-gallery-empty">Dokumentasi perjalanan akan tampil di sini setelah tersedia.</div>
        @endif
    </div>

    <div id="gallery-lightbox" class="fixed inset-0 z-[100] bg-stone-950/90 backdrop-blur-md hidden opacity-0 items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="lightbox-title">
        <button type="button" id="lightbox-close" class="absolute top-6 right-6 z-[110] bg-white/10 hover:bg-white/20 text-white p-3 rounded-full transition duration-200 active:scale-95 backdrop-blur-md border border-white/10" aria-label="Tutup galeri">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
        <button type="button" id="lightbox-prev" class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 z-[110] bg-white/10 hover:bg-white/20 text-white w-12 h-12 rounded-full flex items-center justify-center transition duration-200 active:scale-95 backdrop-blur-md border border-white/10" aria-label="Foto sebelumnya">
            <i data-lucide="chevron-left" class="w-7 h-7"></i>
        </button>
        <button type="button" id="lightbox-next" class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 z-[110] bg-white/10 hover:bg-white/20 text-white w-12 h-12 rounded-full flex items-center justify-center transition duration-200 active:scale-95 backdrop-blur-md border border-white/10" aria-label="Foto selanjutnya">
            <i data-lucide="chevron-right" class="w-7 h-7"></i>
        </button>
        <div class="max-w-5xl w-full flex flex-col items-center justify-center gap-4 scale-95" id="lightbox-content-box">
            <div class="relative w-full max-h-[70vh] flex items-center justify-center rounded-3xl overflow-hidden bg-black/40 border border-white/5 shadow-2xl">
                <img id="lightbox-img" src="" alt="" class="max-w-full max-h-[70vh] object-contain hidden transition-all duration-300 opacity-0" />
                <div id="lightbox-video-container" class="aspect-video w-full hidden">
                    <iframe id="lightbox-iframe" title="Pemutar video galeri" class="w-full h-full" src="" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
            <div class="w-full max-w-3xl text-center px-4">
                <span id="lightbox-tag" class="text-xs text-amber-400 font-extrabold uppercase tracking-widest block mb-1"></span>
                <h3 id="lightbox-title" class="text-white font-black text-lg md:text-xl tracking-wide"></h3>
                <p id="lightbox-counter" class="text-white/60 text-xs mt-2 font-bold tracking-widest"></p>
            </div>
        </div>
    </div>
</section>

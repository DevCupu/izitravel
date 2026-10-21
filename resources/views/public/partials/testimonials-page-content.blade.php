@php
    $filterLabels = [
        'all' => 'Semua cerita',
        'video' => 'Video',
        'photo' => 'Dengan foto',
        'star5' => 'Rating 5',
    ];
@endphp
<body class="izi-public testimonial-page antialiased">
    @include('public.partials.navigation')

    <main id="main-content">
        <header class="story-page-hero">
            <div class="story-page-shell">
                <a class="story-back-link" href="{{ url('/#testimoni') }}"><i data-lucide="arrow-left" aria-hidden="true"></i>Kembali ke beranda</a>
                <span class="story-kicker">Testimoni jemaah IZI Travel</span>
                <h1>Cerita mereka, dengan kata-kata sendiri.</h1>
                <p>Simak pengalaman para jemaah selama perjalanan ibadah bersama IZI Travel, langsung dari cerita dan video mereka.</p>
                @if ($filterCounts['all'] > 0)
                    <span class="story-total"><i data-lucide="message-square-quote" aria-hidden="true"></i>{{ $filterCounts['all'] }} cerita jemaah</span>
                @endif
            </div>
        </header>

        <section class="story-page-list story-page-shell" aria-labelledby="story-list-title">
            <div class="story-list-heading">
                <div>
                    <span class="story-kicker">Jelajahi cerita</span>
                    <h2 id="story-list-title">Pengalaman jemaah</h2>
                </div>
                <p>Pilih jenis cerita yang ingin Anda lihat. Setiap filter menelusuri seluruh testimoni yang tersedia.</p>
            </div>

            <nav class="story-filters" aria-label="Filter testimoni">
                @foreach ($filterLabels as $key => $label)
                    <a href="{{ $key === 'all' ? route('public.testimonials') : route('public.testimonials', ['filter' => $key]) }}"
                       class="story-filter {{ $filter === $key ? 'is-active' : '' }}"
                       @if ($filter === $key) aria-current="page" @endif>
                        {{ $label }} <span>{{ $filterCounts[$key] }}</span>
                    </a>
                @endforeach
            </nav>

            @if ($testimonials->count())
                <p class="story-result-count">Menampilkan {{ $testimonials->firstItem() }}–{{ $testimonials->lastItem() }} dari {{ $testimonials->total() }} {{ ['all' => 'testimoni', 'video' => 'video', 'photo' => 'testimoni dengan foto', 'star5' => 'testimoni bintang 5'][$filter] }}.</p>
                <div class="story-page-grid">
                    @foreach ($testimonials as $testimonial)
                        @include('public.partials.testimonial-story', ['context' => 'page'])
                    @endforeach
                </div>
                @if ($testimonials->hasPages())
                    <div class="story-pagination">{{ $testimonials->links() }}</div>
                @endif
            @else
                <div class="story-empty">
                    <i data-lucide="messages-square" aria-hidden="true"></i>
                    <h3>Belum ada cerita pada filter ini</h3>
                    <p>Coba lihat semua testimoni jemaah yang sudah tersedia.</p>
                    @if ($filter !== 'all') <a href="{{ route('public.testimonials') }}">Lihat semua cerita <i data-lucide="arrow-right" aria-hidden="true"></i></a> @endif
                </div>
            @endif
        </section>

        <section class="story-page-next" aria-labelledby="story-next-title">
            <div class="story-page-shell">
                <div><span class="story-kicker">Rencanakan ibadah Anda</span><h2 id="story-next-title">Perjalanan berikutnya bisa menjadi cerita Anda.</h2></div>
                <a href="{{ url('/#paket-umrah') }}">Lihat paket umrah <i data-lucide="arrow-up-right" aria-hidden="true"></i></a>
            </div>
        </section>
    </main>

    @include('public.partials.footer')

    <div id="story-video-modal" class="story-modal" hidden role="dialog" aria-modal="true" aria-label="Video testimoni">
        <button type="button" class="story-modal-backdrop" data-story-close aria-label="Tutup video"></button>
        <div class="story-modal-panel">
            <button type="button" class="story-modal-close" data-story-close aria-label="Tutup video"><i data-lucide="x" aria-hidden="true"></i></button>
            <div id="story-video-frame"></div>
        </div>
    </div>

    <script>
        (() => {
            const modal = document.getElementById('story-video-modal');
            const frame = document.getElementById('story-video-frame');
            let previousFocus = null;

            function closeVideo() {
                modal.hidden = true;
                frame.replaceChildren();
                document.body.style.overflow = '';
                previousFocus?.focus();
            }

            document.querySelectorAll('[data-story-video]').forEach(button => {
                button.addEventListener('click', () => {
                    const url = button.dataset.storyVideo;
                    if (!/^https:\/\/(www\.youtube\.com|www\.instagram\.com|player\.vimeo\.com)\//.test(url)) return;
                    previousFocus = button;
                    const iframe = document.createElement('iframe');
                    iframe.src = url;
                    iframe.title = 'Video testimoni jemaah';
                    iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
                    iframe.allowFullscreen = true;
                    frame.replaceChildren(iframe);
                    modal.classList.toggle('is-portrait', url.includes('instagram.com'));
                    modal.hidden = false;
                    document.body.style.overflow = 'hidden';
                    modal.querySelector('.story-modal-close').focus();
                });
            });
            modal.querySelectorAll('[data-story-close]').forEach(button => button.addEventListener('click', closeVideo));
            document.addEventListener('keydown', event => {
                if (modal.hidden) return;
                if (event.key === 'Escape') closeVideo();
                if (event.key === 'Tab') {
                    event.preventDefault();
                    modal.querySelector('.story-modal-close').focus();
                }
            });

            document.querySelectorAll('[data-story-expand]').forEach(button => {
                const card = button.closest('.story-card');
                const quote = card.querySelector('.story-quote p');
                card.classList.add('story-clamp-ready');
                const syncExpandButton = () => {
                    if (!card.classList.contains('is-expanded')) button.hidden = quote.scrollHeight <= quote.clientHeight + 2;
                };
                requestAnimationFrame(syncExpandButton);
                window.addEventListener('load', syncExpandButton, { once: true });
                window.addEventListener('resize', syncExpandButton);
                button.addEventListener('click', () => {
                    const expanded = button.getAttribute('aria-expanded') === 'true';
                    button.setAttribute('aria-expanded', String(!expanded));
                    card.classList.toggle('is-expanded', !expanded);
                    button.innerHTML = !expanded ? 'Tampilkan lebih singkat <i data-lucide="chevron-up" aria-hidden="true"></i>' : 'Baca selengkapnya <i data-lucide="chevron-down" aria-hidden="true"></i>';
                    window.lucide?.createIcons();
                    if (expanded) requestAnimationFrame(syncExpandButton);
                });
            });

            function expandLinkedStory() {
                const target = document.getElementById(window.location.hash.slice(1));
                if (!target?.classList.contains('story-card-page')) return;
                const button = target.querySelector('[data-story-expand]');
                if (button && !button.hidden && button.getAttribute('aria-expanded') === 'false') button.click();
            }
            window.addEventListener('load', () => requestAnimationFrame(expandLinkedStory), { once: true });
            window.addEventListener('hashchange', () => requestAnimationFrame(expandLinkedStory));
        })();
    </script>
</body>

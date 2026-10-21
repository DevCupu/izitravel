<section class="story-home" id="testimoni" data-purpose="testimonials" aria-labelledby="home-stories-title">
    <div class="story-home-inner">
        <div class="story-home-heading">
            <span class="story-kicker">{{ $settings['testimonials_label'] ?? 'Testimoni jemaah' }}</span>
            <h2 id="home-stories-title">{{ $settings['testimonials_section_title'] ?? 'Cerita jemaah kami' }}</h2>
            <p>{{ $settings['testimonials_section_subtitle'] ?? 'Pengalaman ibadah yang diceritakan langsung oleh jemaah IZI Travel.' }}</p>
            <span class="story-swipe-hint" aria-hidden="true">Geser untuk melihat cerita lain <i data-lucide="arrow-right"></i></span>
        </div>

        @if ($testimonials->isNotEmpty())
            @php $previewTestimonials = $testimonials->take(5)->sortByDesc(fn ($story) => !empty($story->video_url)); @endphp
            <div class="story-home-grid">
                @foreach ($previewTestimonials as $testimonial)
                    @include('public.partials.testimonial-story', ['context' => 'home', 'featured' => $loop->first])
                @endforeach
            </div>
            <a class="story-home-link" href="{{ route('public.testimonials') }}">
                Lihat semua cerita
                @if (!empty($allTestimonialsCount)) <span>({{ $allTestimonialsCount }})</span> @endif
                <i data-lucide="arrow-right" aria-hidden="true"></i>
            </a>
        @else
            <div class="story-home-empty">Cerita jemaah akan tampil di sini setelah tersedia.</div>
        @endif
    </div>
</section>
<script>
    (() => {
        const section = document.getElementById('testimoni');
        document.addEventListener('click', event => {
            const button = event.target.closest('#testimoni [data-story-video]');
            if (button) window.openVideoTestimonialModal?.(button.dataset.storyVideo);
        });

        function syncReadLinks() {
            section.querySelectorAll('.story-card').forEach(card => {
                const quote = card.querySelector('.story-quote p');
                const link = card.querySelector('.story-read-more');
                if (quote && link) link.hidden = quote.scrollHeight <= quote.clientHeight + 2;
            });
        }
        requestAnimationFrame(syncReadLinks);
        window.addEventListener('load', syncReadLinks, { once: true });
        window.addEventListener('resize', syncReadLinks);
    })();
</script>

<div>
    @php $locale = app()->getLocale(); @endphp

    <article>
        {{-- Hero --}}
        <header class="page-header">
            <div class="container-site" style="max-width:760px;">
                @if ($post->category)
                    <a href="{{ route('blog.index') }}"
                       style="display:inline-block; margin-bottom:1rem; font-size:.85rem; font-weight:600; color:var(--color-primary); text-decoration:none;">
                        ← {{ $post->category->getTranslation('name', $locale, true) }}
                    </a>
                @endif
                <h1 class="page-header__title" style="font-size:clamp(1.8rem,4vw,2.8rem);">
                    {{ $post->getTranslation('title', $locale) }}
                </h1>
                <div style="display:flex; align-items:center; gap:1rem; margin-top:1.5rem; color:var(--color-text-muted); font-size:.9rem;">
                    @if ($post->published_at)
                        <time datetime="{{ $post->published_at->toDateString() }}">
                            {{ $post->published_at->translatedFormat('d M Y') }}
                        </time>
                    @endif
                    @if ($post->read_time)
                        <span>· {{ $post->read_time }} {{ __('min read') }}</span>
                    @endif
                    @if ($post->view_count)
                        <span>· {{ number_format($post->view_count) }} {{ __('views') }}</span>
                    @endif
                </div>
            </div>
        </header>

        {{-- Featured image --}}
        @if ($post->getFirstMediaUrl('featured_image'))
            <div class="container-site" style="max-width:900px; margin-bottom:3rem;">
                <img src="{{ $post->getFirstMediaUrl('featured_image') }}"
                     alt="{{ $post->getTranslation('title', $locale) }}"
                     style="width:100%; border-radius:1rem; object-fit:cover; max-height:480px;">
            </div>
        @endif

        {{-- Content --}}
        <div class="container-site prose" style="max-width:760px; margin-bottom:4rem;">
            {!! $post->getTranslation('content', $locale) !!}
        </div>

        {{-- Back link --}}
        <div class="container-site" style="max-width:760px; padding-bottom:4rem;">
            <a href="{{ route('blog.index') }}" class="btn-secondary">← {{ __('Back to Blog') }}</a>
        </div>
    </article>
</div>

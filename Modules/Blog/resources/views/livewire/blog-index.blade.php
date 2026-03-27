<div>
    {{-- Page header --}}
    <section class="page-header">
        <div class="container-site">
            <h1 class="page-header__title">{{ __('Blog') }}</h1>
            <p class="page-header__subtitle">{{ __('Latest articles, news and insights.') }}</p>
        </div>
    </section>

    {{-- Search + categories --}}
    <section class="section-sm">
        <div class="container-site">
            <div style="display:flex; gap:1rem; flex-wrap:wrap; align-items:center; margin-bottom:2rem;">
                <input wire:model.live.debounce.300ms="search"
                       type="search"
                       placeholder="{{ __('Search articles…') }}"
                       style="flex:1; min-width:200px; padding:.625rem 1rem; border:1px solid var(--color-border); border-radius:.5rem; font-size:.9rem;">

                <div style="display:flex; gap:.5rem; flex-wrap:wrap;">
                    <button wire:click="$set('categoryId', null)"
                            style="padding:.4rem .9rem; border-radius:2rem; font-size:.85rem; border:1px solid var(--color-border); cursor:pointer;
                                   {{ $categoryId === null ? 'background:var(--color-primary); color:#fff; border-color:var(--color-primary);' : 'background:#fff; color:var(--color-text-muted);' }}">
                        {{ __('All') }}
                    </button>
                    @foreach ($categories as $cat)
                        <button wire:click="$set('categoryId', {{ $cat->id }})"
                                style="padding:.4rem .9rem; border-radius:2rem; font-size:.85rem; border:1px solid var(--color-border); cursor:pointer;
                                       {{ $categoryId === $cat->id ? 'background:var(--color-primary); color:#fff; border-color:var(--color-primary);' : 'background:#fff; color:var(--color-text-muted);' }}">
                            {{ $cat->getTranslation('name', app()->getLocale(), true) }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Posts grid --}}
            @if ($posts->isEmpty())
                <div class="empty-state">
                    <div class="empty-state__icon">📝</div>
                    <h3 class="empty-state__title">{{ __('No articles yet') }}</h3>
                    <p class="empty-state__text">{{ __('Check back soon for new content.') }}</p>
                </div>
            @else
                <div class="card-grid card-grid--3">
                    @foreach ($posts as $post)
                        @php $locale = app()->getLocale(); @endphp
                        <article class="card card--blog">
                            @if ($post->getFirstMediaUrl('featured_image'))
                                <a href="{{ route('blog.post', $post->slug) }}" class="card__image-wrap">
                                    <img src="{{ $post->getFirstMediaUrl('featured_image') }}"
                                         alt="{{ $post->getTranslation('title', $locale) }}"
                                         class="card__image">
                                </a>
                            @endif
                            <div class="card__body">
                                @if ($post->category)
                                    <span class="card__badge">{{ $post->category->getTranslation('name', $locale, true) }}</span>
                                @endif
                                <h2 class="card__title">
                                    <a href="{{ route('blog.post', $post->slug) }}">
                                        {{ $post->getTranslation('title', $locale) }}
                                    </a>
                                </h2>
                                @if ($post->getTranslation('excerpt', $locale))
                                    <p class="card__excerpt">{{ $post->getTranslation('excerpt', $locale) }}</p>
                                @endif
                                <div class="card__meta">
                                    @if ($post->published_at)
                                        <time datetime="{{ $post->published_at->toDateString() }}">
                                            {{ $post->published_at->translatedFormat('d M Y') }}
                                        </time>
                                    @endif
                                    @if ($post->read_time)
                                        <span>· {{ $post->read_time }} {{ __('min read') }}</span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div style="margin-top:2rem;">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>
</div>

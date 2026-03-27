<div>
    <section class="page-header">
        <div class="container-site">
            <h1 class="page-header__title">{{ __('Portfolio') }}</h1>
            <p class="page-header__subtitle">{{ __('Our work and completed projects.') }}</p>
        </div>
    </section>

    <section class="section">
        <div class="container-site">
            @if ($categories->isNotEmpty())
                <div style="display:flex; gap:.5rem; flex-wrap:wrap; margin-bottom:2rem;">
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
            @endif

            @if ($projects->isEmpty())
                <div class="empty-state">
                    <div class="empty-state__icon">🖼️</div>
                    <h3 class="empty-state__title">{{ __('No projects yet') }}</h3>
                    <p class="empty-state__text">{{ __('Our portfolio is coming soon.') }}</p>
                </div>
            @else
                <div class="card-grid card-grid--3">
                    @foreach ($projects as $project)
                        @php $locale = app()->getLocale(); @endphp
                        <div class="card">
                            @if ($project->getFirstMediaUrl('featured_image'))
                                <a href="{{ route('portfolio.project', $project->slug) }}" class="card__image-wrap">
                                    <img src="{{ $project->getFirstMediaUrl('featured_image') }}"
                                         alt="{{ $project->getTranslation('title', $locale) }}"
                                         class="card__image">
                                </a>
                            @endif
                            <div class="card__body">
                                <h2 class="card__title">
                                    <a href="{{ route('portfolio.project', $project->slug) }}">
                                        {{ $project->getTranslation('title', $locale) }}
                                    </a>
                                </h2>
                                @if ($project->getTranslation('short_description', $locale))
                                    <p class="card__excerpt">{{ $project->getTranslation('short_description', $locale) }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>

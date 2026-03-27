<div>
    @php $locale = app()->getLocale(); @endphp

    <header class="page-header">
        <div class="container-site">
            <a href="{{ route('portfolio.index') }}" style="display:inline-block; margin-bottom:1rem; font-size:.85rem; font-weight:600; color:var(--color-primary); text-decoration:none;">
                ← {{ __('Portfolio') }}
            </a>
            <h1 class="page-header__title">{{ $project->getTranslation('title', $locale) }}</h1>
            @if ($project->getTranslation('short_description', $locale))
                <p class="page-header__subtitle">{{ $project->getTranslation('short_description', $locale) }}</p>
            @endif
        </div>
    </header>

    <section class="section">
        <div class="container-site prose" style="max-width:800px;">
            {!! $project->getTranslation('description', $locale) !!}

            @if ($project->technologies && count($project->technologies) > 0)
                <div style="margin-top:2rem;">
                    <h3>{{ __('Technologies') }}</h3>
                    <div style="display:flex; flex-wrap:wrap; gap:.5rem;">
                        @foreach ($project->technologies as $tech)
                            <span style="background:var(--color-surface); padding:.3rem .75rem; border-radius:2rem; font-size:.85rem; font-weight:500;">{{ $tech }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($project->client_url)
                <div style="margin-top:2rem;">
                    <a href="{{ $project->client_url }}" target="_blank" rel="noopener" class="btn-primary">
                        {{ __('Visit project') }} →
                    </a>
                </div>
            @endif
        </div>
    </section>

    <div class="container-site" style="padding-bottom:4rem;">
        <a href="{{ route('portfolio.index') }}" class="btn-secondary">← {{ __('All Projects') }}</a>
    </div>
</div>

<div>
    @php $locale = app()->getLocale(); @endphp

    <header class="page-header">
        <div class="container-site">
            <a href="{{ route('services.index') }}" style="display:inline-block; margin-bottom:1rem; font-size:.85rem; font-weight:600; color:var(--color-primary); text-decoration:none;">
                ← {{ __('Services') }}
            </a>
            <h1 class="page-header__title">{{ $service->getTranslation('title', $locale) }}</h1>
            @if ($service->getTranslation('short_description', $locale))
                <p class="page-header__subtitle">{{ $service->getTranslation('short_description', $locale) }}</p>
            @endif
        </div>
    </header>

    <section class="section">
        <div class="container-site prose" style="max-width:800px;">
            {!! $service->getTranslation('description', $locale) !!}

            @php
                // Normalise features to a flat list of strings, regardless of storage format.
                // Old Repeater format: [{"feature": "x"}] → new TagsInput format: ["x"]
                $featureList = collect($service->features ?? [])
                    ->map(fn ($item) => is_array($item) ? ($item['feature'] ?? null) : $item)
                    ->filter()
                    ->values();
            @endphp

            @if ($featureList->isNotEmpty())
                <h3>{{ __('Features') }}</h3>
                <ul>
                    @foreach ($featureList as $feature)
                        <li>{{ $feature }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    <div class="container-site" style="padding-bottom:4rem;">
        <a href="{{ route('services.index') }}" class="btn-secondary">← {{ __('All Services') }}</a>
    </div>
</div>

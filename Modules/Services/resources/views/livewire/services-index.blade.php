<div>
    <section class="page-header">
        <div class="container-site">
            <h1 class="page-header__title">{{ __('Services') }}</h1>
            <p class="page-header__subtitle">{{ __('What we do for our clients.') }}</p>
        </div>
    </section>

    <section class="section">
        <div class="container-site">
            @if ($services->isEmpty())
                <div class="empty-state">
                    <div class="empty-state__icon">⚙️</div>
                    <h3 class="empty-state__title">{{ __('Services coming soon') }}</h3>
                    <p class="empty-state__text">{{ __('We are preparing our service offerings.') }}</p>
                </div>
            @else
                <div class="card-grid card-grid--3">
                    @foreach ($services as $service)
                        @php $locale = app()->getLocale(); @endphp
                        <div class="card">
                            @if ($service->icon)
                                <div class="card__icon">{{ $service->icon }}</div>
                            @endif
                            <div class="card__body">
                                <h2 class="card__title">
                                    <a href="{{ route('services.show', $service->slug) }}">
                                        {{ $service->getTranslation('title', $locale) }}
                                    </a>
                                </h2>
                                @if ($service->getTranslation('short_description', $locale))
                                    <p class="card__excerpt">{{ $service->getTranslation('short_description', $locale) }}</p>
                                @endif
                                @if ($service->price_from)
                                    <p style="font-weight:700; color:var(--color-primary); margin-top:.75rem;">
                                        {{ __('From') }} {{ number_format((float) $service->price_from, 0) }} ₺
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>

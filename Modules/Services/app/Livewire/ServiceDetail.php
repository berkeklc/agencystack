<?php

declare(strict_types=1);

namespace Modules\Services\App\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Services\App\Models\Service;

#[Layout('layouts.app')]
final class ServiceDetail extends Component
{
    public string $slug;

    public function mount(string $slug): void
    {
        $this->slug = $slug;
    }

    public function render(): View
    {
        $locale = app()->getLocale();

        $service = Service::where('slug', $this->slug)
            ->where('status', 'published')
            ->firstOrFail();

        view()->share('seoTitle', $service->getTranslation('meta_title', $locale) ?: $service->getTranslation('title', $locale));
        view()->share('seoDescription', $service->getTranslation('meta_description', $locale) ?: $service->getTranslation('short_description', $locale));

        return view('services::livewire.service-detail', compact('service'));
    }
}

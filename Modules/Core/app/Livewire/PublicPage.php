<?php

declare(strict_types=1);

namespace Modules\Core\App\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Core\App\Enums\PageStatus;
use Modules\Core\App\Models\Page;

#[Layout('layouts.app')]
final class PublicPage extends Component
{
    public string $slug = '';

    public function mount(string $slug = ''): void
    {
        $this->slug = $slug;
    }

    public function render(): View
    {
        // Always fetch fresh — no caching so that admin changes reflect immediately.
        $page = $this->resolvePage();

        if ($page === null) {
            // No homepage configured: show a friendly first-run screen.
            return view('core::livewire.no-homepage');
        }

        // Share SEO data with the layout.
        $locale = app()->getLocale();
        view()->share('seoTitle', $page->getTranslation('meta_title', $locale) ?: $page->getTranslation('title', $locale));
        view()->share('seoDescription', $page->getTranslation('meta_description', $locale));

        return view('core::livewire.public-page', compact('page'));
    }

    private function resolvePage(): ?Page
    {
        if ($this->slug === '') {
            $page = Page::query()
                ->where('is_home', true)
                ->where('status', PageStatus::Published->value)
                ->first();

            // Fallback: any published page when no homepage is set.
            return $page;
        }

        $page = Page::query()
            ->where('slug', $this->slug)
            ->where('status', PageStatus::Published->value)
            ->first();

        if ($page === null) {
            abort(404);
        }

        return $page;
    }
}

<?php

declare(strict_types=1);

namespace Modules\Portfolio\App\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Portfolio\App\Models\Project;

#[Layout('layouts.app')]
final class PortfolioProject extends Component
{
    public string $slug;

    public function mount(string $slug): void
    {
        $this->slug = $slug;
    }

    public function render(): View
    {
        $locale = app()->getLocale();

        $project = Project::where('slug', $this->slug)
            ->where('status', 'published')
            ->firstOrFail();

        view()->share('seoTitle', $project->getTranslation('meta_title', $locale) ?: $project->getTranslation('title', $locale));
        view()->share('seoDescription', $project->getTranslation('meta_description', $locale) ?: $project->getTranslation('short_description', $locale));

        return view('portfolio::livewire.portfolio-project', compact('project'));
    }
}

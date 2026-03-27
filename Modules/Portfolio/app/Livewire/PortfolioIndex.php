<?php

declare(strict_types=1);

namespace Modules\Portfolio\App\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Portfolio\App\Models\Project;
use Modules\Portfolio\App\Models\ProjectCategory;

#[Layout('layouts.app')]
final class PortfolioIndex extends Component
{
    public ?int $categoryId = null;

    public function render(): View
    {
        $projects = Project::query()
            ->where('status', 'published')
            ->when($this->categoryId, fn ($q) => $q->where('category_id', $this->categoryId))
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->get();

        $categories = ProjectCategory::has('projects')->get();

        view()->share('seoTitle', __('Portfolio'));
        view()->share('seoDescription', __('Our work and projects.'));

        return view('portfolio::livewire.portfolio-index', compact('projects', 'categories'));
    }
}

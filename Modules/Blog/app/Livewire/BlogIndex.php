<?php

declare(strict_types=1);

namespace Modules\Blog\App\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Blog\App\Models\Post;
use Modules\Blog\App\Models\PostCategory;

#[Layout('layouts.app')]
final class BlogIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $categoryId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $locale = app()->getLocale();

        $posts = Post::query()
            ->where('status', 'published')
            ->when($this->search, fn ($q) => $q->where("title->{$locale}", 'like', "%{$this->search}%"))
            ->when($this->categoryId, fn ($q) => $q->where('category_id', $this->categoryId))
            ->orderByDesc('published_at')
            ->paginate(12);

        $categories = PostCategory::has('posts')->orderBy('sort_order')->get();

        view()->share('seoTitle', __('Blog'));
        view()->share('seoDescription', __('Latest articles and news.'));

        return view('blog::livewire.blog-index', compact('posts', 'categories'));
    }
}

<?php

declare(strict_types=1);

namespace Modules\Blog\App\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Blog\App\Models\Post;

#[Layout('layouts.app')]
final class BlogPost extends Component
{
    public string $slug;

    public function mount(string $slug): void
    {
        $this->slug = $slug;
    }

    public function render(): View
    {
        $locale = app()->getLocale();

        $post = Post::where('slug', $this->slug)
            ->where('status', 'published')
            ->firstOrFail();

        $post->increment('view_count');

        view()->share('seoTitle', $post->getTranslation('meta_title', $locale) ?: $post->getTranslation('title', $locale));
        view()->share('seoDescription', $post->getTranslation('meta_description', $locale) ?: $post->getTranslation('excerpt', $locale));

        return view('blog::livewire.blog-post', compact('post'));
    }
}

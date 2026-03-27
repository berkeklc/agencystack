<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Blog\App\Livewire\BlogIndex;
use Modules\Blog\App\Livewire\BlogPost;

Route::prefix('blog')->name('blog.')->group(function (): void {
    Route::get('/', BlogIndex::class)->name('index');
    Route::get('/{slug}', BlogPost::class)->name('post')
        ->where('slug', '[a-z0-9\-]+');
});

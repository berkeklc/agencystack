<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Portfolio\App\Livewire\PortfolioIndex;
use Modules\Portfolio\App\Livewire\PortfolioProject;

Route::prefix('portfolio')->name('portfolio.')->group(function (): void {
    Route::get('/', PortfolioIndex::class)->name('index');
    Route::get('/{slug}', PortfolioProject::class)->name('project')
        ->where('slug', '[a-z0-9\-]+');
});

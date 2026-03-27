<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Services\App\Livewire\ServiceDetail;
use Modules\Services\App\Livewire\ServicesIndex;

Route::prefix('services')->name('services.')->group(function (): void {
    Route::get('/', ServicesIndex::class)->name('index');
    Route::get('/{slug}', ServiceDetail::class)->name('show')
        ->where('slug', '[a-z0-9\-]+');
});

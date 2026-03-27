<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Team\App\Livewire\TeamIndex;

Route::prefix('team')->name('team.')->group(function (): void {
    Route::get('/', TeamIndex::class)->name('index');
});

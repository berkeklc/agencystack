<?php

declare(strict_types=1);

namespace Modules\Services\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\Services\App\Livewire\ServiceDetail;
use Modules\Services\App\Livewire\ServicesIndex;

final class ServicesServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Services';

    protected string $moduleNameLower = 'services';

    public function register(): void {}

    public function boot(): void
    {
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/config.php'), $this->moduleNameLower);
        $this->loadViewsFrom(module_path($this->moduleName, 'resources/views'), $this->moduleNameLower);
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));

        Route::middleware('web')
            ->group(module_path($this->moduleName, 'routes/web.php'));

        Livewire::component('services::services-index', ServicesIndex::class);
        Livewire::component('services::service-detail', ServiceDetail::class);
    }
}

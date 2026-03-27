<?php

declare(strict_types=1);

namespace Modules\Services\App\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Services\App\Models\Service;

#[Layout('layouts.app')]
final class ServicesIndex extends Component
{
    public function render(): View
    {
        $services = Service::where('status', 'published')
            ->orderBy('sort_order')
            ->get();

        view()->share('seoTitle', __('Services'));
        view()->share('seoDescription', __('Our services.'));

        return view('services::livewire.services-index', compact('services'));
    }
}

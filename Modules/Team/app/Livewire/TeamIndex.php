<?php

declare(strict_types=1);

namespace Modules\Team\App\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Team\App\Models\TeamMember;

#[Layout('layouts.app')]
final class TeamIndex extends Component
{
    public function render(): View
    {
        $members = TeamMember::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        view()->share('seoTitle', __('Our Team'));
        view()->share('seoDescription', __('Meet the people behind our work.'));

        return view('team::livewire.team-index', compact('members'));
    }
}

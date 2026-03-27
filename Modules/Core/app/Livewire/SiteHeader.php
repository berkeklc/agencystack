<?php

declare(strict_types=1);

namespace Modules\Core\App\Livewire;

use Illuminate\View\View;
use Livewire\Component;
use Modules\Core\App\Enums\LayoutType;
use Modules\Core\App\Models\Layout;
use Modules\Core\App\Models\Menu;
use Modules\Core\App\Settings\GeneralSettings;

final class SiteHeader extends Component
{
    public function render(): View
    {
        // Always fetch fresh — no Livewire computed caching so admin changes reflect immediately.
        $layout = Layout::where('type', LayoutType::Header->value)->where('is_active', true)->first();
        $settings = app(GeneralSettings::class);
        $primaryMenu = Menu::where('location', 'primary')->first();

        // Resolve logo: prefer MediaLibrary image, then text logo, then site name.
        $logoMedia = $layout?->getFirstMediaUrl('logo');
        $logoUrl = $logoMedia ?: null;
        $logoAlt = $logoUrl
            ? (collect($layout?->rows ?? [])->firstWhere('type', 'logo')['data']['alt'] ?? $settings->site_name)
            : null;

        $ctaRow = collect($layout?->rows ?? [])->firstWhere('type', 'cta_button');

        return view('core::livewire.site-header', compact(
            'layout',
            'settings',
            'primaryMenu',
            'logoUrl',
            'logoAlt',
            'ctaRow',
        ));
    }
}

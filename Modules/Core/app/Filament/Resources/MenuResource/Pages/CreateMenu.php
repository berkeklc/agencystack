<?php

declare(strict_types=1);

namespace Modules\Core\App\Filament\Resources\MenuResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Core\App\Filament\Resources\MenuResource;

final class CreateMenu extends CreateRecord
{
    protected static string $resource = MenuResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

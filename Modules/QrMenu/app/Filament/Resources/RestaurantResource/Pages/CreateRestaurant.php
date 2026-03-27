<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Filament\Resources\RestaurantResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\QrMenu\App\Filament\Resources\RestaurantResource;

final class CreateRestaurant extends CreateRecord
{
    protected static string $resource = RestaurantResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

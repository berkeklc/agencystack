<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Filament\Resources\RestaurantResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\QrMenu\App\Filament\Resources\RestaurantResource;

final class ListRestaurants extends ListRecords
{
    protected static string $resource = RestaurantResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}

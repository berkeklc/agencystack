<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Filament\Resources\RestaurantResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\QrMenu\App\Filament\Resources\RestaurantResource;

final class EditRestaurant extends EditRecord
{
    protected static string $resource = RestaurantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view_menu')
                ->label('View public menu')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn () => RestaurantResource::publicMenuUrl($this->record), true)
                ->color('gray'),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

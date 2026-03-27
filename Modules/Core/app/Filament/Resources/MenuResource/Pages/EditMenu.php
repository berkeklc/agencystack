<?php

declare(strict_types=1);

namespace Modules\Core\App\Filament\Resources\MenuResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Core\App\Filament\Resources\MenuResource;

final class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview_site')
                ->label('Preview site')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(config('app.url'), true)
                ->color('gray'),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

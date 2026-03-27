<?php

declare(strict_types=1);

namespace Modules\Core\App\Filament\Resources\PageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Core\App\Filament\Resources\PageResource;

final class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label('Preview page')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn () => $this->record->is_home
                    ? config('app.url')
                    : config('app.url').'/'.$this->record->slug, true)
                ->color('gray'),
            Actions\DeleteAction::make(),
        ];
    }
}

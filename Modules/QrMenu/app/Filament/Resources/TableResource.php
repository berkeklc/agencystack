<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Modules\QrMenu\App\Actions\GenerateTableQrCode;
use Modules\QrMenu\App\Filament\Resources\TableResource\Pages;
use Modules\QrMenu\App\Models\MenuTable;
use Modules\QrMenu\App\Models\Restaurant;

final class TableResource extends Resource
{
    protected static ?string $model = MenuTable::class;

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static ?string $navigationGroup = 'QR Menu';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'QR Table';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('restaurant_id')
                ->label('Restaurant')
                ->options(self::restaurantOptions())
                ->required()
                ->searchable()
                ->default(Restaurant::value('id')),

            Forms\Components\TextInput::make('name')
                ->label('Table Name / Number')
                ->required()
                ->maxLength(100),

            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('restaurant.name')
                    ->formatStateUsing(fn ($record) => $record->restaurant?->getTranslation('name', app()->getLocale())),
                Tables\Columns\IconColumn::make('qr_code_path')
                    ->icon(fn (?string $state): string => $state ? 'heroicon-o-check-circle' : 'heroicon-o-clock')
                    ->color(fn (?string $state): string => $state ? 'success' : 'gray')
                    ->label('QR Generated'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('restaurant_id')
                    ->label('Restaurant')
                    ->relationship('restaurant', 'name'),
            ])
            ->actions([
                Tables\Actions\Action::make('generate_qr')
                    ->label('Generate QR')
                    ->icon('heroicon-o-qr-code')
                    ->color('success')
                    ->action(function (MenuTable $record): void {
                        $url = app(GenerateTableQrCode::class)->execute($record);
                        Notification::make()
                            ->title('QR Code generated')
                            ->body('QR Code saved to: '.$url)
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('view_qr')
                    ->label('View QR')
                    ->icon('heroicon-o-eye')
                    ->url(fn (MenuTable $record): ?string => $record->qr_code_path)
                    ->openUrlInNewTab()
                    ->visible(fn (MenuTable $record): bool => filled($record->qr_code_path)),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('generate_all_qr')
                    ->label('Generate QR Codes')
                    ->icon('heroicon-o-qr-code')
                    ->action(function (Collection $records): void {
                        $generator = app(GenerateTableQrCode::class);
                        $records->each(fn (MenuTable $table) => $generator->execute($table));
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTables::route('/'),
            'create' => Pages\CreateTable::route('/create'),
            'edit' => Pages\EditTable::route('/{record}/edit'),
        ];
    }

    /** @return array<int|string, string> */
    private static function restaurantOptions(): array
    {
        $locale = app()->getLocale();

        return Restaurant::all()
            ->mapWithKeys(fn (Restaurant $r) => [
                $r->id => $r->getTranslation('name', $locale, useFallbackLocale: true),
            ])
            ->toArray();
    }
}

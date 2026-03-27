<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\QrMenu\App\Filament\Resources\MenuCategoryResource\Pages;
use Modules\QrMenu\App\Models\MenuCategory;
use Modules\QrMenu\App\Models\Restaurant;

final class MenuCategoryResource extends Resource
{
    protected static ?string $model = MenuCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'QR Menu';

    protected static ?string $navigationLabel = 'Menu Categories';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('restaurant_id')
                ->label('Restaurant')
                ->options(self::restaurantOptions())
                ->required()
                ->searchable()
                ->default(self::firstRestaurantId())
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')
                        ->label('Restaurant name')
                        ->required(),
                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required(),
                ])
                ->createOptionUsing(function (array $data): int {
                    $restaurant = Restaurant::create([
                        'name' => ['tr' => $data['name'], 'en' => $data['name']],
                        'slug' => $data['slug'],
                        'is_active' => true,
                    ]);

                    return $restaurant->id;
                })
                ->helperText(Restaurant::count() === 0
                    ? '⚠ No restaurants yet — create one first under QR Menu → Restaurants.'
                    : null
                ),

            Forms\Components\TextInput::make('name')
                ->label('Category name')
                ->required()
                ->maxLength(255)
                ->helperText('Translatable — save and edit to add other languages.'),

            Forms\Components\Textarea::make('description')
                ->label('Description')
                ->rows(2)
                ->columnSpanFull(),

            Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                ->collection('image')
                ->label('Category image')
                ->image()
                ->imageEditor(),

            Forms\Components\TextInput::make('sort_order')
                ->label('Sort order')
                ->numeric()
                ->default(0),

            Forms\Components\Toggle::make('is_active')
                ->label('Active')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->collection('image')
                    ->label('Image')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Category')
                    ->searchable()
                    ->formatStateUsing(fn ($record) => $record->getTranslation('name', app()->getLocale())),

                Tables\Columns\TextColumn::make('restaurant.name')
                    ->label('Restaurant')
                    ->formatStateUsing(fn ($record) => $record->restaurant?->getTranslation('name', app()->getLocale()))
                    ->sortable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items')
                    ->badge(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('restaurant_id')
                    ->label('Restaurant')
                    ->options(self::restaurantOptions()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuCategories::route('/'),
            'create' => Pages\CreateMenuCategory::route('/create'),
            'edit' => Pages\EditMenuCategory::route('/{record}/edit'),
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

    private static function firstRestaurantId(): ?int
    {
        return Restaurant::value('id');
    }
}

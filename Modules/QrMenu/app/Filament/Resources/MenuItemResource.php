<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\QrMenu\App\Filament\Resources\MenuItemResource\Pages;
use Modules\QrMenu\App\Models\MenuCategory;
use Modules\QrMenu\App\Models\MenuItem;
use Modules\QrMenu\App\Models\Restaurant;

final class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'QR Menu';

    protected static ?string $navigationLabel = 'Menu Items';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        $locale = app()->getLocale();

        return $form->schema([
            Forms\Components\Section::make('Categorisation')
                ->schema([
                    Forms\Components\Select::make('restaurant_id')
                        ->label('Restaurant')
                        ->options(self::restaurantOptions())
                        ->required()
                        ->default(self::firstRestaurantId())
                        ->live()
                        ->afterStateUpdated(fn (Forms\Set $set) => $set('category_id', null))
                        ->helperText(Restaurant::count() === 0
                            ? '⚠ No restaurants yet — create one first under QR Menu → Restaurants.'
                            : null
                        ),

                    Forms\Components\Select::make('category_id')
                        ->label('Category')
                        ->options(fn (Forms\Get $get): array => self::categoryOptions((int) $get('restaurant_id')))
                        ->required()
                        ->searchable()
                        ->helperText('Select a restaurant first.'),
                ])
                ->columns(2),

            Forms\Components\Section::make('Item Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Item name')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('price')
                        ->label('Price')
                        ->numeric()
                        ->prefix('₺')
                        ->required()
                        ->default(0),

                    Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                        ->collection('image')
                        ->label('Photo')
                        ->image()
                        ->imageEditor(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Flags & Badges')
                ->schema([
                    Forms\Components\TagsInput::make('allergens')
                        ->label('Allergens')
                        ->placeholder('Add allergen…'),

                    Forms\Components\CheckboxList::make('badges')
                        ->label('Badges')
                        ->options([
                            'vegan' => '🌱 Vegan',
                            'vegetarian' => '🥦 Vegetarian',
                            'gluten_free' => '🌾 Gluten Free',
                            'spicy' => '🌶 Spicy',
                            'new' => '✨ New',
                            'popular' => '🔥 Popular',
                            'featured' => '⭐ Featured',
                        ])
                        ->columns(3),

                    Forms\Components\Toggle::make('is_featured')
                        ->label('Featured')
                        ->default(false),

                    Forms\Components\Toggle::make('is_available')
                        ->label('Available')
                        ->default(true),

                    Forms\Components\TextInput::make('sort_order')
                        ->label('Sort order')
                        ->numeric()
                        ->default(0),
                ])
                ->columns(2)
                ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        $locale = app()->getLocale();

        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->collection('image')
                    ->label('Photo')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Item')
                    ->searchable()
                    ->formatStateUsing(fn ($record) => $record->getTranslation('name', $locale)),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->formatStateUsing(fn ($record) => $record->category?->getTranslation('name', $locale)),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('TRY')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_available')
                    ->label('Available')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('restaurant_id')
                    ->label('Restaurant')
                    ->options(self::restaurantOptions()),

                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),

                Tables\Filters\TernaryFilter::make('is_available')
                    ->label('Available'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
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

    /** @return array<int|string, string> */
    private static function categoryOptions(int $restaurantId): array
    {
        if (! $restaurantId) {
            return [];
        }

        $locale = app()->getLocale();

        return MenuCategory::where('restaurant_id', $restaurantId)
            ->orderBy('sort_order')
            ->get()
            ->mapWithKeys(fn (MenuCategory $c) => [
                $c->id => $c->getTranslation('name', $locale, useFallbackLocale: true),
            ])
            ->toArray();
    }
}

<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\QrMenu\App\Filament\Resources\RestaurantResource\Pages;
use Modules\QrMenu\App\Models\Restaurant;

final class RestaurantResource extends Resource
{
    protected static ?string $model = Restaurant::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'QR Menu';

    protected static ?string $navigationLabel = 'Restaurants';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Basic Info')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->maxLength(255)
                        ->helperText('Translatable — fill in at least TR or EN.'),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(100)
                        ->helperText('Used in the public QR menu URL: /menu/{slug}/{table-id}'),

                    Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ])
                ->columns(2),

            Forms\Components\Section::make('Appearance')
                ->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('logo')
                        ->label('Logo')
                        ->collection('logo')
                        ->image()
                        ->imageEditor()
                        ->helperText('Displayed on the public QR menu page.'),

                    Forms\Components\ColorPicker::make('primary_color')
                        ->label('Primary colour')
                        ->default('#1a1a2e'),

                    Forms\Components\TextInput::make('currency')
                        ->label('Currency code')
                        ->default('TRY')
                        ->maxLength(10),
                ])
                ->columns(3),

            Forms\Components\Section::make('Working Hours')
                ->description('Optional — shown on the public menu page.')
                ->schema([
                    Forms\Components\KeyValue::make('working_hours')
                        ->label('')
                        ->keyLabel('Day')
                        ->valueLabel('Hours (e.g. 09:00–22:00)')
                        ->columnSpanFull(),
                ])
                ->collapsible()
                ->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('logo')
                    ->collection('logo')
                    ->label('Logo')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($record) => $record->getTranslation('name', app()->getLocale())),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->copyable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('categories_count')
                    ->counts('categories')
                    ->label('Categories')
                    ->badge(),

                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Menu items')
                    ->badge(),

                Tables\Columns\TextColumn::make('tables_count')
                    ->counts('tables')
                    ->label('Tables')
                    ->badge(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\Action::make('view_menu')
                    ->label('View public menu')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Restaurant $record) => self::publicMenuUrl($record), true)
                    ->color('gray'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRestaurants::route('/'),
            'create' => Pages\CreateRestaurant::route('/create'),
            'edit' => Pages\EditRestaurant::route('/{record}/edit'),
        ];
    }

    /**
     * Build the correct public menu URL for a restaurant.
     * The route is /menu/{restaurant:slug}/{table} — use the first active table,
     * or fall back to table ID 1 for the preview link.
     */
    public static function publicMenuUrl(Restaurant $record): string
    {
        $table = $record->tables()->where('is_active', true)->first();

        if ($table) {
            return route('qr-menu.public', [
                'restaurant' => $record->slug,
                'table' => $table->id,
            ]);
        }

        // No tables yet — link to the restaurant slug for awareness
        return config('app.url').'/menu/'.$record->slug.'/1';
    }
}

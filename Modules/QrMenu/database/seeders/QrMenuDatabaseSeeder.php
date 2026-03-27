<?php

declare(strict_types=1);

namespace Modules\QrMenu\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\QrMenu\App\Models\MenuCategory;
use Modules\QrMenu\App\Models\MenuItem;
use Modules\QrMenu\App\Models\MenuTable;
use Modules\QrMenu\App\Models\Restaurant;

final class QrMenuDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $restaurant = Restaurant::firstOrCreate(
            ['slug' => 'my-restaurant'],
            [
                'name' => ['tr' => 'Restoranım', 'en' => 'My Restaurant'],
                'description' => ['tr' => 'Lezzetli yemekler için doğru adres.', 'en' => 'The right place for delicious food.'],
                'currency' => 'TRY',
                'primary_color' => '#1a1a2e',
                'is_active' => true,
            ]
        );

        // Default tables
        foreach (['Table 1', 'Table 2', 'Table 3'] as $i => $tableName) {
            MenuTable::firstOrCreate(
                ['restaurant_id' => $restaurant->id, 'name' => $tableName],
                ['is_active' => true]
            );
        }

        // Default category
        $category = MenuCategory::firstOrCreate(
            ['restaurant_id' => $restaurant->id, 'sort_order' => 0],
            [
                'name' => ['tr' => 'Ana Yemekler', 'en' => 'Main Dishes'],
                'description' => ['tr' => 'Günün özel yemekleri', 'en' => 'Daily specials'],
                'is_active' => true,
            ]
        );

        // A couple of sample items
        $items = [
            ['tr' => 'Izgara Tavuk', 'en' => 'Grilled Chicken', 'price' => 180.00],
            ['tr' => 'Vegetable Stir Fry', 'en' => 'Vegetable Stir Fry', 'price' => 140.00],
        ];

        foreach ($items as $i => $item) {
            MenuItem::firstOrCreate(
                ['category_id' => $category->id, 'sort_order' => $i],
                [
                    'restaurant_id' => $restaurant->id,
                    'name' => ['tr' => $item['tr'], 'en' => $item['en']],
                    'price' => $item['price'],
                    'is_available' => true,
                    'is_featured' => $i === 0,
                ]
            );
        }

        $this->command?->info('QR Menu default restaurant, tables, categories and items seeded.');
    }
}

<?php

namespace Database\Seeders;

use App\Enums\CommodityCategory;
use App\Enums\CommodityUnit;
use App\Models\Commodity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CommoditySeeder extends Seeder
{
    public function run(): void
    {
        $commodities = [
            ['name' => 'Maize',          'category' => CommodityCategory::GRAINS,      'unit' => CommodityUnit::BAG,        'description' => '90kg bag of dry maize'],
            ['name' => 'Wheat',          'category' => CommodityCategory::GRAINS,      'unit' => CommodityUnit::BAG,        'description' => '90kg bag of wheat'],
            ['name' => 'Rice',           'category' => CommodityCategory::GRAINS,      'unit' => CommodityUnit::KG,         'description' => 'Rice per kg'],
            ['name' => 'Beans',          'category' => CommodityCategory::LEGUMES,     'unit' => CommodityUnit::KG,         'description' => 'Dry beans per kg'],
            ['name' => 'Green Grams',    'category' => CommodityCategory::LEGUMES,     'unit' => CommodityUnit::KG,         'description' => 'Green grams per kg'],
            ['name' => 'Tomatoes',       'category' => CommodityCategory::VEGETABLES,  'unit' => CommodityUnit::KG,         'description' => 'Fresh tomatoes per kg'],
            ['name' => 'Kale',           'category' => CommodityCategory::VEGETABLES,  'unit' => CommodityUnit::DOZEN,     'description' => 'Sukuma wiki per bundle'],
            ['name' => 'Potatoes',       'category' => CommodityCategory::VEGETABLES,  'unit' => CommodityUnit::BAG,        'description' => '50kg bag of potatoes'],
            ['name' => 'Carrots',        'category' => CommodityCategory::VEGETABLES,  'unit' => CommodityUnit::KG,         'description' => 'Fresh carrots per kg'],
            ['name' => 'Milk',           'category' => CommodityCategory::DAIRY,       'unit' => CommodityUnit::LITRE,      'description' => 'Fresh cow milk per litre'],
            ['name' => 'Avocado',        'category' => CommodityCategory::FRUITS,      'unit' => CommodityUnit::KG,         'description' => 'Fresh avocado per kg'],
            ['name' => 'Mango',          'category' => CommodityCategory::FRUITS,      'unit' => CommodityUnit::KG,         'description' => 'Fresh mango per kg'],
        ];

        foreach ($commodities as $data) {
            Commodity::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                array_merge($data, [
                    'slug'      => Str::slug($data['name']),
                    'is_active' => true,
                ])
            );
        }
    }
}
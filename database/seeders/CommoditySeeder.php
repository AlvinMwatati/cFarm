<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Commodity;

class CommoditySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commodities = [
            ['name' => 'Maize',       'category' => 'grains',     'unit' => 'bag',   'description' => '90kg bag of dry maize'],
            ['name' => 'Wheat',       'category' => 'grains',     'unit' => 'bag',   'description' => '90kg bag of wheat'],
            ['name' => 'Rice',        'category' => 'grains',     'unit' => 'kg',    'description' => 'Per kg of rice'],
            ['name' => 'Beans',       'category' => 'legumes',    'unit' => 'kg',    'description' => 'Per kg of dry beans'],
            ['name' => 'Green Grams', 'category' => 'legumes',    'unit' => 'kg',    'description' => 'Per kg of green grams'],
            ['name' => 'Potatoes',    'category' => 'tubers',     'unit' => 'bag',   'description' => '50kg bag of potatoes'],
            ['name' => 'Sweet Potato','category' => 'tubers',     'unit' => 'kg',    'description' => 'Per kg of sweet potatoes'],
            ['name' => 'Tomatoes',    'category' => 'vegetables', 'unit' => 'crate', 'description' => 'Crate of tomatoes'],
            ['name' => 'Kale',        'category' => 'vegetables', 'unit' => 'kg',    'description' => 'Per kg of kale (sukuma wiki)'],
            ['name' => 'Avocado',     'category' => 'fruits',     'unit' => 'dozen', 'description' => 'Per dozen avocados'],
            ['name' => 'Mango',       'category' => 'fruits',     'unit' => 'dozen', 'description' => 'Per dozen mangoes'],
            ['name' => 'Milk',        'category' => 'dairy',      'unit' => 'litre', 'description' => 'Per litre of fresh milk'],
        ];

        foreach ($commodities as $commodity) {
            Commodity::firstOrCreate(['name' => $commodity['name']], $commodity);
        }
    }
}

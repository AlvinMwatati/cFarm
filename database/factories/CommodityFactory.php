<?php

namespace Database\Factories;

use App\Models\Commodity;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use App\Enums\CommodityCategory;
use App\Enums\CommodityUnit;

/**
 * @extends Factory<Commodity>
 */
class CommodityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->word();
        return [
            'name'        => $name,
            'slug'        => Str::slug($name),
            'category'    => $this->faker->randomElement(CommodityCategory::cases())->value,
            'unit'        => $this->faker->randomElement(CommodityUnit::cases())->value,
            'description' => $this->faker->sentence(),
            'is_active'   => true,
        ];
    }

     public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}

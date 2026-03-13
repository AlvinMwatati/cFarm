<?php

namespace Database\Factories;

use App\Models\Listing;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Commodity;
use App\Enums\KenyaCounty;
use App\Enums\ListingStatus;

/**
 * @extends Factory<Listing>
 */
class ListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(10, 500);

        return [
            'user_id'                => User::factory(),
            'commodity_id'           => Commodity::factory(),
            'title'                  => $this->faker->sentence(4),
            'description'            => $this->faker->paragraph(),
            'price_per_unit'         => $this->faker->randomFloat(2, 100, 10000),
            'quantity_available'     => $quantity,
            'minimum_order_quantity' => $this->faker->numberBetween(1, $quantity),
            'county'                 => $this->faker->randomElement(KenyaCounty::cases())->value,
            'town'                   => $this->faker->city(),
            'status'                 => ListingStatus::ACTIVE->value,
        ];
    }

     public function inactive(): static
    {
        return $this->state(['status' => ListingStatus::INACTIVE->value]);
    }
}

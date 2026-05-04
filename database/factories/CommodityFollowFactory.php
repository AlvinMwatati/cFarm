<?php

namespace Database\Factories;

use App\Models\CommodityFollow;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Commodity;

/**
 * @extends Factory<CommodityFollow>
 */
class CommodityFollowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
            'user_id'                => User::factory(),
            'commodity_id'           => Commodity::factory(),
            'notify_price_drop'      => true,
            'notify_price_spike'     => true,
            'notify_new_listing'     => true,
            'notify_weekly_summary'  => true,
            'via_app'                => true,
            'via_email'              => true,
            'via_sms'                => false,
            'price_change_threshold' => 10.00,
        ];
    }
}

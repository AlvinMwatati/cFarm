<?php

namespace Tests\Feature;

use App\Enums\KenyaCounty;
use App\Enums\ListingStatus;
use App\Models\Commodity;
use App\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class InsightsTest extends TestCase
{
    use RefreshDatabase, CreatesUsers;

    #[Test]
    public function authenticated_user_can_view_insights_index(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
             ->get(route('insights.index'))
             ->assertOk();
    }

    #[Test]
    public function guest_cannot_view_insights(): void
    {
        $this->get(route('insights.index'))
             ->assertRedirect(route('login'));
    }

    #[Test]
    public function insights_show_page_displays_for_a_commodity(): void
    {
        $user      = $this->createUser();
        $commodity = Commodity::factory()->create();

        $this->actingAs($user)
             ->get(route('insights.show', $commodity))
             ->assertOk()
             ->assertSee($commodity->name);
    }

    #[Test]
    public function price_range_reflects_active_listings(): void
    {
        $user      = $this->createUser();
        $commodity = Commodity::factory()->create();

        Listing::factory()->create([
            'commodity_id'   => $commodity->id,
            'price_per_unit' => 2000,
            'status'         => ListingStatus::ACTIVE->value,
        ]);

        Listing::factory()->create([
            'commodity_id'   => $commodity->id,
            'price_per_unit' => 5000,
            'status'         => ListingStatus::ACTIVE->value,
        ]);

        // Inactive listing should NOT affect range
        Listing::factory()->create([
            'commodity_id'   => $commodity->id,
            'price_per_unit' => 99999,
            'status'         => ListingStatus::INACTIVE->value,
        ]);

        $this->actingAs($user)
             ->get(route('insights.show', $commodity))
             ->assertOk()
             ->assertSee('2,000.00')
             ->assertSee('5,000.00')
             ->assertDontSee('99,999.00');
    }

    #[Test]
    public function county_averages_are_calculated_correctly(): void
    {
        $user      = $this->createUser();
        $commodity = Commodity::factory()->create();

        // Two Nairobi listings averaging 3000
        Listing::factory()->create([
            'commodity_id'   => $commodity->id,
            'county'         => KenyaCounty::NAIROBI->value,
            'price_per_unit' => 2000,
            'status'         => ListingStatus::ACTIVE->value,
        ]);

        Listing::factory()->create([
            'commodity_id'   => $commodity->id,
            'county'         => KenyaCounty::NAIROBI->value,
            'price_per_unit' => 4000,
            'status'         => ListingStatus::ACTIVE->value,
        ]);

        $this->actingAs($user)
             ->get(route('insights.show', $commodity))
             ->assertOk()
             ->assertSee('Nairobi')
             ->assertSee('3,000.00');
    }

    #[Test]
    public function most_listed_commodities_appear_on_insights_index(): void
    {
        $user      = $this->createUser();
        $commodity = Commodity::factory()->create(['name' => 'Maize']);

        Listing::factory()->count(5)->create([
            'commodity_id' => $commodity->id,
            'status'       => ListingStatus::ACTIVE->value,
        ]);

        $this->actingAs($user)
             ->get(route('insights.index'))
             ->assertOk()
             ->assertSee('Maize');
    }

    #[Test]
    public function inactive_listings_do_not_appear_in_insights(): void
    {
        $user      = $this->createUser();
        $commodity = Commodity::factory()->create();

        Listing::factory()->create([
            'commodity_id'   => $commodity->id,
            'price_per_unit' => 99999,
            'status'         => ListingStatus::INACTIVE->value,
        ]);

        $response = $this->actingAs($user)
             ->get(route('insights.show', $commodity));

        $response->assertOk();
        $response->assertDontSee('99,999.00');
    }
}

<?php

namespace Tests\Feature;

use App\Enums\CommodityCategory;
use App\Enums\KenyaCounty;
use App\Enums\ListingStatus;
use App\Models\Commodity;
use App\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class MarketplaceTest extends TestCase
{
    use RefreshDatabase, CreatesUsers;

    // ─── Visibility ───────────────────────────────────────────────────────────

    /** @test */
    public function marketplace_shows_only_active_listings(): void
    {
        $user = $this->createUser();

       $active   = Listing::factory()->create([
        'title'  => 'Active Listing',
        'status' => ListingStatus::ACTIVE->value,
    ]);
    $inactive = Listing::factory()->create([
        'title'  => 'Inactive Listing',
        'status' => ListingStatus::INACTIVE->value,
    ]);

        $this->actingAs($user)
             ->get(route('listings.index'))
             ->assertSee('Active Listing')
             ->assertDontSee('Inactive Listing');
    }

    // ─── Search ───────────────────────────────────────────────────────────────

    /** @test */
    public function user_can_search_listings_by_keyword(): void
    {
        $user = $this->createUser();

        $matching    = Listing::factory()->create(['title' => 'Fresh Maize From Eldoret']);
        $notMatching = Listing::factory()->create(['title' => 'Tomatoes For Sale']);

        $this->actingAs($user)
             ->get(route('listings.index', ['search' => 'Maize']))
             ->assertSee($matching->title)
             ->assertDontSee($notMatching->title);
    }

    // ─── Filters ──────────────────────────────────────────────────────────────

    /** @test */
    public function user_can_filter_listings_by_commodity(): void
    {
        $user = $this->createUser();

        $maize    = Commodity::factory()->create(['name' => 'Maize']);
        $tomatoes = Commodity::factory()->create(['name' => 'Tomatoes']);

        $maizeListing    = Listing::factory()->create(['commodity_id' => $maize->id]);
        $tomatoListing   = Listing::factory()->create(['commodity_id' => $tomatoes->id]);

        $this->actingAs($user)
             ->get(route('listings.index', ['commodity_id' => $maize->id]))
             ->assertSee($maizeListing->title)
             ->assertDontSee($tomatoListing->title);
    }

    /** @test */
    public function user_can_filter_listings_by_category(): void
    {
        $user = $this->createUser();

        $grain     = Commodity::factory()->create(['category' => CommodityCategory::GRAINS->value]);
        $vegetable = Commodity::factory()->create(['category' => CommodityCategory::VEGETABLES->value]);

        $grainListing     = Listing::factory()->create(['commodity_id' => $grain->id]);
        $vegetableListing = Listing::factory()->create(['commodity_id' => $vegetable->id]);

        $this->actingAs($user)
             ->get(route('listings.index', ['category' => CommodityCategory::GRAINS->value]))
             ->assertSee($grainListing->title)
             ->assertDontSee($vegetableListing->title);
    }

    /** @test */
    public function user_can_filter_listings_by_county(): void
    {
        $user = $this->createUser();

        $nairobiListing = Listing::factory()->create(['county' => KenyaCounty::NAIROBI->value]);
        $nakuruListing  = Listing::factory()->create(['county' => KenyaCounty::NAKURU->value]);

        $this->actingAs($user)
             ->get(route('listings.index', ['county' => KenyaCounty::NAIROBI->value]))
             ->assertSee($nairobiListing->title)
             ->assertDontSee($nakuruListing->title);
    }

    /** @test */
    public function user_can_filter_listings_by_price_range(): void
    {
        $user = $this->createUser();

        $cheap     = Listing::factory()->create(['price_per_unit' => 500]);
        $expensive = Listing::factory()->create(['price_per_unit' => 9000]);

        $this->actingAs($user)
             ->get(route('listings.index', ['min_price' => 100, 'max_price' => 1000]))
             ->assertSee($cheap->title)
             ->assertDontSee($expensive->title);
    }

    // ─── Sorting ──────────────────────────────────────────────────────────────

    /** @test */
    public function listings_can_be_sorted_by_price_ascending(): void
    {
        $user = $this->createUser();

        $expensive = Listing::factory()->create(['price_per_unit' => 9000, 'title' => 'Expensive']);
        $cheap     = Listing::factory()->create(['price_per_unit' => 500,  'title' => 'Cheap']);

        $response = $this->actingAs($user)
             ->get(route('listings.index', ['sort' => 'price_asc']));

        // Cheap should appear before expensive in the response
        $content  = $response->getContent();
        $cheapPos = strpos($content, 'Cheap');
        $expPos   = strpos($content, 'Expensive');

        $this->assertLessThan($expPos, $cheapPos);
    }

    /** @test */
    public function listings_can_be_sorted_by_price_descending(): void
    {
        $user = $this->createUser();

        $expensive = Listing::factory()->create(['price_per_unit' => 9000, 'title' => 'Expensive']);
        $cheap     = Listing::factory()->create(['price_per_unit' => 500,  'title' => 'Cheap']);

        $response = $this->actingAs($user)
             ->get(route('listings.index', ['sort' => 'price_desc']));

        $content  = $response->getContent();
        $cheapPos = strpos($content, 'Cheap');
        $expPos   = strpos($content, 'Expensive');

        $this->assertLessThan($cheapPos, $expPos);
    }

    // ─── Combined Filters ─────────────────────────────────────────────────────

    /** @test */
    public function user_can_combine_multiple_filters(): void
    {
        $user = $this->createUser();

        $maize = Commodity::factory()->create(['name' => 'Maize']);

        $match = Listing::factory()->create([
            'commodity_id'   => $maize->id,
            'county'         => KenyaCounty::NAIROBI->value,
            'price_per_unit' => 3500,
            'title'          => 'Nairobi Maize Listing',
        ]);

        $noMatch = Listing::factory()->create([
            'commodity_id'   => $maize->id,
            'county'         => KenyaCounty::NAKURU->value, // wrong county
            'price_per_unit' => 3500,
            'title'          => 'Nakuru Maize Listing',
        ]);

        $this->actingAs($user)
             ->get(route('listings.index', [
                 'commodity_id' => $maize->id,
                 'county'       => KenyaCounty::NAIROBI->value,
             ]))
             ->assertSee($match->title)
             ->assertDontSee($noMatch->title);
    }
}

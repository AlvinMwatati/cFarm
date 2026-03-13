<?php

namespace Tests\Feature;

use App\Enums\KenyaCounty;
use App\Enums\ListingStatus;
use App\Models\Commodity;
use App\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class ListingTest extends TestCase
{
    use RefreshDatabase, CreatesUsers;

    // ─── Viewing ──────────────────────────────────────────────────────────────

    /** @test */
    public function any_authenticated_user_can_view_listings(): void
    {
        $user = $this->createUser();
        Listing::factory()->count(3)->create();

        $this->actingAs($user)
             ->get(route('listings.index'))
             ->assertOk();
    }

    /** @test */
    public function guest_cannot_view_listings(): void
    {
        $this->get(route('listings.index'))
             ->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_view_a_single_listing(): void
    {
        $user    = $this->createUser();
        $listing = Listing::factory()->create();

        $this->actingAs($user)
             ->get(route('listings.show', $listing))
             ->assertOk()
             ->assertSee($listing->title);
    }

    // ─── Creating ─────────────────────────────────────────────────────────────

    /** @test */
    public function authenticated_user_can_create_a_listing(): void
    {
        $user      = $this->createUser();
        $commodity = Commodity::factory()->create();

        $this->actingAs($user)
             ->post(route('listings.store'), [
                 'commodity_id'           => $commodity->id,
                 'title'                  => '200 bags of fresh maize',
                 'description'            => 'Freshly harvested dry maize',
                 'price_per_unit'         => 3500,
                 'quantity_available'     => 200,
                 'minimum_order_quantity' => 10,
                 'county'                 => KenyaCounty::NAIROBI->value,
                 'town'                   => 'Westlands',
             ])
             ->assertRedirect(route('listings.index'));

        $this->assertDatabaseHas('listings', [
            'title'   => '200 bags of fresh maize',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function guest_cannot_create_a_listing(): void
    {
        $commodity = Commodity::factory()->create();

        $this->post(route('listings.store'), [
                 'commodity_id'           => $commodity->id,
                 'title'                  => '200 bags of fresh maize',
                 'price_per_unit'         => 3500,
                 'quantity_available'     => 200,
                 'minimum_order_quantity' => 10,
                 'county'                 => KenyaCounty::NAIROBI->value,
             ])
             ->assertRedirect(route('login'));
    }

    // ─── Validation ───────────────────────────────────────────────────────────

    /** @test */
    public function listing_requires_all_required_fields(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
             ->post(route('listings.store'), [])
             ->assertSessionHasErrors([
                 'commodity_id',
                 'title',
                 'price_per_unit',
                 'quantity_available',
                 'minimum_order_quantity',
                 'county',
             ]);
    }

    /** @test */
    public function minimum_order_cannot_exceed_quantity_available(): void
    {
        $user      = $this->createUser();
        $commodity = Commodity::factory()->create();

        $this->actingAs($user)
             ->post(route('listings.store'), [
                 'commodity_id'           => $commodity->id,
                 'title'                  => 'Test listing',
                 'price_per_unit'         => 3500,
                 'quantity_available'     => 10,
                 'minimum_order_quantity' => 50, // exceeds quantity
                 'county'                 => KenyaCounty::NAIROBI->value,
             ])
             ->assertSessionHasErrors('minimum_order_quantity');
    }

    /** @test */
    public function county_must_be_a_valid_kenya_county(): void
    {
        $user      = $this->createUser();
        $commodity = Commodity::factory()->create();

        $this->actingAs($user)
             ->post(route('listings.store'), [
                 'commodity_id'           => $commodity->id,
                 'title'                  => 'Test listing',
                 'price_per_unit'         => 3500,
                 'quantity_available'     => 100,
                 'minimum_order_quantity' => 10,
                 'county'                 => 'Wakanda', // not a real county
             ])
             ->assertSessionHasErrors('county');
    }

    // ─── Images ───────────────────────────────────────────────────────────────

    /** @test */
    public function user_can_upload_images_with_a_listing(): void
    {
        Storage::fake('public');

        $user      = $this->createUser();
        $commodity = Commodity::factory()->create();

        $this->actingAs($user)
             ->post(route('listings.store'), [
                 'commodity_id'           => $commodity->id,
                 'title'                  => 'Listing with images',
                 'price_per_unit'         => 3500,
                 'quantity_available'     => 100,
                 'minimum_order_quantity' => 10,
                 'county'                 => KenyaCounty::NAIROBI->value,
                 'images'                 => [
                     UploadedFile::fake()->image('produce1.jpg'),
                     UploadedFile::fake()->image('produce2.jpg'),
                 ],
             ])
             ->assertRedirect(route('listings.index'));

        $listing = Listing::where('title', 'Listing with images')->first();
        $this->assertCount(2, $listing->getMedia('images'));
    }

    /** @test */
    public function listing_cannot_have_more_than_five_images(): void
    {
        $user      = $this->createUser();
        $commodity = Commodity::factory()->create();

        $this->actingAs($user)
             ->post(route('listings.store'), [
                 'commodity_id'           => $commodity->id,
                 'title'                  => 'Too many images',
                 'price_per_unit'         => 3500,
                 'quantity_available'     => 100,
                 'minimum_order_quantity' => 10,
                 'county'                 => KenyaCounty::NAIROBI->value,
                 'images'                 => [
                     UploadedFile::fake()->image('p1.jpg'),
                     UploadedFile::fake()->image('p2.jpg'),
                     UploadedFile::fake()->image('p3.jpg'),
                     UploadedFile::fake()->image('p4.jpg'),
                     UploadedFile::fake()->image('p5.jpg'),
                     UploadedFile::fake()->image('p6.jpg'), // 6th image
                 ],
             ])
             ->assertSessionHasErrors('images');
    }

    // ─── Toggle Status ────────────────────────────────────────────────────────

    /** @test */
    public function owner_can_toggle_listing_status(): void
    {
        $user    = $this->createUser();
        $listing = Listing::factory()->create([
            'user_id' => $user->id,
            'status'  => ListingStatus::ACTIVE->value,
        ]);

        $this->actingAs($user)
             ->patch(route('listings.toggle-status', $listing))
             ->assertRedirect();

        $this->assertDatabaseHas('listings', [
            'id'     => $listing->id,
            'status' => ListingStatus::INACTIVE->value,
        ]);
    }

    /** @test */
    public function non_owner_cannot_toggle_listing_status(): void
    {
        $owner     = $this->createUser();
        $otherUser = $this->createUser();

        $listing = Listing::factory()->create([
            'user_id' => $owner->id,
            'status'  => ListingStatus::ACTIVE->value,
        ]);

        $this->actingAs($otherUser)
             ->patch(route('listings.toggle-status', $listing))
             ->assertForbidden();
    }

    /** @test */
    public function admin_can_toggle_any_listing_status(): void
    {
        $admin   = $this->createAdmin();
        $listing = Listing::factory()->create([
            'status' => ListingStatus::ACTIVE->value,
        ]);

        $this->actingAs($admin)
             ->patch(route('listings.toggle-status', $listing))
             ->assertRedirect();

        $this->assertDatabaseHas('listings', [
            'id'     => $listing->id,
            'status' => ListingStatus::INACTIVE->value,
        ]);
    }

    // ─── My Listings ──────────────────────────────────────────────────────────

    /** @test */
    public function user_can_only_see_their_own_listings_on_my_listings_page(): void
    {
        $user      = $this->createUser();
        $otherUser = $this->createUser();

        $myListing    = Listing::factory()->create(['user_id' => $user->id]);
        $otherListing = Listing::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($user)
             ->get(route('listings.mine'))
             ->assertOk()
             ->assertSee($myListing->title)
             ->assertDontSee($otherListing->title);
    }
}

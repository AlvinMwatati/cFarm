<?php

namespace Tests\Feature;

use App\Enums\KenyaCounty;
use App\Models\Commodity;
use App\Models\CommodityFollow;
use App\Models\Listing;
use App\Notifications\NewListingAlert;
use App\Notifications\PriceDropAlert;
use App\Notifications\PriceSpikeAlert;
use App\Services\Notifications\NewListingNotificationService;
use App\Services\Notifications\PriceAlertService;
use Database\Factories\CommodityFollowFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class NotificationTest extends TestCase
{
    use RefreshDatabase, CreatesUsers;

    #[Test]
    public function user_can_follow_a_commodity(): void
    {
        $user      = $this->createUser();
        $commodity = Commodity::factory()->create();

        $this->actingAs($user)
             ->post(route('commodities.follow', $commodity))
             ->assertRedirect();

        $this->assertDatabaseHas('commodity_follows', [
            'user_id'      => $user->id,
            'commodity_id' => $commodity->id,
        ]);
    }

    #[Test]
    public function user_can_unfollow_a_commodity(): void
    {
        $user      = $this->createUser();
        $commodity = Commodity::factory()->create();

        CommodityFollow::factory()->create([
            'user_id'      => $user->id,
            'commodity_id' => $commodity->id,
        ]);

        $this->actingAs($user)
             ->delete(route('commodities.unfollow', $commodity))
             ->assertRedirect();

        $this->assertDatabaseMissing('commodity_follows', [
            'user_id'      => $user->id,
            'commodity_id' => $commodity->id,
        ]);
    }

    #[Test]
    public function price_drop_alert_is_sent_to_followers(): void
    {
        Notification::fake();

        $user      = $this->createUser();
        $commodity = Commodity::factory()->create();

        CommodityFollow::factory()->create([
            'user_id'                => $user->id,
            'commodity_id'           => $commodity->id,
            'notify_price_drop'      => true,
            'price_change_threshold' => 5,
        ]);

        $service = new PriceAlertService();
        $service->checkAndNotify($commodity, 100, 80, KenyaCounty::NAIROBI); // 20% drop

        Notification::assertSentTo($user, PriceDropAlert::class);
    }

    #[Test]
    public function price_spike_alert_is_sent_to_followers(): void
    {
        Notification::fake();

        $user      = $this->createUser();
        $commodity = Commodity::factory()->create();

        CommodityFollow::factory()->create([
            'user_id'                => $user->id,
            'commodity_id'           => $commodity->id,
            'notify_price_spike'     => true,
            'price_change_threshold' => 5,
        ]);

        $service = new PriceAlertService();
        $service->checkAndNotify($commodity, 100, 130, KenyaCounty::NAIROBI); // 30% spike

        Notification::assertSentTo($user, PriceSpikeAlert::class);
    }

    #[Test]
    public function no_alert_sent_when_change_below_threshold(): void
    {
        Notification::fake();

        $user      = $this->createUser();
        $commodity = Commodity::factory()->create();

        CommodityFollow::factory()->create([
            'user_id'                => $user->id,
            'commodity_id'           => $commodity->id,
            'notify_price_drop'      => true,
            'price_change_threshold' => 20, // 20% threshold
        ]);

        $service = new PriceAlertService();
        $service->checkAndNotify($commodity, 100, 92, KenyaCounty::NAIROBI); // only 8% drop

        Notification::assertNotSentTo($user, PriceDropAlert::class);
    }

    #[Test]
    public function new_listing_alert_sent_to_followers(): void
    {
        Notification::fake();

        $seller    = $this->createUser();
        $follower  = $this->createUser();
        $commodity = Commodity::factory()->create();

        CommodityFollow::factory()->create([
            'user_id'           => $follower->id,
            'commodity_id'      => $commodity->id,
            'notify_new_listing' => true,
        ]);

        $listing = Listing::factory()->create([
            'user_id'      => $seller->id,
            'commodity_id' => $commodity->id,
        ]);

        $listing->load('commodity', 'user');

        $service = new NewListingNotificationService();
        $service->notify($listing);

        Notification::assertSentTo($follower, NewListingAlert::class);
        Notification::assertNotSentTo($seller, NewListingAlert::class);
    }

    #[Test]
    public function user_can_view_notifications_page(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
             ->get(route('notifications.index'))
             ->assertOk();
    }

    #[Test]
    public function user_can_view_their_follows(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
             ->get(route('follows.index'))
             ->assertOk();
    }
}

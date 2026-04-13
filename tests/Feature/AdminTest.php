<?php

namespace Tests\Feature;

use App\Models\Commodity;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class AdminTest extends TestCase
{
    use RefreshDatabase, CreatesUsers;

    #[Test]
    public function admin_can_access_dashboard(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
             ->get(route('admin.dashboard'))
             ->assertOk();
    }

    #[Test]
    public function regular_user_cannot_access_admin(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
             ->get(route('admin.dashboard'))
             ->assertForbidden();
    }

    #[Test]
    public function guest_cannot_access_admin(): void
    {
        $this->get(route('admin.dashboard'))
             ->assertRedirect(route('login'));
    }

    #[Test]
    public function admin_can_view_users(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
             ->get(route('admin.users'))
             ->assertOk();
    }

    #[Test]
    public function admin_can_ban_a_user(): void
    {
        $admin = $this->createAdmin();
        $user  = $this->createUser();

        $this->actingAs($admin)
             ->patch(route('admin.users.ban', $user))
             ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id'        => $user->id,
            'is_banned' => true,
        ]);
    }

    #[Test]
    public function admin_can_unban_a_user(): void
    {
        $admin = $this->createAdmin();
        $user  = $this->createUser();
        $user->update(['is_banned' => true]);

        $this->actingAs($admin)
             ->patch(route('admin.users.unban', $user))
             ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id'        => $user->id,
            'is_banned' => false,
        ]);
    }

    #[Test]
    public function admin_cannot_ban_another_admin(): void
    {
        $admin      = $this->createAdmin();
        $otherAdmin = $this->createAdmin();

        $this->actingAs($admin)
             ->patch(route('admin.users.ban', $otherAdmin))
             ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id'        => $otherAdmin->id,
            'is_banned' => false,
        ]);
    }

    #[Test]
    public function admin_can_toggle_listing_status(): void
    {
        $admin   = $this->createAdmin();
        $listing = Listing::factory()->create(['status' => 'active']);

        $this->actingAs($admin)
             ->patch(route('admin.listings.toggle', $listing))
             ->assertRedirect();

        $this->assertDatabaseHas('listings', [
            'id'     => $listing->id,
            'status' => 'inactive',
        ]);
    }

    #[Test]
    public function admin_can_delete_a_listing(): void
    {
        $admin   = $this->createAdmin();
        $listing = Listing::factory()->create();

        $this->actingAs($admin)
             ->delete(route('admin.listings.destroy', $listing))
             ->assertRedirect();

        $this->assertDatabaseMissing('listings', ['id' => $listing->id]);
    }

    #[Test]
    public function admin_can_toggle_commodity_active_status(): void
    {
        $admin     = $this->createAdmin();
        $commodity = Commodity::factory()->create(['is_active' => true]);

        $this->actingAs($admin)
             ->patch(route('admin.commodities.toggle', $commodity))
             ->assertRedirect();

        $this->assertDatabaseHas('commodities', [
            'id'        => $commodity->id,
            'is_active' => false,
        ]);
    }

    #[Test]
    public function banned_user_cannot_login(): void
    {
        $user = $this->createUser();
        $user->update(['is_banned' => true]);

        $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');
    }
}

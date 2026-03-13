<?php

namespace Tests\Feature;

use App\Enums\CommodityCategory;
use App\Enums\CommodityUnit;
use App\Models\Commodity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class CommodityTest extends TestCase
{
    use RefreshDatabase, CreatesUsers;

    // ─── Viewing ─────────────────────────────────────────────────────────────

    /** @test */
    public function any_authenticated_user_can_view_commodities(): void
    {
        $user = $this->createUser();
        Commodity::factory()->count(3)->create();

        $this->actingAs($user)
             ->get(route('commodities.index'))
             ->assertOk();
    }

    /** @test */
    public function guest_cannot_view_commodities(): void
    {
        $this->get(route('commodities.index'))
             ->assertRedirect(route('login'));
    }

    /** @test */
    public function any_authenticated_user_can_view_a_single_commodity(): void
    {
        $user      = $this->createUser();
        $commodity = Commodity::factory()->create();

        $this->actingAs($user)
             ->get(route('commodities.show', $commodity))
             ->assertOk()
             ->assertSee($commodity->name);
    }

    // ─── Creating ─────────────────────────────────────────────────────────────

    /** @test */
    public function admin_can_view_create_commodity_form(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
             ->get(route('commodities.create'))
             ->assertOk();
    }

    /** @test */
    public function non_admin_cannot_view_create_commodity_form(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
             ->get(route('commodities.create'))
             ->assertForbidden();
    }

    /** @test */
    public function admin_can_create_a_commodity(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
             ->post(route('commodities.store'), [
                 'name'        => 'Maize',
                 'category'    => CommodityCategory::GRAINS->value,
                 'unit'        => CommodityUnit::BAG->value,
                 'description' => 'A bag of dry maize',
             ])
             ->assertRedirect(route('commodities.index'));

        $this->assertDatabaseHas('commodities', ['name' => 'Maize']);
    }

    /** @test */
    public function non_admin_cannot_create_a_commodity(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
             ->post(route('commodities.store'), [
                 'name'     => 'Maize',
                 'category' => CommodityCategory::GRAINS->value,
                 'unit'     => CommodityUnit::BAG->value,
             ])
             ->assertForbidden();

        $this->assertDatabaseMissing('commodities', ['name' => 'Maize']);
    }

    // ─── Validation ───────────────────────────────────────────────────────────

    /** @test */
    public function commodity_name_is_required(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
             ->post(route('commodities.store'), [
                 'name'     => '',
                 'category' => CommodityCategory::GRAINS->value,
                 'unit'     => CommodityUnit::BAG->value,
             ])
             ->assertSessionHasErrors('name');
    }

    /** @test */
    public function commodity_name_must_be_unique(): void
    {
        $admin = $this->createAdmin();
        Commodity::factory()->create(['name' => 'Maize']);

        $this->actingAs($admin)
             ->post(route('commodities.store'), [
                 'name'     => 'Maize',
                 'category' => CommodityCategory::GRAINS->value,
                 'unit'     => CommodityUnit::BAG->value,
             ])
             ->assertSessionHasErrors('name');
    }

    /** @test */
    public function commodity_category_must_be_valid(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
             ->post(route('commodities.store'), [
                 'name'     => 'Maize',
                 'category' => 'invalid_category',
                 'unit'     => CommodityUnit::BAG->value,
             ])
             ->assertSessionHasErrors('category');
    }
}

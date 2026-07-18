<?php

namespace Tests\Feature\Market;

use App\Domains\Market\Actions\UpsertMarketAction;
use App\Domains\Market\Models\Market;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class MarketModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_action_creates_a_normalized_market(): void
    {
        $market = app(UpsertMarketAction::class)->execute(null, [
            'code' => ' sgp ',
            'name' => ' Singapore ',
            'slug' => '',
            'timezone' => 'Asia/Singapore',
            'is_active' => true,
            'sort_order' => 1,
            'notes' => ' Pasaran utama ',
        ]);

        $this->assertDatabaseHas('markets', [
            'id' => $market->id,
            'code' => 'SGP',
            'name' => 'Singapore',
            'slug' => 'singapore',
            'timezone' => 'Asia/Singapore',
            'is_active' => true,
            'sort_order' => 1,
            'notes' => 'Pasaran utama',
        ]);
    }

    public function test_action_updates_an_existing_market(): void
    {
        $market = Market::factory()->create([
            'code' => 'HK',
            'slug' => 'hong-kong',
        ]);

        $updated = app(UpsertMarketAction::class)->execute($market, [
            'code' => ' hk ',
            'name' => ' Hong Kong ',
            'slug' => 'hong-kong',
            'timezone' => 'Asia/Hong_Kong',
            'is_active' => false,
            'sort_order' => 2,
            'notes' => null,
        ]);

        $this->assertSame($market->id, $updated->id);
        $this->assertSame('HK', $updated->code);
        $this->assertSame('Hong Kong', $updated->name);
        $this->assertFalse($updated->is_active);
        $this->assertSame(2, $updated->sort_order);
    }

    public function test_market_code_must_be_unique(): void
    {
        Market::factory()->create([
            'code' => 'SGP',
        ]);

        $this->expectException(ValidationException::class);

        app(UpsertMarketAction::class)->execute(null, [
            'code' => 'sgp',
            'name' => 'Singapore Baru',
            'slug' => 'singapore-baru',
            'timezone' => 'Asia/Singapore',
            'is_active' => true,
            'sort_order' => 10,
            'notes' => null,
        ]);
    }

    public function test_market_slug_must_be_unique(): void
    {
        Market::factory()->create([
            'slug' => 'singapore',
        ]);

        $this->expectException(ValidationException::class);

        app(UpsertMarketAction::class)->execute(null, [
            'code' => 'NEW',
            'name' => 'Singapore Baru',
            'slug' => 'singapore',
            'timezone' => 'Asia/Singapore',
            'is_active' => true,
            'sort_order' => 10,
            'notes' => null,
        ]);
    }

    public function test_active_scope_returns_only_active_markets(): void
    {
        Market::factory()->create([
            'code' => 'ACT',
            'is_active' => true,
        ]);

        Market::factory()->inactive()->create([
            'code' => 'OFF',
        ]);

        $markets = Market::query()
            ->active()
            ->get();

        $this->assertCount(1, $markets);
        $this->assertSame('ACT', $markets->first()->code);
    }

    public function test_ordered_scope_sorts_markets_by_sort_order(): void
    {
        Market::factory()->create([
            'code' => 'TWO',
            'name' => 'Market Two',
            'sort_order' => 2,
        ]);

        Market::factory()->create([
            'code' => 'ONE',
            'name' => 'Market One',
            'sort_order' => 1,
        ]);

        $codes = Market::query()
            ->ordered()
            ->pluck('code')
            ->all();

        $this->assertSame(['ONE', 'TWO'], $codes);
    }

    public function test_admin_can_open_market_resource(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/markets')
            ->assertOk();
    }

    public function test_regular_user_cannot_open_market_resource(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/admin/markets')
            ->assertForbidden();
    }
}

<?php

namespace Tests\Feature\Market;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
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
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

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

    public function test_action_assigns_context_brand_when_creating_market(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $market = app(UpsertMarketAction::class)->execute(null, [
            'code' => 'BRD',
            'name' => 'Brand Market',
            'slug' => 'brand-market',
            'timezone' => 'Asia/Jakarta',
            'is_active' => true,
            'sort_order' => 1,
            'notes' => null,
        ]);

        $this->assertSame($brand->id, $market->brand_id);
    }

    public function test_updating_market_does_not_move_it_to_context_brand(): void
    {
        $originalBrand = Brand::factory()->create();
        $contextBrand = Brand::factory()->create();

        $market = Market::factory()->create([
            'brand_id' => $originalBrand->id,
        ]);

        app(BrandContext::class)->set($contextBrand);

        $updated = app(UpsertMarketAction::class)->execute($market, [
            'code' => $market->code,
            'name' => 'Updated Market',
            'slug' => $market->slug,
            'timezone' => $market->timezone,
            'is_active' => true,
            'sort_order' => 1,
            'notes' => null,
        ]);

        $this->assertSame($originalBrand->id, $updated->brand_id);
    }
    public function test_schedule_accepts_close_then_result_then_open(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $market = app(UpsertMarketAction::class)->execute(null, [
            'code' => 'SCH',
            'name' => 'Schedule Market',
            'slug' => 'schedule-market',
            'timezone' => 'Asia/Jakarta',
            'active_days' => [1, 3, 5],
            'close_time' => '17:30',
            'result_time' => '17:35',
            'open_time' => '17:40',
            'is_holiday' => false,
            'is_active' => true,
            'sort_order' => 1,
            'notes' => null,
        ]);

        $this->assertSame('17:30', $market->close_time);
        $this->assertSame('17:35', $market->result_time);
        $this->assertSame('17:40', $market->open_time);
    }

    public function test_schedule_rejects_result_not_after_close(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        try {
            app(UpsertMarketAction::class)->execute(null, [
                'code' => 'BAD1',
                'name' => 'Invalid Result Time',
                'slug' => 'invalid-result-time',
                'timezone' => 'Asia/Jakarta',
                'close_time' => '17:30',
                'result_time' => '17:25',
                'open_time' => '17:40',
                'is_holiday' => false,
                'is_active' => true,
                'sort_order' => 1,
            ]);

            $this->fail('ValidationException was not thrown.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey(
                'result_time',
                $exception->errors(),
            );
        }
    }

    public function test_schedule_rejects_open_not_after_result(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        try {
            app(UpsertMarketAction::class)->execute(null, [
                'code' => 'BAD2',
                'name' => 'Invalid Open Time',
                'slug' => 'invalid-open-time',
                'timezone' => 'Asia/Jakarta',
                'close_time' => '17:30',
                'result_time' => '17:35',
                'open_time' => '17:34',
                'is_holiday' => false,
                'is_active' => true,
                'sort_order' => 1,
            ]);

            $this->fail('ValidationException was not thrown.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey(
                'open_time',
                $exception->errors(),
            );
        }
    }
}

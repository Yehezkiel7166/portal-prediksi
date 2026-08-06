<?php

namespace Tests\Feature\Paito;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Market\Models\Market;
use App\Domains\Result\Models\Result;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaitoMarketIsolationContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_market_must_be_selected(): void
    {
        $brand = Brand::factory()->create([
            'is_primary' => true,
            'is_active' => true,
        ]);

        app(BrandContext::class)->set($brand);

        Market::factory()->create([
            'brand_id' => $brand->id,
            'name' => 'Singapore',
            'slug' => 'singapore',
            'is_active' => true,
        ]);

        $this->get(route('tools.paito'))
            ->assertOk()
            ->assertSee('Pilih Pasaran')
            ->assertSee('data-paito-market-required', false);
    }

    public function test_results_from_other_market_are_not_mixed(): void
    {
        $brand = Brand::factory()->create([
            'is_primary' => true,
            'is_active' => true,
        ]);

        app(BrandContext::class)->set($brand);

        $singapore = Market::factory()->create([
            'brand_id' => $brand->id,
            'name' => 'Singapore',
            'slug' => 'singapore',
            'is_active' => true,
        ]);

        $sydney = Market::factory()->create([
            'brand_id' => $brand->id,
            'name' => 'Sydney',
            'slug' => 'sydney',
            'is_active' => true,
        ]);

        Result::factory()->create([
            'brand_id' => $brand->id,
            'market_id' => $singapore->id,
            'result_date' => '2026-08-03',
            'winning_numbers' => '1234',
        ]);

        Result::factory()->create([
            'brand_id' => $brand->id,
            'market_id' => $sydney->id,
            'result_date' => '2026-08-03',
            'winning_numbers' => '9876',
        ]);

        $this->get(route('tools.paito', [
            'market' => 'singapore',
        ]))
            ->assertOk()
            ->assertSee('1234')
            ->assertDontSee('9876');
    }

    public function test_controller_uses_selected_market_id(): void
    {
        $controller = file_get_contents(
            app_path(
                'Http/Controllers/Frontend/PaitoController.php'
            )
        );

        $this->assertStringContainsString(
            '$selectedMarket->getKey()',
            $controller,
        );

        $this->assertStringContainsString(
            '->whereRaw(',
            $controller,
        );

        $this->assertStringContainsString(
            "'1 = 0'",
            $controller,
        );
    }
}

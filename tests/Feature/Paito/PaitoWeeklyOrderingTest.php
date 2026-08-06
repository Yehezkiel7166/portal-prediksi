<?php

namespace Tests\Feature\Paito;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Market\Models\Market;
use App\Domains\Result\Models\Result;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaitoWeeklyOrderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_duplicate_market_date_is_rejected(): void
    {
        $brand = Brand::factory()->create([
            'is_primary' => true,
            'is_active' => true,
        ]);

        app(BrandContext::class)->set($brand);

        $market = Market::factory()->create([
            'brand_id' => $brand->id,
            'name' => 'Singapore',
            'slug' => 'singapore',
            'is_active' => true,
        ]);

        Result::factory()->create([
            'brand_id' => $brand->id,
            'market_id' => $market->id,
            'result_date' => '2026-08-03',
            'winning_numbers' => '1111',
        ]);

        $this->expectException(
            QueryException::class
        );

        Result::factory()->create([
            'brand_id' => $brand->id,
            'market_id' => $market->id,
            'result_date' => '2026-08-03',
            'winning_numbers' => '9876',
        ]);
    }

    public function test_week_order_is_newest_first(): void
    {
        $controller = file_get_contents(
            app_path(
                'Http/Controllers/Frontend/PaitoController.php'
            )
        );

        $this->assertStringContainsString(
            "->sortByDesc('week_start')",
            $controller,
        );

        $this->assertStringContainsString(
            "->orderBy('result_date')",
            $controller,
        );

        $this->assertStringContainsString(
            "->orderBy('id')",
            $controller,
        );
    }

    public function test_grid_has_no_number_or_date_column(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        $this->assertStringContainsString(
            'data-paito-weekly-grid',
            $view,
        );

        foreach (
            [
                '>No<',
                '>Nomor<',
                '>Tanggal<',
            ] as $heading
        ) {
            $this->assertStringNotContainsString(
                $heading,
                $view,
            );
        }
    }
}

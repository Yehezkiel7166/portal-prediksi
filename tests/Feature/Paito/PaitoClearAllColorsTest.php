<?php

namespace Tests\Feature\Paito;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Market\Models\Market;
use App\Domains\Paito\Models\PaitoCellColor;
use App\Domains\Result\Models\Result;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaitoClearAllColorsTest extends TestCase
{
    use RefreshDatabase;

    public function test_clear_endpoint_deletes_all_colors_for_selected_market(): void
    {
        $brand = Brand::factory()->create([
            'is_primary' => true,
            'is_active' => true,
        ]);

        app(BrandContext::class)->set($brand);

        $market = Market::factory()->create([
            'brand_id' => $brand->id,
            'name' => 'Kentucky Midday',
            'slug' => 'kentucky-midday',
            'is_active' => true,
        ]);

        $result = Result::factory()->create([
            'brand_id' => $brand->id,
            'market_id' => $market->id,
            'result_date' => '2026-08-08',
            'winning_numbers' => '1234',
        ]);

        PaitoCellColor::query()->create([
            'brand_id' => $brand->id,
            'market_id' => $market->id,
            'result_id' => $result->id,
            'position' => 'as',
            'color' => 'red',
        ]);

        PaitoCellColor::query()->create([
            'brand_id' => $brand->id,
            'market_id' => $market->id,
            'result_id' => $result->id,
            'position' => 'kop',
            'color' => 'blue',
        ]);

        $this->assertSame(
            2,
            PaitoCellColor::query()
                ->where('market_id', $market->id)
                ->count(),
        );

        $this->deleteJson(
            route('tools.paito.color.clear', [
                'market' => $market->id,
            ])
        )
            ->assertOk()
            ->assertJson([
                'deleted' => 2,
            ]);

        $this->assertSame(
            0,
            PaitoCellColor::query()
                ->where('market_id', $market->id)
                ->count(),
        );
    }

    public function test_clear_endpoint_does_not_delete_other_market_colors(): void
    {
        $brand = Brand::factory()->create([
            'is_primary' => true,
            'is_active' => true,
        ]);

        app(BrandContext::class)->set($brand);

        $marketA = Market::factory()->create([
            'brand_id' => $brand->id,
            'name' => 'Market A',
            'slug' => 'market-a',
            'is_active' => true,
        ]);

        $marketB = Market::factory()->create([
            'brand_id' => $brand->id,
            'name' => 'Market B',
            'slug' => 'market-b',
            'is_active' => true,
        ]);

        $resultA = Result::factory()->create([
            'brand_id' => $brand->id,
            'market_id' => $marketA->id,
            'result_date' => '2026-08-07',
            'winning_numbers' => '1234',
        ]);

        $resultB = Result::factory()->create([
            'brand_id' => $brand->id,
            'market_id' => $marketB->id,
            'result_date' => '2026-08-07',
            'winning_numbers' => '9876',
        ]);

        PaitoCellColor::query()->create([
            'brand_id' => $brand->id,
            'market_id' => $marketA->id,
            'result_id' => $resultA->id,
            'position' => 'kepala',
            'color' => 'green',
        ]);

        PaitoCellColor::query()->create([
            'brand_id' => $brand->id,
            'market_id' => $marketB->id,
            'result_id' => $resultB->id,
            'position' => 'ekor',
            'color' => 'yellow',
        ]);

        $this->deleteJson(
            route('tools.paito.color.clear', [
                'market' => $marketA->id,
            ])
        )
            ->assertOk()
            ->assertJson([
                'deleted' => 1,
            ]);

        $this->assertDatabaseMissing(
            'paito_cell_colors',
            [
                'market_id' => $marketA->id,
            ],
        );

        $this->assertDatabaseHas(
            'paito_cell_colors',
            [
                'market_id' => $marketB->id,
                'result_id' => $resultB->id,
                'position' => 'ekor',
                'color' => 'yellow',
            ],
        );
    }

    public function test_clear_all_frontend_has_hardened_request_contract(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach ([
            'const clearAllButton',
            'const market = @json($selectedMarket?->getKey());',
            "credentials: 'same-origin'",
            "'X-Requested-With': 'XMLHttpRequest'",
            'response.status',
            'response.ok',
            'requestInProgress',
            'Warna berhasil dihapus',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }
}

<?php

namespace Tests\Feature\Market;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Market\Models\Market;
use App\Filament\Resources\Markets\MarketResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class MarketResourceBrandIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_query_only_returns_markets_for_current_brand(): void
    {
        $currentBrand = Brand::factory()->create();
        $otherBrand = Brand::factory()->create();

        $currentMarket = Market::factory()->create([
            'brand_id' => $currentBrand->id,
            'name' => 'CURRENT BRAND MARKET',
        ]);

        Market::factory()->create([
            'brand_id' => $otherBrand->id,
            'name' => 'OTHER BRAND MARKET',
        ]);

        app(BrandContext::class)->set($currentBrand);

        $marketIds = MarketResource::getEloquentQuery()
            ->pluck('id')
            ->all();

        $this->assertSame(
            [$currentMarket->id],
            $marketIds,
        );
    }
}

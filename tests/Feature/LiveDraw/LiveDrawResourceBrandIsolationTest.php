<?php

namespace Tests\Feature\LiveDraw;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\LiveDraw\Models\LiveDraw;
use App\Domains\Market\Models\Market;
use App\Filament\Resources\LiveDraws\LiveDrawResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class LiveDrawResourceBrandIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_query_only_returns_live_draws_for_current_brand(): void
    {
        $currentBrand = Brand::factory()->create();
        $otherBrand = Brand::factory()->create();

        $currentMarket = Market::factory()->create([
            'brand_id' => $currentBrand->id,
        ]);

        $otherMarket = Market::factory()->create([
            'brand_id' => $otherBrand->id,
        ]);

        $currentLiveDraw = LiveDraw::factory()->create([
            'market_id' => $currentMarket->id,
        ]);

        LiveDraw::factory()->create([
            'market_id' => $otherMarket->id,
        ]);

        app(BrandContext::class)->set($currentBrand);

        $liveDrawIds = LiveDrawResource::getEloquentQuery()
            ->pluck('id')
            ->all();

        $this->assertSame(
            [$currentLiveDraw->id],
            $liveDrawIds,
        );
    }
}

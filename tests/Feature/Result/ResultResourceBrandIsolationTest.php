<?php

namespace Tests\Feature\Result;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Market\Models\Market;
use App\Domains\Result\Models\Result;
use App\Filament\Resources\Results\ResultResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ResultResourceBrandIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_query_only_returns_results_for_current_brand(): void
    {
        $currentBrand = Brand::factory()->create();
        $otherBrand = Brand::factory()->create();

        $currentMarket = Market::factory()->create([
            'brand_id' => $currentBrand->id,
        ]);

        $otherMarket = Market::factory()->create([
            'brand_id' => $otherBrand->id,
        ]);

        $currentResult = Result::factory()->create([
            'brand_id' => $currentBrand->id,
            'market_id' => $currentMarket->id,
        ]);

        Result::factory()->create([
            'brand_id' => $otherBrand->id,
            'market_id' => $otherMarket->id,
        ]);

        app(BrandContext::class)->set($currentBrand);

        $resultIds = ResultResource::getEloquentQuery()
            ->pluck('id')
            ->all();

        $this->assertSame(
            [$currentResult->id],
            $resultIds,
        );
    }
}

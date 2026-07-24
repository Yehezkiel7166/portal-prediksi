<?php

namespace Tests\Feature\Prediction;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Market\Models\Market;
use App\Domains\Prediction\Models\Prediction;
use App\Filament\Resources\Predictions\PredictionResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PredictionResourceBrandIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_query_only_returns_predictions_for_current_brand(): void
    {
        $currentBrand = Brand::factory()->create();
        $otherBrand = Brand::factory()->create();

        $currentMarket = Market::factory()->create([
            'brand_id' => $currentBrand->id,
        ]);

        $otherMarket = Market::factory()->create([
            'brand_id' => $otherBrand->id,
        ]);

        $currentPrediction = Prediction::factory()->create([
            'brand_id' => $currentBrand->id,
            'market_id' => $currentMarket->id,
        ]);

        Prediction::factory()->create([
            'brand_id' => $otherBrand->id,
            'market_id' => $otherMarket->id,
        ]);

        app(BrandContext::class)->set($currentBrand);

        $predictionIds = PredictionResource::getEloquentQuery()
            ->pluck('id')
            ->all();

        $this->assertSame(
            [$currentPrediction->id],
            $predictionIds,
        );
    }
    public function test_resource_query_cannot_resolve_prediction_from_another_brand(): void
    {
        $currentBrand = Brand::factory()->create();
        $otherBrand = Brand::factory()->create();

        $currentMarket = Market::factory()->create([
            'brand_id' => $currentBrand->id,
        ]);

        $otherMarket = Market::factory()->create([
            'brand_id' => $otherBrand->id,
        ]);

        $currentPrediction = Prediction::factory()->create([
            'brand_id' => $currentBrand->id,
            'market_id' => $currentMarket->id,
        ]);

        $otherPrediction = Prediction::factory()->create([
            'brand_id' => $otherBrand->id,
            'market_id' => $otherMarket->id,
        ]);

        app(BrandContext::class)->set($currentBrand);

        $this->assertSame(
            $currentPrediction->id,
            PredictionResource::getEloquentQuery()
                ->findOrFail($currentPrediction->id)
                ->id,
        );

        $this->assertNull(
            PredictionResource::getEloquentQuery()
                ->find($otherPrediction->id),
        );
    }

}

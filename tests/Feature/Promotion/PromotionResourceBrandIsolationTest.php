<?php

namespace Tests\Feature\Promotion;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Promotion\Models\Promotion;
use App\Filament\Resources\Promotions\PromotionResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PromotionResourceBrandIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_query_only_returns_promotions_for_current_brand(): void
    {
        $currentBrand = Brand::factory()->create();
        $otherBrand = Brand::factory()->create();

        $currentPromotion = Promotion::factory()->create([
            'brand_id' => $currentBrand->id,
        ]);

        Promotion::factory()->create([
            'brand_id' => $otherBrand->id,
        ]);

        app(BrandContext::class)->set($currentBrand);

        $promotionIds = PromotionResource::getEloquentQuery()
            ->pluck('id')
            ->all();

        $this->assertSame(
            [$currentPromotion->id],
            $promotionIds,
        );
    }
    public function test_resource_query_cannot_resolve_promotion_from_another_brand(): void
    {
        $currentBrand = Brand::factory()->create();
        $otherBrand = Brand::factory()->create();

        $currentPromotion = Promotion::factory()->create([
            'brand_id' => $currentBrand->id,
        ]);

        $otherPromotion = Promotion::factory()->create([
            'brand_id' => $otherBrand->id,
        ]);

        app(BrandContext::class)->set($currentBrand);

        $this->assertSame(
            $currentPromotion->id,
            PromotionResource::getEloquentQuery()
                ->findOrFail($currentPromotion->id)
                ->id,
        );

        $this->assertNull(
            PromotionResource::getEloquentQuery()
                ->find($otherPromotion->id),
        );
    }

}

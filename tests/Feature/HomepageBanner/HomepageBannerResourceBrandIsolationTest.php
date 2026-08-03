<?php

declare(strict_types=1);

namespace Tests\Feature\HomepageBanner;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\HomepageBanner\Models\HomepageBanner;
use App\Filament\Resources\HomepageBanners\HomepageBannerResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class HomepageBannerResourceBrandIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_only_returns_current_brand_records(): void
    {
        $currentBrand = Brand::factory()->create();
        $otherBrand = Brand::factory()->create();

        $currentBanner = HomepageBanner::factory()->create([
            'brand_id' => $currentBrand->id,
        ]);

        HomepageBanner::factory()->create([
            'brand_id' => $otherBrand->id,
        ]);

        app(BrandContext::class)->set($currentBrand);

        $this->assertSame(
            [$currentBanner->id],
            HomepageBannerResource::getEloquentQuery()
                ->pluck('id')
                ->all()
        );
    }

    public function test_resource_cannot_resolve_other_brand_record(): void
    {
        $currentBrand = Brand::factory()->create();
        $otherBrand = Brand::factory()->create();

        $currentBanner = HomepageBanner::factory()->create([
            'brand_id' => $currentBrand->id,
        ]);

        $otherBanner = HomepageBanner::factory()->create([
            'brand_id' => $otherBrand->id,
        ]);

        app(BrandContext::class)->set($currentBrand);

        $this->assertSame(
            $currentBanner->id,
            HomepageBannerResource::getEloquentQuery()
                ->findOrFail($currentBanner->id)
                ->id
        );

        $this->assertNull(
            HomepageBannerResource::getEloquentQuery()
                ->find($otherBanner->id)
        );
    }
}

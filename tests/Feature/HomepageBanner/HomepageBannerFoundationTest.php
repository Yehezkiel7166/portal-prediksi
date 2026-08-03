<?php

declare(strict_types=1);

namespace Tests\Feature\HomepageBanner;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\HomepageBanner\Models\HomepageBanner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

final class HomepageBannerFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_model_and_relationship_exist(): void
    {
        $this->assertTrue(Schema::hasTable('homepage_banners'));

        $this->assertSame(
            'homepage_banners',
            (new HomepageBanner())->getTable()
        );

        $brand = Brand::factory()->create();

        HomepageBanner::factory()
            ->for($brand)
            ->create();

        $this->assertCount(1, $brand->homepageBanners);
    }

    public function test_published_scope_filters_invalid_records(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $visible = HomepageBanner::factory()
            ->published()
            ->create(['brand_id' => $brand->id]);

        HomepageBanner::factory()->create([
            'brand_id' => $brand->id,
        ]);

        HomepageBanner::factory()->create([
            'brand_id' => $brand->id,
            'status' => HomepageBanner::STATUS_PUBLISHED,
            'published_at' => now()->addHour(),
        ]);

        HomepageBanner::factory()->create([
            'brand_id' => $brand->id,
            'status' => HomepageBanner::STATUS_PUBLISHED,
            'published_at' => now()->subDay(),
            'expires_at' => now()->subMinute(),
        ]);

        $results = HomepageBanner::query()->published()->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->contains($visible));
    }

    public function test_query_is_brand_scoped_and_ordered(): void
    {
        $brand = Brand::factory()->create();
        $other = Brand::factory()->create();

        $second = HomepageBanner::factory()->create([
            'brand_id' => $brand->id,
            'sort_order' => 20,
        ]);

        $first = HomepageBanner::factory()->create([
            'brand_id' => $brand->id,
            'sort_order' => 10,
        ]);

        HomepageBanner::factory()->create([
            'brand_id' => $other->id,
            'sort_order' => 1,
        ]);

        app(BrandContext::class)->set($brand);

        $this->assertSame(
            [$first->id, $second->id],
            HomepageBanner::query()
                ->ordered()
                ->pluck('id')
                ->all()
        );
    }
}

<?php

namespace Tests\Feature\Brand;

use App\Domains\Blog\Models\BlogPost;
use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Brand\Support\BrandQuery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandQueryTest extends TestCase
{
    use RefreshDatabase;

    public function test_normal_query_only_returns_current_brand_records(): void
    {
        $brandA = Brand::factory()->create();
        $brandB = Brand::factory()->create();

        BlogPost::factory()->create(['brand_id' => $brandA->id]);
        BlogPost::factory()->create(['brand_id' => $brandB->id]);

        app(BrandContext::class)->set($brandA);

        $this->assertCount(1, BlogPost::query()->get());
        $this->assertTrue(BlogPost::query()->first()->brand_id === $brandA->id);
    }

    public function test_brand_query_can_read_records_from_all_brands(): void
    {
        $brandA = Brand::factory()->create();
        $brandB = Brand::factory()->create();

        BlogPost::factory()->create(['brand_id' => $brandA->id]);
        BlogPost::factory()->create(['brand_id' => $brandB->id]);

        app(BrandContext::class)->set($brandA);

        $this->assertCount(2, BrandQuery::allBrands(BlogPost::class)->get());
    }
}

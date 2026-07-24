<?php

namespace Tests\Feature\Brand;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Market\Models\Market;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LogicException;
use Tests\TestCase;

class BelongsToBrandOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_assigns_the_current_brand_when_creating_a_brand_owned_model(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $market = Market::query()->create([
            'code' => 'SG',
            'name' => 'Singapore',
            'slug' => 'singapore',
            'timezone' => 'Asia/Singapore',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->assertSame($brand->id, $market->brand_id);

        $this->assertDatabaseHas('markets', [
            'id' => $market->id,
            'brand_id' => $brand->id,
        ]);
    }

    public function test_it_does_not_replace_an_existing_brand_ownership(): void
    {
        $currentBrand = Brand::factory()->create();
        $existingBrand = Brand::factory()->create();

        app(BrandContext::class)->set($currentBrand);

        $market = new Market([
            'code' => 'HK',
            'name' => 'Hong Kong',
            'slug' => 'hong-kong',
            'timezone' => 'Asia/Hong_Kong',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $market->brand_id = $existingBrand->id;
        $market->save();

        $this->assertSame($existingBrand->id, $market->brand_id);
    }

    public function test_it_rejects_creation_without_brand_context_and_without_explicit_ownership(): void
    {
        app(BrandContext::class)->clear();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(
            'Cannot create a brand-owned model without an active Brand context.',
        );

        Market::query()->create([
            'code' => 'MY',
            'name' => 'Malaysia',
            'slug' => 'malaysia',
            'timezone' => 'Asia/Kuala_Lumpur',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    public function test_brand_relationship_remains_available(): void
    {
        $brand = Brand::factory()->create();

        $market = Market::factory()
            ->for($brand, 'brand')
            ->create();

        $this->assertTrue($market->brand->is($brand));
    }
}

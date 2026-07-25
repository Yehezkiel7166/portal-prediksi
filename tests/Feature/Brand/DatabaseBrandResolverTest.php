<?php

namespace Tests\Feature\Brand;

use App\Domains\Brand\Contracts\BrandResolver;
use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\DatabaseBrandResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseBrandResolverTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_implements_contract(): void
    {
        $this->assertInstanceOf(
            BrandResolver::class,
            new DatabaseBrandResolver()
        );
    }

    public function test_it_resolves_primary_brand(): void
    {
        $brand = Brand::factory()->create([
            'is_primary' => true,
            'is_active'  => true,
        ]);

        $resolved = (new DatabaseBrandResolver())->resolve();

        $this->assertNotNull($resolved);
        $this->assertTrue($brand->is($resolved));
    }

    public function test_it_ignores_inactive_primary_brand(): void
    {
        Brand::factory()->create([
            'is_primary' => true,
            'is_active'  => false,
        ]);

        $this->assertNull(
            (new DatabaseBrandResolver())->resolve()
        );
    }

    public function test_it_returns_null_when_no_primary_brand_exists(): void
    {
        Brand::factory()->create([
            'is_primary' => false,
            'is_active'  => true,
        ]);

        $this->assertNull(
            (new DatabaseBrandResolver())->resolve()
        );
    }

    public function test_it_returns_null_when_table_missing(): void
    {
        Schema::dropIfExists('brands');

        $this->assertNull(
            (new DatabaseBrandResolver())->resolve()
        );
    }
}

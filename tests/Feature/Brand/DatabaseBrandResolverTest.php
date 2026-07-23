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

    public function test_it_implements_brand_resolver_contract(): void
    {
        $resolver = new DatabaseBrandResolver();

        $this->assertInstanceOf(BrandResolver::class, $resolver);
    }

    public function test_it_resolves_the_active_canonical_brand(): void
    {
        $brand = Brand::factory()->create([
            'code' => 'DEFAULT',
            'is_active' => true,
        ]);

        $resolved = (new DatabaseBrandResolver())->resolve();

        $this->assertNotNull($resolved);
        $this->assertTrue($brand->is($resolved));
    }

    public function test_it_does_not_resolve_an_inactive_canonical_brand(): void
    {
        Brand::factory()->create([
            'code' => 'DEFAULT',
            'is_active' => false,
        ]);

        $this->assertNull(
            (new DatabaseBrandResolver())->resolve()
        );
    }

    public function test_it_returns_null_when_canonical_brand_does_not_exist(): void
    {
        Brand::factory()->create([
            'code' => 'OTHER',
            'is_active' => true,
        ]);

        $this->assertNull(
            (new DatabaseBrandResolver())->resolve()
        );
    }

    public function test_it_uses_the_configured_brand_code(): void
    {
        config()->set('brand.default_code', 'TRIAL');

        $brand = Brand::factory()->create([
            'code' => 'TRIAL',
            'is_active' => true,
        ]);

        Brand::factory()->create([
            'code' => 'DEFAULT',
            'is_active' => true,
        ]);

        $resolved = (new DatabaseBrandResolver())->resolve();

        $this->assertNotNull($resolved);
        $this->assertTrue($brand->is($resolved));
        $this->assertSame('TRIAL', $resolved->code);
    }

    public function test_it_returns_null_when_configured_code_is_empty(): void
    {
        config()->set('brand.default_code', '   ');

        Brand::factory()->create([
            'code' => 'DEFAULT',
            'is_active' => true,
        ]);

        $this->assertNull(
            (new DatabaseBrandResolver())->resolve()
        );
    }

    public function test_it_returns_null_when_brands_table_is_unavailable(): void
    {
        Schema::dropIfExists('brands');

        $this->assertNull(
            (new DatabaseBrandResolver())->resolve()
        );
    }
}

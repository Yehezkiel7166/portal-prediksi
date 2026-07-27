<?php

namespace Tests\Feature\Brand;

use App\Domains\Brand\Contracts\BrandResolver;
use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\DatabaseBrandResolver;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Models\BrandDomain;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
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

    public function test_it_resolves_brand_from_registered_domain_host(): void
    {
        $primary = Brand::factory()->create([
            'domain' => 'primary-legacy.example.test',
            'is_primary' => true,
            'is_active' => true,
        ]);

        $brand = Brand::factory()->create([
            'domain' => 'brand-legacy.example.test',
            'is_primary' => false,
            'is_active' => true,
        ]);

        BrandDomain::factory()
            ->for($brand)
            ->primary()
            ->create([
                'host' => 'brand.example.test',
                'type' => DomainType::Frontend,
            ]);

        $resolved = (new DatabaseBrandResolver())->resolve(
            Request::create('https://brand.example.test/')
        );

        $this->assertNotNull($resolved);
        $this->assertTrue($brand->is($resolved));
        $this->assertFalse($primary->is($resolved));
    }

    public function test_it_resolves_brand_from_alias_domain(): void
    {
        $brand = Brand::factory()->create([
            'domain' => 'canonical-legacy.example.test',
            'is_primary' => false,
            'is_active' => true,
        ]);

        BrandDomain::factory()
            ->for($brand)
            ->primary()
            ->create([
                'host' => 'canonical.example.test',
            ]);

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'alias.example.test',
            ]);

        $resolved = (new DatabaseBrandResolver())->resolve(
            Request::create('https://alias.example.test/')
        );

        $this->assertNotNull($resolved);
        $this->assertTrue($brand->is($resolved));
    }

    public function test_it_matches_registered_domain_case_insensitively(): void
    {
        $brand = Brand::factory()->create([
            'domain' => 'legacy.example.test',
            'is_primary' => false,
            'is_active' => true,
        ]);

        BrandDomain::factory()
            ->for($brand)
            ->create([
                'host' => 'mixed.example.test',
            ]);

        $resolved = (new DatabaseBrandResolver())->resolve(
            Request::create('https://MIXED.EXAMPLE.TEST/')
        );

        $this->assertNotNull($resolved);
        $this->assertTrue($brand->is($resolved));
    }

    public function test_it_ignores_inactive_registered_domain(): void
    {
        $primary = Brand::factory()->create([
            'is_primary' => true,
            'is_active' => true,
        ]);

        $brand = Brand::factory()->create([
            'domain' => 'inactive-domain-legacy.example.test',
            'is_primary' => false,
            'is_active' => true,
        ]);

        BrandDomain::factory()
            ->for($brand)
            ->inactive()
            ->create([
                'host' => 'inactive-domain.example.test',
            ]);

        $resolved = (new DatabaseBrandResolver())->resolve(
            Request::create('https://inactive-domain.example.test/')
        );

        $this->assertNotNull($resolved);
        $this->assertTrue($primary->is($resolved));
    }

    public function test_it_ignores_registered_domain_owned_by_inactive_brand(): void
    {
        $primary = Brand::factory()->create([
            'is_primary' => true,
            'is_active' => true,
        ]);

        $inactiveBrand = Brand::factory()->create([
            'domain' => 'inactive-brand-legacy.example.test',
            'is_primary' => false,
            'is_active' => false,
        ]);

        BrandDomain::factory()
            ->for($inactiveBrand)
            ->create([
                'host' => 'inactive-brand.example.test',
            ]);

        $resolved = (new DatabaseBrandResolver())->resolve(
            Request::create('https://inactive-brand.example.test/')
        );

        $this->assertNotNull($resolved);
        $this->assertTrue($primary->is($resolved));
    }

    public function test_it_falls_back_to_legacy_brand_domain(): void
    {
        $brand = Brand::factory()->create([
            'domain' => 'legacy.example.test',
            'is_primary' => false,
            'is_active' => true,
        ]);

        $resolved = (new DatabaseBrandResolver())->resolve(
            Request::create('https://legacy.example.test/')
        );

        $this->assertNotNull($resolved);
        $this->assertTrue($brand->is($resolved));
    }

    public function test_registered_domain_takes_priority_over_legacy_domain(): void
    {
        $legacyBrand = Brand::factory()->create([
            'domain' => 'shared.example.test',
            'is_primary' => false,
            'is_active' => true,
        ]);

        $registeredBrand = Brand::factory()->create([
            'domain' => 'registered-legacy.example.test',
            'is_primary' => false,
            'is_active' => true,
        ]);

        BrandDomain::factory()
            ->for($registeredBrand)
            ->create([
                'host' => 'shared.example.test',
            ]);

        $resolved = (new DatabaseBrandResolver())->resolve(
            Request::create('https://shared.example.test/')
        );

        $this->assertNotNull($resolved);
        $this->assertTrue($registeredBrand->is($resolved));
        $this->assertFalse($legacyBrand->is($resolved));
    }

    public function test_it_uses_legacy_resolution_without_brand_domains_table(): void
    {
        $brand = Brand::factory()->create([
            'domain' => 'legacy-without-table.example.test',
            'is_primary' => false,
            'is_active' => true,
        ]);

        Schema::dropIfExists('brand_domains');

        $resolved = (new DatabaseBrandResolver())->resolve(
            Request::create('https://legacy-without-table.example.test/')
        );

        $this->assertNotNull($resolved);
        $this->assertTrue($brand->is($resolved));
    }
    public function test_it_returns_null_when_table_missing(): void
    {
        Schema::dropIfExists('brands');

        $this->assertNull(
            (new DatabaseBrandResolver())->resolve()
        );
    }
}

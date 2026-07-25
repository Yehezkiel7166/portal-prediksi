<?php

declare(strict_types=1);

namespace Tests\Feature\Domain;

use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Models\BrandDomain;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandDomainFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_domain_type_exposes_supported_values(): void
    {
        $this->assertSame([
            'frontend',
            'admin',
            'api',
            'asset',
            'preview',
        ], DomainType::values());
    }

    public function test_brand_can_have_multiple_domains(): void
    {
        $brand = Brand::factory()->create();

        BrandDomain::factory()->for($brand)->create([
            'host' => 'public.example.test',
            'type' => DomainType::Frontend,
        ]);

        BrandDomain::factory()->for($brand)->admin()->create([
            'host' => 'admin.example.test',
        ]);

        $this->assertCount(2, $brand->domains()->get());

        $this->assertTrue(
            $brand->domains()
                ->where('type', DomainType::Frontend->value)
                ->where('host', 'public.example.test')
                ->exists(),
        );

        $this->assertTrue(
            $brand->domains()
                ->where('type', DomainType::Admin->value)
                ->where('host', 'admin.example.test')
                ->exists(),
        );
    }

    public function test_brand_domain_belongs_to_brand(): void
    {
        $brand = Brand::factory()->create();

        $domain = BrandDomain::factory()
            ->for($brand)
            ->primary()
            ->create([
                'host' => 'primary.example.test',
            ]);

        $this->assertTrue($domain->brand->is($brand));
    }

    public function test_brand_domain_casts_domain_configuration(): void
    {
        $domain = BrandDomain::factory()->create([
            'type' => DomainType::Preview,
            'is_primary' => true,
            'is_active' => false,
            'force_https' => false,
            'sort_order' => 25,
            'settings' => [
                'canonical' => false,
            ],
        ]);

        $this->assertSame(DomainType::Preview, $domain->type);
        $this->assertTrue($domain->is_primary);
        $this->assertFalse($domain->is_active);
        $this->assertFalse($domain->force_https);
        $this->assertSame(25, $domain->sort_order);
        $this->assertSame(
            ['canonical' => false],
            $domain->settings,
        );
    }

    public function test_domain_host_must_be_unique(): void
    {
        BrandDomain::factory()->create([
            'host' => 'unique.example.test',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        BrandDomain::factory()->create([
            'host' => 'unique.example.test',
        ]);
    }

    public function test_deleting_brand_deletes_registered_domains(): void
    {
        $brand = Brand::factory()->create();

        $domain = BrandDomain::factory()
            ->for($brand)
            ->create();

        $brand->delete();

        $this->assertDatabaseMissing('brand_domains', [
            'id' => $domain->id,
        ]);
    }
}

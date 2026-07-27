<?php

declare(strict_types=1);

namespace Tests\Feature\Domain;

use App\Domains\Brand\Models\Brand;
use App\Domains\Domain\Actions\BackfillLegacyBrandDomains;
use App\Domains\Domain\Enums\DomainType;
use App\Domains\Domain\Models\BrandDomain;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BackfillLegacyBrandDomainsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_registered_domain_from_legacy_domain(): void
    {
        $brand = Brand::factory()->create([
            'domain' => 'Legacy.Example.Test',
            'is_active' => true,
        ]);

        $created = app(BackfillLegacyBrandDomains::class)->execute();

        $this->assertSame(1, $created);

        $domain = BrandDomain::query()
            ->where('host', 'legacy.example.test')
            ->first();

        $this->assertNotNull($domain);
        $this->assertTrue($domain->brand->is($brand));
        $this->assertSame(DomainType::Frontend, $domain->type);
        $this->assertTrue($domain->is_primary);
        $this->assertTrue($domain->is_active);
        $this->assertTrue($domain->force_https);
        $this->assertSame(
            ['source' => 'legacy_brand_domain'],
            $domain->settings,
        );
    }

    public function test_it_is_idempotent(): void
    {
        Brand::factory()->create([
            'domain' => 'idempotent.example.test',
        ]);

        $action = app(BackfillLegacyBrandDomains::class);

        $this->assertSame(1, $action->execute());
        $this->assertSame(0, $action->execute());

        $this->assertDatabaseCount('brand_domains', 1);
    }

    public function test_it_does_not_replace_existing_registered_domain(): void
    {
        $existingBrand = Brand::factory()->create([
            'domain' => 'existing-brand.example.test',
        ]);

        BrandDomain::factory()
            ->for($existingBrand)
            ->create([
                'host' => 'shared.example.test',
                'is_primary' => false,
                'settings' => [
                    'source' => 'manual',
                ],
            ]);

        Brand::factory()->create([
            'domain' => 'shared.example.test',
        ]);

        $created = app(BackfillLegacyBrandDomains::class)->execute();

        $this->assertSame(1, $created);

        $sharedDomain = BrandDomain::query()
            ->where('host', 'shared.example.test')
            ->firstOrFail();

        $this->assertTrue($sharedDomain->brand->is($existingBrand));
        $this->assertSame(
            ['source' => 'manual'],
            $sharedDomain->settings,
        );
    }

    public function test_it_skips_empty_legacy_domain(): void
    {
        Brand::factory()->create([
            'domain' => '',
        ]);

        $created = app(BackfillLegacyBrandDomains::class)->execute();

        $this->assertSame(0, $created);
        $this->assertDatabaseCount('brand_domains', 0);
    }

    public function test_it_normalizes_url_and_port_to_host(): void
    {
        $brand = Brand::factory()->create([
            'domain' => 'HTTPS://Portal.Example.Test:8443/path',
        ]);

        $created = app(BackfillLegacyBrandDomains::class)->execute();

        $this->assertSame(1, $created);

        $this->assertDatabaseHas('brand_domains', [
            'brand_id' => $brand->getKey(),
            'host' => 'portal.example.test',
        ]);
    }

    public function test_it_returns_zero_without_brand_domains_table(): void
    {
        Schema::dropIfExists('brand_domains');

        $created = app(BackfillLegacyBrandDomains::class)->execute();

        $this->assertSame(0, $created);
    }

    public function test_command_backfills_legacy_domains(): void
    {
        Brand::factory()->create([
            'domain' => 'command.example.test',
        ]);

        $this->artisan('brand-domains:backfill-legacy')
            ->expectsOutputToContain(
                'Legacy brand domains created: 1'
            )
            ->assertSuccessful();

        $this->assertDatabaseHas('brand_domains', [
            'host' => 'command.example.test',
        ]);
    }
}

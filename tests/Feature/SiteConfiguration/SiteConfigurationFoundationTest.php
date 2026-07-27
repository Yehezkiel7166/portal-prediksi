<?php

declare(strict_types=1);

namespace Tests\Feature\SiteConfiguration;

use App\Domains\Brand\Models\Brand;
use App\Domains\SiteConfiguration\Actions\UpsertSiteConfiguration;
use App\Domains\SiteConfiguration\Models\SiteConfiguration;
use App\Domains\SiteConfiguration\Support\SiteConfigurationResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

final class SiteConfigurationFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_brand_has_one_site_configuration(): void
    {
        $brand = Brand::factory()->create();
        $configuration = SiteConfiguration::query()->create([
            'brand_id' => $brand->id,
            'site_name' => 'Brand Portal',
        ]);

        $this->assertTrue($brand->siteConfiguration->is($configuration));
    }

    public function test_each_brand_can_only_have_one_configuration(): void
    {
        $brand = Brand::factory()->create();
        SiteConfiguration::query()->create(['brand_id' => $brand->id]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        SiteConfiguration::query()->create(['brand_id' => $brand->id]);
    }

    public function test_resolver_uses_safe_brand_fallback_when_configuration_is_absent(): void
    {
        $brand = Brand::factory()->create(['name' => 'Fallback Brand']);

        $resolved = app(SiteConfigurationResolver::class)->resolve($brand);

        $this->assertSame('Fallback Brand', $resolved->siteName);
        $this->assertSame('Fallback Brand', $resolved->defaultSeoTitle);
        $this->assertSame([], $resolved->socialLinks);
        $this->assertFalse($resolved->fromDatabase);
    }

    public function test_resolver_returns_active_database_configuration(): void
    {
        $brand = Brand::factory()->create(['name' => 'Fallback Brand']);
        SiteConfiguration::query()->create([
            'brand_id' => $brand->id,
            'site_name' => 'Configured Brand',
            'default_seo_title' => 'Configured SEO',
            'social_links' => ['facebook' => 'https://example.com/facebook'],
            'is_active' => true,
        ]);

        $resolved = app(SiteConfigurationResolver::class)->resolve($brand);

        $this->assertSame('Configured Brand', $resolved->siteName);
        $this->assertSame('Configured SEO', $resolved->defaultSeoTitle);
        $this->assertSame(['facebook' => 'https://example.com/facebook'], $resolved->socialLinks);
        $this->assertTrue($resolved->fromDatabase);
    }

    public function test_inactive_configuration_is_not_exposed(): void
    {
        $brand = Brand::factory()->create(['name' => 'Safe Fallback']);
        SiteConfiguration::query()->create([
            'brand_id' => $brand->id,
            'site_name' => 'Inactive Name',
            'is_active' => false,
        ]);

        $resolved = app(SiteConfigurationResolver::class)->resolve($brand);

        $this->assertSame('Safe Fallback', $resolved->siteName);
        $this->assertFalse($resolved->fromDatabase);
    }

    public function test_upsert_normalizes_values_and_invalidates_cached_resolution(): void
    {
        Cache::flush();
        $brand = Brand::factory()->create(['name' => 'Original']);
        $resolver = app(SiteConfigurationResolver::class);
        $this->assertSame('Original', $resolver->resolve($brand)->siteName);

        $configuration = app(UpsertSiteConfiguration::class)->execute($brand, [
            'site_name' => '  Updated Site  ',
            'tagline' => '   ',
            'social_links' => ['instagram' => 'https://example.com/instagram'],
            'is_active' => true,
            'brand_id' => 999999,
        ]);

        $this->assertSame($brand->id, $configuration->brand_id);
        $this->assertSame('Updated Site', $configuration->site_name);
        $this->assertNull($configuration->tagline);
        $this->assertSame('Updated Site', $resolver->resolve($brand)->siteName);
    }

    public function test_deleting_brand_cascades_configuration(): void
    {
        $brand = Brand::factory()->create();
        SiteConfiguration::query()->create(['brand_id' => $brand->id]);

        $brand->delete();

        $this->assertDatabaseCount('site_configurations', 0);
    }
}

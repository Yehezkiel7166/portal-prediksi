<?php

declare(strict_types=1);

namespace Tests\Feature\SiteConfiguration;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\SiteConfiguration\Models\SiteConfiguration;
use App\Filament\Resources\SiteConfigurations\SiteConfigurationResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SiteConfigurationFilamentAdministrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_only_queries_current_brand_configuration(): void
    {
        $brandA = Brand::factory()->create();
        $brandB = Brand::factory()->create();
        $configurationA = SiteConfiguration::query()->create(['brand_id' => $brandA->id, 'site_name' => 'A', 'is_active' => true]);
        SiteConfiguration::query()->create(['brand_id' => $brandB->id, 'site_name' => 'B', 'is_active' => true]);
        app(BrandContext::class)->set($brandA);

        $results = SiteConfigurationResource::getEloquentQuery()->get();

        $this->assertCount(1, $results);
        $this->assertTrue($configurationA->is($results->first()));
    }

    public function test_resource_returns_nothing_without_brand_context(): void
    {
        $brand = Brand::factory()->create();
        SiteConfiguration::query()->create(['brand_id' => $brand->id, 'site_name' => 'A', 'is_active' => true]);
        app(BrandContext::class)->clear();
        $this->assertCount(0, SiteConfigurationResource::getEloquentQuery()->get());
    }

    public function test_create_is_only_available_before_current_brand_has_configuration(): void
    {
        $brand = Brand::factory()->create();
        app(BrandContext::class)->set($brand);
        $this->assertTrue(SiteConfigurationResource::canCreate());
        SiteConfiguration::query()->create(['brand_id' => $brand->id, 'site_name' => 'A', 'is_active' => true]);
        $this->assertFalse(SiteConfigurationResource::canCreate());
    }

    public function test_admin_can_open_site_configuration_resource(): void
    {
        $brand = Brand::factory()->create(['is_primary' => true]);
        app(BrandContext::class)->set($brand);
        $admin = User::factory()->create(['is_admin' => true, 'email_verified_at' => now()]);
        $this->actingAs($admin)->get('/admin/site-configuration')->assertOk();
    }

    public function test_regular_user_cannot_open_site_configuration_resource(): void
    {
        $brand = Brand::factory()->create(['is_primary' => true]);
        app(BrandContext::class)->set($brand);
        $user = User::factory()->create(['is_admin' => false, 'email_verified_at' => now()]);
        $this->actingAs($user)->get('/admin/site-configuration')->assertForbidden();
    }

    public function test_resource_registers_index_create_and_edit_pages(): void
    {
        $pages = SiteConfigurationResource::getPages();
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }
}

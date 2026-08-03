<?php

declare(strict_types=1);

namespace Tests\Feature\HomepageBanner;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\HomepageBanner\Models\HomepageBanner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class HomepageBannerResourcePagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_create_page(): void
    {
        $brand = Brand::factory()->create([
            'is_active' => true,
            'is_primary' => true,
        ]);

        app(BrandContext::class)->set($brand);

        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/homepage-banners/create')
            ->assertOk();
    }

    public function test_admin_can_open_current_brand_edit_page(): void
    {
        $brand = Brand::factory()->create([
            'is_active' => true,
            'is_primary' => true,
        ]);

        app(BrandContext::class)->set($brand);

        $banner = HomepageBanner::factory()->create([
            'brand_id' => $brand->id,
        ]);

        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(
                "/admin/homepage-banners/{$banner->id}/edit"
            )
            ->assertOk();
    }

    public function test_other_brand_edit_page_returns_not_found(): void
    {
        $currentBrand = Brand::factory()->create([
            'is_active' => true,
            'is_primary' => true,
        ]);

        $otherBrand = Brand::factory()->create([
            'is_active' => true,
            'is_primary' => false,
        ]);

        app(BrandContext::class)->set($currentBrand);

        $otherBanner = HomepageBanner::factory()->create([
            'brand_id' => $otherBrand->id,
        ]);

        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(
                "/admin/homepage-banners/{$otherBanner->id}/edit"
            )
            ->assertNotFound();
    }
}

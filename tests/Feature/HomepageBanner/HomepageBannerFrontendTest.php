<?php

declare(strict_types=1);

namespace Tests\Feature\HomepageBanner;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\HomepageBanner\Models\HomepageBanner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class HomepageBannerFrontendTest extends TestCase
{
    use RefreshDatabase;

    private Brand $brand;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->brand = Brand::factory()->create([
            'is_active' => true,
            'is_primary' => true,
        ]);

        app(BrandContext::class)->set($this->brand);
    }

    public function test_homepage_receives_only_published_banners(): void
    {
        $visible = HomepageBanner::factory()
            ->published()
            ->create([
                'brand_id' => $this->brand->id,
                'title' => 'Banner Aktif',
            ]);

        HomepageBanner::factory()->create([
            'brand_id' => $this->brand->id,
            'title' => 'Banner Draft',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertViewHas(
                'homepageBanners',
                fn ($items): bool =>
                    $items->count() === 1
                    && $items->contains($visible)
            )
            ->assertSee('Banner Aktif')
            ->assertDontSee('Banner Draft');
    }

    public function test_homepage_banner_is_brand_scoped(): void
    {
        $otherBrand = Brand::factory()->create();

        HomepageBanner::factory()
            ->published()
            ->create([
                'brand_id' => $otherBrand->id,
                'title' => 'Banner Brand Lain',
            ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('Banner Brand Lain');
    }

    public function test_homepage_uses_desktop_and_mobile_images(): void
    {
        HomepageBanner::factory()
            ->published()
            ->create([
                'brand_id' => $this->brand->id,
                'title' => 'Responsive Banner',
                'desktop_image_path' =>
                    'homepage-banners/desktop/banner.jpg',
                'mobile_image_path' =>
                    'homepage-banners/mobile/banner.jpg',
            ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee(
                Storage::disk('public')->url(
                    'homepage-banners/desktop/banner.jpg'
                ),
                false
            )
            ->assertSee(
                Storage::disk('public')->url(
                    'homepage-banners/mobile/banner.jpg'
                ),
                false
            )
            ->assertSee(
                'media="(max-width: 639px)"',
                false
            );
    }

    public function test_multiple_banners_render_controls(): void
    {
        HomepageBanner::factory()
            ->published()
            ->count(2)
            ->create([
                'brand_id' => $this->brand->id,
            ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('data-homepage-slider', false)
            ->assertSee('data-slider-previous', false)
            ->assertSee('data-slider-next', false)
            ->assertSee('data-slider-indicator="0"', false)
            ->assertSee('data-slider-indicator="1"', false);
    }

    public function test_homepage_keeps_fallback_without_banner(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Portal Informasi Terpadu')
            ->assertDontSee('data-homepage-slider', false);
    }

    public function test_no_brand_context_uses_empty_banner_collection(): void
    {
        app(BrandContext::class)->clear();

        $this->get(route('home'))
            ->assertOk()
            ->assertViewHas(
                'homepageBanners',
                fn ($items): bool => $items->isEmpty()
            );
    }
}

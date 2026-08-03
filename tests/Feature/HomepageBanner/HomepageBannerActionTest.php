<?php

declare(strict_types=1);

namespace Tests\Feature\HomepageBanner;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\HomepageBanner\Actions\UpsertHomepageBannerAction;
use App\Domains\HomepageBanner\Models\HomepageBanner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

final class HomepageBannerActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_action_creates_normalized_brand_banner(): void
    {
        $brand = Brand::factory()->create();
        app(BrandContext::class)->set($brand);

        $banner = app(UpsertHomepageBannerAction::class)->execute([
            'title' => '  Banner Utama  ',
            'subtitle' => '  Informasi terbaru  ',
            'desktop_image_path' => 'homepage-banners/desktop.jpg',
            'mobile_image_path' => null,
            'cta_label' => '  Lihat Sekarang  ',
            'cta_url' => 'https://example.com/banner',
            'focal_point' => 'center',
            'status' => HomepageBanner::STATUS_DRAFT,
            'published_at' => null,
            'expires_at' => null,
            'sort_order' => 10,
            'notes' => null,
        ]);

        $this->assertSame($brand->id, $banner->brand_id);
        $this->assertSame('Banner Utama', $banner->title);
        $this->assertSame('Informasi terbaru', $banner->subtitle);
        $this->assertSame('Lihat Sekarang', $banner->cta_label);
    }

    public function test_update_does_not_move_brand(): void
    {
        $original = Brand::factory()->create();
        $context = Brand::factory()->create();

        $banner = HomepageBanner::factory()->create([
            'brand_id' => $original->id,
        ]);

        app(BrandContext::class)->set($context);

        $updated = app(UpsertHomepageBannerAction::class)->execute([
            'title' => 'Updated Banner',
            'subtitle' => null,
            'desktop_image_path' => 'homepage-banners/updated.jpg',
            'mobile_image_path' => null,
            'cta_label' => null,
            'cta_url' => null,
            'focal_point' => 'top',
            'status' => HomepageBanner::STATUS_DRAFT,
            'published_at' => null,
            'expires_at' => null,
            'sort_order' => 2,
            'notes' => null,
        ], $banner);

        $this->assertSame($original->id, $updated->brand_id);
        $this->assertSame('top', $updated->focal_point);
    }

    public function test_published_banner_requires_date(): void
    {
        $brand = Brand::factory()->create();
        app(BrandContext::class)->set($brand);

        $this->expectException(ValidationException::class);

        app(UpsertHomepageBannerAction::class)->execute([
            'title' => 'Invalid',
            'subtitle' => null,
            'desktop_image_path' => 'invalid.jpg',
            'mobile_image_path' => null,
            'cta_label' => null,
            'cta_url' => null,
            'focal_point' => 'center',
            'status' => HomepageBanner::STATUS_PUBLISHED,
            'published_at' => null,
            'expires_at' => null,
            'sort_order' => 0,
            'notes' => null,
        ]);
    }

    public function test_cta_fields_must_be_complete_and_safe(): void
    {
        $brand = Brand::factory()->create();
        app(BrandContext::class)->set($brand);

        $this->expectException(ValidationException::class);

        app(UpsertHomepageBannerAction::class)->execute([
            'title' => 'Invalid CTA',
            'subtitle' => null,
            'desktop_image_path' => 'invalid.jpg',
            'mobile_image_path' => null,
            'cta_label' => 'Klik',
            'cta_url' => 'javascript:alert(1)',
            'focal_point' => 'center',
            'status' => HomepageBanner::STATUS_DRAFT,
            'published_at' => null,
            'expires_at' => null,
            'sort_order' => 0,
            'notes' => null,
        ]);
    }
}

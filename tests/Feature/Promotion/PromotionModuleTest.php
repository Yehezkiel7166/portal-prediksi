<?php

namespace Tests\Feature\Promotion;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Promotion\Actions\UpsertPromotionAction;
use App\Domains\Promotion\Models\Promotion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PromotionModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_action_creates_normalized_promotion(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $promotion = app(UpsertPromotionAction::class)->execute([
            'title' => '  Promo Hadiah Utama  ',
            'slug' => '',
            'excerpt' => 'Ringkasan promosi.',
            'content' => 'Isi promosi.',
            'media_source' => 'url',
            'media_path' => 'must-be-cleared.jpg',
            'media_url' => 'https://example.com/promotion.jpg',
            'embed_url' => null,
            'focal_point' => 'center',
            'status' => 'draft',
            'published_at' => null,
            'sort_order' => 10,
            'notes' => null,
        ]);

        $this->assertSame('Promo Hadiah Utama', $promotion->title);
        $this->assertSame('promo-hadiah-utama', $promotion->slug);
        $this->assertSame('url', $promotion->media_source);
        $this->assertNull($promotion->media_path);
        $this->assertSame(
            'https://example.com/promotion.jpg',
            $promotion->media_url
        );
    }

    public function test_action_updates_existing_promotion(): void
    {
        $promotion = Promotion::factory()->create();

        $updated = app(UpsertPromotionAction::class)->execute([
            'title' => 'Promotion Updated',
            'slug' => 'promotion-updated',
            'excerpt' => null,
            'content' => null,
            'media_source' => 'upload',
            'media_path' => 'promotions/updated.jpg',
            'media_url' => null,
            'embed_url' => null,
            'focal_point' => 'top',
            'status' => 'draft',
            'published_at' => null,
            'sort_order' => 2,
            'notes' => null,
        ], $promotion);

        $this->assertSame($promotion->id, $updated->id);
        $this->assertSame('Promotion Updated', $updated->title);
        $this->assertSame('top', $updated->focal_point);
    }

    public function test_slug_must_be_unique(): void
    {
        Promotion::factory()->create([
            'slug' => 'promo-unik',
        ]);

        $this->expectException(ValidationException::class);

        app(UpsertPromotionAction::class)->execute([
            'title' => 'Duplicate Promotion',
            'slug' => 'promo-unik',
            'excerpt' => null,
            'content' => null,
            'media_source' => 'upload',
            'media_path' => 'promotions/duplicate.jpg',
            'media_url' => null,
            'embed_url' => null,
            'focal_point' => 'center',
            'status' => 'draft',
            'published_at' => null,
            'sort_order' => 0,
            'notes' => null,
        ]);
    }

    public function test_url_source_requires_valid_url(): void
    {
        $this->expectException(ValidationException::class);

        app(UpsertPromotionAction::class)->execute([
            'title' => 'Invalid URL Promotion',
            'slug' => 'invalid-url-promotion',
            'excerpt' => null,
            'content' => null,
            'media_source' => 'url',
            'media_path' => null,
            'media_url' => 'not-a-url',
            'embed_url' => null,
            'focal_point' => 'center',
            'status' => 'draft',
            'published_at' => null,
            'sort_order' => 0,
            'notes' => null,
        ]);
    }

    public function test_published_scope_hides_future_and_draft_records(): void
    {
        $published = Promotion::factory()->published()->create();

        Promotion::factory()->create();

        Promotion::factory()->create([
            'status' => Promotion::STATUS_PUBLISHED,
            'published_at' => now()->addDay(),
        ]);

        $results = Promotion::query()->published()->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->contains($published));
    }

    public function test_action_assigns_context_brand_when_creating_promotion(): void
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $promotion = app(UpsertPromotionAction::class)->execute([
            'title' => 'Brand Promotion',
            'slug' => 'brand-promotion',
            'excerpt' => null,
            'content' => null,
            'media_source' => Promotion::MEDIA_SOURCE_UPLOAD,
            'media_path' => 'promotions/brand.jpg',
            'media_url' => null,
            'embed_url' => null,
            'focal_point' => 'center',
            'status' => Promotion::STATUS_DRAFT,
            'published_at' => null,
            'sort_order' => 0,
            'notes' => null,
        ]);

        $this->assertSame($brand->id, $promotion->brand_id);
    }

    public function test_updating_promotion_does_not_move_it_to_context_brand(): void
    {
        $originalBrand = Brand::factory()->create();
        $contextBrand = Brand::factory()->create();

        $promotion = Promotion::factory()->create([
            'brand_id' => $originalBrand->id,
        ]);

        app(BrandContext::class)->set($contextBrand);

        $updated = app(UpsertPromotionAction::class)->execute([
            'title' => 'Updated Promotion',
            'slug' => $promotion->slug,
            'excerpt' => null,
            'content' => null,
            'media_source' => Promotion::MEDIA_SOURCE_UPLOAD,
            'media_path' => 'promotions/updated-brand.jpg',
            'media_url' => null,
            'embed_url' => null,
            'focal_point' => 'center',
            'status' => Promotion::STATUS_DRAFT,
            'published_at' => null,
            'sort_order' => 0,
            'notes' => null,
        ], $promotion);

        $this->assertSame($originalBrand->id, $updated->brand_id);
    }
}

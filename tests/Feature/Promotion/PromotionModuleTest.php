<?php

namespace Tests\Feature\Promotion;

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
}

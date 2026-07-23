<?php

namespace Tests\Feature\Frontend;

use App\Domains\Brand\Models\Brand;
use App\Domains\Promotion\Models\Promotion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPromotionFrontendTest extends TestCase
{
    use RefreshDatabase;

    public function test_listing_only_shows_published_promotions(): void
    {
        $published = Promotion::factory()->published()->create([
            'title' => 'Promosi Publik',
            'slug' => 'promosi-publik',
        ]);

        Promotion::factory()->create([
            'title' => 'Promosi Draft',
            'slug' => 'promosi-draft',
        ]);

        Promotion::factory()->create([
            'title' => 'Promosi Mendatang',
            'slug' => 'promosi-mendatang',
            'status' => Promotion::STATUS_PUBLISHED,
            'published_at' => now()->addDay(),
        ]);

        $this->get(route('promotions.index'))
            ->assertOk()
            ->assertViewIs('frontend.promotions.index')
            ->assertViewHas('promotions')
            ->assertSee($published->title)
            ->assertDontSee('Promosi Draft')
            ->assertDontSee('Promosi Mendatang');
    }

    public function test_listing_is_paginated(): void
    {
        Promotion::factory()
            ->published()
            ->count(13)
            ->create();

        $this->get(route('promotions.index'))
            ->assertOk()
            ->assertViewHas('promotions', fn ($promotions): bool =>
                $promotions->count() === 12
                && $promotions->total() === 13
            );
    }

    public function test_published_promotion_detail_can_be_opened(): void
    {
        $promotion = Promotion::factory()->published()->create([
            'title' => 'Bonus Member Terbaru',
            'slug' => 'bonus-member-terbaru',
            'excerpt' => 'Ringkasan promosi.',
            'content' => 'Informasi lengkap promosi.',
        ]);

        $this->get(route('promotions.show', $promotion->slug))
            ->assertOk()
            ->assertViewIs('frontend.promotions.show')
            ->assertViewHas(
                'promotion',
                fn ($record): bool => $record->is($promotion),
            )
            ->assertSee($promotion->title)
            ->assertSee($promotion->excerpt)
            ->assertSee($promotion->content);
    }

    public function test_unpublished_promotion_detail_returns_not_found(): void
    {
        $draft = Promotion::factory()->create([
            'slug' => 'promosi-belum-publik',
        ]);

        $future = Promotion::factory()->create([
            'slug' => 'promosi-masa-depan',
            'status' => Promotion::STATUS_PUBLISHED,
            'published_at' => now()->addDay(),
        ]);

        $this->get(route('promotions.show', $draft->slug))
            ->assertNotFound();

        $this->get(route('promotions.show', $future->slug))
            ->assertNotFound();
    }

    public function test_unknown_promotion_returns_not_found(): void
    {
        $this->get(route('promotions.show', 'promosi-tidak-ada'))
            ->assertNotFound();
    }

    public function test_listing_only_displays_promotions_for_the_current_brand(): void
    {
        config()->set('brand.default_code', 'brand-a');

        $brandA = Brand::factory()->create([
            'code' => 'brand-a',
            'name' => 'Brand A',
            'slug' => 'brand-a',
            'is_active' => true,
        ]);

        $brandB = Brand::factory()->create([
            'code' => 'brand-b',
            'name' => 'Brand B',
            'slug' => 'brand-b',
            'is_active' => true,
        ]);

        Promotion::factory()
            ->published()
            ->create([
                'brand_id' => $brandA->id,
                'title' => 'CURRENT-BRAND-PROMOTION',
                'slug' => 'current-brand-promotion',
            ]);

        Promotion::factory()
            ->published()
            ->create([
                'brand_id' => $brandB->id,
                'title' => 'OTHER-BRAND-PROMOTION',
                'slug' => 'other-brand-promotion',
            ]);

        $this->get(route('promotions.index'))
            ->assertOk()
            ->assertSee('CURRENT-BRAND-PROMOTION')
            ->assertDontSee('OTHER-BRAND-PROMOTION');
    }

    public function test_detail_does_not_display_promotion_from_another_brand(): void
    {
        config()->set('brand.default_code', 'brand-a');

        Brand::factory()->create([
            'code' => 'brand-a',
            'name' => 'Brand A',
            'slug' => 'brand-a',
            'is_active' => true,
        ]);

        $brandB = Brand::factory()->create([
            'code' => 'brand-b',
            'name' => 'Brand B',
            'slug' => 'brand-b',
            'is_active' => true,
        ]);

        Promotion::factory()
            ->published()
            ->create([
                'brand_id' => $brandB->id,
                'title' => 'OTHER BRAND PROMOTION',
                'slug' => 'other-brand-promotion',
            ]);

        $this->get(route('promotions.show', 'other-brand-promotion'))
            ->assertNotFound();
    }

}

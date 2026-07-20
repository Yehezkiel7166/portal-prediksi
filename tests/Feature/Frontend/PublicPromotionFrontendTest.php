<?php

namespace Tests\Feature\Frontend;

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
}

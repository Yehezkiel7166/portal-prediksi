<?php

declare(strict_types=1);

namespace Tests\Feature\Frontend;

use App\Domains\Blog\Models\BlogPost;
use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\LiveDraw\Models\LiveDraw;
use App\Domains\Market\Models\Market;
use App\Domains\Prediction\Models\Prediction;
use App\Domains\Promotion\Models\Promotion;
use App\Domains\Result\Models\Result;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PublicProductionHomepageTest extends TestCase
{
    use RefreshDatabase;

    private Brand $brand;

    private Market $market;

    private BrandContext $brandContext;

    protected function setUp(): void
    {
        parent::setUp();

        $this->brandContext = app(BrandContext::class);

        $this->brand = Brand::factory()->create([
            'is_active' => true,
            'is_primary' => true,
        ]);

        $this->brandContext->set($this->brand);

        $this->market = Market::factory()->create([
            'brand_id' => $this->brand->getKey(),
            'is_active' => true,
        ]);
    }

    public function test_homepage_receives_available_brand_1_production_content(): void
    {
        $liveDraw = LiveDraw::factory()->create([
            'market_id' => $this->market->getKey(),
        ]);

        $result = Result::factory()->create([
            'market_id' => $this->market->getKey(),
        ]);

        $prediction = Prediction::factory()->published()->create([
            'market_id' => $this->market->getKey(),
        ]);

        $promotion = Promotion::factory()->published()->create([
            'brand_id' => $this->brand->getKey(),
        ]);

        $article = BlogPost::factory()->published()->create([
            'brand_id' => $this->brand->getKey(),
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertViewIs('frontend.home')
            ->assertViewHas('liveDraws')
            ->assertViewHas('latestResults')
            ->assertViewHas('currentPredictions')
            ->assertViewHas('activePromotions')
            ->assertViewHas('latestArticles')
            ->assertViewHas(
                'liveDraws',
                fn ($items): bool => $items->contains($liveDraw)
            )
            ->assertViewHas(
                'latestResults',
                fn ($items): bool => $items->contains($result)
            )
            ->assertViewHas(
                'currentPredictions',
                fn ($items): bool => $items->contains($prediction)
            )
            ->assertViewHas(
                'activePromotions',
                fn ($items): bool => $items->contains($promotion)
            )
            ->assertViewHas(
                'latestArticles',
                fn ($items): bool => $items->contains($article)
            );
    }

    public function test_homepage_does_not_expose_another_brand_content(): void
    {
        $otherBrand = Brand::factory()->create([
            'is_active' => true,
            'is_primary' => false,
        ]);

        $this->brandContext->set($otherBrand);

        $otherMarket = Market::factory()->create([
            'brand_id' => $otherBrand->getKey(),
            'is_active' => true,
        ]);

        $foreignLiveDraw = LiveDraw::factory()->create([
            'market_id' => $otherMarket->getKey(),
        ]);

        $foreignResult = Result::factory()->create([
            'market_id' => $otherMarket->getKey(),
        ]);

        $foreignPrediction = Prediction::factory()->published()->create([
            'market_id' => $otherMarket->getKey(),
        ]);

        $foreignPromotion = Promotion::factory()->published()->create([
            'brand_id' => $otherBrand->getKey(),
        ]);

        $foreignArticle = BlogPost::factory()->published()->create([
            'brand_id' => $otherBrand->getKey(),
        ]);

        $this->assertSame(
            $otherBrand->getKey(),
            $foreignLiveDraw->brand_id
        );

        $this->assertSame(
            $otherBrand->getKey(),
            $foreignResult->brand_id
        );

        $this->assertSame(
            $otherBrand->getKey(),
            $foreignPrediction->brand_id
        );

        $this->assertSame(
            $otherBrand->getKey(),
            $foreignPromotion->brand_id
        );

        $this->assertSame(
            $otherBrand->getKey(),
            $foreignArticle->brand_id
        );

        $this->brandContext->set($this->brand);

        $response = $this->get(route('home'));

        $response
            ->assertOk()
            ->assertViewHas(
                'liveDraws',
                fn ($items): bool => ! $items->contains($foreignLiveDraw)
            )
            ->assertViewHas(
                'latestResults',
                fn ($items): bool => ! $items->contains($foreignResult)
            )
            ->assertViewHas(
                'currentPredictions',
                fn ($items): bool => ! $items->contains($foreignPrediction)
            )
            ->assertViewHas(
                'activePromotions',
                fn ($items): bool => ! $items->contains($foreignPromotion)
            )
            ->assertViewHas(
                'latestArticles',
                fn ($items): bool => ! $items->contains($foreignArticle)
            );
    }

    public function test_homepage_renders_required_public_sections(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Live Draw')
            ->assertSee('Data Result')
            ->assertSee('Prediksi Togel')
            ->assertSee('Slot Gacor / RTP')
            ->assertSee('Bukti Jackpot')
            ->assertSee('Promosi')
            ->assertSee('Keluhan')
            ->assertSee('Panduan')
            ->assertSee('Alat Togel');
    }

    public function test_homepage_has_minimum_seo_metadata(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('rel="canonical"', false)
            ->assertSee('property="og:title"', false)
            ->assertSee('property="og:description"', false)
            ->assertSee('property="og:url"', false);
    }

    public function test_homepage_has_safe_empty_states(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Belum ada live draw aktif')
            ->assertSee('Belum ada data result')
            ->assertSee('Belum ada prediksi aktif')
            ->assertSee('Belum ada promosi aktif');
    }

    public function test_homepage_remains_available_without_brand_context(): void
    {
        $this->brandContext->clear();

        $this->get(route('home'))
            ->assertOk()
            ->assertViewIs('frontend.home')
            ->assertViewHas(
                'liveDraws',
                fn ($items): bool => $items->isEmpty()
            )
            ->assertViewHas(
                'latestResults',
                fn ($items): bool => $items->isEmpty()
            )
            ->assertViewHas(
                'currentPredictions',
                fn ($items): bool => $items->isEmpty()
            )
            ->assertViewHas(
                'activePromotions',
                fn ($items): bool => $items->isEmpty()
            )
            ->assertViewHas(
                'latestArticles',
                fn ($items): bool => $items->isEmpty()
            );
    }
}

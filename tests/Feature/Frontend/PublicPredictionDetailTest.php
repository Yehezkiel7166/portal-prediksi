<?php

namespace Tests\Feature\Frontend;

use App\Domains\Market\Models\Market;
use App\Domains\Prediction\Models\Prediction;
use App\Http\Controllers\Frontend\PredictionDetailController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PublicPredictionDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_prediction_detail_route_uses_the_frontend_controller(): void
    {
        $market = Market::factory()->create([
            'name' => 'Singapore',
            'slug' => 'singapore',
            'code' => 'SGP',
            'is_active' => true,
        ]);

        Prediction::factory()->create([
            'market_id' => $market->id,
            'prediction_date' => '2026-07-19',
            'status' => Prediction::STATUS_PUBLISHED,
            'published_at' => now()->subMinute(),
        ]);

        $response = $this->get(route('predictions.show', [
            'marketSlug' => 'singapore',
            'predictionDate' => '2026-07-19',
        ]));

        $response
            ->assertOk()
            ->assertViewIs('frontend.predictions.show');

        $this->assertSame(
            PredictionDetailController::class,
            app('router')
                ->getRoutes()
                ->getByName('predictions.show')
                ->getActionName(),
        );
    }

    public function test_detail_displays_the_published_prediction(): void
    {
        $market = Market::factory()->create([
            'name' => 'Singapore',
            'slug' => 'singapore',
            'code' => 'SGP',
            'timezone' => 'Asia/Singapore',
            'is_active' => true,
        ]);

        $prediction = Prediction::factory()->create([
            'market_id' => $market->id,
            'prediction_date' => '2026-07-19',
            'predicted_numbers' => '12 34 56 78',
            'notes' => 'Catatan prediksi detail.',
            'status' => Prediction::STATUS_PUBLISHED,
            'published_at' => now()->subMinute(),
        ]);

        $response = $this->get(route('predictions.show', [
            'marketSlug' => 'singapore',
            'predictionDate' => '2026-07-19',
        ]));

        $response
            ->assertOk()
            ->assertViewIs('frontend.predictions.show')
            ->assertViewHas(
                'prediction',
                fn (Prediction $viewPrediction): bool =>
                    $viewPrediction->is($prediction),
            )
            ->assertSee('Prediksi Singapore')
            ->assertSee('SGP')
            ->assertSee('Asia/Singapore')
            ->assertSee('12 34 56 78')
            ->assertSee('Catatan prediksi detail.')
            ->assertSee('19 Juli 2026')
            ->assertSee(route('predictions.index', [
                'market' => 'singapore',
            ]));
    }

    public function test_draft_prediction_detail_returns_not_found(): void
    {
        $market = Market::factory()->create([
            'slug' => 'singapore',
            'is_active' => true,
        ]);

        Prediction::factory()->create([
            'market_id' => $market->id,
            'prediction_date' => '2026-07-19',
            'status' => Prediction::STATUS_DRAFT,
            'published_at' => null,
        ]);

        $this->get(route('predictions.show', [
            'marketSlug' => 'singapore',
            'predictionDate' => '2026-07-19',
        ]))->assertNotFound();
    }

    public function test_future_published_prediction_detail_returns_not_found(): void
    {
        $market = Market::factory()->create([
            'slug' => 'singapore',
            'is_active' => true,
        ]);

        Prediction::factory()->create([
            'market_id' => $market->id,
            'prediction_date' => '2026-07-19',
            'status' => Prediction::STATUS_PUBLISHED,
            'published_at' => now()->addMinute(),
        ]);

        $this->get(route('predictions.show', [
            'marketSlug' => 'singapore',
            'predictionDate' => '2026-07-19',
        ]))->assertNotFound();
    }

    public function test_prediction_from_inactive_market_returns_not_found(): void
    {
        $market = Market::factory()->create([
            'slug' => 'singapore',
            'is_active' => false,
        ]);

        Prediction::factory()->create([
            'market_id' => $market->id,
            'prediction_date' => '2026-07-19',
            'status' => Prediction::STATUS_PUBLISHED,
            'published_at' => now()->subMinute(),
        ]);

        $this->get(route('predictions.show', [
            'marketSlug' => 'singapore',
            'predictionDate' => '2026-07-19',
        ]))->assertNotFound();
    }

    public function test_detail_requires_matching_market_slug_and_date(): void
    {
        $market = Market::factory()->create([
            'slug' => 'singapore',
            'is_active' => true,
        ]);

        Prediction::factory()->create([
            'market_id' => $market->id,
            'prediction_date' => '2026-07-19',
            'status' => Prediction::STATUS_PUBLISHED,
            'published_at' => now()->subMinute(),
        ]);

        $this->get(route('predictions.show', [
            'marketSlug' => 'sydney',
            'predictionDate' => '2026-07-19',
        ]))->assertNotFound();

        $this->get(route('predictions.show', [
            'marketSlug' => 'singapore',
            'predictionDate' => '2026-07-20',
        ]))->assertNotFound();
    }
}

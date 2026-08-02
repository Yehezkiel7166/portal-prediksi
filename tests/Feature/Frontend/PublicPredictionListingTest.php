<?php

namespace Tests\Feature\Frontend;

use App\Domains\Brand\Models\Brand;
use App\Domains\Market\Models\Market;
use App\Domains\Prediction\Models\Prediction;
use App\Http\Controllers\Frontend\PredictionsController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PublicPredictionListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_prediction_route_uses_the_frontend_controller(): void
    {
        $response = $this->get(route('predictions.index'));

        $response
            ->assertOk()
            ->assertViewIs('frontend.predictions.index')
            ->assertSee('Prediksi Togel Terbaru')
            ->assertSee('Tidak ada prediksi yang ditemukan');

        $this->assertSame(
            PredictionsController::class,
            app('router')
                ->getRoutes()
                ->getByName('predictions.index')
                ->getActionName(),
        );
    }

    public function test_listing_displays_only_published_predictions_from_active_markets(): void
    {
        $activeMarket = Market::factory()->create([
            'name' => 'Singapore',
            'code' => 'SGP',
            'is_active' => true,
        ]);

        $inactiveMarket = Market::factory()->create([
            'name' => 'Inactive Market',
            'code' => 'OFF',
            'is_active' => false,
        ]);

        Prediction::factory()->create([
            'market_id' => $activeMarket->id,
            'prediction_date' => '2026-07-19',
            'predicted_numbers' => '12 34 56 78',
            'notes' => 'Prediksi publik aktif.',
            'status' => Prediction::STATUS_PUBLISHED,
            'published_at' => now()->subMinute(),
        ]);

        Prediction::factory()->create([
            'market_id' => $activeMarket->id,
            'prediction_date' => '2026-07-20',
            'predicted_numbers' => 'DRAFT-HIDDEN',
            'status' => Prediction::STATUS_DRAFT,
            'published_at' => null,
        ]);

        Prediction::factory()->create([
            'market_id' => $activeMarket->id,
            'prediction_date' => '2026-07-21',
            'predicted_numbers' => 'FUTURE-HIDDEN',
            'status' => Prediction::STATUS_PUBLISHED,
            'published_at' => now()->addDay(),
        ]);

        Prediction::factory()->create([
            'market_id' => $inactiveMarket->id,
            'prediction_date' => '2026-07-22',
            'predicted_numbers' => 'INACTIVE-HIDDEN',
            'status' => Prediction::STATUS_PUBLISHED,
            'published_at' => now()->subMinute(),
        ]);

        $response = $this->get(route('predictions.index'));

        $response
            ->assertOk()
            ->assertSee('Singapore')
            ->assertSee('SGP')
            ->assertSee('12 34 56 78')
            ->assertSee('Prediksi publik aktif.')
            ->assertDontSee('DRAFT-HIDDEN')
            ->assertDontSee('FUTURE-HIDDEN')
            ->assertDontSee('INACTIVE-HIDDEN')
            ->assertDontSee('Inactive Market');
    }

    public function test_listing_orders_newest_prediction_date_first(): void
    {
        $olderMarket = Market::factory()->create([
            'name' => 'Older Market',
            'code' => 'OLD',
            'is_active' => true,
        ]);

        $newerMarket = Market::factory()->create([
            'name' => 'Newer Market',
            'code' => 'NEW',
            'is_active' => true,
        ]);

        Prediction::factory()->create([
            'market_id' => $olderMarket->id,
            'prediction_date' => '2026-07-18',
            'predicted_numbers' => 'OLDER-NUMBERS',
            'status' => Prediction::STATUS_PUBLISHED,
            'published_at' => now()->subMinutes(2),
        ]);

        Prediction::factory()->create([
            'market_id' => $newerMarket->id,
            'prediction_date' => '2026-07-19',
            'predicted_numbers' => 'NEWER-NUMBERS',
            'status' => Prediction::STATUS_PUBLISHED,
            'published_at' => now()->subMinute(),
        ]);

        $response = $this->get(route('predictions.index'));

        $response
            ->assertOk()
            ->assertSeeInOrder([
                'Newer Market',
                'NEWER-NUMBERS',
                'Older Market',
                'OLDER-NUMBERS',
            ]);
    }

    public function test_listing_paginates_predictions_by_twelve_records(): void
    {
        $market = Market::factory()->create([
            'name' => 'Pagination Market',
            'code' => 'PAGE',
            'is_active' => true,
        ]);

        foreach (range(1, 13) as $index) {
            Prediction::factory()->create([
                'market_id' => $market->id,
                'prediction_date' => now()
                    ->subDays($index)
                    ->toDateString(),
                'predicted_numbers' => sprintf(
                    'PREDICTION-%02d',
                    $index,
                ),
                'status' => Prediction::STATUS_PUBLISHED,
                'published_at' => now()->subMinutes($index),
            ]);
        }

        $firstPage = $this->get(route('predictions.index'));

        $firstPage
            ->assertOk()
            ->assertViewHas(
                'predictions',
                fn ($predictions): bool =>
                    $predictions->perPage() === 12
                    && $predictions->total() === 13
                    && $predictions->count() === 12,
            )
            ->assertSee('PREDICTION-01')
            ->assertDontSee('PREDICTION-13');

        $secondPage = $this->get(
            route('predictions.index', ['page' => 2]),
        );

        $secondPage
            ->assertOk()
            ->assertViewHas(
                'predictions',
                fn ($predictions): bool =>
                    $predictions->currentPage() === 2
                    && $predictions->count() === 1,
            )
            ->assertSee('PREDICTION-13')
            ->assertDontSee('PREDICTION-01');
    }

    public function test_listing_only_displays_predictions_for_the_current_brand(): void
    {

        $brandA = Brand::factory()->create([
            'code' => 'brand-a',
            'domain' => 'brand-a.test',
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

        $marketA = Market::factory()->create([
            'brand_id' => $brandA->id,
            'name' => 'Market Brand A',
            'code' => 'BRA',
            'slug' => 'market-brand-a',
            'is_active' => true,
        ]);

        $marketB = Market::factory()->create([
            'brand_id' => $brandB->id,
            'name' => 'Market Brand B',
            'code' => 'BRB',
            'slug' => 'market-brand-b',
            'is_active' => true,
        ]);

        Prediction::factory()->create([
            'brand_id' => $brandA->id,
            'market_id' => $marketA->id,
            'prediction_date' => '2026-07-20',
            'predicted_numbers' => 'CURRENT-BRAND-PREDICTION',
            'status' => Prediction::STATUS_PUBLISHED,
            'published_at' => now()->subMinute(),
        ]);

        Prediction::factory()->create([
            'brand_id' => $brandB->id,
            'market_id' => $marketB->id,
            'prediction_date' => '2026-07-20',
            'predicted_numbers' => 'OTHER-BRAND-PREDICTION',
            'status' => Prediction::STATUS_PUBLISHED,
            'published_at' => now()->subMinute(),
        ]);

        $this->get('http://brand-a.test'.parse_url(route('predictions.index'), PHP_URL_PATH))
            ->assertOk()
            ->assertSee('CURRENT-BRAND-PREDICTION')
            ->assertSee('Market Brand A')
            ->assertDontSee('OTHER-BRAND-PREDICTION')
            ->assertDontSee('Market Brand B');
    }
    public function test_listing_displays_structured_prediction_fields(): void
    {
        $market = Market::factory()->create([
            'name' => 'Bogota',
            'code' => 'BGT',
            'slug' => 'bogota',
            'is_active' => true,
        ]);

        Prediction::factory()->create([
            'market_id' => $market->id,
            'prediction_date' => '2026-08-02',
            'bbfs' => '209184',
            'colok_bebas' => '9-4',
            'prediction_2d' => '18,91,82',
            'prediction_3d' => '028,492',
            'prediction_4d' => '9482,8491',
            'kembar' => '88,99',
            'shio' => 'TIKUS',
            'status' => Prediction::STATUS_PUBLISHED,
            'published_at' => now()->subMinute(),
        ]);

        $this->get(route('predictions.index'))
            ->assertOk()
            ->assertSee('BBFS')
            ->assertSee('209184')
            ->assertSee('Colok Bebas')
            ->assertSee('9-4')
            ->assertSee('18,91,82')
            ->assertSee('028,492')
            ->assertSee('9482,8491')
            ->assertSee('88,99')
            ->assertSee('TIKUS');
    }
}
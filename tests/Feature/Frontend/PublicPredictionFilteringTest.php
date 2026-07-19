<?php

namespace Tests\Feature\Frontend;

use App\Domains\Market\Models\Market;
use App\Domains\Prediction\Models\Prediction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PublicPredictionFilteringTest extends TestCase
{
    use RefreshDatabase;

    public function test_filter_options_only_include_active_markets_in_configured_order(): void
    {
        Market::factory()->create([
            'name' => 'Second Market',
            'slug' => 'second-market',
            'code' => 'SEC',
            'is_active' => true,
            'sort_order' => 20,
        ]);

        Market::factory()->create([
            'name' => 'First Market',
            'slug' => 'first-market',
            'code' => 'FIR',
            'is_active' => true,
            'sort_order' => 10,
        ]);

        Market::factory()->create([
            'name' => 'Hidden Market',
            'slug' => 'hidden-market',
            'code' => 'HID',
            'is_active' => false,
            'sort_order' => 1,
        ]);

        $response = $this->get(route('predictions.index'));

        $response
            ->assertOk()
            ->assertSeeInOrder([
                'First Market',
                'Second Market',
            ])
            ->assertDontSee('Hidden Market')
            ->assertViewHas(
                'markets',
                fn ($markets): bool =>
                    $markets->pluck('slug')->all() === [
                        'first-market',
                        'second-market',
                    ],
            );
    }

    public function test_listing_can_be_filtered_by_active_market_slug(): void
    {
        $singapore = Market::factory()->create([
            'name' => 'Singapore',
            'slug' => 'singapore',
            'code' => 'SGP',
            'is_active' => true,
        ]);

        $sydney = Market::factory()->create([
            'name' => 'Sydney',
            'slug' => 'sydney',
            'code' => 'SDY',
            'is_active' => true,
        ]);

        $this->createPublishedPrediction(
            market: $singapore,
            date: '2026-07-19',
            numbers: 'SINGAPORE-NUMBERS',
        );

        $this->createPublishedPrediction(
            market: $sydney,
            date: '2026-07-19',
            numbers: 'SYDNEY-NUMBERS',
        );

        $response = $this->get(route('predictions.index', [
            'market' => 'singapore',
        ]));

        $response
            ->assertOk()
            ->assertSee('SINGAPORE-NUMBERS')
            ->assertDontSee('SYDNEY-NUMBERS')
            ->assertSee('value="singapore"', false)
            ->assertSee('selected', false)
            ->assertSee('Filter aktif');
    }

    public function test_listing_can_be_filtered_by_prediction_date(): void
    {
        $market = Market::factory()->create([
            'name' => 'Singapore',
            'slug' => 'singapore',
            'code' => 'SGP',
            'is_active' => true,
        ]);

        $this->createPublishedPrediction(
            market: $market,
            date: '2026-07-18',
            numbers: 'OLDER-DATE',
        );

        $this->createPublishedPrediction(
            market: $market,
            date: '2026-07-19',
            numbers: 'FILTERED-DATE',
        );

        $response = $this->get(route('predictions.index', [
            'date' => '2026-07-19',
        ]));

        $response
            ->assertOk()
            ->assertSee('FILTERED-DATE')
            ->assertDontSee('OLDER-DATE')
            ->assertSee('value="2026-07-19"', false);
    }

    public function test_market_and_date_filters_can_be_combined(): void
    {
        $singapore = Market::factory()->create([
            'name' => 'Singapore',
            'slug' => 'singapore',
            'code' => 'SGP',
            'is_active' => true,
        ]);

        $sydney = Market::factory()->create([
            'name' => 'Sydney',
            'slug' => 'sydney',
            'code' => 'SDY',
            'is_active' => true,
        ]);

        $this->createPublishedPrediction(
            market: $singapore,
            date: '2026-07-19',
            numbers: 'EXPECTED-COMBINED',
        );

        $this->createPublishedPrediction(
            market: $singapore,
            date: '2026-07-18',
            numbers: 'WRONG-DATE',
        );

        $this->createPublishedPrediction(
            market: $sydney,
            date: '2026-07-19',
            numbers: 'WRONG-MARKET',
        );

        $response = $this->get(route('predictions.index', [
            'market' => 'singapore',
            'date' => '2026-07-19',
        ]));

        $response
            ->assertOk()
            ->assertSee('EXPECTED-COMBINED')
            ->assertDontSee('WRONG-DATE')
            ->assertDontSee('WRONG-MARKET');
    }

    public function test_invalid_market_filter_redirects_to_clean_listing(): void
    {
        $response = $this
            ->from(route('predictions.index'))
            ->get(route('predictions.index', [
                'market' => 'unknown-market',
            ]));

        $response
            ->assertRedirect(route('predictions.index'))
            ->assertSessionHasErrors(['market']);
    }

    public function test_invalid_date_filter_redirects_to_clean_listing(): void
    {
        $response = $this
            ->from(route('predictions.index'))
            ->get(route('predictions.index', [
                'date' => '19-07-2026',
            ]));

        $response
            ->assertRedirect(route('predictions.index'))
            ->assertSessionHasErrors(['date']);
    }

    public function test_pagination_preserves_active_filter_query_parameters(): void
    {
        $market = Market::factory()->create([
            'name' => 'Singapore',
            'slug' => 'singapore',
            'code' => 'SGP',
            'is_active' => true,
        ]);

        foreach (range(1, 13) as $index) {
            $this->createPublishedPrediction(
                market: $market,
                date: now()->subDays($index)->toDateString(),
                numbers: sprintf('FILTERED-PAGE-%02d', $index),
                publishedAt: now()->subMinutes($index),
            );
        }

        $response = $this->get(route('predictions.index', [
            'market' => 'singapore',
        ]));

        $response
            ->assertOk()
            ->assertViewHas(
                'predictions',
                function ($predictions): bool {
                    $url = $predictions->url(2);
                    $query = [];

                    parse_str(
                        (string) parse_url($url, PHP_URL_QUERY),
                        $query,
                    );

                    return $predictions->total() === 13
                        && $predictions->perPage() === 12
                        && ($query['page'] ?? null) === '2'
                        && ($query['market'] ?? null) === 'singapore'
                        && ! array_key_exists('date', $query);
                },
            );
    }

    private function createPublishedPrediction(
        Market $market,
        string $date,
        string $numbers,
        mixed $publishedAt = null,
    ): Prediction {
        return Prediction::factory()->create([
            'market_id' => $market->id,
            'prediction_date' => $date,
            'predicted_numbers' => $numbers,
            'status' => Prediction::STATUS_PUBLISHED,
            'published_at' => $publishedAt ?? now()->subMinute(),
        ]);
    }
}

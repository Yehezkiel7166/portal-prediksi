<?php

namespace Tests\Feature\Frontend;

use App\Domains\Brand\Models\Brand;
use App\Domains\Market\Models\Market;
use App\Domains\Result\Models\Result;
use App\Http\Controllers\Frontend\MarketResultHistoryController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PublicResultHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_history_route_uses_controller(): void
    {
        $market = Market::factory()->create([
            'slug' => 'singapore',
            'is_active' => true,
        ]);

        $this->get(route('results.history', [
            'marketSlug' => $market->slug,
        ]))
            ->assertOk()
            ->assertViewIs(
                'frontend.results.history'
            );

        $this->assertSame(
            MarketResultHistoryController::class,
            app('router')
                ->getRoutes()
                ->getByName('results.history')
                ->getActionName(),
        );
    }

    public function test_history_only_displays_selected_market(): void
    {
        $market = Market::factory()->create([
            'name' => 'Singapore',
            'slug' => 'singapore',
            'is_active' => true,
        ]);

        $other = Market::factory()->create([
            'name' => 'Sydney',
            'slug' => 'sydney',
            'is_active' => true,
        ]);

        Result::factory()->create([
            'market_id' => $market->id,
            'winning_numbers' => 'EXPECTED-HISTORY',
        ]);

        Result::factory()->create([
            'market_id' => $other->id,
            'winning_numbers' => 'OTHER-HISTORY',
        ]);

        $this->get(route('results.history', [
            'marketSlug' => $market->slug,
        ]))
            ->assertOk()
            ->assertSee('EXPECTED-HISTORY')
            ->assertDontSee('OTHER-HISTORY');
    }

    public function test_history_is_paginated_by_twenty(): void
    {
        $market = Market::factory()->create([
            'slug' => 'singapore',
            'is_active' => true,
        ]);

        foreach (range(1, 21) as $index) {
            Result::factory()->create([
                'market_id' => $market->id,
                'result_date' => now()
                    ->subDays($index)
                    ->toDateString(),
                'winning_numbers' => sprintf(
                    'HISTORY-%02d',
                    $index,
                ),
            ]);
        }

        $this->get(route('results.history', [
            'marketSlug' => $market->slug,
        ]))
            ->assertOk()
            ->assertViewHas(
                'results',
                fn ($results): bool => $results->perPage() === 20
                    && $results->total() === 21,
            );
    }

    public function test_history_does_not_cross_brand(): void
    {
        $brandA = Brand::factory()->create([
            'domain' => 'brand-a.test',
            'is_active' => true,
        ]);

        $brandB = Brand::factory()->create([
            'is_active' => true,
        ]);

        Market::factory()->create([
            'brand_id' => $brandA->id,
            'slug' => 'brand-a-market',
            'is_active' => true,
        ]);

        $marketB = Market::factory()->create([
            'brand_id' => $brandB->id,
            'slug' => 'brand-b-market',
            'is_active' => true,
        ]);

        Result::factory()->create([
            'brand_id' => $brandB->id,
            'market_id' => $marketB->id,
            'winning_numbers' => 'OTHER-BRAND-HISTORY',
        ]);

        $path = parse_url(
            route('results.history', [
                'marketSlug' => 'brand-b-market',
            ]),
            PHP_URL_PATH,
        );

        $this->get(
            'http://brand-a.test'.$path
        )->assertNotFound();
    }
}

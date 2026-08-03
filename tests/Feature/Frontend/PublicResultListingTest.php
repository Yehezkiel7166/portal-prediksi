<?php

namespace Tests\Feature\Frontend;

use App\Domains\Brand\Models\Brand;
use App\Domains\Market\Models\Market;
use App\Domains\Result\Models\Result;
use App\Http\Controllers\Frontend\ResultsController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PublicResultListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_result_route_uses_frontend_controller(): void
    {
        $this->get(route('results.index'))
            ->assertOk()
            ->assertViewIs(
                'frontend.results.index'
            )
            ->assertSee(
                'Result Terbaru Setiap Pasaran'
            );

        $this->assertSame(
            ResultsController::class,
            app('router')
                ->getRoutes()
                ->getByName('results.index')
                ->getActionName(),
        );
    }

    public function test_listing_displays_one_card_per_active_market(): void
    {
        $market = Market::factory()->create([
            'name' => 'Singapore',
            'code' => 'SGP',
            'slug' => 'singapore',
            'is_active' => true,
        ]);

        Result::factory()->create([
            'market_id' => $market->id,
            'result_date' => '2026-07-18',
            'winning_numbers' => 'OLDER',
        ]);

        Result::factory()->create([
            'market_id' => $market->id,
            'result_date' => '2026-07-19',
            'winning_numbers' => 'LATEST',
        ]);

        $this->get(route('results.index'))
            ->assertOk()
            ->assertSee('Singapore')
            ->assertSee('LATEST')
            ->assertDontSee('OLDER')
            ->assertViewHas(
                'markets',
                fn ($markets): bool => $markets->total() === 1
                    && $markets
                        ->first()
                        ->results_count === 2,
            );
    }

    public function test_market_without_result_is_displayed(): void
    {
        Market::factory()->create([
            'name' => 'Empty Market',
            'code' => 'EMP',
            'is_active' => true,
        ]);

        $this->get(route('results.index'))
            ->assertOk()
            ->assertSee('Empty Market')
            ->assertSee('Belum ada result.');
    }

    public function test_inactive_market_is_not_displayed(): void
    {
        Market::factory()->create([
            'name' => 'Inactive Market',
            'is_active' => false,
        ]);

        $this->get(route('results.index'))
            ->assertOk()
            ->assertDontSee('Inactive Market');
    }

    public function test_listing_paginates_markets_by_twelve(): void
    {
        foreach (range(1, 13) as $index) {
            Market::factory()->create([
                'name' => sprintf(
                    'Market %02d',
                    $index,
                ),
                'code' => sprintf(
                    'M%02d',
                    $index,
                ),
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }

        $this->get(route('results.index'))
            ->assertOk()
            ->assertViewHas(
                'markets',
                fn ($markets): bool => $markets->perPage() === 12
                    && $markets->total() === 13
                    && $markets->count() === 12,
            )
            ->assertSee('Market 01');

        $this->get(route('results.index', [
            'page' => 2,
        ]))
            ->assertOk()
            ->assertViewHas(
                'markets',
                fn ($markets): bool => $markets->currentPage() === 2
                    && $markets->count() === 1
                    && $markets->first()->name === 'Market 13',
            )
            ->assertSee('Market 13');
    }

    public function test_header_links_to_public_result_listing(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(
                route('results.index'),
                false,
            );
    }

    public function test_listing_only_displays_current_brand(): void
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
            'slug' => 'market-brand-a',
            'is_active' => true,
        ]);

        $marketB = Market::factory()->create([
            'brand_id' => $brandB->id,
            'name' => 'Market Brand B',
            'slug' => 'market-brand-b',
            'is_active' => true,
        ]);

        Result::factory()->create([
            'brand_id' => $brandA->id,
            'market_id' => $marketA->id,
            'winning_numbers' => 'CURRENT-BRAND',
        ]);

        Result::factory()->create([
            'brand_id' => $brandB->id,
            'market_id' => $marketB->id,
            'winning_numbers' => 'OTHER-BRAND',
        ]);

        $path = parse_url(
            route('results.index'),
            PHP_URL_PATH,
        );

        $this->get(
            'http://brand-a.test'.$path
        )
            ->assertOk()
            ->assertSee('CURRENT-BRAND')
            ->assertDontSee('OTHER-BRAND')
            ->assertDontSee('Market Brand B');
    }
}

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

    public function test_public_result_route_uses_the_frontend_controller(): void
    {
        $response = $this->get(route('results.index'));

        $response
            ->assertOk()
            ->assertViewIs('frontend.results.index')
            ->assertSee('Data Result Togel Terbaru')
            ->assertSee('Belum ada data result');

        $this->assertSame(
            ResultsController::class,
            app('router')
                ->getRoutes()
                ->getByName('results.index')
                ->getActionName(),
        );
    }

    public function test_listing_displays_results_from_active_markets_only(): void
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

        Result::factory()->create([
            'market_id' => $activeMarket->id,
            'result_date' => '2026-07-19',
            'winning_numbers' => '1234',
            'notes' => 'Result publik aktif.',
        ]);

        Result::factory()->create([
            'market_id' => $inactiveMarket->id,
            'result_date' => '2026-07-19',
            'winning_numbers' => 'INACTIVE-HIDDEN',
        ]);

        $response = $this->get(route('results.index'));

        $response
            ->assertOk()
            ->assertSee('Singapore')
            ->assertSee('SGP')
            ->assertSee('1234')
            ->assertSee('Result publik aktif.')
            ->assertDontSee('Inactive Market')
            ->assertDontSee('INACTIVE-HIDDEN');
    }

    public function test_listing_orders_newest_result_date_first(): void
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

        Result::factory()->create([
            'market_id' => $olderMarket->id,
            'result_date' => '2026-07-18',
            'winning_numbers' => 'OLDER-RESULT',
        ]);

        Result::factory()->create([
            'market_id' => $newerMarket->id,
            'result_date' => '2026-07-19',
            'winning_numbers' => 'NEWER-RESULT',
        ]);

        $response = $this->get(route('results.index'));

        $response
            ->assertOk()
            ->assertSeeInOrder([
                'Newer Market',
                'NEWER-RESULT',
                'Older Market',
                'OLDER-RESULT',
            ]);
    }

    public function test_listing_paginates_results_by_twelve_records(): void
    {
        $market = Market::factory()->create([
            'name' => 'Pagination Market',
            'code' => 'PAGE',
            'is_active' => true,
        ]);

        foreach (range(1, 13) as $index) {
            Result::factory()->create([
                'market_id' => $market->id,
                'result_date' => now()
                    ->subDays($index)
                    ->toDateString(),
                'winning_numbers' => sprintf(
                    'RESULT-%02d',
                    $index,
                ),
            ]);
        }

        $firstPage = $this->get(route('results.index'));

        $firstPage
            ->assertOk()
            ->assertViewHas(
                'results',
                fn ($results): bool =>
                    $results->perPage() === 12
                    && $results->total() === 13
                    && $results->count() === 12,
            )
            ->assertSee('RESULT-01')
            ->assertDontSee('RESULT-13');

        $secondPage = $this->get(
            route('results.index', ['page' => 2]),
        );

        $secondPage
            ->assertOk()
            ->assertViewHas(
                'results',
                fn ($results): bool =>
                    $results->currentPage() === 2
                    && $results->count() === 1,
            )
            ->assertSee('RESULT-13')
            ->assertDontSee('RESULT-01');
    }

    public function test_header_links_to_the_public_result_listing(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('results.index'), false);
    }

    public function test_listing_only_displays_results_for_the_current_brand(): void
    {
        config()->set('brand.default_code','brand-a');

        $brandA=Brand::factory()->create([
            'code'=>'brand-a',
            'name'=>'Brand A',
            'slug'=>'brand-a',
            'is_active'=>true,
        ]);

        $brandB=Brand::factory()->create([
            'code'=>'brand-b',
            'name'=>'Brand B',
            'slug'=>'brand-b',
            'is_active'=>true,
        ]);

        $marketA=Market::factory()->create([
            'brand_id'=>$brandA->id,
            'name'=>'Market Brand A',
            'code'=>'BRA',
            'slug'=>'market-brand-a',
            'is_active'=>true,
        ]);

        $marketB=Market::factory()->create([
            'brand_id'=>$brandB->id,
            'name'=>'Market Brand B',
            'code'=>'BRB',
            'slug'=>'market-brand-b',
            'is_active'=>true,
        ]);

        Result::factory()->create([
            'brand_id'=>$brandA->id,
            'market_id'=>$marketA->id,
            'result_date'=>'2026-07-20',
            'winning_numbers'=>'CURRENT-BRAND-RESULT',
        ]);

        Result::factory()->create([
            'brand_id'=>$brandB->id,
            'market_id'=>$marketB->id,
            'result_date'=>'2026-07-20',
            'winning_numbers'=>'OTHER-BRAND-RESULT',
        ]);

        $this->get(route('results.index'))
            ->assertOk()
            ->assertSee('CURRENT-BRAND-RESULT')
            ->assertSee('Market Brand A')
            ->assertDontSee('OTHER-BRAND-RESULT')
            ->assertDontSee('Market Brand B');
    }

}

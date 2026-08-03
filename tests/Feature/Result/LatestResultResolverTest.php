<?php

namespace Tests\Feature\Result;

use App\Domains\Market\Models\Market;
use App\Domains\Result\Models\Result;
use App\Domains\Result\Support\LatestResultResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class LatestResultResolverTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_latest_result_for_market(): void
    {
        $market = Market::factory()->create();

        Result::factory()->create([
            'market_id' => $market->id,
            'result_date' => '2026-07-18',
            'winning_numbers' => 'OLDER',
        ]);

        $latest = Result::factory()->create([
            'market_id' => $market->id,
            'result_date' => '2026-07-20',
            'winning_numbers' => 'LATEST',
        ]);

        $resolved = app(LatestResultResolver::class)
            ->forMarket($market);

        $this->assertNotNull($resolved);
        $this->assertTrue($resolved->is($latest));
        $this->assertSame('LATEST', $resolved->winning_numbers);
    }

    public function test_it_only_returns_result_from_requested_market(): void
    {
        $requestedMarket = Market::factory()->create();
        $otherMarket = Market::factory()->create();

        $requestedResult = Result::factory()->create([
            'market_id' => $requestedMarket->id,
            'result_date' => '2026-07-19',
            'winning_numbers' => 'REQUESTED',
        ]);

        Result::factory()->create([
            'market_id' => $otherMarket->id,
            'result_date' => '2026-07-20',
            'winning_numbers' => 'OTHER',
        ]);

        $resolved = app(LatestResultResolver::class)
            ->forMarketId($requestedMarket->id);

        $this->assertNotNull($resolved);
        $this->assertTrue($resolved->is($requestedResult));
        $this->assertSame(
            $requestedMarket->id,
            $resolved->market_id,
        );
    }

    public function test_it_attaches_latest_results_to_markets(): void
    {
        $marketA = Market::factory()->create();
        $marketB = Market::factory()->create();

        Result::factory()->create([
            'market_id' => $marketA->id,
            'result_date' => '2026-07-18',
            'winning_numbers' => 'A-OLD',
        ]);

        Result::factory()->create([
            'market_id' => $marketA->id,
            'result_date' => '2026-07-20',
            'winning_numbers' => 'A-LATEST',
        ]);

        Result::factory()->create([
            'market_id' => $marketB->id,
            'result_date' => '2026-07-19',
            'winning_numbers' => 'B-LATEST',
        ]);

        $markets = Market::query()
            ->whereKey([
                $marketA->id,
                $marketB->id,
            ])
            ->get();

        app(LatestResultResolver::class)
            ->attachToMarkets($markets);

        $this->assertSame(
            'A-LATEST',
            $markets
                ->firstWhere('id', $marketA->id)
                ->getRelation('latestResult')
                ->winning_numbers,
        );

        $this->assertSame(
            'B-LATEST',
            $markets
                ->firstWhere('id', $marketB->id)
                ->getRelation('latestResult')
                ->winning_numbers,
        );
    }

    public function test_it_returns_null_when_market_has_no_results(): void
    {
        $market = Market::factory()->create();

        $resolved = app(LatestResultResolver::class)
            ->forMarketId($market->id);

        $this->assertNull($resolved);
    }
}

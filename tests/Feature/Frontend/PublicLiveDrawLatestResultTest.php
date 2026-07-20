<?php

namespace Tests\Feature\Frontend;

use App\Domains\LiveDraw\Models\LiveDraw;
use App\Domains\Market\Models\Market;
use App\Domains\Result\Models\Result;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PublicLiveDrawLatestResultTest extends TestCase
{
    use RefreshDatabase;

    public function test_controller_attaches_latest_result_relation(): void
    {
        $market = Market::factory()->create([
            'is_active' => true,
        ]);

        Result::factory()->create([
            'market_id' => $market->id,
            'result_date' => '2026-07-18',
            'winning_numbers' => '1111',
        ]);

        $latest = Result::factory()->create([
            'market_id' => $market->id,
            'result_date' => '2026-07-20',
            'winning_numbers' => '9999',
        ]);

        LiveDraw::factory()->create([
            'market_id' => $market->id,
            'status' => LiveDraw::STATUS_FINISHED,
        ]);

        $response = $this->get(route('live-draw.index'));

        $response->assertOk();

        $draw = $response->viewData('liveDraws')->first();

        $this->assertTrue(
            $draw->relationLoaded('latestResult')
        );

        $this->assertTrue(
            $draw->latestResult->is($latest)
        );

        $this->assertSame(
            '9999',
            $draw->latestResult->winning_numbers
        );
    }
}

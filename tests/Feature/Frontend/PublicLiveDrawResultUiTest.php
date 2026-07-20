<?php

namespace Tests\Feature\Frontend;

use App\Domains\LiveDraw\Models\LiveDraw;
use App\Domains\Market\Models\Market;
use App\Domains\Result\Models\Result;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PublicLiveDrawResultUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_live_draw_displays_latest_market_result(): void
    {
        $market = Market::factory()->create([
            'name' => 'Singapore',
            'slug' => 'singapore',
            'is_active' => true,
        ]);

        Result::factory()->create([
            'market_id' => $market->id,
            'result_date' => '2026-07-18',
            'winning_numbers' => '1111',
        ]);

        Result::factory()->create([
            'market_id' => $market->id,
            'result_date' => '2026-07-20',
            'winning_numbers' => '9876',
        ]);

        LiveDraw::factory()->create([
            'market_id' => $market->id,
            'title' => 'Singapore Live Draw',
            'status' => LiveDraw::STATUS_SCHEDULED,
        ]);

        $this->get(route('live-draw.index'))
            ->assertOk()
            ->assertSee('Hasil terbaru')
            ->assertSee('20-07-2026')
            ->assertSee('Nomor keluar')
            ->assertSee('9876')
            ->assertSee(
                route('results.index', [
                    'market' => $market->slug,
                ]),
                false,
            )
            ->assertDontSee('1111');
    }

    public function test_current_live_stream_hides_previous_result_panel(): void
    {
        $market = Market::factory()->create([
            'name' => 'Live Market',
            'is_active' => true,
        ]);

        Result::factory()->create([
            'market_id' => $market->id,
            'result_date' => '2026-07-20',
            'winning_numbers' => '2468',
        ]);

        LiveDraw::factory()->create([
            'market_id' => $market->id,
            'title' => 'Current Live Draw',
            'provider' => LiveDraw::PROVIDER_YOUTUBE,
            'stream_type' => LiveDraw::STREAM_TYPE_IFRAME,
            'source_url' =>
                'https://www.youtube.com/watch?v=abcdefghijk',
            'status' => LiveDraw::STATUS_LIVE,
        ]);

        $this->get(route('live-draw.index'))
            ->assertOk()
            ->assertSee('Current Live Draw')
            ->assertSee(
                'https://www.youtube-nocookie.com/embed/abcdefghijk',
                false,
            )
            ->assertDontSee('Hasil terbaru')
            ->assertDontSee('2468');
    }

    public function test_non_live_draw_without_result_uses_existing_status_ui(): void
    {
        $market = Market::factory()->create([
            'is_active' => true,
        ]);

        LiveDraw::factory()->create([
            'market_id' => $market->id,
            'title' => 'Result Not Available',
            'status' => LiveDraw::STATUS_OFFLINE,
        ]);

        $this->get(route('live-draw.index'))
            ->assertOk()
            ->assertSee('Result Not Available')
            ->assertSee('Siaran live draw sedang tidak tersedia.')
            ->assertDontSee('Hasil terbaru')
            ->assertDontSee('Nomor keluar');
    }
}

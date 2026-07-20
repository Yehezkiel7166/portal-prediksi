<?php

namespace Tests\Feature\Frontend;

use App\Domains\LiveDraw\Models\LiveDraw;
use App\Domains\Market\Models\Market;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PublicLiveDrawHlsPlayerTest extends TestCase
{
    use RefreshDatabase;

    public function test_live_hls_source_renders_video_player(): void
    {
        $market = Market::factory()->create([
            'name' => 'HLS Market',
            'is_active' => true,
        ]);

        $liveDraw = LiveDraw::factory()->create([
            'market_id' => $market->id,
            'title' => 'HLS Live Draw',
            'provider' => LiveDraw::PROVIDER_OFFICIAL,
            'stream_type' => LiveDraw::STREAM_TYPE_HLS,
            'source_url' => 'https://stream.example.com/live/index.m3u8',
            'status' => LiveDraw::STATUS_LIVE,
        ]);

        $this->get(route('live-draw.index'))
            ->assertOk()
            ->assertSee('HLS Live Draw')
            ->assertSee('data-hls-player', false)
            ->assertSee(
                'data-hls-source="https://stream.example.com/live/index.m3u8"',
                false,
            )
            ->assertSee(
                'data-hls-fallback="hls-fallback-'.$liveDraw->id.'"',
                false,
            )
            ->assertSee('Siaran HLS tidak dapat diputar pada browser ini.')
            ->assertDontSee('Pemutar HLS akan tersedia');
    }

    public function test_offline_hls_source_is_not_exposed(): void
    {
        $market = Market::factory()->create([
            'is_active' => true,
        ]);

        LiveDraw::factory()->create([
            'market_id' => $market->id,
            'title' => 'Offline HLS Draw',
            'provider' => LiveDraw::PROVIDER_OFFICIAL,
            'stream_type' => LiveDraw::STREAM_TYPE_HLS,
            'source_url' => 'https://stream.example.com/offline/index.m3u8',
            'status' => LiveDraw::STATUS_OFFLINE,
        ]);

        $this->get(route('live-draw.index'))
            ->assertOk()
            ->assertSee('Offline HLS Draw')
            ->assertSee('Siaran live draw sedang tidak tersedia.')
            ->assertDontSee('data-hls-player', false)
            ->assertDontSee(
                'https://stream.example.com/offline/index.m3u8',
                false,
            );
    }

    public function test_live_hls_without_source_uses_unavailable_state(): void
    {
        $market = Market::factory()->create([
            'is_active' => true,
        ]);

        LiveDraw::factory()->create([
            'market_id' => $market->id,
            'title' => 'Missing HLS Source',
            'provider' => LiveDraw::PROVIDER_OFFICIAL,
            'stream_type' => LiveDraw::STREAM_TYPE_HLS,
            'source_url' => null,
            'status' => LiveDraw::STATUS_LIVE,
        ]);

        $this->get(route('live-draw.index'))
            ->assertOk()
            ->assertSee('Missing HLS Source')
            ->assertSee('Siaran live draw sedang tidak tersedia.')
            ->assertDontSee('data-hls-player', false);
    }
}

<?php

namespace Tests\Feature\Frontend;

use App\Domains\Brand\Models\Brand;
use App\Domains\LiveDraw\Models\LiveDraw;
use App\Domains\Market\Models\Market;
use App\Http\Controllers\Frontend\LiveDrawController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PublicLiveDrawTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_route_uses_live_draw_controller(): void
    {
        $response = $this->get(route('live-draw.index'));

        $response
            ->assertOk()
            ->assertViewIs('frontend.live-draw.index')
            ->assertSee('Live Draw Togel Hari Ini')
            ->assertSee('Belum ada Live Draw');

        $this->assertSame(
            LiveDrawController::class,
            app('router')
                ->getRoutes()
                ->getByName('live-draw.index')
                ->getActionName(),
        );
    }

    public function test_page_only_displays_visible_live_draws_from_active_markets(): void
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

        LiveDraw::factory()->create([
            'market_id' => $activeMarket->id,
            'title' => 'Singapore Live Draw',
            'status' => LiveDraw::STATUS_SCHEDULED,
        ]);

        LiveDraw::factory()->create([
            'market_id' => $activeMarket->id,
            'title' => 'Cancelled Hidden',
            'status' => LiveDraw::STATUS_CANCELLED,
        ]);

        LiveDraw::factory()->create([
            'market_id' => $inactiveMarket->id,
            'title' => 'Inactive Hidden',
            'status' => LiveDraw::STATUS_LIVE,
        ]);

        $this->get(route('live-draw.index'))
            ->assertOk()
            ->assertSee('Singapore Live Draw')
            ->assertSee('Singapore')
            ->assertSee('SGP')
            ->assertDontSee('Cancelled Hidden')
            ->assertDontSee('Inactive Hidden')
            ->assertDontSee('Inactive Market');
    }

    public function test_live_youtube_source_is_rendered_as_safe_embed(): void
    {
        $market = Market::factory()->create([
            'name' => 'YouTube Market',
            'is_active' => true,
        ]);

        LiveDraw::factory()->create([
            'market_id' => $market->id,
            'title' => 'YouTube Live Draw',
            'provider' => LiveDraw::PROVIDER_YOUTUBE,
            'stream_type' => LiveDraw::STREAM_TYPE_IFRAME,
            'source_url' =>
                'https://www.youtube.com/watch?v=abcdefghijk',
            'status' => LiveDraw::STATUS_LIVE,
        ]);

        $this->get(route('live-draw.index'))
            ->assertOk()
            ->assertSee('YouTube Live Draw')
            ->assertSee('Sedang Live')
            ->assertSee(
                'https://www.youtube-nocookie.com/embed/abcdefghijk',
                false,
            );
    }

    public function test_offline_source_is_not_rendered(): void
    {
        $market = Market::factory()->create([
            'is_active' => true,
        ]);

        LiveDraw::factory()->create([
            'market_id' => $market->id,
            'title' => 'Offline Draw',
            'provider' => LiveDraw::PROVIDER_YOUTUBE,
            'stream_type' => LiveDraw::STREAM_TYPE_IFRAME,
            'source_url' =>
                'https://www.youtube.com/watch?v=offline12345',
            'status' => LiveDraw::STATUS_OFFLINE,
        ]);

        $this->get(route('live-draw.index'))
            ->assertOk()
            ->assertSee('Offline Draw')
            ->assertSee('Siaran live draw sedang tidak tersedia.')
            ->assertDontSee(
                'https://www.youtube-nocookie.com/embed/offline12345',
                false,
            );
    }

    public function test_live_draws_follow_configured_priority(): void
    {
        $market = Market::factory()->create([
            'is_active' => true,
        ]);

        LiveDraw::factory()->create([
            'market_id' => $market->id,
            'title' => 'Second Priority',
            'priority' => 20,
        ]);

        LiveDraw::factory()->create([
            'market_id' => $market->id,
            'title' => 'First Priority',
            'priority' => 10,
        ]);

        $this->get(route('live-draw.index'))
            ->assertOk()
            ->assertSeeInOrder([
                'First Priority',
                'Second Priority',
            ]);
    }

    public function test_header_links_to_public_live_draw_page(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('live-draw.index'), false);
    }

    public function test_page_only_displays_live_draws_for_the_current_brand(): void
    {
        config()->set('brand.default_code', 'brand-a');

        $brandA = Brand::factory()->create([
            'code' => 'brand-a',
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

        $marketA = \App\Domains\Market\Models\Market::factory()->create([
            'brand_id' => $brandA->id,
            'is_active' => true,
        ]);

        $marketB = \App\Domains\Market\Models\Market::factory()->create([
            'brand_id' => $brandB->id,
            'is_active' => true,
        ]);

        LiveDraw::factory()->create([
            'brand_id' => $brandA->id,
            'market_id' => $marketA->id,
            'title' => 'CURRENT BRAND LIVE DRAW',
        ]);

        LiveDraw::factory()->create([
            'brand_id' => $brandB->id,
            'market_id' => $marketB->id,
            'title' => 'OTHER BRAND LIVE DRAW',
        ]);

        $this->get(route('live-draw.index'))
            ->assertOk()
            ->assertSee('CURRENT BRAND LIVE DRAW')
            ->assertDontSee('OTHER BRAND LIVE DRAW');
    }

}

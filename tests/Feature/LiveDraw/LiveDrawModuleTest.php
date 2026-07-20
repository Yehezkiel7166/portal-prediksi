<?php

namespace Tests\Feature\LiveDraw;

use App\Domains\LiveDraw\Actions\UpsertLiveDrawAction;
use App\Domains\LiveDraw\Models\LiveDraw;
use App\Domains\Market\Models\Market;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class LiveDrawModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_action_creates_normalized_live_draw(): void
    {
        $market = Market::factory()->create();

        $liveDraw = app(UpsertLiveDrawAction::class)->execute([
            'market_id' => $market->id,
            'title' => '  Singapore Live Draw  ',
            'slug' => '',
            'provider' => 'youtube',
            'stream_type' => 'iframe',
            'source_url' => ' https://example.com/live ',
            'draw_days' => [7, 1, 1, 3],
            'draw_time' => '20:00',
            'timezone' => 'Asia/Singapore',
            'status' => 'scheduled',
            'headline' => ' Live Singapore ',
            'footer' => null,
            'logo_path' => null,
            'background_path' => null,
            'background_focal_point' => 'center',
            'priority' => 10,
            'notes' => ' Catatan ',
        ]);

        $this->assertSame(
            'Singapore Live Draw',
            $liveDraw->title
        );

        $this->assertSame(
            'singapore-live-draw',
            $liveDraw->slug
        );

        $this->assertSame([1, 3, 7], $liveDraw->draw_days);
        $this->assertSame('Live Singapore', $liveDraw->headline);
        $this->assertSame('Catatan', $liveDraw->notes);
        $this->assertTrue($liveDraw->market->is($market));
    }

    public function test_action_updates_existing_live_draw(): void
    {
        $liveDraw = LiveDraw::factory()->create();

        $updated = app(UpsertLiveDrawAction::class)->execute([
            'market_id' => $liveDraw->market_id,
            'title' => 'Live Draw Diperbarui',
            'slug' => 'live-draw-diperbarui',
            'provider' => 'official',
            'stream_type' => 'url',
            'source_url' => null,
            'draw_days' => [],
            'draw_time' => null,
            'timezone' => 'Asia/Jakarta',
            'status' => 'offline',
            'headline' => null,
            'footer' => null,
            'logo_path' => null,
            'background_path' => null,
            'background_focal_point' => 'top',
            'priority' => 2,
            'notes' => null,
        ], $liveDraw);

        $this->assertSame($liveDraw->id, $updated->id);
        $this->assertSame('Live Draw Diperbarui', $updated->title);
        $this->assertSame('top', $updated->background_focal_point);
        $this->assertNull($updated->draw_days);
    }

    public function test_slug_must_be_unique(): void
    {
        LiveDraw::factory()->create([
            'slug' => 'live-draw-unik',
        ]);

        $this->expectException(ValidationException::class);

        app(UpsertLiveDrawAction::class)->execute([
            'market_id' => Market::factory()->create()->id,
            'title' => 'Live Draw Duplikat',
            'slug' => 'live-draw-unik',
            'provider' => 'official',
            'stream_type' => 'url',
            'source_url' => null,
            'draw_days' => null,
            'draw_time' => null,
            'timezone' => 'Asia/Jakarta',
            'status' => 'offline',
            'headline' => null,
            'footer' => null,
            'logo_path' => null,
            'background_path' => null,
            'background_focal_point' => 'center',
            'priority' => 0,
            'notes' => null,
        ]);
    }

    public function test_invalid_timezone_is_rejected(): void
    {
        $this->expectException(ValidationException::class);

        app(UpsertLiveDrawAction::class)->execute([
            'market_id' => Market::factory()->create()->id,
            'title' => 'Invalid Timezone',
            'slug' => 'invalid-timezone',
            'provider' => 'official',
            'stream_type' => 'url',
            'source_url' => null,
            'draw_days' => null,
            'draw_time' => null,
            'timezone' => 'Invalid/Timezone',
            'status' => 'offline',
            'headline' => null,
            'footer' => null,
            'logo_path' => null,
            'background_path' => null,
            'background_focal_point' => 'center',
            'priority' => 0,
            'notes' => null,
        ]);
    }

    public function test_visible_scope_hides_cancelled_records(): void
    {
        $visible = LiveDraw::factory()->create();

        LiveDraw::factory()->create([
            'status' => LiveDraw::STATUS_CANCELLED,
        ]);

        $results = LiveDraw::query()->visible()->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->contains($visible));
    }
}

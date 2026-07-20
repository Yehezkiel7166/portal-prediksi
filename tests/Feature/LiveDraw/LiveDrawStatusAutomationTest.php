<?php

namespace Tests\Feature\LiveDraw;

use App\Core\Contracts\Clock;
use App\Domains\LiveDraw\Actions\UpdateLiveDrawStatusAction;
use App\Domains\LiveDraw\Models\LiveDraw;
use App\Domains\Market\Models\Market;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class LiveDrawStatusAutomationTest extends TestCase
{
    use RefreshDatabase;

    public function test_status_becomes_scheduled_before_draw_time(): void
    {
        $this->freezeApplicationClock(
            '2026-07-20 19:30:00',
            'Asia/Jakarta',
        );

        $liveDraw = $this->createLiveDraw([
            'draw_days' => [1],
            'draw_time' => '20:00',
            'timezone' => 'Asia/Jakarta',
            'status' => LiveDraw::STATUS_OFFLINE,
        ]);

        $updated = app(UpdateLiveDrawStatusAction::class)
            ->update($liveDraw);

        $this->assertTrue($updated);
        $this->assertSame(
            LiveDraw::STATUS_SCHEDULED,
            $liveDraw->refresh()->status,
        );
    }

    public function test_status_becomes_live_at_draw_time(): void
    {
        $this->freezeApplicationClock(
            '2026-07-20 20:10:00',
            'Asia/Jakarta',
        );

        $liveDraw = $this->createLiveDraw([
            'draw_days' => [1],
            'draw_time' => '20:00',
            'timezone' => 'Asia/Jakarta',
            'status' => LiveDraw::STATUS_SCHEDULED,
        ]);

        app(UpdateLiveDrawStatusAction::class)->update($liveDraw);

        $this->assertSame(
            LiveDraw::STATUS_LIVE,
            $liveDraw->refresh()->status,
        );
    }

    public function test_status_becomes_finished_after_live_window(): void
    {
        $this->freezeApplicationClock(
            '2026-07-20 20:31:00',
            'Asia/Jakarta',
        );

        $liveDraw = $this->createLiveDraw([
            'draw_days' => [1],
            'draw_time' => '20:00',
            'timezone' => 'Asia/Jakarta',
            'status' => LiveDraw::STATUS_LIVE,
        ]);

        app(UpdateLiveDrawStatusAction::class)->update($liveDraw);

        $this->assertSame(
            LiveDraw::STATUS_FINISHED,
            $liveDraw->refresh()->status,
        );
    }

    public function test_finished_status_returns_to_scheduled_before_next_draw(): void
    {
        $this->freezeApplicationClock(
            '2026-07-27 19:15:00',
            'Asia/Jakarta',
        );

        $liveDraw = $this->createLiveDraw([
            'draw_days' => [1],
            'draw_time' => '20:00',
            'timezone' => 'Asia/Jakarta',
            'status' => LiveDraw::STATUS_FINISHED,
        ]);

        app(UpdateLiveDrawStatusAction::class)->update($liveDraw);

        $this->assertSame(
            LiveDraw::STATUS_SCHEDULED,
            $liveDraw->refresh()->status,
        );
    }

    public function test_schedule_uses_live_draw_timezone(): void
    {
        $this->freezeApplicationClock(
            '2026-07-20 12:30:00',
            'UTC',
        );

        $liveDraw = $this->createLiveDraw([
            'draw_days' => [1],
            'draw_time' => '20:00',
            'timezone' => 'Asia/Jakarta',
            'status' => LiveDraw::STATUS_OFFLINE,
        ]);

        app(UpdateLiveDrawStatusAction::class)->update($liveDraw);

        $this->assertSame(
            LiveDraw::STATUS_SCHEDULED,
            $liveDraw->refresh()->status,
        );
    }

    public function test_cancelled_status_is_not_changed(): void
    {
        $this->freezeApplicationClock(
            '2026-07-20 20:10:00',
            'Asia/Jakarta',
        );

        $liveDraw = $this->createLiveDraw([
            'status' => LiveDraw::STATUS_CANCELLED,
        ]);

        $updated = app(UpdateLiveDrawStatusAction::class)
            ->update($liveDraw);

        $this->assertFalse($updated);
        $this->assertSame(
            LiveDraw::STATUS_CANCELLED,
            $liveDraw->refresh()->status,
        );
    }

    public function test_missing_schedule_becomes_offline(): void
    {
        $this->freezeApplicationClock(
            '2026-07-20 20:10:00',
            'Asia/Jakarta',
        );

        $liveDraw = $this->createLiveDraw([
            'draw_days' => null,
            'draw_time' => null,
            'status' => LiveDraw::STATUS_LIVE,
        ]);

        app(UpdateLiveDrawStatusAction::class)->update($liveDraw);

        $this->assertSame(
            LiveDraw::STATUS_OFFLINE,
            $liveDraw->refresh()->status,
        );
    }

    public function test_execute_updates_non_cancelled_records(): void
    {
        $this->freezeApplicationClock(
            '2026-07-20 20:10:00',
            'Asia/Jakarta',
        );

        $first = $this->createLiveDraw([
            'slug' => 'first-draw',
            'status' => LiveDraw::STATUS_OFFLINE,
        ]);

        $second = $this->createLiveDraw([
            'slug' => 'second-draw',
            'status' => LiveDraw::STATUS_SCHEDULED,
        ]);

        $cancelled = $this->createLiveDraw([
            'slug' => 'cancelled-draw',
            'status' => LiveDraw::STATUS_CANCELLED,
        ]);

        $updated = app(UpdateLiveDrawStatusAction::class)
            ->execute();

        $this->assertSame(2, $updated);

        $this->assertSame(
            LiveDraw::STATUS_LIVE,
            $first->refresh()->status,
        );

        $this->assertSame(
            LiveDraw::STATUS_LIVE,
            $second->refresh()->status,
        );

        $this->assertSame(
            LiveDraw::STATUS_CANCELLED,
            $cancelled->refresh()->status,
        );
    }

    /**
     * @param array<string, mixed> $attributes
     */
    private function createLiveDraw(
        array $attributes = [],
    ): LiveDraw {
        $market = Market::factory()->create([
            'is_active' => true,
        ]);

        return LiveDraw::factory()->create(
            array_merge([
                'market_id' => $market->id,
                'draw_days' => [1],
                'draw_time' => '20:00',
                'timezone' => 'Asia/Jakarta',
                'status' => LiveDraw::STATUS_OFFLINE,
            ], $attributes),
        );
    }

    private function freezeApplicationClock(
        string $dateTime,
        string $timezone,
    ): void {
        $time = CarbonImmutable::parse(
            $dateTime,
            $timezone,
        );

        $this->app->instance(
            Clock::class,
            new class($time) implements Clock
            {
                public function __construct(
                    private readonly CarbonImmutable $time,
                ) {
                }

                public function now(): CarbonImmutable
                {
                    return $this->time;
                }
            },
        );
    }
}

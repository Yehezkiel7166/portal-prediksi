<?php

namespace Tests\Feature\LiveDraw;

use App\Core\Contracts\Clock;
use App\Domains\LiveDraw\Models\LiveDraw;
use App\Domains\Market\Models\Market;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class UpdateLiveDrawStatusesCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_updates_live_draw_statuses(): void
    {
        $time = CarbonImmutable::parse(
            '2026-07-20 20:10:00',
            'Asia/Jakarta',
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

        $market = Market::factory()->create([
            'is_active' => true,
        ]);

        $liveDraw = LiveDraw::factory()->create([
            'market_id' => $market->id,
            'draw_days' => [1],
            'draw_time' => '20:00',
            'timezone' => 'Asia/Jakarta',
            'status' => LiveDraw::STATUS_SCHEDULED,
        ]);

        $this->artisan('live-draw:update-status')
            ->expectsOutput(
                'Live Draw status automation completed. Updated: 1.',
            )
            ->assertSuccessful();

        $this->assertSame(
            LiveDraw::STATUS_LIVE,
            $liveDraw->refresh()->status,
        );
    }

    public function test_scheduler_registers_live_draw_command(): void
    {
        $consoleRoutes = file_get_contents(
            base_path('routes/console.php'),
        );

        $this->assertIsString($consoleRoutes);

        $this->assertStringContainsString(
            "Schedule::command('live-draw:update-status')",
            $consoleRoutes,
        );

        $this->assertStringContainsString(
            '->everyMinute()',
            $consoleRoutes,
        );

        $this->assertStringContainsString(
            '->withoutOverlapping()',
            $consoleRoutes,
        );
    }
}

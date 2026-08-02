<?php

declare(strict_types=1);

namespace Tests\Feature\Sprint20JHotfix;

use App\Domains\Market\Models\Market;
use App\Domains\Market\Support\MarketScheduleStatus;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PcsoSundayAndRunningClockTest extends TestCase
{
    use RefreshDatabase;

    public function test_pcso_is_holiday_on_sunday(): void
    {
        $market = Market::factory()->create([
            'code' => 'PCSO',
            'name' => 'PCSO',
            'timezone' => 'Asia/Jakarta',
            'active_days' => [1, 2, 3, 4, 5, 6],
            'close_time' => '19:45',
            'result_time' => '20:00',
            'open_time' => '20:10',
            'is_active' => true,
            'is_holiday' => false,
        ]);

        $status = app(
            MarketScheduleStatus::class
        )->resolve(
            $market,
            CarbonImmutable::parse(
                '2026-08-02 18:48:55',
                'Asia/Jakarta',
            ),
        );

        $this->assertSame(
            'holiday',
            $status['key'],
        );

        $this->assertSame(
            'Libur',
            $status['label'],
        );
    }

    public function test_previous_active_cycle_remains_live_after_midnight(): void
    {
        $market = Market::factory()->create([
            'code' => 'OVERNIGHT',
            'name' => 'Overnight Market',
            'timezone' => 'Asia/Jakarta',
            'active_days' => [1],
            'close_time' => '00:01',
            'result_time' => '00:15',
            'open_time' => '00:20',
            'is_active' => true,
            'is_holiday' => false,
        ]);

        $status = app(
            MarketScheduleStatus::class
        )->resolve(
            $market,
            CarbonImmutable::parse(
                '2026-07-28 00:08:00',
                'Asia/Jakarta',
            ),
        );

        $this->assertSame(
            'live',
            $status['key'],
        );

        $this->assertSame(
            'Menunggu Hasil',
            $status['label'],
        );
    }

    public function test_non_operational_day_is_not_carried_until_evening(): void
    {
        $market = Market::factory()->create([
            'code' => 'LONG-CYCLE',
            'name' => 'Long Cycle Market',
            'timezone' => 'Asia/Jakarta',
            'active_days' => [1, 2, 3, 4, 5, 6],
            'close_time' => '19:45',
            'result_time' => '20:00',
            'open_time' => '20:10',
            'is_active' => true,
            'is_holiday' => false,
        ]);

        $status = app(
            MarketScheduleStatus::class
        )->resolve(
            $market,
            CarbonImmutable::parse(
                '2026-08-02 18:00:00',
                'Asia/Jakarta',
            ),
        );

        $this->assertSame(
            'holiday',
            $status['key'],
        );
    }

    public function test_header_contains_running_jakarta_clock(): void
    {
        $source = file_get_contents(
            resource_path(
                'views/frontend/partials/header.blade.php'
            )
        );

        $this->assertIsString($source);

        $this->assertStringContainsString(
            'data-live-clock',
            $source,
        );

        $this->assertStringContainsString(
            'Asia/Jakarta',
            $source,
        );

        $this->assertStringContainsString(
            'id-ID',
            $source,
        );

        $this->assertStringContainsString(
            'Intl.DateTimeFormat',
            $source,
        );

        $this->assertStringContainsString(
            'setInterval',
            $source,
        );

        $this->assertStringContainsString(
            'second',
            $source,
        );
    }
}

<?php

namespace Tests\Feature\LotteryTools;

use App\Domains\Market\Models\Market;
use App\Domains\Market\Support\MarketScheduleStatus;
use App\Domains\Result\Models\Result;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LotteryScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_schedule_only_lists_active_brand_scoped_markets(): void
    {
        Market::factory()->create(['name' => 'Pasaran Aktif']);
        Market::factory()->inactive()->create(['name' => 'Pasaran Nonaktif']);

        $this->get('/alat-togel/jadwal-togel')
            ->assertOk()
            ->assertSee('Pasaran Aktif')
            ->assertDontSee('Pasaran Nonaktif')
            ->assertSee('rel="canonical"', false);
    }

    public function test_status_resolver_covers_open_live_and_result_available(): void
    {
        $market = Market::factory()->create([
            'timezone' => 'Asia/Jakarta',
            'active_days' => [1],
            'open_time' => '09:00',
            'close_time' => '18:00',
            'result_time' => '19:00',
        ]);

        $resolver = app(MarketScheduleStatus::class);

        $this->assertSame('open', $resolver->resolve(
            $market,
            CarbonImmutable::parse('2026-07-27 10:00:00', 'Asia/Jakarta'),
        )['key']);

        $this->assertSame('live', $resolver->resolve(
            $market,
            CarbonImmutable::parse('2026-07-27 18:30:00', 'Asia/Jakarta'),
        )['key']);

        Result::factory()->create([
            'market_id' => $market->getKey(),
            'result_date' => '2026-07-27',
        ]);

        $this->assertSame('result_available', $resolver->resolve(
            $market,
            CarbonImmutable::parse('2026-07-27 19:30:00', 'Asia/Jakarta'),
        )['key']);
    }

    public function test_holiday_status_overrides_daily_schedule(): void
    {
        $market = Market::factory()->create([
            'is_holiday' => true,
            'holiday_note' => 'Libur nasional',
        ]);

        $status = app(MarketScheduleStatus::class)->resolve($market);

        $this->assertSame('holiday', $status['key']);
        $this->assertSame('Libur nasional', $status['description']);
    }
}

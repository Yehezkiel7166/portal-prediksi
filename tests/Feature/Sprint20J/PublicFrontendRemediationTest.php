<?php

declare(strict_types=1);

namespace Tests\Feature\Sprint20J;

use App\Domains\Market\Models\Market;
use App\Domains\Market\Support\MarketScheduleStatus;
use App\Domains\Shio\Models\ShioPeriod;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class PublicFrontendRemediationTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_does_not_expose_internal_labels(): void
    {
        $source = file_get_contents(
            resource_path('views/frontend/home.blade.php')
        );

        $this->assertIsString($source);

        $this->assertStringNotContainsString(
            'Modul Publik Brand 1',
            $source,
        );

        $this->assertStringNotContainsString(
            'Modul sedang dipersiapkan',
            $source,
        );
    }

    public function test_schedule_uses_requested_column_order(): void
    {
        $source = file_get_contents(
            resource_path(
                'views/frontend/tools/lottery-schedule.blade.php'
            )
        );

        $this->assertIsString($source);

        $this->assertStringNotContainsString(
            'Zona Waktu',
            $source,
        );

        $this->assertMatchesRegularExpression(
            '/>Tutup<.*>Hasil<.*>Buka<.*>Status<.*>Link</s',
            $source,
        );

        $this->assertStringContainsString(
            "route('predictions.index')",
            $source,
        );

        $this->assertStringContainsString(
            "route('results.index')",
            $source,
        );

        $this->assertStringContainsString(
            "route('live-draw.index')",
            $source,
        );

        $this->assertStringContainsString(
            "route('tools.paito')",
            $source,
        );
    }

    public function test_overnight_market_is_open_after_daily_open(): void
    {
        $market = Market::factory()->create([
            'timezone' => 'Asia/Jakarta',
            'active_days' => [1],
            'open_time' => '00:20',
            'close_time' => '00:01',
            'result_time' => '00:15',
        ]);

        $status = app(MarketScheduleStatus::class)->resolve(
            $market,
            CarbonImmutable::parse(
                '2026-07-27 18:00:00',
                'Asia/Jakarta',
            ),
        );

        $this->assertSame('open', $status['key']);
        $this->assertSame('Buka', $status['label']);
    }

    public function test_overnight_market_keeps_live_key_while_waiting_result(): void
    {
        $market = Market::factory()->create([
            'timezone' => 'Asia/Jakarta',
            'active_days' => [1],
            'open_time' => '00:20',
            'close_time' => '00:01',
            'result_time' => '00:15',
        ]);

        $status = app(MarketScheduleStatus::class)->resolve(
            $market,
            CarbonImmutable::parse(
                '2026-07-28 00:08:00',
                'Asia/Jakarta',
            ),
        );

        $this->assertSame('live', $status['key']);

        $this->assertSame(
            'Menunggu Hasil',
            $status['label'],
        );
    }

    public function test_public_shio_page_renders_generated_banner(): void
    {
        Storage::fake('public');

        $bannerPath = 'shio/generated/shio-period-1.png';

        Storage::disk('public')->put(
            $bannerPath,
            'generated-banner',
        );

        $period = ShioPeriod::factory()->create([
            'title' => 'Tabel Shio Aktif',
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'generated_banner' => $bannerPath,
            'status' => 'published',
        ]);

        $this->get(route('tools.shio-table'))
            ->assertOk()
            ->assertViewHas(
                'bannerUrl',
                Storage::disk('public')->url($bannerPath),
            )
            ->assertSee(
                Storage::disk('public')->url($bannerPath),
                false,
            )
            ->assertSee(
                'Banner '.$period->title,
            );
    }
}

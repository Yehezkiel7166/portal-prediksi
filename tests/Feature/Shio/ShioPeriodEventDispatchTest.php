<?php

namespace Tests\Feature\Shio;

use App\Domains\Shio\Events\ShioChanged;
use App\Domains\Shio\Models\ShioPeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ShioPeriodEventDispatchTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_period_dispatches_shio_changed(): void
    {
        Event::fake([ShioChanged::class]);

        $period = ShioPeriod::factory()->create();

        Event::assertDispatched(
            ShioChanged::class,
            static fn (ShioChanged $event): bool =>
                $event->period->is($period),
        );
    }

    public function test_updating_source_data_dispatches_shio_changed(): void
    {
        $period = ShioPeriod::factory()->create();

        Event::fake([ShioChanged::class]);

        $period->update([
            'title' => 'Tabel Shio Diperbarui',
        ]);

        Event::assertDispatched(
            ShioChanged::class,
            static fn (ShioChanged $event): bool =>
                $event->period->is($period),
        );
    }

    public function test_generated_banner_update_does_not_dispatch_event(): void
    {
        $period = ShioPeriod::factory()->create();

        Event::fake([ShioChanged::class]);

        $period->update([
            'generated_banner' =>
                'shio/generated/shio-period-1.png',
        ]);

        Event::assertNotDispatched(ShioChanged::class);
    }

    public function test_saving_without_changes_does_not_dispatch_event(): void
    {
        $period = ShioPeriod::factory()->create();

        Event::fake([ShioChanged::class]);

        $period->save();

        Event::assertNotDispatched(ShioChanged::class);
    }
}

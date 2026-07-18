<?php

namespace Tests\Feature\Shio;

use App\Domains\Shio\Actions\GenerateShioBannerAction;
use App\Domains\Shio\Events\ShioChanged;
use App\Domains\Shio\Listeners\GenerateShioBannerListener;
use App\Domains\Shio\Models\ShioPeriod;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShioEventListenerTest extends TestCase
{
    use RefreshDatabase;

    public function test_shio_changed_dispatches_after_commit(): void
    {
        $period = ShioPeriod::factory()->create();

        $this->assertInstanceOf(
            ShouldDispatchAfterCommit::class,
            new ShioChanged($period),
        );
    }

    public function test_banner_listener_handles_after_commit(): void
    {
        $listener = new GenerateShioBannerListener(
            $this->mock(GenerateShioBannerAction::class),
        );

        $this->assertInstanceOf(
            ShouldHandleEventsAfterCommit::class,
            $listener,
        );
    }

    public function test_listener_generates_banner_when_template_exists(): void
    {
        $period = ShioPeriod::factory()->create([
            'banner_template' =>
                'shio/banner-templates/template.png',
        ]);

        $action = $this->mock(GenerateShioBannerAction::class);

        $action->shouldReceive('execute')
            ->once()
            ->with($period)
            ->andReturn($period);

        (new GenerateShioBannerListener($action))
            ->handle(new ShioChanged($period));
    }

    public function test_listener_skips_period_without_template(): void
    {
        $period = ShioPeriod::factory()->create([
            'banner_template' => null,
        ]);

        $action = $this->mock(GenerateShioBannerAction::class);

        $action->shouldNotReceive('execute');

        (new GenerateShioBannerListener($action))
            ->handle(new ShioChanged($period));
    }
}

<?php

namespace App\Domains\Shio\Listeners;

use App\Domains\Shio\Actions\GenerateShioBannerAction;
use App\Domains\Shio\Events\ShioChanged;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class GenerateShioBannerListener implements ShouldHandleEventsAfterCommit
{
    public function __construct(
        private readonly GenerateShioBannerAction $generateBanner,
    ) {}

    public function handle(ShioChanged $event): void
    {
        if (blank($event->period->banner_template)) {
            return;
        }

        $this->generateBanner->execute($event->period);
    }
}

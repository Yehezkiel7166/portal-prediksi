<?php

namespace Tests\Feature\Core\Support;

use App\Core\Contracts\Clock;
use App\Core\Support\SystemClock;
use Carbon\CarbonImmutable;
use Tests\TestCase;

class ClockBindingTest extends TestCase
{
    public function test_clock_contract_resolves_to_system_clock(): void
    {
        $clock = $this->app->make(Clock::class);

        $this->assertInstanceOf(SystemClock::class, $clock);
    }

    public function test_system_clock_returns_immutable_time(): void
    {
        $clock = $this->app->make(Clock::class);

        $this->assertInstanceOf(CarbonImmutable::class, $clock->now());
    }

    public function test_clock_is_registered_as_singleton(): void
    {
        $first = $this->app->make(Clock::class);
        $second = $this->app->make(Clock::class);

        $this->assertSame($first, $second);
    }
}

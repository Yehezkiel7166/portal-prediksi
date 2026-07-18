<?php

namespace Tests\Feature\Core\Events;

use App\Providers\EventServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as LaravelEventServiceProvider;
use Tests\TestCase;

class EventInfrastructureTest extends TestCase
{
    public function test_application_registers_event_provider_once(): void
    {
        $providers = require base_path('bootstrap/providers.php');

        $matches = array_filter(
            $providers,
            static fn (string $provider): bool =>
                $provider === EventServiceProvider::class,
        );

        $this->assertCount(1, $matches);
        $this->assertInstanceOf(
            EventServiceProvider::class,
            $this->app->getProvider(EventServiceProvider::class),
        );
    }

    public function test_provider_uses_laravel_native_event_system(): void
    {
        $provider = $this->app->getProvider(EventServiceProvider::class);

        $this->assertInstanceOf(
            LaravelEventServiceProvider::class,
            $provider,
        );

        $this->assertInstanceOf(
            Dispatcher::class,
            $this->app->make(Dispatcher::class),
        );
    }
}

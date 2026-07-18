<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Explicit application event-to-listener mappings.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [];
}

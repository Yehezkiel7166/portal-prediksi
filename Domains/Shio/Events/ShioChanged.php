<?php

namespace App\Domains\Shio\Events;

use App\Domains\Shio\Models\ShioPeriod;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;

class ShioChanged implements ShouldDispatchAfterCommit
{
    use Dispatchable;
    public function __construct(
        public readonly ShioPeriod $period,
    ) {}
}

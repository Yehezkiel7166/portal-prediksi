<?php

namespace App\Domains\Shio\Events;

use App\Domains\Shio\Models\ShioPeriod;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;

class ShioChanged implements ShouldDispatchAfterCommit
{
    public function __construct(
        public readonly ShioPeriod $period,
    ) {}
}

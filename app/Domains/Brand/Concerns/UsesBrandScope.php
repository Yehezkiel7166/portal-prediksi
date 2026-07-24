<?php

namespace App\Domains\Brand\Concerns;

use App\Domains\Brand\Scopes\BrandScope;

trait UsesBrandScope
{
    protected static function bootUsesBrandScope(): void
    {
        static::addGlobalScope(new BrandScope);
    }
}

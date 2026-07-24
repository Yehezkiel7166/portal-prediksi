<?php

namespace App\Domains\Brand\Support;

use App\Domains\Brand\Scopes\BrandScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final class BrandQuery
{
    public static function allBrands(Model|string $model): Builder
    {
        $instance = is_string($model) ? new $model() : $model;

        return $instance->newQuery()->withoutGlobalScope(BrandScope::class);
    }
}

<?php

namespace App\Domains\Brand\Scopes;

use App\Domains\Brand\Support\BrandContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BrandScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $context = app(BrandContext::class);

        if (! $context->has()) {
            return;
        }

        $builder->where(
            $model->qualifyColumn('brand_id'),
            $context->get()->getKey()
        );
    }
}

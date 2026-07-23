<?php

namespace App\Domains\Brand\Support;

use App\Domains\Brand\Contracts\BrandResolver;
use App\Domains\Brand\Models\Brand;

class DefaultBrandResolver implements BrandResolver
{
    public function resolve(): ?Brand
    {
        return null;
    }
}

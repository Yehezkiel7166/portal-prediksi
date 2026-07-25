<?php

namespace App\Domains\Brand\Support;

use App\Domains\Brand\Contracts\BrandResolver;
use App\Domains\Brand\Models\Brand;
use Illuminate\Http\Request;

class DefaultBrandResolver implements BrandResolver
{
    public function resolve(?Request $request = null): ?Brand
    {
        return null;
    }
}

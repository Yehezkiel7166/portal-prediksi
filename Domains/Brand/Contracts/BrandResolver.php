<?php

namespace App\Domains\Brand\Contracts;

use App\Domains\Brand\Models\Brand;
use Illuminate\Http\Request;

interface BrandResolver
{
    public function resolve(?Request $request = null): ?Brand;
}

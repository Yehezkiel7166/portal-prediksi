<?php

namespace App\Domains\Brand\Contracts;

use App\Domains\Brand\Models\Brand;

interface BrandResolver
{
    public function resolve(): ?Brand;
}

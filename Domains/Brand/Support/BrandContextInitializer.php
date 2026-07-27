<?php

namespace App\Domains\Brand\Support;

use App\Domains\Brand\Contracts\BrandResolver;
use App\Domains\Brand\Models\Brand;
use Illuminate\Http\Request;

class BrandContextInitializer
{
    public function __construct(
        private readonly BrandResolver $resolver,
        private readonly BrandContext $context,
    ) {
    }

    public function initialize(?Request $request = null): ?Brand
    {
        $brand = $this->resolver->resolve($request);

        $this->context->set($brand);

        return $brand;
    }
}

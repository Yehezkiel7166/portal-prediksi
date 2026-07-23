<?php

namespace App\Domains\Brand\Support;

use App\Domains\Brand\Contracts\BrandResolver;
use App\Domains\Brand\Models\Brand;

class BrandContextInitializer
{
    public function __construct(
        private readonly BrandResolver $resolver,
        private readonly BrandContext $context,
    ) {
    }

    public function initialize(): ?Brand
    {
        $brand = $this->resolver->resolve();

        $this->context->set($brand);

        return $brand;
    }
}

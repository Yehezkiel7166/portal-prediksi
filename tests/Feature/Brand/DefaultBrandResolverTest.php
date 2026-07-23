<?php

namespace Tests\Feature\Brand;

use App\Domains\Brand\Contracts\BrandResolver;
use App\Domains\Brand\Support\DefaultBrandResolver;
use PHPUnit\Framework\TestCase;

class DefaultBrandResolverTest extends TestCase
{
    public function test_it_implements_brand_resolver_contract(): void
    {
        $resolver = new DefaultBrandResolver();

        $this->assertInstanceOf(BrandResolver::class, $resolver);
    }

    public function test_it_returns_null_before_brand_resolution_is_activated(): void
    {
        $resolver = new DefaultBrandResolver();

        $this->assertNull($resolver->resolve());
    }
}

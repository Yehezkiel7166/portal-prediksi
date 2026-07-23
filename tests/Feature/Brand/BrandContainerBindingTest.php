<?php

namespace Tests\Feature\Brand;

use App\Domains\Brand\Contracts\BrandResolver;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Brand\Support\DefaultBrandResolver;
use Tests\TestCase;

class BrandContainerBindingTest extends TestCase
{
    public function test_brand_resolver_contract_uses_default_resolver(): void
    {
        $resolver = app(BrandResolver::class);

        $this->assertInstanceOf(DefaultBrandResolver::class, $resolver);
        $this->assertNull($resolver->resolve());
    }

    public function test_brand_resolver_is_shared_by_the_container(): void
    {
        $first = app(BrandResolver::class);
        $second = app(BrandResolver::class);

        $this->assertSame($first, $second);
    }

    public function test_brand_context_is_shared_within_the_current_scope(): void
    {
        $first = app(BrandContext::class);
        $second = app(BrandContext::class);

        $this->assertSame($first, $second);
        $this->assertFalse($first->has());
        $this->assertNull($first->get());
    }

    public function test_brand_context_is_fresh_after_scoped_instances_are_flushed(): void
    {
        $first = app(BrandContext::class);

        $first->set(new \App\Domains\Brand\Models\Brand([
            'code' => 'DEFAULT',
            'name' => 'Default Brand',
            'slug' => 'default',
            'domain' => 'example.test',
            'is_active' => true,
            'sort_order' => 0,
        ]));

        $this->assertTrue($first->has());

        app()->forgetScopedInstances();

        $second = app(BrandContext::class);

        $this->assertNotSame($first, $second);
        $this->assertFalse($second->has());
        $this->assertNull($second->get());
    }
}

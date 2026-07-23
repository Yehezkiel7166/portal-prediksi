<?php

namespace Tests\Feature\Brand;

use App\Domains\Brand\Contracts\BrandResolver;
use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Brand\Support\DatabaseBrandResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandContainerBindingTest extends TestCase
{
    use RefreshDatabase;

    public function test_brand_resolver_contract_uses_database_resolver(): void
    {
        $resolver = app(BrandResolver::class);

        $this->assertInstanceOf(DatabaseBrandResolver::class, $resolver);
    }

    public function test_brand_resolver_is_shared_by_the_container(): void
    {
        $first = app(BrandResolver::class);
        $second = app(BrandResolver::class);

        $this->assertSame($first, $second);
    }

    public function test_bound_resolver_resolves_the_active_canonical_brand(): void
    {
        $brand = Brand::factory()->create([
            'code' => 'DEFAULT',
            'is_active' => true,
        ]);

        $resolved = app(BrandResolver::class)->resolve();

        $this->assertNotNull($resolved);
        $this->assertTrue($brand->is($resolved));
    }

    public function test_bound_resolver_returns_null_without_canonical_brand(): void
    {
        Brand::factory()->create([
            'code' => 'OTHER',
            'is_active' => true,
        ]);

        $this->assertNull(
            app(BrandResolver::class)->resolve()
        );
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

        $first->set(new Brand([
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

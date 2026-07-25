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
        $this->assertInstanceOf(
            DatabaseBrandResolver::class,
            app(BrandResolver::class)
        );
    }

    public function test_brand_resolver_is_shared_by_the_container(): void
    {
        $this->assertSame(
            app(BrandResolver::class),
            app(BrandResolver::class)
        );
    }

    public function test_bound_resolver_resolves_the_active_canonical_brand(): void
    {
        $brand = Brand::factory()->create([
            'is_primary' => true,
            'is_active'  => true,
        ]);

        $resolved = app(BrandResolver::class)->resolve();

        $this->assertNotNull($resolved);
        $this->assertTrue($brand->is($resolved));
    }

    public function test_bound_resolver_returns_null_without_canonical_brand(): void
    {
        Brand::factory()->create([
            'is_primary' => false,
            'is_active'  => true,
        ]);

        $this->assertNull(
            app(BrandResolver::class)->resolve()
        );
    }

    public function test_brand_context_is_shared_within_the_current_scope(): void
    {
        $a = app(BrandContext::class);
        $b = app(BrandContext::class);

        $this->assertSame($a, $b);
        $this->assertFalse($a->has());
        $this->assertNull($a->get());
    }

    public function test_brand_context_is_fresh_after_scope_flush(): void
    {
        $ctx = app(BrandContext::class);

        $ctx->set(new Brand([
            'code'       => 'DEFAULT',
            'name'       => 'Default',
            'slug'       => 'default',
            'domain'     => 'example.test',
            'is_primary' => true,
            'is_active'  => true,
        ]));

        $this->assertTrue($ctx->has());

        app()->forgetScopedInstances();

        $fresh = app(BrandContext::class);

        $this->assertFalse($fresh->has());
        $this->assertNull($fresh->get());
    }
}

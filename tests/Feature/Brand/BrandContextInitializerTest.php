<?php

namespace Tests\Feature\Brand;

use App\Domains\Brand\Contracts\BrandResolver;
use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Brand\Support\BrandContextInitializer;
use Mockery;
use Tests\TestCase;

class BrandContextInitializerTest extends TestCase
{
    public function test_it_resolves_and_stores_the_brand_in_context(): void
    {
        $brand = new Brand([
            'code' => 'DEFAULT',
            'name' => 'Default Brand',
            'slug' => 'default',
            'domain' => 'localhost',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $resolver = Mockery::mock(BrandResolver::class);
        $resolver
            ->shouldReceive('resolve')
            ->once()
            ->andReturn($brand);

        $context = new BrandContext();

        $initializer = new BrandContextInitializer(
            $resolver,
            $context,
        );

        $resolved = $initializer->initialize();

        $this->assertSame($brand, $resolved);
        $this->assertTrue($context->has());
        $this->assertSame($brand, $context->get());
    }

    public function test_it_clears_the_context_when_resolution_returns_null(): void
    {
        $resolver = Mockery::mock(BrandResolver::class);
        $resolver
            ->shouldReceive('resolve')
            ->once()
            ->andReturnNull();

        $context = new BrandContext();

        $context->set(new Brand([
            'code' => 'OLD',
            'name' => 'Old Brand',
            'slug' => 'old',
            'domain' => 'old.test',
            'is_active' => true,
            'sort_order' => 1,
        ]));

        $initializer = new BrandContextInitializer(
            $resolver,
            $context,
        );

        $resolved = $initializer->initialize();

        $this->assertNull($resolved);
        $this->assertFalse($context->has());
        $this->assertNull($context->get());
    }

    public function test_it_can_be_resolved_from_the_container(): void
    {
        $initializer = app(BrandContextInitializer::class);

        $this->assertInstanceOf(
            BrandContextInitializer::class,
            $initializer,
        );
    }

    public function test_container_initializer_uses_the_scoped_brand_context(): void
    {
        $initializer = app(BrandContextInitializer::class);
        $context = app(BrandContext::class);

        $reflection = new \ReflectionClass($initializer);
        $property = $reflection->getProperty('context');

        $this->assertSame(
            $context,
            $property->getValue($initializer),
        );
    }
}

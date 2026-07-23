<?php

namespace Tests\Feature\Brand;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use PHPUnit\Framework\TestCase;

class BrandContextTest extends TestCase
{
    public function test_context_is_empty_initially(): void
    {
        $context = new BrandContext();

        $this->assertFalse($context->has());
        $this->assertNull($context->get());
    }

    public function test_context_can_store_a_brand(): void
    {
        $brand = new Brand([
            'code' => 'DEFAULT',
            'name' => 'Default Brand',
            'slug' => 'default',
            'domain' => 'example.test',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $context = new BrandContext();

        $context->set($brand);

        $this->assertTrue($context->has());
        $this->assertSame($brand, $context->get());
    }

    public function test_context_can_be_cleared(): void
    {
        $brand = new Brand([
            'code' => 'DEFAULT',
            'name' => 'Default Brand',
            'slug' => 'default',
            'domain' => 'example.test',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $context = new BrandContext();
        $context->set($brand);
        $context->clear();

        $this->assertFalse($context->has());
        $this->assertNull($context->get());
    }

    public function test_context_can_be_reset_with_null(): void
    {
        $context = new BrandContext();

        $context->set(null);

        $this->assertFalse($context->has());
        $this->assertNull($context->get());
    }
}

<?php

namespace Tests\Feature\Brand;

use Tests\TestCase;

class BrandConfigurationTest extends TestCase
{
    public function test_default_brand_code_configuration_exists(): void
    {
        $this->assertSame(
            'DEFAULT',
            config('brand.default_code')
        );
    }

    public function test_default_brand_code_can_be_overridden(): void
    {
        config()->set('brand.default_code', 'TEST');

        $this->assertSame(
            'TEST',
            config('brand.default_code')
        );
    }
}

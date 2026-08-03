<?php

declare(strict_types=1);

namespace Tests\Feature\HomepageBanner;

use Tests\TestCase;

final class HomepageBannerLayoutContractTest extends TestCase
{
    private string $source;

    protected function setUp(): void
    {
        parent::setUp();

        $source = file_get_contents(
            resource_path(
                'views/frontend/partials/'
                .'homepage-banner-slider.blade.php'
            )
        );

        $this->assertIsString($source);

        $this->source = $source;
    }

    public function test_track_has_runtime_height_without_tailwind_build(): void
    {
        $this->assertStringContainsString(
            'style="height: clamp(280px, 32vw, 460px); min-height: 280px;"',
            $this->source
        );
    }

    public function test_picture_fills_slider_track(): void
    {
        $this->assertStringContainsString(
            '<picture class="absolute inset-0 block h-full w-full">',
            $this->source
        );

        $this->assertStringContainsString(
            'style="height: 100%; width: 100%; object-fit: cover;',
            $this->source
        );
    }

    public function test_image_is_not_absolutely_positioned_inside_picture(): void
    {
        $this->assertStringNotContainsString(
            'class="absolute inset-0 h-full w-full object-cover"',
            $this->source
        );

        $this->assertStringContainsString(
            'class="block h-full w-full object-cover"',
            $this->source
        );
    }
}

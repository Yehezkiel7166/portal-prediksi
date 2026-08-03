<?php

declare(strict_types=1);

namespace Tests\Feature\HomepageBanner;

use Tests\TestCase;

final class HomepageBannerJavascriptContractTest extends TestCase
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

    public function test_slider_uses_five_second_autoplay(): void
    {
        $this->assertStringContainsString(
            "slider.dataset.autoplayDelay ?? '5000'",
            $this->source
        );

        $this->assertStringContainsString(
            'window.setInterval(',
            $this->source
        );

        $this->assertStringContainsString(
            'showSlide(activeIndex + 1);',
            $this->source
        );

        $this->assertStringContainsString(
            'normalizedDelay',
            $this->source
        );
    }

    public function test_slider_supports_controls_and_indicators(): void
    {
        $this->assertStringContainsString(
            "'[data-slider-previous]'",
            $this->source
        );

        $this->assertStringContainsString(
            "'[data-slider-next]'",
            $this->source
        );

        $this->assertStringContainsString(
            "'[data-slider-indicator]'",
            $this->source
        );
    }

    public function test_slider_pauses_for_user_and_page_state(): void
    {
        $this->assertStringContainsString(
            "'mouseenter'",
            $this->source
        );

        $this->assertStringContainsString(
            "'focusin'",
            $this->source
        );

        $this->assertStringContainsString(
            "'visibilitychange'",
            $this->source
        );
    }

    public function test_inline_script_is_conditional_on_available_banners(): void
    {
        $conditionPosition = strpos(
            $this->source,
            '@if ($homepageBanners->isNotEmpty())',
            strpos($this->source, '@endif') + 6
        );

        $scriptPosition = strpos(
            $this->source,
            '<script>'
        );

        $this->assertNotFalse($conditionPosition);
        $this->assertNotFalse($scriptPosition);
        $this->assertLessThan(
            $scriptPosition,
            $conditionPosition
        );
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\Theme;

use Tests\TestCase;

final class ThemeDatepickerBrowserRegressionTest extends TestCase
{
    public function test_prediction_datepicker_uses_valid_compound_selector(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/predictions/index.blade.php',
            ),
        );

        $this->assertIsString($view);

        $this->assertStringContainsString(
            'data-dark-datepicker data-theme-datepicker',
            $view,
        );

        $this->assertStringContainsString(
            "document.querySelectorAll('[data-dark-datepicker][data-theme-datepicker]')",
            $view,
        );

        $this->assertStringNotContainsString(
            "document.querySelectorAll('[data-dark-datepicker data-theme-datepicker]')",
            $view,
        );
    }

    public function test_theme_datepicker_css_contract_remains_connected(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/predictions/index.blade.php',
            ),
        );

        $tokens = file_get_contents(
            resource_path(
                'views/frontend/partials/theme-tokens.blade.php',
            ),
        );

        $this->assertIsString($view);
        $this->assertIsString($tokens);

        $this->assertStringContainsString(
            'data-theme-datepicker',
            $view,
        );

        $this->assertStringContainsString(
            '[data-theme-datepicker] [data-datepicker-panel]',
            $tokens,
        );
    }
}

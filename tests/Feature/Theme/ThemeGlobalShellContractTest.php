<?php

declare(strict_types=1);

namespace Tests\Feature\Theme;

use Tests\TestCase;

final class ThemeGlobalShellContractTest extends TestCase
{
    public function test_layout_uses_theme_root_without_static_page_color(): void
    {
        $layout = file_get_contents(
            resource_path(
                'views/frontend/layouts/app.blade.php',
            ),
        );

        $this->assertStringContainsString(
            'data-theme-root',
            $layout,
        );

        $this->assertStringContainsString(
            'data-theme-main',
            $layout,
        );

        $this->assertStringNotContainsString(
            'bg-slate-950',
            $layout,
        );

        $this->assertStringNotContainsString(
            'text-slate-100',
            $layout,
        );
    }

    public function test_theme_tokens_control_global_page_shell(): void
    {
        $tokens = file_get_contents(
            resource_path(
                'views/frontend/partials/theme-tokens.blade.php',
            ),
        );

        foreach ([
            'body[data-theme-root]',
            '--theme-page-bg',
            '--theme-component-opacity-percent',
            '[data-theme-surface]',
            '[data-theme-primary-button]',
            '[data-theme-secondary-button]',
            '[data-theme-input]',
            '[data-theme-header]',
            '[data-theme-footer]',
            '[data-theme-clock]',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $tokens,
            );
        }
    }

    public function test_custom_background_is_attached_to_theme_root(): void
    {
        $tokens = file_get_contents(
            resource_path(
                'views/frontend/partials/theme-tokens.blade.php',
            ),
        );

        $this->assertStringContainsString(
            "asset('storage/'.\$themeBackgroundImage)",
            $tokens,
        );

        $this->assertStringContainsString(
            'body[data-theme-root]::before',
            $tokens,
        );

        $this->assertStringContainsString(
            'background-attachment:',
            $tokens,
        );
    }

    public function test_component_styles_are_applied_to_theme_surface(): void
    {
        $tokens = file_get_contents(
            resource_path(
                'views/frontend/partials/theme-tokens.blade.php',
            ),
        );

        foreach ([
            "\$themeComponentStyle === 'solid'",
            "\$themeComponentStyle === 'semi-transparent'",
            "\$themeComponentStyle === 'glass'",
            "\$themeComponentStyle === 'outline'",
            'backdrop-filter:',
            'color-mix(',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $tokens,
            );
        }
    }

    public function test_header_uses_theme_hooks_instead_of_hardcoded_colors(): void
    {
        $header = file_get_contents(
            resource_path(
                'views/frontend/partials/header.blade.php',
            ),
        );

        foreach ([
            'data-theme-header',
            'data-theme-header-menu',
            'data-theme-clock',
            'aria-current="page"',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $header,
            );
        }

        foreach ([
            'bg-slate-950',
            'bg-slate-900',
            'text-amber-400',
            'text-amber-300',
            'text-slate-300',
            'border-slate-700',
        ] as $legacy) {
            $this->assertStringNotContainsString(
                $legacy,
                $header,
            );
        }
    }

    public function test_footer_uses_theme_hooks_instead_of_hardcoded_colors(): void
    {
        $footer = file_get_contents(
            resource_path(
                'views/frontend/partials/footer.blade.php',
            ),
        );

        $this->assertStringContainsString(
            'data-theme-footer',
            $footer,
        );

        $this->assertStringContainsString(
            'data-theme-muted',
            $footer,
        );

        foreach ([
            'bg-slate-950',
            'text-slate-400',
            'text-amber-300',
            'border-slate-800',
        ] as $legacy) {
            $this->assertStringNotContainsString(
                $legacy,
                $footer,
            );
        }
    }

    public function test_existing_theme_partial_remains_loaded_by_layout(): void
    {
        $layout = file_get_contents(
            resource_path(
                'views/frontend/layouts/app.blade.php',
            ),
        );

        $this->assertStringContainsString(
            "@include('frontend.partials.theme-tokens')",
            $layout,
        );
    }
}

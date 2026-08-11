<?php

namespace Tests\Feature\Theme;

use Tests\TestCase;

class ThemeBrightBackgroundContrastContractTest extends TestCase
{
    private function source(): string
    {
        $source = file_get_contents(
            resource_path(
                'views/frontend/partials/theme-tokens.blade.php'
            )
        );

        $this->assertIsString($source);

        return $source;
    }

    public function test_page_foreground_tokens_exist(): void
    {
        $source = $this->source();

        $this->assertStringContainsString(
            '--theme-page-foreground:',
            $source
        );

        $this->assertStringContainsString(
            '--theme-page-muted:',
            $source
        );

        $this->assertStringContainsString(
            '--theme-page-accent:',
            $source
        );
    }

    public function test_homepage_direct_background_content_uses_page_tokens(): void
    {
        $source = $this->source();

        $this->assertMatchesRegularExpression(
            '/\[data-theme-home-section\]\s*\{'
            . '\s*position:\s*relative;'
            . '\s*color:\s*var\(--theme-page-foreground\);'
            . '\s*\}/s',
            $source
        );

        $this->assertMatchesRegularExpression(
            '/\[data-theme-home-section\]\s+'
            . '\[data-theme-accent\]\s*\{'
            . '\s*color:\s*var\(--theme-page-accent\);'
            . '\s*\}/s',
            $source
        );

        $this->assertMatchesRegularExpression(
            '/\[data-theme-home-section\]\s+'
            . '\[data-theme-muted\]\s*\{'
            . '\s*color:\s*var\(--theme-page-muted\);'
            . '\s*\}/s',
            $source
        );
    }

    public function test_homepage_surface_descendants_keep_surface_tokens(): void
    {
        $source = $this->source();

        $this->assertMatchesRegularExpression(
            '/\[data-theme-home-section\]\s+'
            . '\[data-theme-surface\]\s+'
            . '\[data-theme-accent\]\s*\{'
            . '\s*color:\s*var\(--theme-primary\);'
            . '\s*\}/s',
            $source
        );

        $this->assertMatchesRegularExpression(
            '/\[data-theme-home-section\]\s+'
            . '\[data-theme-surface\]\s+'
            . '\[data-theme-muted\]\s*\{'
            . '\s*color:\s*var\(--theme-text-muted\);'
            . '\s*\}/s',
            $source
        );
    }

    public function test_core_data_shell_uses_page_foreground(): void
    {
        $source = $this->source();

        $this->assertMatchesRegularExpression(
            '/\[data-theme-module="predictions"\],\s*'
            . '\[data-theme-module="prediction-detail"\],\s*'
            . '\[data-theme-module="live-draw"\]\s*\{'
            . '\s*position:\s*relative;'
            . '\s*color:\s*'
            . 'var\(--theme-page-foreground\);/s',
            $source
        );
    }

    public function test_global_surface_contract_remains_unchanged(): void
    {
        $source = $this->source();

        $this->assertMatchesRegularExpression(
            '/\[data-theme-surface\]\s*\{'
            . '\s*color:\s*var\(--theme-text\);'
            . '\s*border-color:\s*var\(--theme-border\);'
            . '\s*\}/s',
            $source
        );
    }

    public function test_predictions_marks_direct_page_foreground_text(): void
    {
        $source = file_get_contents(
            resource_path('views/frontend/predictions/index.blade.php')
        );

        $this->assertIsString($source);

        $this->assertSame(
            2,
            substr_count($source, 'data-theme-direct-page-muted')
        );

        $this->assertSame(
            1,
            substr_count($source, 'data-theme-direct-page-accent')
        );
    }

    public function test_live_draw_marks_direct_page_foreground_text(): void
    {
        $source = file_get_contents(
            resource_path('views/frontend/live-draw/index.blade.php')
        );

        $this->assertIsString($source);

        $this->assertSame(
            2,
            substr_count($source, 'data-theme-direct-page-muted')
        );

        $this->assertSame(
            1,
            substr_count($source, 'data-theme-direct-page-accent')
        );
    }

    public function test_direct_page_hooks_override_legacy_important_colors(): void
    {
        $source = $this->source();

        $this->assertStringContainsString(
            '[data-theme-direct-page-muted]',
            $source
        );

        $this->assertStringContainsString(
            'color: var(--theme-page-muted) !important;',
            $source
        );

        $this->assertStringContainsString(
            '[data-theme-direct-page-accent]',
            $source
        );

        $this->assertStringContainsString(
            'color: var(--theme-page-accent) !important;',
            $source
        );
    }

    public function test_page_token_block_has_no_corrupted_text_inverse_suffix(): void
    {
        $source = $this->source();

        $this->assertStringNotContainsString(
            ");#020617' }};",
            $source
        );

        $this->assertSame(
            1,
            substr_count(
                $source,
                "{{ \$themeTokens['text_inverse'] ?? '#020617' }};"
            )
        );

        $this->assertStringContainsString(
            "--theme-page-accent:\n"
            . "            color-mix(\n"
            . "                in srgb,\n"
            . "                var(--theme-text) 92%,\n"
            . "                var(--theme-primary)\n"
            . "            );",
            $source
        );
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\Theme;

use App\Domains\Theme\Support\ThemePresetCatalog;
use Tests\TestCase;

final class ThemeFrontendCompletionGateTest extends TestCase
{
    /**
     * @return array<string, list<string>>
     */
    private function visualTargets(): array
    {
        return [
            'home.blade.php' => [
                'data-theme-home-section',
            ],

            'layouts/app.blade.php' => [
                'theme-tokens',
            ],

            'partials/footer.blade.php' => [
                'data-theme-',
            ],

            'partials/header.blade.php' => [
                'data-theme-',
            ],

            'partials/homepage-banner-slider.blade.php' => [
                'data-theme-homepage-banner',
                'data-theme-homepage-banner-fallback',
            ],

            'partials/theme-tokens.blade.php' => [
                '--theme-',
                'data-theme-',
            ],

            'results/index.blade.php' => [
                'data-theme-result-workspace="index"',
                'data-theme-result-master-detail',
                'data-theme-result-master-row',
                'data-theme-result-primary-card',
            ],

            'results/history.blade.php' => [
                'data-theme-result-history-card',
            ],

            'results/show.blade.php' => [
                'data-theme-result-detail',
            ],

            'predictions/index.blade.php' => [
                'data-theme-module="predictions"',
            ],

            'predictions/show.blade.php' => [
                'data-theme-module="prediction-detail"',
            ],

            'live-draw/index.blade.php' => [
                'data-theme-module="live-draw"',
            ],

            'tools/bbfs-generator.blade.php' => [
                'data-theme-tool="bbfs"',
            ],

            'tools/dream-book-index.blade.php' => [
                'data-theme-tool="dream-book-index"',
            ],

            'tools/dream-book-show.blade.php' => [
                'data-theme-tool="dream-book-detail"',
            ],

            'tools/lottery-schedule.blade.php' => [
                'data-theme-tool="lottery-schedule"',
            ],

            'tools/paito.blade.php' => [
                'data-theme-tool="paito"',
            ],

            'tools/sgp-number-converter.blade.php' => [
                'data-sgp-converter',
                'var(--theme-',
            ],

            'tools/shio-table.blade.php' => [
                'data-theme-tool="shio"',
            ],

            'blog/index.blade.php' => [
                'data-theme-content="blog-index"',
            ],

            'blog/show.blade.php' => [
                'data-theme-content="blog-detail"',
            ],

            'guides/index.blade.php' => [
                'data-theme-content="guide-index"',
            ],

            'guides/show.blade.php' => [
                'data-theme-content="guide-detail"',
            ],

            'promotions/index.blade.php' => [
                'data-theme-content="promotion-index"',
            ],

            'promotions/show.blade.php' => [
                'data-theme-content="promotion-detail"',
            ],

            'slot-gacor/index.blade.php' => [
                'data-theme-special="slot-gacor"',
            ],

            'jackpot-proofs/index.blade.php' => [
                'data-theme-special="jackpot-index"',
            ],

            'jackpot-proofs/show.blade.php' => [
                'data-theme-special="jackpot-detail"',
            ],

            'complaints/create.blade.php' => [
                'data-theme-special="complaints"',
            ],
        ];
    }

    public function test_frontend_inventory_is_exactly_thirty_blade_files(): void
    {
        $files = glob(
            resource_path(
                'views/frontend/**/*.blade.php',
            ),
            GLOB_BRACE,
        );

        /*
         * glob ** is not recursive on every platform.
         * Use RecursiveDirectoryIterator as authoritative inventory.
         */
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                resource_path('views/frontend'),
                \FilesystemIterator::SKIP_DOTS,
            ),
        );

        $count = 0;

        foreach ($iterator as $file) {
            if (
                $file->isFile()
                && str_ends_with(
                    $file->getFilename(),
                    '.blade.php',
                )
            ) {
                $count++;
            }
        }

        $this->assertSame(
            30,
            $count,
        );
    }

    public function test_exactly_twenty_nine_visual_targets_are_registered(): void
    {
        $this->assertCount(
            29,
            $this->visualTargets(),
        );
    }

    public function test_sitemap_is_the_only_non_visual_exclusion(): void
    {
        $allVisualPaths = array_keys(
            $this->visualTargets(),
        );

        $this->assertNotContains(
            'sitemap.blade.php',
            $allVisualPaths,
        );

        $this->assertFileExists(
            resource_path(
                'views/frontend/sitemap.blade.php',
            ),
        );
    }

    public function test_all_visual_targets_have_theme_protection(): void
    {
        foreach (
            $this->visualTargets() as $relativePath => $contracts
        ) {
            $path = resource_path(
                'views/frontend/'.$relativePath,
            );

            $this->assertFileExists(
                $path,
                $relativePath,
            );

            $view = file_get_contents($path);

            $this->assertIsString(
                $view,
                $relativePath,
            );

            foreach ($contracts as $contract) {
                $this->assertStringContainsString(
                    $contract,
                    $view,
                    "{$relativePath} tidak memiliki {$contract}",
                );
            }
        }
    }

    public function test_any_hardcoded_palette_is_inside_theme_protected_file(): void
    {
        $palettePattern =
            '/(?:bg|text|border|ring|divide|from|via|to)-'.
            '(?:slate|gray|zinc|neutral|stone|red|orange|amber|'.
            'yellow|lime|green|emerald|teal|cyan|sky|blue|indigo|'.
            'violet|purple|fuchsia|pink|rose|black|white)'.
            '(?:-\d{2,3})?/';

        foreach (
            $this->visualTargets() as $relativePath => $contracts
        ) {
            $view = file_get_contents(
                resource_path(
                    'views/frontend/'.$relativePath,
                ),
            );

            $this->assertIsString($view);

            if (
                preg_match(
                    $palettePattern,
                    $view,
                ) !== 1
            ) {
                continue;
            }

            $isProtected = false;

            foreach ($contracts as $contract) {
                if (
                    str_contains(
                        $view,
                        $contract,
                    )
                ) {
                    $isProtected = true;

                    break;
                }
            }

            $this->assertTrue(
                $isProtected,
                "{$relativePath} memiliki hardcoded palette tanpa Theme protection.",
            );
        }
    }

    public function test_homepage_banner_media_and_slider_logic_are_preserved(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/partials/homepage-banner-slider.blade.php',
            ),
        );

        foreach ([
            '$banner->desktop_image_path',
            '$banner->mobile_image_path',
            '$banner->title',
            '<picture',
            '<img',
            'data-slider-track',
            'data-slider-slide',
            'data-slider-previous',
            'data-slider-next',
            'data-slider-indicator',
            'initializeHomepageSlider',
            'showSlide',
            'startAutoplay',
            'visibilitychange',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_banner_chrome_not_banner_media_is_themed(): void
    {
        $tokens = file_get_contents(
            resource_path(
                'views/frontend/partials/theme-tokens.blade.php',
            ),
        );

        foreach ([
            '<style id="brand-theme-homepage-banner">',
            '[data-theme-homepage-banner]',
            '[data-theme-banner-control]',
            '[data-theme-banner-indicator]',
            '[data-theme-homepage-banner-fallback]',
            'var(--theme-primary)',
            'var(--theme-border)',
            'var(--theme-surface)',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $tokens,
            );
        }

        $this->assertStringContainsString(
            'color-scheme:',
            $tokens,
        );
    }

    public function test_catalog_still_contains_exactly_one_hundred_presets(): void
    {
        $catalog = app(
            ThemePresetCatalog::class,
        );

        $this->assertCount(
            100,
            $catalog->all(),
        );
    }

    public function test_four_component_styles_remain_supported(): void
    {
        $config = config(
            'brand-theme.allowed.component_styles',
        );

        $this->assertIsArray($config);

        foreach ([
            'solid',
            'semi-transparent',
            'glass',
            'outline',
        ] as $style) {
            $this->assertContains(
                $style,
                $config,
            );
        }
    }

    public function test_custom_background_contract_remains_available(): void
    {
        $tokens = file_get_contents(
            resource_path(
                'views/frontend/partials/theme-tokens.blade.php',
            ),
        );

        foreach ([
            '--theme-page-bg',
            'background-image',
            'background-size',
            'background-position',
            'background-repeat',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $tokens,
            );
        }
    }

    public function test_responsive_contract_exists_across_major_modules(): void
    {
        $paths = [
            'home.blade.php',
            'results/index.blade.php',
            'predictions/index.blade.php',
            'live-draw/index.blade.php',
            'tools/paito.blade.php',
            'blog/index.blade.php',
            'promotions/index.blade.php',
            'slot-gacor/index.blade.php',
            'complaints/create.blade.php',
        ];

        foreach ($paths as $relativePath) {
            $view = file_get_contents(
                resource_path(
                    'views/frontend/'.$relativePath,
                ),
            );

            $this->assertMatchesRegularExpression(
                '/(?:sm|md|lg|xl|2xl):/',
                $view,
                "{$relativePath} tidak memiliki responsive breakpoint.",
            );
        }
    }
}

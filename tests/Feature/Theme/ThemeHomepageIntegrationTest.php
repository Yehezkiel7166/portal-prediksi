<?php

declare(strict_types=1);

namespace Tests\Feature\Theme;

use Tests\TestCase;

final class ThemeHomepageIntegrationTest extends TestCase
{
    public function test_homepage_uses_theme_hooks(): void
    {
        $home = file_get_contents(
            resource_path(
                'views/frontend/home.blade.php',
            ),
        );

        foreach ([
            'data-theme-home-section',
            'data-theme-surface',
            'data-theme-accent',
            'data-theme-muted',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $home,
            );
        }
    }

    public function test_static_dark_palette_is_removed(): void
    {
        $home = file_get_contents(
            resource_path(
                'views/frontend/home.blade.php',
            ),
        );

        foreach ([
            'bg-slate-950',
            'bg-slate-900',
            'text-amber-400',
            'text-amber-300',
            'text-slate-400',
            'text-slate-300',
            'text-slate-200',
            'text-white',
            'border-slate-800',
            'border-slate-700',
        ] as $legacy) {
            $this->assertStringNotContainsString(
                $legacy,
                $home,
            );
        }
    }

    public function test_banner_slider_is_preserved(): void
    {
        $home = file_get_contents(
            resource_path(
                'views/frontend/home.blade.php',
            ),
        );

        $banner =
            "@include('frontend.partials.homepage-banner-slider')";

        $this->assertStringContainsString(
            $banner,
            $home,
        );

        $bannerPosition = strpos(
            $home,
            $banner,
        );

        $sectionPosition = strpos(
            $home,
            '<section',
        );

        $this->assertNotFalse(
            $bannerPosition,
        );

        $this->assertNotFalse(
            $sectionPosition,
        );

        $this->assertLessThan(
            $sectionPosition,
            $bannerPosition,
        );
    }

    public function test_status_uses_semantic_theme_colors(): void
    {
        $home = file_get_contents(
            resource_path(
                'views/frontend/home.blade.php',
            ),
        );

        $tokens = file_get_contents(
            resource_path(
                'views/frontend/partials/theme-tokens.blade.php',
            ),
        );

        $this->assertStringContainsString(
            'data-theme-status="{{ $liveDraw->status }}"',
            $home,
        );

        foreach ([
            '[data-theme-status="live"]',
            'var(--theme-danger)',
            '[data-theme-status="scheduled"]',
            'var(--theme-warning)',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $tokens,
            );
        }
    }

    public function test_homepage_theme_css_is_responsive(): void
    {
        $tokens = file_get_contents(
            resource_path(
                'views/frontend/partials/theme-tokens.blade.php',
            ),
        );

        foreach ([
            '<style id="brand-theme-homepage">',
            '@media (max-width: 767px)',
            '@media (prefers-reduced-motion: reduce)',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $tokens,
            );
        }
    }

    public function test_homepage_data_contract_remains_present(): void
    {
        $home = file_get_contents(
            resource_path(
                'views/frontend/home.blade.php',
            ),
        );

        foreach ([
            '$liveDraws',
            '$latestResults',
            '$currentPredictions',
            '$activePromotions',
            '$latestArticles',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $home,
            );
        }
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\Theme;

use Tests\TestCase;

final class ThemeResultIntegrationTest extends TestCase
{
    public function test_result_index_uses_responsive_cards_not_desktop_table(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/results/index.blade.php',
            ),
        );

        foreach ([
            'data-theme-result-grid',
            'data-theme-result-card',
            'sm:grid-cols-2',
            'xl:grid-cols-3',
            '$market->name',
            '$market->code',
            '$latestResult->winning_numbers',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }

        $this->assertStringNotContainsString(
            'md:grid-cols-[minmax(180px',
            $view,
        );
    }

    public function test_each_result_card_is_backed_by_market_loop(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/results/index.blade.php',
            ),
        );

        $this->assertStringContainsString(
            '@foreach ($markets as $market)',
            $view,
        );

        $this->assertStringContainsString(
            'Detail {{ $market->name }}',
            $view,
        );
    }

    public function test_history_is_theme_aware_and_responsive(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/results/history.blade.php',
            ),
        );

        foreach ([
            'data-theme-result-history-card',
            'data-theme-surface',
            'data-theme-result-number-panel',
            'sm:grid-cols-2',
            'xl:grid-cols-3',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_result_detail_has_structured_detail_layout(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/results/show.blade.php',
            ),
        );

        foreach ([
            'data-theme-result-detail',
            'Detail Result',
            'Hasil Result',
            'data-theme-result-number',
            'data-theme-result-meta',
            'Pasaran',
            'Tanggal',
            'Zona Waktu',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_result_views_do_not_use_old_static_palette(): void
    {
        $paths = [
            'views/frontend/results/index.blade.php',
            'views/frontend/results/history.blade.php',
            'views/frontend/results/show.blade.php',
        ];

        foreach ($paths as $path) {
            $view = file_get_contents(
                resource_path($path),
            );

            foreach ([
                'bg-slate-950',
                'bg-slate-900',
                'text-amber-400',
                'text-amber-300',
                'text-slate-400',
                'text-slate-300',
                'text-white',
                'border-slate-800',
                'border-slate-700',
            ] as $legacy) {
                $this->assertStringNotContainsString(
                    $legacy,
                    $view,
                    "{$legacy} masih ditemukan di {$path}",
                );
            }
        }
    }

    public function test_result_theme_css_exposes_semantic_statuses(): void
    {
        $tokens = file_get_contents(
            resource_path(
                'views/frontend/partials/theme-tokens.blade.php',
            ),
        );

        foreach ([
            '<style id="brand-theme-results">',
            '[data-theme-market-status="open"]',
            '[data-theme-market-status="closed"]',
            '[data-theme-market-status="holiday"]',
            'var(--theme-success)',
            'var(--theme-danger)',
            'var(--theme-warning)',
            'var(--theme-result-bg)',
            'var(--theme-result-text)',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $tokens,
            );
        }
    }

    public function test_result_routes_are_preserved(): void
    {
        $index = file_get_contents(
            resource_path(
                'views/frontend/results/index.blade.php',
            ),
        );

        $history = file_get_contents(
            resource_path(
                'views/frontend/results/history.blade.php',
            ),
        );

        $show = file_get_contents(
            resource_path(
                'views/frontend/results/show.blade.php',
            ),
        );

        $this->assertStringContainsString(
            "route('results.history'",
            $index,
        );

        $this->assertStringContainsString(
            "route('results.show'",
            $history,
        );

        $this->assertStringContainsString(
            "route('results.history'",
            $show,
        );

        $this->assertStringContainsString(
            "route('results.index')",
            $show,
        );
    }
}

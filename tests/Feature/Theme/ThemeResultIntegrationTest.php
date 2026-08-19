<?php

declare(strict_types=1);

namespace Tests\Feature\Theme;

use Tests\TestCase;

final class ThemeResultIntegrationTest extends TestCase
{
    public function test_result_index_uses_master_detail_workspace(): void
    {
        $view = file_get_contents(
            resource_path('views/frontend/results/index.blade.php')
        );

        $this->assertIsString($view);

        foreach ([
            'data-theme-result-workspace="index"',
            'data-theme-result-filter-panel',
            'data-theme-result-master-detail',
            'data-theme-result-master-panel',
            'data-theme-result-master-list',
            'data-theme-result-master-row',
            'data-theme-result-primary-panel',
            'data-theme-result-primary-card',
            '$market->name',
            '$market->code',
            '$latestResult->winning_numbers',
            'Filter aktif',
        ] as $contract) {
            $this->assertStringContainsString($contract, $view);
        }

        foreach ([
            'data-theme-result-grid',
            'data-theme-result-card',
            'Detail {{ $market->name }}',
        ] as $legacy) {
            $this->assertStringNotContainsString($legacy, $view);
        }
    }

    public function test_each_master_row_is_backed_by_market_loop(): void
    {
        $view = file_get_contents(
            resource_path('views/frontend/results/index.blade.php')
        );

        $this->assertIsString($view);

        $this->assertStringContainsString(
            '@foreach ($markets as $market)',
            $view
        );

        $this->assertStringContainsString(
            'data-theme-result-master-row',
            $view
        );

        $this->assertStringContainsString(
            "route('results.history'",
            $view
        );
    }

    public function test_history_is_theme_aware_and_responsive(): void
    {
        $view = file_get_contents(
            resource_path('views/frontend/results/history.blade.php')
        );

        $this->assertIsString($view);

        foreach ([
            'data-theme-result-workspace="history"',
            'data-theme-result-history-card',
            'data-theme-result-history-row',
            'data-theme-surface',
            'data-theme-result-number-panel',
            'md:grid-cols-[160px_minmax(0,1fr)_140px]',
        ] as $contract) {
            $this->assertStringContainsString($contract, $view);
        }
    }

    public function test_result_detail_has_structured_detail_layout(): void
    {
        $view = file_get_contents(
            resource_path('views/frontend/results/show.blade.php')
        );

        $this->assertIsString($view);

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
            $this->assertStringContainsString($contract, $view);
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
            $view = file_get_contents(resource_path($path));

            $this->assertIsString($view);

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
                    "{$legacy} masih ditemukan di {$path}"
                );
            }
        }
    }

    public function test_result_theme_css_exposes_semantic_statuses(): void
    {
        $tokens = file_get_contents(
            resource_path('views/frontend/partials/theme-tokens.blade.php')
        );

        $this->assertIsString($tokens);

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
            $this->assertStringContainsString($contract, $tokens);
        }
    }

    public function test_result_routes_are_preserved(): void
    {
        $index = file_get_contents(
            resource_path('views/frontend/results/index.blade.php')
        );

        $history = file_get_contents(
            resource_path('views/frontend/results/history.blade.php')
        );

        $show = file_get_contents(
            resource_path('views/frontend/results/show.blade.php')
        );

        $this->assertStringContainsString(
            "route('results.history'",
            $index
        );

        $this->assertStringContainsString(
            "route('results.show'",
            $history
        );

        $this->assertStringContainsString(
            "route('results.history'",
            $show
        );

        $this->assertStringContainsString(
            "route('results.index')",
            $show
        );
    }
}
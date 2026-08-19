<?php

declare(strict_types=1);

namespace Tests\Feature\Result;

use Tests\TestCase;

final class ResultResponsiveWorkspaceContractTest extends TestCase
{
    public function test_index_has_master_detail_workspace(): void
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
            '@foreach ($markets as $market)',
            'Filter aktif',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view
            );
        }

        foreach ([
            'data-theme-result-grid',
            'data-theme-result-card',
        ] as $legacy) {
            $this->assertStringNotContainsString(
                $legacy,
                $view
            );
        }
    }
    public function test_history_has_compact_row_workspace(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/results/history.blade.php',
            ),
        );

        $this->assertIsString($view);

        foreach ([
            'data-theme-result-workspace="history"',
            'data-theme-result-context-panel',
            'data-theme-result-detail-panel',
            'data-theme-result-history-list',
            'data-theme-result-history-row',
            'md:grid-cols-[160px_minmax(0,1fr)_140px]',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }

        $this->assertStringNotContainsString(
            'xl:grid-cols-3',
            $view,
        );
    }

    public function test_detail_has_master_detail_workspace(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/results/show.blade.php',
            ),
        );

        $this->assertIsString($view);

        foreach ([
            'data-theme-result-workspace="detail"',
            'data-theme-result-context-panel',
            'data-theme-result-detail-panel',
            'data-theme-result-detail',
            'data-theme-result-number-panel',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_workspace_theme_contract_exists(): void
    {
        $tokens = file_get_contents(
            resource_path(
                'views/frontend/partials/theme-tokens.blade.php',
            ),
        );

        $this->assertIsString($tokens);

        foreach ([
            '<style id="brand-theme-result-workspace">',
            '[data-theme-result-workspace]',
            '[data-theme-result-filter-panel]',
            '[data-theme-result-context-panel]',
            '[data-theme-result-detail-panel]',
            '[data-theme-result-history-row]',
            '@media (min-width: 1024px)',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $tokens,
            );
        }
    }

    public function test_business_navigation_contract_is_preserved(): void
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

        $detail = file_get_contents(
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
            $detail,
        );

        $this->assertStringContainsString(
            "route('results.index')",
            $detail,
        );
    }
    public function test_index_mobile_workspace_prevents_horizontal_overflow(): void
    {
        $index = file_get_contents(
            resource_path('views/frontend/results/index.blade.php')
        );

        $tokens = file_get_contents(
            resource_path('views/frontend/partials/theme-tokens.blade.php')
        );

        $this->assertIsString($index);
        $this->assertIsString($tokens);

        foreach ([
            'data-theme-result-workspace="index"',
            'data-theme-result-master-detail',
            'data-theme-result-master-row',
            'data-theme-result-primary-panel',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $index,
            );
        }

        foreach ([
            '[data-theme-result-workspace="index"]',
            'overflow-x: clip;',
            '[data-theme-result-master-row] > *',
            'min-width: 0;',
            'max-width: 100%;',
            '@media (max-width: 767px)',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $tokens,
            );
        }

        $this->assertStringNotContainsString(
            'body { overflow-x: hidden',
            $tokens,
        );

        $this->assertStringNotContainsString(
            'html { overflow-x: hidden',
            $tokens,
        );
    }
}

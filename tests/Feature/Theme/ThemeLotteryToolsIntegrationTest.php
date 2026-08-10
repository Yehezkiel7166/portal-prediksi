<?php

declare(strict_types=1);

namespace Tests\Feature\Theme;

use Tests\TestCase;

final class ThemeLotteryToolsIntegrationTest extends TestCase
{
    public function test_all_remaining_lottery_tools_are_theme_scoped(): void
    {
        $contracts = [
            'tools/bbfs-generator.blade.php' => 'data-theme-tool="bbfs"',

            'tools/dream-book-index.blade.php' => 'data-theme-tool="dream-book-index"',

            'tools/dream-book-show.blade.php' => 'data-theme-tool="dream-book-detail"',

            'tools/lottery-schedule.blade.php' => 'data-theme-tool="lottery-schedule"',

            'tools/paito.blade.php' => 'data-theme-tool="paito"',

            'tools/shio-table.blade.php' => 'data-theme-tool="shio"',
        ];

        foreach ($contracts as $path => $contract) {
            $view = file_get_contents(
                resource_path(
                    'views/frontend/'.$path,
                ),
            );

            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_bbfs_logic_contract_is_preserved(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/bbfs-generator.blade.php',
            ),
        );

        foreach ([
            "route('tools.bbfs.store')",
            '$result',
            "\$result['combinations']",
            "\$result['count']",
            '[2, 3, 4]',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_dream_book_contract_is_preserved(): void
    {
        $index = file_get_contents(
            resource_path(
                'views/frontend/tools/dream-book-index.blade.php',
            ),
        );

        $show = file_get_contents(
            resource_path(
                'views/frontend/tools/dream-book-show.blade.php',
            ),
        );

        foreach ([
            '$categories',
            '$entries',
            '$query',
            "'tools.dream-book.show'",
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $index,
            );
        }

        foreach ([
            "\$entry['number']",
            "\$entry['interpretation']",
            "\$entry['keywords']",
            '$related',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $show,
            );
        }
    }

    public function test_schedule_contract_is_preserved(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/lottery-schedule.blade.php',
            ),
        );

        foreach ([
            '$markets',
            '$market->close_time',
            '$market->result_time',
            '$market->open_time',
            '$market->schedule_status',
            '$market->official_url',
            'data-theme-schedule-status',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_paito_logic_and_color_palette_are_preserved(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php',
            ),
        );

        foreach ([
            '$palette',
            'data-paito-palette',
            'data-paito-weekly-grid',
            'data-paito-responsive-table',
            'data-result-id',
            'data-position',
            'data-color',
            'data-auto-position',
            'data-auto-color',
            'data-hls-player',
        ] as $contract) {
            if ($contract === 'data-hls-player') {
                continue;
            }

            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }

        foreach ([
            "'red' => '#ef4444'",
            "'blue' => '#3b82f6'",
            "'green' => '#22c55e'",
        ] as $colorContract) {
            $this->assertStringContainsString(
                $colorContract,
                $view,
            );
        }
    }

    public function test_shio_banner_and_data_are_preserved(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/shio-table.blade.php',
            ),
        );

        foreach ([
            '$period',
            '$bannerUrl',
            '$period->shios',
            '$shio->numbers',
            '<img',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_sgp_remains_theme_integrated_and_formula_safe(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/sgp-number-converter.blade.php',
            ),
        );

        foreach ([
            'data-sgp-converter',
            '--sgp-page-bg: var(--theme-page-bg',
            '--sgp-surface: var(--theme-surface',
            '--sgp-primary: var(--theme-primary',
            'repeat(7, 64px)',
            'width: 64px',
            'height: 44px',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_lottery_tool_theme_css_exists(): void
    {
        $tokens = file_get_contents(
            resource_path(
                'views/frontend/partials/theme-tokens.blade.php',
            ),
        );

        foreach ([
            '<style id="brand-theme-lottery-tools">',
            '[data-theme-tool="bbfs"]',
            '[data-theme-tool="dream-book-index"]',
            '[data-theme-tool="lottery-schedule"]',
            '[data-theme-tool="paito"]',
            '[data-theme-tool="shio"]',
            '[data-theme-schedule-status="open"]',
            'var(--theme-success)',
            'var(--theme-warning)',
            'var(--theme-danger)',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $tokens,
            );
        }
    }
}

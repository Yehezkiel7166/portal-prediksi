<?php

namespace Tests\Feature\Paito;

use Tests\TestCase;

class PaitoResponsiveGridContractTest extends TestCase
{
    public function test_grid_supports_horizontal_scroll(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach ([
            'data-paito-responsive-scroll',
            'data-paito-responsive-table',
            'overflow-x-auto',
            'max-w-full',
            'w-max',
            'min-w-[1680px]',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_all_weekdays_remain_in_same_table(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach ([
            "1 => 'Senin'",
            "2 => 'Selasa'",
            "3 => 'Rabu'",
            "4 => 'Kamis'",
            "5 => 'Jumat'",
            "6 => 'Sabtu'",
            "7 => 'Minggu'",
        ] as $day) {
            $this->assertStringContainsString(
                $day,
                $view,
            );
        }
    }

    public function test_sum_column_is_wider_and_dimmed(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach ([
            'w-12 min-w-12',
            'md:w-14',
            'font-normal',
            'text-slate-400/60',
            'bg-slate-950/40',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_head_tail_separator_is_clear(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach ([
            'data-paito-head-tail-divider',
            'data-paito-head-tail-cell=',
            'border-r-2',
            'border-r-amber-500/70',
            'border-r-amber-500/60',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_result_digits_remain_white_and_bold(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        $this->assertStringContainsString(
            'text-sm font-bold text-white',
            $view,
        );
    }
}

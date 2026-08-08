<?php

namespace Tests\Feature\Paito;

use Tests\TestCase;

class PaitoCompactHeaderContractTest extends TestCase
{
    public function test_position_labels_are_not_rendered(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        $start = strpos($view, '<thead');
        $end = strpos($view, '</thead>');

        $this->assertNotFalse($start);
        $this->assertNotFalse($end);

        $thead = substr(
            $view,
            $start,
            $end - $start,
        );

        foreach ([
            '>AS<',
            '>KOP<',
            '>KEPALA<',
            '>EKOR<',
            'data-paito-position-header',
        ] as $forbidden) {
            $this->assertStringNotContainsString(
                $forbidden,
                $thead,
            );
        }
    }

    public function test_weekday_and_d_headers_remain(): void
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
            'data-paito-sum-header',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_result_cells_are_compact(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach ([
            'data-paito-compact-table',
            'min-w-[1260px]',
            'w-9 min-w-9 text-sm font-bold text-white',
            'md:w-10 md:min-w-10',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_sum_cells_are_compact_and_dimmed(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach ([
            'w-9 min-w-9',
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

    public function test_horizontal_scroll_is_preserved(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach ([
            'overflow-x-auto',
            'data-paito-responsive-scroll',
            'data-paito-responsive-table',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }
}

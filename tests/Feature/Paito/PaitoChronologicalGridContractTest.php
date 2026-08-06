<?php

namespace Tests\Feature\Paito;

use Tests\TestCase;

class PaitoChronologicalGridContractTest extends TestCase
{
    public function test_oldest_week_is_rendered_first(): void
    {
        $controller = file_get_contents(
            app_path(
                'Http/Controllers/Frontend/PaitoController.php'
            )
        );

        $this->assertStringContainsString(
            "->sortBy('week_start')",
            $controller,
        );

        $this->assertStringNotContainsString(
            "->sortByDesc('week_start')",
            $controller,
        );
    }

    public function test_result_digits_remain_separate_cells(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach ([
            '$positions = [',
            "'as',",
            "'kop',",
            "'kepala',",
            "'ekor',",
            "'jumlah',",
            '@foreach ($positions as $position)',
            'data-paito-result-cell=',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_sum_cells_are_visually_distinct(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach ([
            'data-paito-sum-header',
            'data-paito-sum-cell=',
            "position === 'jumlah'",
            'text-xs font-normal text-slate-400',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_grid_has_no_number_column(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach ([
            '>No<',
            '>Nomor<',
            '>Tanggal<',
        ] as $heading) {
            $this->assertStringNotContainsString(
                $heading,
                $view,
            );
        }
    }

    public function test_auto_inputs_show_single_digit_hint(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        $this->assertStringContainsString(
            'maxlength="1"',
            $view,
        );

        $this->assertStringContainsString(
            'placeholder="0–9"',
            $view,
        );
    }
}

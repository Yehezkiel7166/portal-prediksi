<?php

namespace Tests\Feature\Paito;

use Tests\TestCase;

class PaitoGoldHeaderContractTest extends TestCase
{
    public function test_only_grid_headers_use_gold_theme(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach ([
            'data-paito-gold-header',
            'data-paito-weekday-header',
            'data-paito-position-header',
            'text-amber-400',
            'rowspan="2"',
            'colspan="4"',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_day_and_position_headers_are_separate(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach ([
            "1 => 'Senin'",
            "7 => 'Minggu'",
            '@foreach (range(1, 7) as $dayNumber)',
            'AS',
            'KOP',
            'KEPALA',
            'EKOR',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_result_and_form_text_remain_white(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        $this->assertStringContainsString(
            'min-w-[46px] text-sm font-bold text-white',
            $view,
        );

        $this->assertStringContainsString(
            'class="mb-2 block text-sm font-semibold text-white"',
            $view,
        );

        $this->assertStringContainsString(
            'w-12 min-w-12 bg-slate-950/40 text-xs font-normal text-slate-400/60',
            $view,
        );
    }

    public function test_number_column_is_not_rendered(): void
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
}

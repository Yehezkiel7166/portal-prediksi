<?php

namespace Tests\Feature\Paito;

use Tests\TestCase;

class PaitoManualPaintContractTest extends TestCase
{
    public function test_paito_uses_weekly_result_grid(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        $this->assertStringContainsString(
            'data-paito-weekly-grid',
            $view,
        );

        $this->assertStringContainsString(
            '@foreach ($weeks as $week)',
            $view,
        );

        $this->assertStringContainsString(
            '@foreach (range(1, 7) as $dayNumber)',
            $view,
        );

        $this->assertStringNotContainsString(
            'data-paito-day',
            $view,
        );
    }

    public function test_manual_paint_controls_exist(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach (
            [
                'data-tool="paint"',
                'data-tool="erase"',
                'Hapus Semua Warna',
                'activeColor = button.dataset.color;',
                '{ position, color: activeColor }',
                'handleCellPaint',
            ] as $contract
        ) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }
}

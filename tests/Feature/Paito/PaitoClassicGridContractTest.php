<?php

namespace Tests\Feature\Paito;

use Tests\TestCase;

class PaitoClassicGridContractTest extends TestCase
{
    public function test_grid_uses_weekly_classic_layout(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach (
            [
                'data-paito-weekly-grid',
                "1 => 'Senin'",
                "2 => 'Selasa'",
                "3 => 'Rabu'",
                "4 => 'Kamis'",
                "5 => 'Jumat'",
                "6 => 'Sabtu'",
                "7 => 'Minggu'",
                'colspan="4"',
                'aria-label="Jumlah {{ $dayLabel }}"',
            ] as $contract
        ) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_weekly_grid_has_no_number_or_position_headers(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach (
            [
                '>No<',
                '>Nomor<',
                '>AS<',
                '>KOP<',
                '>KEPALA<',
                '>EKOR<',
                '>JUMLAH<',
                '>Tanggal<',
                '>Hari<',
            ] as $heading
        ) {
            $this->assertStringNotContainsString(
                $heading,
                $view,
            );
        }
    }

    public function test_paint_features_remain_available(): void
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
                'id="auto-paint"',
                'data-auto-color="{{ $position }}"',
                'activeColor = button.dataset.color;',
                'data-position="{{ $position }}"',
                'data-digit="{{ $digit }}"',
            ] as $contract
        ) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }
}

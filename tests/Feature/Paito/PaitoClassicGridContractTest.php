<?php

namespace Tests\Feature\Paito;

use Tests\TestCase;

class PaitoClassicGridContractTest extends TestCase
{
    public function test_grid_uses_classic_day_layout(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach (
            [
                'data-paito-classic-grid',
                'table-fixed',
                'data-paito-day',
                'data-digit="{{ $digit }}"',
                'role="button"',
                'h-16',
            ] as $contract
        ) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_position_headers_remain_hidden(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach (
            [
                '>AS<',
                '>KOP<',
                '>KEPALA<',
                '>EKOR<',
                '>JUMLAH<',
                '>Tanggal<',
            ] as $heading
        ) {
            $this->assertStringNotContainsString(
                $heading,
                $view,
            );
        }

        $this->assertStringContainsString(
            'Hari',
            $view,
        );
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
            ] as $contract
        ) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }
}

<?php

namespace Tests\Feature\Paito;

use Tests\TestCase;

class PaitoManualPaintContractTest extends TestCase
{
    public function test_paito_uses_day_grid(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        $this->assertStringContainsString(
            'data-paito-day',
            $view,
        );

        $this->assertStringContainsString(
            "translatedFormat('l')",
            $view,
        );

        $this->assertStringNotContainsString(
            '>Tanggal<',
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

        $this->assertStringContainsString(
            'data-tool="paint"',
            $view,
        );

        $this->assertStringContainsString(
            'data-tool="erase"',
            $view,
        );

        $this->assertStringContainsString(
            'Hapus Semua Warna',
            $view,
        );

        $this->assertStringContainsString(
            '{{ ucfirst($name) }}',
            $view,
        );
    }
}

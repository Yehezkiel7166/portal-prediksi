<?php

namespace Tests\Feature\Paito;

use Tests\TestCase;

class PaitoManualPaintContractTest extends TestCase
{
    public function test_paito_uses_required_columns(): void
    {
        $view = file_get_contents(
            resource_path('views/frontend/tools/paito.blade.php')
        );

        foreach (['AS', 'KOP', 'KEPALA', 'EKOR', 'JUMLAH'] as $column) {
            $this->assertStringContainsString(
                '>'.$column.'<',
                $view,
            );
        }

        $this->assertStringNotContainsString(
            '>Tanggal<',
            $view,
        );
    }

    public function test_manual_paint_controls_exist(): void
    {
        $view = file_get_contents(
            resource_path('views/frontend/tools/paito.blade.php')
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
            'data-position="{{ $position }}"',
            $view,
        );
    }
}

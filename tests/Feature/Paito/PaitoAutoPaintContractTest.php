<?php

namespace Tests\Feature\Paito;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PaitoAutoPaintContractTest extends TestCase
{
    public function test_auto_paint_interface_exists(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        $this->assertStringContainsString(
            'id="auto-paint"',
            $view,
        );

        $this->assertStringContainsString(
            'data-auto-position="{{ $position }}"',
            $view,
        );

        $this->assertStringContainsString(
            '/alat-togel/paito-warna/colors/bulk',
            $view,
        );

        $this->assertStringContainsString(
            'JUMLAH',
            $view,
        );
    }

    public function test_bulk_route_is_registered(): void
    {
        $this->assertTrue(
            Route::has('tools.paito.color.bulk')
        );
    }
}

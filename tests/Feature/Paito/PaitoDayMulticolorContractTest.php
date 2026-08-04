<?php

namespace Tests\Feature\Paito;

use Tests\TestCase;

class PaitoDayMulticolorContractTest extends TestCase
{
    public function test_auto_paint_uses_color_per_position(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        $this->assertStringContainsString(
            'data-auto-color="{{ $position }}"',
            $view,
        );

        $this->assertStringContainsString(
            'color: rule.color',
            $view,
        );

        $this->assertStringContainsString(
            'colors[rule.color]',
            $view,
        );
    }

    public function test_position_headers_are_hidden(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach (
            ['>AS<', '>KOP<', '>KEPALA<', '>EKOR<', '>JUMLAH<'] as $header
        ) {
            $this->assertStringNotContainsString(
                $header,
                $view,
            );
        }
    }
}

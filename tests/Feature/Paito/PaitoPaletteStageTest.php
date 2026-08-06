<?php

namespace Tests\Feature\Paito;

use Tests\TestCase;

class PaitoPaletteStageTest extends TestCase
{
    public function test_palette_is_visible_and_selectable(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach (
            [
                'data-paito-palette',
                'data-color-hex="{{ $hex }}"',
                'id="active-color-preview"',
                'id="active-color-label"',
                '{{ ucfirst($name) }}',
            ] as $contract
        ) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_manual_paint_uses_active_color(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach (
            [
                'activeColor = button.dataset.color;',
                '{ position, color: activeColor }',
                'colors[activeColor]',
                "tool === 'erase'",
            ] as $contract
        ) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }
}

<?php

namespace Tests\Feature\Paito;

use Tests\TestCase;

class PaitoInteractionContractTest extends TestCase
{
    public function test_interaction_is_locked_and_reports_status(): void
    {
        $view = file_get_contents(
            resource_path('views/frontend/tools/paito.blade.php')
        );

        foreach ([
            'id="paito-status"',
            'aria-live="polite"',
            'let requestInProgress = false;',
            'showStatus(',
            "setAttribute('disabled', 'disabled')",
            "removeAttribute('disabled')",
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_cells_support_keyboard_input(): void
    {
        $view = file_get_contents(
            resource_path('views/frontend/tools/paito.blade.php')
        );

        foreach ([
            'handleCellPaint',
            "'keydown'",
            "event.key !== 'Enter'",
            "event.key !== ' '",
            'event.preventDefault();',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }
}

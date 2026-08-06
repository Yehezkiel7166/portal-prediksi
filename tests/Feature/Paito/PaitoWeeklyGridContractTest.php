<?php

namespace Tests\Feature\Paito;

use Tests\TestCase;

class PaitoWeeklyGridContractTest extends TestCase
{
    public function test_grid_uses_weekday_columns(): void
    {
        $view = file_get_contents(
            resource_path('views/frontend/tools/paito.blade.php')
        );

        foreach ([
            'data-paito-weekly-grid',
            "1 => 'Senin'",
            "7 => 'Minggu'",
            'colspan="4"',
            'aria-label="Jumlah {{ $dayLabel }}"',
            '$positions = [',
            "'as',",
            "'kop',",
            "'kepala',",
            "'ekor',",
            "'jumlah',",
            '@foreach ($positions as $position)',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }

        $this->assertStringNotContainsString(
            'data-paito-day',
            $view,
        );
    }

    public function test_result_and_sum_digit_are_available(): void
    {
        $controller = file_get_contents(
            app_path(
                'Http/Controllers/Frontend/PaitoController.php'
            )
        );

        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        $this->assertStringContainsString(
            'array_sum(',
            $controller,
        );

        $this->assertStringContainsString(
            ') % 10',
            $controller,
        );

        $this->assertStringContainsString(
            "{{ \$day['winning_numbers'] }}",
            $view,
        );
    }

    public function test_auto_inputs_accept_one_digit(): void
    {
        $view = file_get_contents(
            resource_path('views/frontend/tools/paito.blade.php')
        );

        foreach ([
            'maxlength="1"',
            'pattern="[0-9]"',
            'data-single-digit',
            '.slice(0, 1)',
            'digits: [digit]',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }
}

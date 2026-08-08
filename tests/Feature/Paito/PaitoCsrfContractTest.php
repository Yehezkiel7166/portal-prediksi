<?php

namespace Tests\Feature\Paito;

use Tests\TestCase;

class PaitoCsrfContractTest extends TestCase
{
    public function test_frontend_layout_exposes_csrf_meta_token(): void
    {
        $layout = file_get_contents(
            resource_path(
                'views/frontend/layouts/app.blade.php'
            )
        );

        $this->assertStringContainsString(
            '<meta name="csrf-token" content="{{ csrf_token() }}">',
            $layout,
        );
    }

    public function test_paito_has_csrf_fallback(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach ([
            "document.querySelector('meta[name=\"csrf-token\"]')?.content",
            '|| @json(csrf_token());',
            "'X-CSRF-TOKEN': token",
            "credentials: 'same-origin'",
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_clear_all_still_validates_token_before_request(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/paito.blade.php'
            )
        );

        foreach ([
            'if (!token)',
            'Token keamanan tidak tersedia.',
            "method: 'DELETE'",
            'clearAllButton',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }
}

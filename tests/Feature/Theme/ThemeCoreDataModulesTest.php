<?php

declare(strict_types=1);

namespace Tests\Feature\Theme;

use Tests\TestCase;

final class ThemeCoreDataModulesTest extends TestCase
{
    public function test_prediction_index_is_theme_scoped(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/predictions/index.blade.php',
            ),
        );

        foreach ([
            'data-theme-module="predictions"',
            'data-theme-datepicker',
            'data-theme-surface',
            '$predictions',
            '$markets',
            '$filters',
            'data-datepicker-trigger',
            'data-datepicker-panel',
            'data-datepicker-days',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_prediction_detail_is_theme_scoped(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/predictions/show.blade.php',
            ),
        );

        foreach ([
            'data-theme-module="prediction-detail"',
            'data-theme-surface',
            '$prediction->bbfs',
            '$prediction->prediction_2d',
            '$prediction->prediction_3d',
            '$prediction->prediction_4d',
            '$prediction->shio',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_live_draw_is_theme_scoped(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/live-draw/index.blade.php',
            ),
        );

        foreach ([
            'data-theme-module="live-draw"',
            'data-theme-live-card',
            'data-theme-live-status="{{ $liveDraw->status }}"',
            '$liveDraw->publicEmbedUrl()',
            '$liveDraw->latestResult',
            'data-hls-player',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_live_media_contract_is_preserved(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/live-draw/index.blade.php',
            ),
        );

        foreach ([
            '<iframe',
            '<video',
            'background-image:',
            '$liveDraw->background_path',
            '$liveDraw->logo_path',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $view,
            );
        }
    }

    public function test_core_data_theme_css_exists(): void
    {
        $tokens = file_get_contents(
            resource_path(
                'views/frontend/partials/theme-tokens.blade.php',
            ),
        );

        foreach ([
            '<style id="brand-theme-core-data">',
            '[data-theme-module="predictions"]',
            '[data-theme-module="prediction-detail"]',
            '[data-theme-module="live-draw"]',
            '[data-theme-live-status="live"]',
            '[data-theme-live-status="scheduled"]',
            '[data-theme-live-status="finished"]',
            'var(--theme-success)',
            'var(--theme-danger)',
            'var(--theme-warning)',
            'var(--theme-info)',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $tokens,
            );
        }
    }

    public function test_theme_does_not_replace_prediction_logic(): void
    {
        $index = file_get_contents(
            resource_path(
                'views/frontend/predictions/index.blade.php',
            ),
        );

        $show = file_get_contents(
            resource_path(
                'views/frontend/predictions/show.blade.php',
            ),
        );

        foreach ([
            'BBFS',
            'Colok Bebas',
            "'2D'",
            "'3D'",
            "'4D'",
            'Kembar',
            'Shio',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $index,
            );

            $this->assertStringContainsString(
                $contract,
                $show,
            );
        }
    }
}

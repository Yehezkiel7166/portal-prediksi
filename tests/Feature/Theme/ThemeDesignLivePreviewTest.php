<?php

declare(strict_types=1);

namespace Tests\Feature\Theme;

use App\Domains\Theme\Support\ThemeDesignPreview;
use Tests\TestCase;

final class ThemeDesignLivePreviewTest extends TestCase
{
    public function test_preview_renders_selected_theme(): void
    {
        $html = (string) app(
            ThemeDesignPreview::class,
        )->renderFromState([
            'theme_preset' => 'gold-black-classic',
            'theme_background_mode' => 'theme',
            'theme_component_style' => 'solid',
            'theme_component_opacity' => 100,
            'theme_component_blur' => 0,
        ]);

        $this->assertStringContainsString(
            'data-theme-live-preview',
            $html,
        );

        $this->assertStringContainsString(
            'Gold Black Classic',
            $html,
        );

        $this->assertStringContainsString(
            'linear-gradient',
            $html,
        );

        $this->assertStringContainsString(
            'Keterbacaan preview: AMAN',
            $html,
        );
    }

    public function test_glass_preview_uses_blur(): void
    {
        $html = (string) app(
            ThemeDesignPreview::class,
        )->renderFromState([
            'theme_preset' => 'white-gold-glass',
            'theme_background_mode' => 'theme',
            'theme_component_style' => 'glass',
            'theme_component_opacity' => 65,
            'theme_component_blur' => 14,
        ]);

        $this->assertStringContainsString(
            'backdrop-filter:blur(14px)',
            $html,
        );

        $this->assertStringContainsString(
            'Opacity 65%',
            $html,
        );
    }

    public function test_low_solid_opacity_produces_warning(): void
    {
        $warnings = app(
            ThemeDesignPreview::class,
        )->readabilityWarnings(
            backgroundMode: 'theme',
            overlayEnabled: false,
            componentStyle: 'solid',
            componentOpacity: 40,
        );

        $this->assertNotEmpty($warnings);

        $this->assertStringContainsString(
            'minimal 85%',
            $warnings[0],
        );
    }

    public function test_custom_image_without_overlay_requires_minimum_opacity(): void
    {
        $warnings = app(
            ThemeDesignPreview::class,
        )->readabilityWarnings(
            backgroundMode: 'image',
            overlayEnabled: false,
            componentStyle: 'glass',
            componentOpacity: 40,
        );

        $this->assertTrue(
            collect($warnings)->contains(
                static fn (
                    string $warning,
                ): bool => str_contains(
                    $warning,
                    'opacity komponen minimal 55%',
                ),
            ),
        );
    }

    public function test_readability_minimum_contract(): void
    {
        $this->assertSame(
            [
                'solid' => 85,
                'semi-transparent' => 45,
                'glass' => 35,
                'outline' => 20,
            ],
            ThemeDesignPreview::MINIMUM_OPACITY,
        );
    }

    public function test_form_delegates_preview_to_preview_service(): void
    {
        $form = file_get_contents(
            app_path(
                'Filament/Resources/SiteConfigurations/Schemas/SiteConfigurationForm.php',
            ),
        );

        foreach ([
            "Placeholder::make('theme_preview')",
            'Live Preview Design',
            'ThemeDesignPreview::class',
            '->render($get)',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $form,
            );
        }

        /*
         * Gradient/palette implementation belongs to the preview service,
         * not the Filament form.
         */
        $preview = file_get_contents(
            app_path(
                'Domains/Theme/Support/ThemeDesignPreview.php',
            ),
        );

        $this->assertStringContainsString(
            'linear-gradient',
            $preview,
        );

        $this->assertStringContainsString(
            "\$preset['palette']",
            $preview,
        );
    }

    public function test_preview_controls_are_reactive(): void
    {
        $form = file_get_contents(
            app_path(
                'Filament/Resources/SiteConfigurations/Schemas/SiteConfigurationForm.php',
            ),
        );

        foreach ([
            'theme_preset',
            'theme_background_mode',
            'theme_background_image',
            'theme_background_size',
            'theme_background_position',
            'theme_overlay_enabled',
            'theme_overlay_color',
            'theme_overlay_opacity',
            'theme_component_style',
            'theme_component_opacity',
            'theme_component_blur',
        ] as $field) {
            $this->assertStringContainsString(
                "'{$field}'",
                $form,
            );
        }

        $this->assertGreaterThanOrEqual(
            10,
            substr_count(
                $form,
                '->live()',
            ),
        );
    }

    public function test_save_page_contains_readability_guard(): void
    {
        $page = file_get_contents(
            app_path(
                'Filament/Resources/SiteConfigurations/Pages/EditSiteConfiguration.php',
            ),
        );

        foreach ([
            'READABILITY SAVE GUARD',
            'ThemeDesignPreview::MINIMUM_OPACITY',
            'opacity komponen minimal 55%',
            'agar konten tetap mudah dibaca',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $page,
            );
        }
    }
}

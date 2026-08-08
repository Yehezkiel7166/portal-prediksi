<?php

declare(strict_types=1);

namespace Tests\Feature\Theme;

use Tests\TestCase;

final class SiteConfigurationThemeAppearanceTest extends TestCase
{
    public function test_custom_background_controls_live_inside_design_section(): void
    {
        $form = file_get_contents(
            app_path(
                'Filament/Resources/SiteConfigurations/Schemas/SiteConfigurationForm.php',
            ),
        );

        foreach ([
            "Section::make('Design')",

            "Select::make('theme_background_mode')",

            'FileUpload::make(',
            "'theme_background_image'",

            "'brand-design/backgrounds'",

            "'theme_background_size'",
            "'theme_background_position'",

            "'theme_background_repeat'",
            "'theme_background_fixed'",
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $form,
            );
        }
    }

    public function test_overlay_controls_exist(): void
    {
        $form = file_get_contents(
            app_path(
                'Filament/Resources/SiteConfigurations/Schemas/SiteConfigurationForm.php',
            ),
        );

        foreach ([
            "'theme_overlay_enabled'",
            "'theme_overlay_color'",
            "'theme_overlay_opacity'",
            'ColorPicker::make(',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $form,
            );
        }
    }

    public function test_component_appearance_controls_exist(): void
    {
        $form = file_get_contents(
            app_path(
                'Filament/Resources/SiteConfigurations/Schemas/SiteConfigurationForm.php',
            ),
        );

        foreach ([
            "'theme_component_style'",
            "'theme_component_opacity'",
            "'theme_component_blur'",

            "'solid'",
            "'semi-transparent'",
            "'glass'",
            "'outline'",
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $form,
            );
        }
    }

    public function test_theme_fields_are_loaded_from_brand_theme_setting(): void
    {
        $page = file_get_contents(
            app_path(
                'Filament/Resources/SiteConfigurations/Pages/EditSiteConfiguration.php',
            ),
        );

        foreach ([
            "'theme_background_mode'",
            "'theme_background_image'",
            "'theme_overlay_enabled'",
            "'theme_overlay_opacity'",
            "'theme_component_style'",
            "'theme_component_opacity'",
            "'theme_component_blur'",
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $page,
            );
        }
    }

    public function test_virtual_design_fields_are_removed_before_site_configuration_save(): void
    {
        $page = file_get_contents(
            app_path(
                'Filament/Resources/SiteConfigurations/Pages/EditSiteConfiguration.php',
            ),
        );

        $this->assertStringContainsString(
            '$virtualField',
            $page,
        );

        $this->assertStringContainsString(
            'unset(',
            $page,
        );

        $this->assertStringContainsString(
            '$data[$virtualField]',
            $page,
        );
    }

    public function test_custom_image_is_required_when_image_mode_is_selected(): void
    {
        $page = file_get_contents(
            app_path(
                'Filament/Resources/SiteConfigurations/Pages/EditSiteConfiguration.php',
            ),
        );

        $this->assertStringContainsString(
            "=== 'image'",
            $page,
        );

        $this->assertStringContainsString(
            'Upload background terlebih dahulu.',
            $page,
        );
    }

    public function test_theme_mode_removes_custom_background_image(): void
    {
        $page = file_get_contents(
            app_path(
                'Filament/Resources/SiteConfigurations/Pages/EditSiteConfiguration.php',
            ),
        );

        $this->assertStringContainsString(
            "\$backgroundMode === 'image'",
            $page,
        );

        $this->assertStringContainsString(
            ': null;',
            $page,
        );
    }

    public function test_percentage_values_are_converted_to_database_decimal_values(): void
    {
        $page = file_get_contents(
            app_path(
                'Filament/Resources/SiteConfigurations/Pages/EditSiteConfiguration.php',
            ),
        );

        $this->assertStringContainsString(
            "'overlay_opacity'",
            $page,
        );

        $this->assertStringContainsString(
            "'component_opacity'",
            $page,
        );

        $this->assertStringContainsString(
            '] / 100',
            $page,
        );
    }

    public function test_appearance_values_have_safety_limits(): void
    {
        $page = file_get_contents(
            app_path(
                'Filament/Resources/SiteConfigurations/Pages/EditSiteConfiguration.php',
            ),
        );

        foreach ([
            'Opacity overlay harus antara 0 sampai 100.',
            'Opacity komponen harus antara 10 sampai 100.',
            'Blur harus antara 0 sampai 30px.',
        ] as $message) {
            $this->assertStringContainsString(
                $message,
                $page,
            );
        }
    }

    public function test_theme_tokens_still_come_from_selected_preset(): void
    {
        $page = file_get_contents(
            app_path(
                'Filament/Resources/SiteConfigurations/Pages/EditSiteConfiguration.php',
            ),
        );

        $this->assertStringContainsString(
            "'tokens'",
            $page,
        );

        $this->assertStringContainsString(
            "\$preset['tokens']",
            $page,
        );
    }
}

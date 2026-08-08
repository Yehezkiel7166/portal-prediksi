<?php

declare(strict_types=1);

namespace Tests\Feature\Theme;

use Tests\TestCase;

final class SiteConfigurationThemeManagerTest extends TestCase
{
    public function test_theme_manager_lives_inside_site_configuration(): void
    {
        $form = file_get_contents(
            app_path(
                'Filament/Resources/SiteConfigurations/Schemas/SiteConfigurationForm.php',
            ),
        );

        $this->assertStringContainsString(
            "Section::make('Design')",
            $form,
        );

        $this->assertStringContainsString(
            "Select::make('theme_preset')",
            $form,
        );

        $this->assertStringContainsString(
            'ThemePresetCatalog::class',
            $form,
        );
    }

    public function test_no_separate_theme_filament_resource_is_created(): void
    {
        $this->assertDirectoryDoesNotExist(
            app_path(
                'Filament/Resources/BrandThemes',
            ),
        );

        $this->assertFileDoesNotExist(
            app_path(
                'Filament/Resources/BrandThemeResource.php',
            ),
        );
    }

    public function test_theme_preview_is_available_in_site_configuration(): void
    {
        $form = file_get_contents(
            app_path(
                'Filament/Resources/SiteConfigurations/Schemas/SiteConfigurationForm.php',
            ),
        );

        foreach ([
            "Placeholder::make('theme_preview')",
            'linear-gradient',
            'palette',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $form,
            );
        }
    }

    public function test_theme_is_loaded_from_active_brand(): void
    {
        $page = file_get_contents(
            app_path(
                'Filament/Resources/SiteConfigurations/Pages/EditSiteConfiguration.php',
            ),
        );

        foreach ([
            'BrandContext::class',
            'BrandThemeSetting::query()',
            "'brand_id'",
            "'is_active'",
            "'theme_preset'",
            'ThemePresetCatalog::class',
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $page,
            );
        }
    }

    public function test_selected_preset_is_saved_to_brand_theme_setting(): void
    {
        $page = file_get_contents(
            app_path(
                'Filament/Resources/SiteConfigurations/Pages/EditSiteConfiguration.php',
            ),
        );

        foreach ([
            'ThemePresetCatalog::class',
            'updateOrCreate(',
            "'theme_slug'",
            "'tokens'",
            "'component_style'",
            "'component_opacity'",
            "'component_blur'",
        ] as $contract) {
            $this->assertStringContainsString(
                $contract,
                $page,
            );
        }
    }

    public function test_virtual_theme_field_is_removed_before_site_configuration_save(): void
    {
        $page = file_get_contents(
            app_path(
                'Filament/Resources/SiteConfigurations/Pages/EditSiteConfiguration.php',
            ),
        );

        $this->assertStringContainsString(
            "\$data['theme_preset']",
            $page,
        );

        $this->assertStringContainsString(
            'unset(',
            $page,
        );

        $unsetPosition = strpos(
            $page,
            'unset(',
        );

        $upsertPosition = strpos(
            $page,
            'UpsertSiteConfiguration::class',
        );

        $this->assertNotFalse(
            $unsetPosition,
        );

        $this->assertNotFalse(
            $upsertPosition,
        );

        $this->assertLessThan(
            $upsertPosition,
            $unsetPosition,
        );
    }

    public function test_invalid_preset_is_rejected(): void
    {
        $page = file_get_contents(
            app_path(
                'Filament/Resources/SiteConfigurations/Pages/EditSiteConfiguration.php',
            ),
        );

        $this->assertStringContainsString(
            'Template design tidak valid.',
            $page,
        );
    }

    public function test_theme_select_is_live_for_preview(): void
    {
        $form = file_get_contents(
            app_path(
                'Filament/Resources/SiteConfigurations/Schemas/SiteConfigurationForm.php',
            ),
        );

        $this->assertStringContainsString(
            '->live()',
            $form,
        );
    }
}

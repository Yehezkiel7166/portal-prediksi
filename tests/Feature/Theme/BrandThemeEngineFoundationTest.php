<?php

declare(strict_types=1);

namespace Tests\Feature\Theme;

use App\Domains\Brand\Models\Brand;
use App\Domains\Theme\Models\BrandThemeSetting;
use App\Domains\Theme\Support\BrandThemeResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class BrandThemeEngineFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_theme_contract_is_complete(): void
    {
        $theme = config(
            'brand-theme.defaults',
        );

        $this->assertSame(
            'midnight-gold',
            $theme['slug'],
        );

        foreach ([
            'page_bg',
            'surface',
            'primary',
            'secondary',
            'accent',
            'text',
            'text_muted',
            'border',
            'button_primary_bg',
            'input_bg',
            'table_header_bg',
            'result_bg',
            'header_bg',
            'footer_bg',
            'glow',
        ] as $token) {
            $this->assertArrayHasKey(
                $token,
                $theme['tokens'],
            );
        }
    }

    public function test_theme_setting_is_scoped_by_brand(): void
    {
        $brandA = Brand::factory()->create();

        $brandB = Brand::factory()->create();

        BrandThemeSetting::query()->create([
            'brand_id' => $brandA->id,
            'theme_slug' => 'white-gold',
            'tokens' => [
                'page_bg' => '#FFFFFF',
                'primary' => '#D4AF37',
            ],
        ]);

        BrandThemeSetting::query()->create([
            'brand_id' => $brandB->id,
            'theme_slug' => 'red-black',
            'tokens' => [
                'page_bg' => '#000000',
                'primary' => '#DC2626',
            ],
        ]);

        $resolver = app(
            BrandThemeResolver::class,
        );

        $themeA = $resolver->resolve(
            $brandA,
        );

        $themeB = $resolver->resolve(
            $brandB,
        );

        $this->assertSame(
            'white-gold',
            $themeA['slug'],
        );

        $this->assertSame(
            '#FFFFFF',
            $themeA['tokens']['page_bg'],
        );

        $this->assertSame(
            'red-black',
            $themeB['slug'],
        );

        $this->assertSame(
            '#DC2626',
            $themeB['tokens']['primary'],
        );
    }

    public function test_custom_background_configuration_is_supported(): void
    {
        $brand = Brand::factory()->create();

        BrandThemeSetting::query()->create([
            'brand_id' => $brand->id,

            'background_mode' => 'image',

            'background_image' => 'brand-design/backgrounds/custom.webp',

            'background_size' => 'cover',
            'background_position' => 'center',

            'overlay_enabled' => true,
            'overlay_color' => '#000000',
            'overlay_opacity' => 0.35,

            'component_style' => 'glass',
            'component_opacity' => 0.72,
            'component_blur' => 14,
        ]);

        $theme = app(
            BrandThemeResolver::class,
        )->resolve($brand);

        $this->assertSame(
            'image',
            $theme['background']['mode'],
        );

        $this->assertSame(
            'brand-design/backgrounds/custom.webp',
            $theme['background']['image'],
        );

        $this->assertTrue(
            $theme['background']['overlay']['enabled'],
        );

        $this->assertSame(
            0.35,
            $theme['background']['overlay']['opacity'],
        );

        $this->assertSame(
            'glass',
            $theme['appearance']['component_style'],
        );

        $this->assertSame(
            14,
            $theme['appearance']['component_blur'],
        );
    }

    public function test_global_theme_partial_exposes_css_tokens(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/partials/theme-tokens.blade.php',
            ),
        );

        foreach ([
            '--theme-page-bg',
            '--theme-surface',
            '--theme-primary',
            '--theme-secondary',
            '--theme-accent',
            '--theme-text',
            '--theme-border',
            '--theme-button-primary-bg',
            '--theme-input-bg',
            '--theme-table-header-bg',
            '--theme-result-bg',
            '--theme-glow',
            '--theme-component-opacity',
            '--theme-component-blur',
        ] as $token) {
            $this->assertStringContainsString(
                $token,
                $view,
            );
        }
    }

    public function test_frontend_layout_loads_theme_tokens(): void
    {
        $layout = file_get_contents(
            resource_path(
                'views/frontend/layouts/app.blade.php',
            ),
        );

        $this->assertStringContainsString(
            "@include('frontend.partials.theme-tokens')",
            $layout,
        );
    }

    public function test_sgp_is_connected_to_global_theme_tokens(): void
    {
        $view = file_get_contents(
            resource_path(
                'views/frontend/tools/sgp-number-converter.blade.php',
            ),
        );

        foreach ([
            '--theme-page-bg',
            '--theme-surface',
            '--theme-primary',
            '--theme-danger',
            '--theme-text',
            '--theme-result-border',
        ] as $token) {
            $this->assertStringContainsString(
                $token,
                $view,
            );
        }
    }

    public function test_allowed_component_styles_are_explicit(): void
    {
        $this->assertSame(
            [
                'solid',
                'semi-transparent',
                'glass',
                'outline',
            ],
            config(
                'brand-theme.allowed.component_styles',
            ),
        );
    }
}

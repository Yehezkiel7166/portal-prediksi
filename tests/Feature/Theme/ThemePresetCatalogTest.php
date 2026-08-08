<?php

declare(strict_types=1);

namespace Tests\Feature\Theme;

use App\Domains\Theme\Support\ThemePresetCatalog;
use Tests\TestCase;

final class ThemePresetCatalogTest extends TestCase
{
    public function test_catalog_contains_exactly_one_hundred_presets(): void
    {
        $presets = app(
            ThemePresetCatalog::class,
        )->all();

        $this->assertCount(
            100,
            $presets,
        );
    }

    public function test_all_preset_slugs_are_unique(): void
    {
        $presets = app(
            ThemePresetCatalog::class,
        )->all();

        $slugs = array_keys(
            $presets,
        );

        $this->assertSame(
            count($slugs),
            count(array_unique($slugs)),
        );
    }

    public function test_every_preset_has_complete_contract(): void
    {
        $requiredTokens = [
            'page_bg',
            'surface',
            'surface_alt',
            'surface_soft',

            'primary',
            'secondary',
            'accent',

            'text',
            'text_muted',
            'text_inverse',

            'border',
            'border_accent',

            'button_primary_bg',
            'button_primary_text',

            'button_secondary_bg',
            'button_secondary_text',

            'input_bg',
            'input_text',
            'input_border',

            'table_header_bg',
            'table_header_text',

            'result_bg',
            'result_text',
            'result_border',

            'success',
            'danger',
            'warning',
            'info',

            'header_bg',
            'footer_bg',

            'glow',
            'shadow',
        ];

        foreach (
            app(ThemePresetCatalog::class)->all() as $preset
        ) {
            foreach ([
                'slug',
                'name',
                'category',
                'variant',
                'palette',
                'preview',
                'background',
                'appearance',
                'tokens',
            ] as $key) {
                $this->assertArrayHasKey(
                    $key,
                    $preset,
                );
            }

            $this->assertCount(
                3,
                $preset['palette'],
            );

            foreach ($requiredTokens as $token) {
                $this->assertArrayHasKey(
                    $token,
                    $preset['tokens'],
                );
            }
        }
    }

    public function test_catalog_contains_requested_popular_color_families(): void
    {
        $catalog = app(
            ThemePresetCatalog::class,
        );

        foreach ([
            'gold-black-classic',
            'pure-gold-classic',
            'obsidian-black-classic',
            'red-black-classic',
            'red-red-black-classic',
            'red-gold-classic',
            'white-gold-classic',
            'navy-cyan-classic',
            'purple-blue-classic',
            'emerald-black-classic',
            'cyan-black-classic',
            'orange-black-classic',
            'silver-black-classic',
            'sunset-classic',
        ] as $slug) {
            $this->assertNotNull(
                $catalog->find($slug),
                "Missing preset {$slug}",
            );
        }
    }

    public function test_each_family_has_four_variants(): void
    {
        $families = config(
            'brand-theme-presets',
        );

        $catalog = app(
            ThemePresetCatalog::class,
        );

        foreach ($families as $family) {
            foreach ([
                'classic',
                'gradient',
                'glass',
                'contrast',
            ] as $variant) {
                $this->assertNotNull(
                    $catalog->find(
                        $family['slug'].'-'.$variant,
                    ),
                );
            }
        }
    }

    public function test_component_styles_are_supported_by_theme_engine(): void
    {
        $allowed = config(
            'brand-theme.allowed.component_styles',
        );

        foreach (
            app(ThemePresetCatalog::class)->all() as $preset
        ) {
            $this->assertContains(
                $preset['appearance']['component_style'],
                $allowed,
            );

            $this->assertGreaterThanOrEqual(
                0,
                $preset['appearance']['component_opacity'],
            );

            $this->assertLessThanOrEqual(
                1,
                $preset['appearance']['component_opacity'],
            );

            $this->assertGreaterThanOrEqual(
                0,
                $preset['appearance']['component_blur'],
            );
        }
    }

    public function test_white_gold_is_readable_light_theme(): void
    {
        $preset = app(
            ThemePresetCatalog::class,
        )->find(
            'white-gold-classic',
        );

        $this->assertNotNull(
            $preset,
        );

        $this->assertSame(
            '#FFFDF7',
            $preset['tokens']['page_bg'],
        );

        $this->assertSame(
            '#111827',
            $preset['tokens']['text'],
        );

        $this->assertSame(
            '#D4AF37',
            $preset['tokens']['primary'],
        );
    }

    public function test_gold_black_is_dark_luxury_theme(): void
    {
        $preset = app(
            ThemePresetCatalog::class,
        )->find(
            'gold-black-classic',
        );

        $this->assertNotNull(
            $preset,
        );

        $this->assertSame(
            'luxury',
            $preset['category'],
        );

        $this->assertSame(
            '#020202',
            $preset['tokens']['page_bg'],
        );

        $this->assertSame(
            '#FFFFFF',
            $preset['tokens']['text'],
        );

        $this->assertSame(
            '#D4AF37',
            $preset['tokens']['primary'],
        );
    }

    public function test_catalog_options_are_ready_for_admin_select(): void
    {
        $options = app(
            ThemePresetCatalog::class,
        )->options();

        $this->assertCount(
            100,
            $options,
        );

        $this->assertArrayHasKey(
            'gold-black-classic',
            $options,
        );

        $this->assertSame(
            'Gold Black Classic',
            $options['gold-black-classic'],
        );
    }
}

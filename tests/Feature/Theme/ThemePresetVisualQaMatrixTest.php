<?php

declare(strict_types=1);

namespace Tests\Feature\Theme;

use App\Domains\Theme\Support\ThemePresetCatalog;
use Tests\TestCase;

final class ThemePresetVisualQaMatrixTest extends TestCase
{
    private ThemePresetCatalog $catalog;

    protected function setUp(): void
    {
        parent::setUp();

        $this->catalog = app(
            ThemePresetCatalog::class,
        );
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function presets(): array
    {
        return $this->catalog->all();
    }

    public function test_catalog_still_contains_exactly_one_hundred_presets(): void
    {
        $this->assertCount(
            100,
            $this->presets(),
        );
    }

    public function test_every_family_has_exactly_four_variants(): void
    {
        $families = [];

        foreach ($this->presets() as $preset) {
            $parts = explode(
                '-',
                $preset['slug'],
            );

            $variant = array_pop($parts);

            $family = implode(
                '-',
                $parts,
            );

            $families[$family][] = $variant;
        }

        $this->assertCount(
            25,
            $families,
        );

        foreach ($families as $family => $variants) {
            sort($variants);

            $this->assertSame(
                [
                    'classic',
                    'contrast',
                    'glass',
                    'gradient',
                ],
                $variants,
                "Variant family {$family} tidak lengkap.",
            );
        }
    }

    public function test_each_variant_maps_to_expected_component_style(): void
    {
        $expected = [
            'classic' => [
                'style' => 'solid',
                'opacity' => 1.00,
                'blur' => 0,
            ],

            'gradient' => [
                'style' => 'semi-transparent',
                'opacity' => 0.92,
                'blur' => 0,
            ],

            'glass' => [
                'style' => 'glass',
                'opacity' => 0.72,
                'blur' => 14,
            ],

            'contrast' => [
                'style' => 'outline',
                'opacity' => 0.88,
                'blur' => 4,
            ],
        ];

        foreach ($this->presets() as $slug => $preset) {
            $variant = $preset['variant'];

            $this->assertArrayHasKey(
                $variant,
                $expected,
                $slug,
            );

            $this->assertSame(
                $expected[$variant]['style'],
                $preset['appearance']['component_style'],
                $slug,
            );

            $this->assertEquals(
                $expected[$variant]['opacity'],
                $preset['appearance']['component_opacity'],
                $slug,
            );

            $this->assertSame(
                $expected[$variant]['blur'],
                $preset['appearance']['component_blur'],
                $slug,
            );
        }
    }

    public function test_all_presets_have_complete_visual_token_contract(): void
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

        foreach ($this->presets() as $slug => $preset) {
            foreach ($requiredTokens as $token) {
                $this->assertArrayHasKey(
                    $token,
                    $preset['tokens'],
                    "{$slug}: missing {$token}",
                );

                $this->assertNotSame(
                    '',
                    trim(
                        (string) $preset['tokens'][$token],
                    ),
                    "{$slug}: empty {$token}",
                );
            }
        }
    }

    public function test_every_preset_has_valid_background_contract(): void
    {
        foreach ($this->presets() as $slug => $preset) {
            $background = $preset['background'];

            $this->assertSame(
                'theme',
                $background['mode'],
                $slug,
            );

            $this->assertNull(
                $background['image'],
                $slug,
            );

            $this->assertContains(
                $background['size'],
                [
                    'cover',
                    'contain',
                    'auto',
                ],
                $slug,
            );

            $this->assertContains(
                $background['position'],
                [
                    'center',
                    'top',
                    'bottom',
                    'left',
                    'right',
                ],
                $slug,
            );

            $this->assertIsArray(
                $background['theme_gradient'],
                $slug,
            );

            $this->assertNotEmpty(
                $background['theme_gradient'],
                $slug,
            );
        }
    }

    public function test_gradient_metadata_matches_variant(): void
    {
        foreach ($this->presets() as $slug => $preset) {
            $isGradient = in_array(
                $preset['variant'],
                [
                    'gradient',
                    'glass',
                ],
                true,
            );

            $this->assertSame(
                $isGradient,
                $preset['preview']['gradient'],
                $slug,
            );

            if ($isGradient) {
                $this->assertIsString(
                    $preset['preview']['gradient_css'],
                    $slug,
                );

                $this->assertStringContainsString(
                    'linear-gradient',
                    $preset['preview']['gradient_css'],
                    $slug,
                );

                $this->assertCount(
                    3,
                    $preset['background']['theme_gradient'],
                    $slug,
                );
            } else {
                $this->assertNull(
                    $preset['preview']['gradient_css'],
                    $slug,
                );

                $this->assertCount(
                    1,
                    $preset['background']['theme_gradient'],
                    $slug,
                );
            }
        }
    }

    public function test_text_has_minimum_readability_against_surface(): void
    {
        foreach ($this->presets() as $slug => $preset) {
            $ratio = $this->contrastRatio(
                $preset['tokens']['text'],
                $preset['tokens']['surface'],
            );

            $this->assertGreaterThanOrEqual(
                4.5,
                $ratio,
                "{$slug}: text/surface contrast={$ratio}",
            );
        }
    }

    public function test_muted_text_remains_readable_against_surface(): void
    {
        foreach ($this->presets() as $slug => $preset) {
            $ratio = $this->contrastRatio(
                $preset['tokens']['text_muted'],
                $preset['tokens']['surface'],
            );

            $this->assertGreaterThanOrEqual(
                3.0,
                $ratio,
                "{$slug}: muted/surface contrast={$ratio}",
            );
        }
    }

    public function test_primary_button_text_is_readable(): void
    {
        foreach ($this->presets() as $slug => $preset) {
            $ratio = $this->contrastRatio(
                $preset['tokens']['button_primary_text'],
                $preset['tokens']['button_primary_bg'],
            );

            $this->assertGreaterThanOrEqual(
                3.0,
                $ratio,
                "{$slug}: primary button contrast={$ratio}",
            );
        }
    }

    public function test_form_controls_have_readable_text(): void
    {
        foreach ($this->presets() as $slug => $preset) {
            $ratio = $this->contrastRatio(
                $preset['tokens']['input_text'],
                $preset['tokens']['input_bg'],
            );

            $this->assertGreaterThanOrEqual(
                4.5,
                $ratio,
                "{$slug}: input contrast={$ratio}",
            );
        }
    }

    public function test_result_panels_have_readable_numbers(): void
    {
        foreach ($this->presets() as $slug => $preset) {
            $ratio = $this->contrastRatio(
                $preset['tokens']['result_text'],
                $preset['tokens']['result_bg'],
            );

            $this->assertGreaterThanOrEqual(
                4.5,
                $ratio,
                "{$slug}: result contrast={$ratio}",
            );
        }
    }

    public function test_light_and_dark_presets_use_expected_text_direction(): void
    {
        foreach ($this->presets() as $slug => $preset) {
            $surfaceLuminance = $this->luminance(
                $preset['tokens']['surface'],
            );

            $textLuminance = $this->luminance(
                $preset['tokens']['text'],
            );

            if ($surfaceLuminance >= 0.5) {
                $this->assertLessThan(
                    $surfaceLuminance,
                    $textLuminance,
                    "{$slug}: light surface membutuhkan dark text.",
                );
            } else {
                $this->assertGreaterThan(
                    $surfaceLuminance,
                    $textLuminance,
                    "{$slug}: dark surface membutuhkan light text.",
                );
            }
        }
    }

    public function test_catalog_options_are_one_to_one_with_presets(): void
    {
        $this->assertCount(
            100,
            $this->catalog->options(),
        );

        $this->assertSame(
            array_keys($this->presets()),
            array_keys($this->catalog->options()),
        );
    }

    public function test_representative_smoke_matrix_exists(): void
    {
        $presets = $this->presets();

        $matrix = [
            'dark' => null,
            'light' => null,
            'gradient' => null,
            'glass' => null,
            'outline' => null,
        ];

        foreach ($presets as $slug => $preset) {
            $surface =
                $this->luminance(
                    $preset['tokens']['surface'],
                );

            if (
                $matrix['dark'] === null
                && $surface < 0.15
            ) {
                $matrix['dark'] = $slug;
            }

            if (
                $matrix['light'] === null
                && $surface > 0.80
            ) {
                $matrix['light'] = $slug;
            }

            if (
                $matrix['gradient'] === null
                && $preset['variant'] === 'gradient'
            ) {
                $matrix['gradient'] = $slug;
            }

            if (
                $matrix['glass'] === null
                && $preset['variant'] === 'glass'
            ) {
                $matrix['glass'] = $slug;
            }

            if (
                $matrix['outline'] === null
                && $preset['variant'] === 'contrast'
            ) {
                $matrix['outline'] = $slug;
            }
        }

        foreach ($matrix as $type => $slug) {
            $this->assertNotNull(
                $slug,
                "Representative {$type} preset tidak ditemukan.",
            );
        }
    }

    private function contrastRatio(
        string $foreground,
        string $background,
    ): float {
        $foregroundLuminance =
            $this->luminance($foreground);

        $backgroundLuminance =
            $this->luminance($background);

        $lighter = max(
            $foregroundLuminance,
            $backgroundLuminance,
        );

        $darker = min(
            $foregroundLuminance,
            $backgroundLuminance,
        );

        return (
            $lighter + 0.05
        ) / (
            $darker + 0.05
        );
    }

    private function luminance(
        string $hex,
    ): float {
        $hex = ltrim(
            $hex,
            '#',
        );

        $this->assertMatchesRegularExpression(
            '/^[0-9A-Fa-f]{6}$/',
            $hex,
            "Unsupported contrast color: {$hex}",
        );

        $channels = [
            hexdec(substr($hex, 0, 2)) / 255,
            hexdec(substr($hex, 2, 2)) / 255,
            hexdec(substr($hex, 4, 2)) / 255,
        ];

        $channels = array_map(
            static function (float $channel): float {
                if ($channel <= 0.04045) {
                    return $channel / 12.92;
                }

                return (
                    ($channel + 0.055) / 1.055
                ) ** 2.4;
            },
            $channels,
        );

        return
            (0.2126 * $channels[0])
            + (0.7152 * $channels[1])
            + (0.0722 * $channels[2]);
    }
}

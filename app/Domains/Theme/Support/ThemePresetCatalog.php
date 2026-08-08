<?php

declare(strict_types=1);

namespace App\Domains\Theme\Support;

use RuntimeException;

final class ThemePresetCatalog
{
    private const VARIANTS = [
        'classic' => [
            'name' => 'Classic',
            'component_style' => 'solid',
            'component_opacity' => 1.00,
            'component_blur' => 0,
            'gradient' => false,
        ],

        'gradient' => [
            'name' => 'Gradient',
            'component_style' => 'semi-transparent',
            'component_opacity' => 0.92,
            'component_blur' => 0,
            'gradient' => true,
        ],

        'glass' => [
            'name' => 'Glass',
            'component_style' => 'glass',
            'component_opacity' => 0.72,
            'component_blur' => 14,
            'gradient' => true,
        ],

        'contrast' => [
            'name' => 'Contrast',
            'component_style' => 'outline',
            'component_opacity' => 0.88,
            'component_blur' => 4,
            'gradient' => false,
        ],
    ];

    /**
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        $presets = [];

        foreach ($this->families() as $family) {
            foreach (self::VARIANTS as $variantSlug => $variant) {
                $preset = $this->build(
                    $family,
                    $variantSlug,
                    $variant,
                );

                $slug = $preset['slug'];

                if (isset($presets[$slug])) {
                    throw new RuntimeException(
                        "Duplicate theme preset slug: {$slug}",
                    );
                }

                $presets[$slug] = $preset;
            }
        }

        if (count($presets) !== 100) {
            throw new RuntimeException(
                'Theme preset catalog must contain exactly 100 presets.',
            );
        }

        return $presets;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(string $slug): ?array
    {
        return $this->all()[$slug] ?? null;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function category(string $category): array
    {
        return array_filter(
            $this->all(),
            static fn (array $preset): bool => $preset['category'] === $category,
        );
    }

    /**
     * @return array<string, string>
     */
    public function options(): array
    {
        return array_map(
            static fn (array $preset): string => $preset['name'],
            $this->all(),
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function families(): array
    {
        return config(
            'brand-theme-presets',
            [],
        );
    }

    /**
     * @param  array<string, mixed>  $family
     * @param  array<string, mixed>  $variant
     * @return array<string, mixed>
     */
    private function build(
        array $family,
        string $variantSlug,
        array $variant,
    ): array {
        [$background, $primary, $accent] =
            $family['palette'];

        $light = (bool) $family['light'];

        $text = $light
            ? '#111827'
            : '#FFFFFF';

        $textMuted = $light
            ? '#475569'
            : '#94A3B8';

        $inverse = $light
            ? '#FFFFFF'
            : '#020617';

        $surface = $light
            ? '#FFFFFF'
            : $this->darkSurface(
                $background,
            );

        $surfaceAlt = $light
            ? '#F8FAFC'
            : '#111827';

        $surfaceSoft = $light
            ? 'rgba(255, 255, 255, 0.82)'
            : 'rgba(15, 23, 42, 0.82)';

        $buttonText = $this->isLightColor(
            $primary,
        )
            ? '#020617'
            : '#FFFFFF';

        $slug =
            $family['slug'].'-'.$variantSlug;

        $name =
            $family['name'].' '.
            $variant['name'];

        return [
            'slug' => $slug,
            'name' => $name,

            'category' => $family['category'],

            'variant' => $variantSlug,

            'palette' => [
                $background,
                $primary,
                $accent,
            ],

            'preview' => [
                'background' => $background,

                'primary' => $primary,

                'accent' => $accent,

                'gradient' => $variant['gradient'],

                'gradient_css' => $variant['gradient']
                        ? "linear-gradient(135deg, {$background} 0%, {$primary} 55%, {$accent} 100%)"
                        : null,
            ],

            'background' => [
                'mode' => 'theme',
                'image' => null,
                'size' => 'cover',
                'position' => 'center',
                'repeat' => false,
                'fixed' => false,

                'theme_gradient' => $variant['gradient']
                        ? [
                            $background,
                            $primary,
                            $accent,
                        ]
                        : [
                            $background,
                        ],

                'overlay' => [
                    'enabled' => false,
                    'color' => '#000000',
                    'opacity' => 0.00,
                ],
            ],

            'appearance' => [
                'component_style' => $variant['component_style'],

                'component_opacity' => $variant['component_opacity'],

                'component_blur' => $variant['component_blur'],
            ],

            'tokens' => [
                'page_bg' => $background,

                'surface' => $surface,

                'surface_alt' => $surfaceAlt,

                'surface_soft' => $surfaceSoft,

                'primary' => $primary,

                'secondary' => $accent,

                'accent' => $accent,

                'text' => $text,

                'text_muted' => $textMuted,

                'text_inverse' => $inverse,

                'border' => $light
                        ? '#CBD5E1'
                        : '#334155',

                'border_accent' => $primary,

                'button_primary_bg' => $primary,

                'button_primary_text' => $buttonText,

                'button_secondary_bg' => $surfaceAlt,

                'button_secondary_text' => $text,

                'input_bg' => $surface,

                'input_text' => $text,

                'input_border' => $primary,

                'table_header_bg' => $surfaceAlt,

                'table_header_text' => $primary,

                'result_bg' => $surface,

                'result_text' => $text,

                'result_border' => $accent,

                'success' => '#22C55E',

                'danger' => '#E11D48',

                'warning' => '#F59E0B',

                'info' => '#22D3EE',

                'header_bg' => $background,

                'footer_bg' => $background,

                'glow' => $accent,

                'shadow' => $light
                        ? 'rgba(15, 23, 42, 0.16)'
                        : 'rgba(0, 0, 0, 0.32)',
            ],
        ];
    }

    private function darkSurface(
        string $background,
    ): string {
        if ($background === '#020202') {
            return '#111111';
        }

        return '#0F172A';
    }

    private function isLightColor(
        string $hex,
    ): bool {
        $hex = ltrim(
            $hex,
            '#',
        );

        if (strlen($hex) !== 6) {
            return false;
        }

        $red = hexdec(
            substr($hex, 0, 2),
        );

        $green = hexdec(
            substr($hex, 2, 2),
        );

        $blue = hexdec(
            substr($hex, 4, 2),
        );

        $luminance =
            (
                (0.299 * $red)
                + (0.587 * $green)
                + (0.114 * $blue)
            ) / 255;

        return $luminance >= 0.58;
    }
}

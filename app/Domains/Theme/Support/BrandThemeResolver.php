<?php

declare(strict_types=1);

namespace App\Domains\Theme\Support;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Theme\Models\BrandThemeSetting;
use Illuminate\Support\Facades\Schema;

final class BrandThemeResolver
{
    /**
     * @return array<string, mixed>
     */
    public function resolve(
        ?Brand $brand = null,
    ): array {
        $defaults = config(
            'brand-theme.defaults',
            [],
        );

        $brand ??= $this->resolveBrand();

        /*
        |--------------------------------------------------------------------------
        | Safe deployment fallback
        |--------------------------------------------------------------------------
        |
        | Source code may exist before the production migration is executed.
        | Frontend must continue working during that window.
        |
        */

        if (
            $brand === null
            || ! Schema::hasTable(
                'brand_theme_settings'
            )
        ) {
            return $defaults;
        }

        $setting = BrandThemeSetting::query()
            ->where(
                'brand_id',
                $brand->getKey(),
            )
            ->where(
                'is_active',
                true,
            )
            ->first();

        if ($setting === null) {
            return $defaults;
        }

        $resolved = $defaults;

        $resolved['slug'] =
            $setting->theme_slug;

        $resolved['background'] = [
            'mode' => $setting->background_mode,

            'image' => $setting->background_image,

            'size' => $setting->background_size,

            'position' => $setting->background_position,

            'repeat' => $setting->background_repeat,

            'fixed' => $setting->background_fixed,

            'overlay' => [
                'enabled' => $setting->overlay_enabled,

                'color' => $setting->overlay_color,

                'opacity' => $setting->overlay_opacity,
            ],
        ];

        $resolved['appearance'] = [
            'component_style' => $setting->component_style,

            'component_opacity' => $setting->component_opacity,

            'component_blur' => $setting->component_blur,
        ];

        $resolved['tokens'] = array_replace(
            $defaults['tokens'] ?? [],
            $setting->tokens ?? [],
        );

        return $resolved;
    }

    private function resolveBrand(): ?Brand
    {
        $context = app(
            BrandContext::class,
        );

        if (! $context->has()) {
            return null;
        }

        return $context->get();
    }
}

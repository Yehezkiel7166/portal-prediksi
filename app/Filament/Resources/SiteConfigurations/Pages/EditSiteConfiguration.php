<?php

declare(strict_types=1);

namespace App\Filament\Resources\SiteConfigurations\Pages;

use App\Domains\Brand\Support\BrandContext;
use App\Domains\SiteConfiguration\Actions\UpsertSiteConfiguration;
use App\Domains\SiteConfiguration\Models\SiteConfiguration;
use App\Domains\Theme\Models\BrandThemeSetting;
use App\Domains\Theme\Support\ThemePresetCatalog;
use App\Filament\Resources\SiteConfigurations\SiteConfigurationResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

final class EditSiteConfiguration extends EditRecord
{
    protected static string $resource =
        SiteConfigurationResource::class;

    protected function mutateFormDataBeforeFill(
        array $data,
    ): array {
        $brand = app(
            BrandContext::class,
        )->get();

        if ($brand === null) {
            return $data;
        }

        $theme = BrandThemeSetting::query()
            ->where(
                'brand_id',
                $brand->getKey(),
            )
            ->where(
                'is_active',
                true,
            )
            ->first();

        $themeSlug =
            $theme?->theme_slug
            ?? config(
                'brand-theme.defaults.slug',
                'midnight-gold',
            );

        if (
            app(
                ThemePresetCatalog::class,
            )->find(
                $themeSlug,
            ) === null
        ) {
            $themeSlug =
                'gold-black-classic';
        }

        $data['theme_preset'] =
            $themeSlug;

        $data['theme_background_mode'] =
            $theme?->background_mode
            ?? 'theme';

        $data['theme_background_image'] =
            $theme?->background_image;

        $data['theme_background_size'] =
            $theme?->background_size
            ?? 'cover';

        $data['theme_background_position'] =
            $theme?->background_position
            ?? 'center';

        $data['theme_background_repeat'] =
            $theme?->background_repeat
            ?? false;

        $data['theme_background_fixed'] =
            $theme?->background_fixed
            ?? false;

        $data['theme_overlay_enabled'] =
            $theme?->overlay_enabled
            ?? false;

        $data['theme_overlay_color'] =
            $theme?->overlay_color
            ?? '#000000';

        $data['theme_overlay_opacity'] =
            (int) round(
                (
                    $theme?->overlay_opacity
                    ?? 0
                ) * 100,
            );

        $data['theme_component_style'] =
            $theme?->component_style
            ?? 'solid';

        $data['theme_component_opacity'] =
            (int) round(
                (
                    $theme?->component_opacity
                    ?? 1
                ) * 100,
            );

        $data['theme_component_blur'] =
            $theme?->component_blur
            ?? 0;

        return $data;
    }

    protected function handleRecordUpdate(
        Model $record,
        array $data,
    ): Model {
        $brand = app(
            BrandContext::class,
        )->get();

        if (
            $brand === null
            || ! $record instanceof SiteConfiguration
            || (int) $record->brand_id
                !== (int) $brand->getKey()
        ) {
            throw ValidationException::withMessages([
                'brand' => 'Konfigurasi bukan milik brand aktif.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Extract virtual Design fields
        |--------------------------------------------------------------------------
        */

        $themeData = [
            'preset' => (string) (
                $data['theme_preset']
                ?? ''
            ),

            'background_mode' => (string) (
                $data['theme_background_mode']
                ?? 'theme'
            ),

            'background_image' => $data['theme_background_image']
                ?? null,

            'background_size' => (string) (
                $data['theme_background_size']
                ?? 'cover'
            ),

            'background_position' => (string) (
                $data['theme_background_position']
                ?? 'center'
            ),

            'background_repeat' => (bool) (
                $data['theme_background_repeat']
                ?? false
            ),

            'background_fixed' => (bool) (
                $data['theme_background_fixed']
                ?? false
            ),

            'overlay_enabled' => (bool) (
                $data['theme_overlay_enabled']
                ?? false
            ),

            'overlay_color' => (string) (
                $data['theme_overlay_color']
                ?? '#000000'
            ),

            'overlay_opacity' => (int) (
                $data['theme_overlay_opacity']
                ?? 0
            ),

            'component_style' => (string) (
                $data['theme_component_style']
                ?? 'solid'
            ),

            'component_opacity' => (int) (
                $data['theme_component_opacity']
                ?? 100
            ),

            'component_blur' => (int) (
                $data['theme_component_blur']
                ?? 0
            ),
        ];

        foreach ([
            'theme_preset',

            'theme_background_mode',
            'theme_background_image',
            'theme_background_size',
            'theme_background_position',
            'theme_background_repeat',
            'theme_background_fixed',

            'theme_overlay_enabled',
            'theme_overlay_color',
            'theme_overlay_opacity',

            'theme_component_style',
            'theme_component_opacity',
            'theme_component_blur',
        ] as $virtualField) {
            unset(
                $data[$virtualField],
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate preset
        |--------------------------------------------------------------------------
        */

        $preset = app(
            ThemePresetCatalog::class,
        )->find(
            $themeData['preset'],
        );

        if ($preset === null) {
            throw ValidationException::withMessages([
                'theme_preset' => 'Template design tidak valid.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate appearance
        |--------------------------------------------------------------------------
        */

        $allowedBackgroundModes =
            config(
                'brand-theme.allowed.background_modes',
                [],
            );

        if (
            ! in_array(
                $themeData['background_mode'],
                $allowedBackgroundModes,
                true,
            )
        ) {
            throw ValidationException::withMessages([
                'theme_background_mode' => 'Mode background tidak valid.',
            ]);
        }

        $allowedBackgroundSizes =
            config(
                'brand-theme.allowed.background_sizes',
                [],
            );

        if (
            ! in_array(
                $themeData['background_size'],
                $allowedBackgroundSizes,
                true,
            )
        ) {
            throw ValidationException::withMessages([
                'theme_background_size' => 'Ukuran background tidak valid.',
            ]);
        }

        $allowedBackgroundPositions =
            config(
                'brand-theme.allowed.background_positions',
                [],
            );

        if (
            ! in_array(
                $themeData['background_position'],
                $allowedBackgroundPositions,
                true,
            )
        ) {
            throw ValidationException::withMessages([
                'theme_background_position' => 'Posisi background tidak valid.',
            ]);
        }

        $allowedComponentStyles =
            config(
                'brand-theme.allowed.component_styles',
                [],
            );

        if (
            ! in_array(
                $themeData['component_style'],
                $allowedComponentStyles,
                true,
            )
        ) {
            throw ValidationException::withMessages([
                'theme_component_style' => 'Tampilan komponen tidak valid.',
            ]);
        }

        if (
            $themeData['background_mode'] === 'image'
            && blank(
                $themeData['background_image'],
            )
        ) {
            throw ValidationException::withMessages([
                'theme_background_image' => 'Upload background terlebih dahulu.',
            ]);
        }

        if (
            $themeData['overlay_opacity'] < 0
            || $themeData['overlay_opacity'] > 100
        ) {
            throw ValidationException::withMessages([
                'theme_overlay_opacity' => 'Opacity overlay harus antara 0 sampai 100.',
            ]);
        }

        if (
            $themeData['component_opacity'] < 10
            || $themeData['component_opacity'] > 100
        ) {
            throw ValidationException::withMessages([
                'theme_component_opacity' => 'Opacity komponen harus antara 10 sampai 100.',
            ]);
        }

        if (
            $themeData['component_blur'] < 0
            || $themeData['component_blur'] > 30
        ) {
            throw ValidationException::withMessages([
                'theme_component_blur' => 'Blur harus antara 0 sampai 30px.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Save Site Configuration first
        |--------------------------------------------------------------------------
        */

        $configuration = app(
            UpsertSiteConfiguration::class,
        )->execute(
            $brand,
            $data,
        );

        /*
        |--------------------------------------------------------------------------
        | Resolve final background
        |--------------------------------------------------------------------------
        |
        | Theme mode returns background control to the selected preset.
        | Image mode preserves the uploaded custom image.
        |
        */

        $backgroundMode =
            $themeData['background_mode'];

        $backgroundImage =
            $backgroundMode === 'image'
                ? $themeData['background_image']
                : null;

        $backgroundSize =
            $backgroundMode === 'image'
                ? $themeData['background_size']
                : $preset['background']['size'];

        $backgroundPosition =
            $backgroundMode === 'image'
                ? $themeData['background_position']
                : $preset['background']['position'];

        $backgroundRepeat =
            $backgroundMode === 'image'
                ? $themeData['background_repeat']
                : $preset['background']['repeat'];

        $backgroundFixed =
            $backgroundMode === 'image'
                ? $themeData['background_fixed']
                : $preset['background']['fixed'];

        $overlayEnabled =
            $backgroundMode === 'image'
                ? $themeData['overlay_enabled']
                : $preset['background']['overlay']['enabled'];

        $overlayColor =
            $backgroundMode === 'image'
                ? $themeData['overlay_color']
                : $preset['background']['overlay']['color'];

        $overlayOpacity =
            $backgroundMode === 'image'
                ? $themeData['overlay_opacity'] / 100
                : $preset['background']['overlay']['opacity'];

        /*
        |--------------------------------------------------------------------------
        | Save Theme
        |--------------------------------------------------------------------------
        */

        BrandThemeSetting::query()
            ->updateOrCreate(
                [
                    'brand_id' => $brand->getKey(),
                ],
                [
                    'theme_slug' => $preset['slug'],

                    'background_mode' => $backgroundMode,

                    'background_image' => $backgroundImage,

                    'background_size' => $backgroundSize,

                    'background_position' => $backgroundPosition,

                    'background_repeat' => $backgroundRepeat,

                    'background_fixed' => $backgroundFixed,

                    'overlay_enabled' => $overlayEnabled,

                    'overlay_color' => $overlayColor,

                    'overlay_opacity' => $overlayOpacity,

                    'component_style' => $themeData[
                            'component_style'
                        ],

                    'component_opacity' => $themeData[
                            'component_opacity'
                        ] / 100,

                    'component_blur' => $themeData[
                            'component_blur'
                        ],

                    /*
                     * Preset owns semantic colors.
                     * Admin appearance controls do not mutate
                     * individual design tokens.
                     */
                    'tokens' => $preset['tokens'],

                    'is_active' => true,
                ],
            );

        return $configuration;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl(
            'index',
        );
    }
}

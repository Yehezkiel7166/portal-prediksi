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

        $data['theme_preset'] =
            $theme?->theme_slug
            ?? config(
                'brand-theme.defaults.slug',
                'midnight-gold',
            );

        if (
            app(
                ThemePresetCatalog::class,
            )->find(
                $data['theme_preset'],
            ) === null
        ) {
            $data['theme_preset'] =
                'gold-black-classic';
        }

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

        $themeSlug = (string) (
            $data['theme_preset']
            ?? ''
        );

        unset(
            $data['theme_preset'],
        );

        $preset = app(
            ThemePresetCatalog::class,
        )->find(
            $themeSlug,
        );

        if ($preset === null) {
            throw ValidationException::withMessages([
                'theme_preset' => 'Template design tidak valid.',
            ]);
        }

        $configuration = app(
            UpsertSiteConfiguration::class,
        )->execute(
            $brand,
            $data,
        );

        BrandThemeSetting::query()
            ->updateOrCreate(
                [
                    'brand_id' => $brand->getKey(),
                ],
                [
                    'theme_slug' => $preset['slug'],

                    'background_mode' => $preset['background']['mode'],

                    'background_image' => null,

                    'background_size' => $preset['background']['size'],

                    'background_position' => $preset['background']['position'],

                    'background_repeat' => $preset['background']['repeat'],

                    'background_fixed' => $preset['background']['fixed'],

                    'overlay_enabled' => $preset['background']['overlay']['enabled'],

                    'overlay_color' => $preset['background']['overlay']['color'],

                    'overlay_opacity' => $preset['background']['overlay']['opacity'],

                    'component_style' => $preset['appearance']['component_style'],

                    'component_opacity' => $preset['appearance']['component_opacity'],

                    'component_blur' => $preset['appearance']['component_blur'],

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

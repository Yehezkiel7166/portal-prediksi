<?php

declare(strict_types=1);

namespace App\Filament\Resources\SiteConfigurations\Pages;

use App\Domains\Brand\Support\BrandContext;
use App\Domains\SiteConfiguration\Actions\UpsertSiteConfiguration;
use App\Domains\SiteConfiguration\Models\SiteConfiguration;
use App\Filament\Resources\SiteConfigurations\SiteConfigurationResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

final class EditSiteConfiguration extends EditRecord
{
    protected static string $resource = SiteConfigurationResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $brand = app(BrandContext::class)->get();
        if ($brand === null || ! $record instanceof SiteConfiguration || (int) $record->brand_id !== (int) $brand->getKey()) {
            throw ValidationException::withMessages(['brand' => 'Konfigurasi bukan milik brand aktif.']);
        }

        return app(UpsertSiteConfiguration::class)->execute($brand, $data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

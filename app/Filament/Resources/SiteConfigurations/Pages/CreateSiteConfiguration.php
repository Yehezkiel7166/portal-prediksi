<?php

declare(strict_types=1);

namespace App\Filament\Resources\SiteConfigurations\Pages;

use App\Domains\Brand\Support\BrandContext;
use App\Domains\SiteConfiguration\Actions\UpsertSiteConfiguration;
use App\Filament\Resources\SiteConfigurations\SiteConfigurationResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

final class CreateSiteConfiguration extends CreateRecord
{
    protected static string $resource = SiteConfigurationResource::class;
    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        $brand = app(BrandContext::class)->get();
        if ($brand === null) {
            throw ValidationException::withMessages(['brand' => 'Brand aktif tidak ditemukan.']);
        }

        return app(UpsertSiteConfiguration::class)->execute($brand, $data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

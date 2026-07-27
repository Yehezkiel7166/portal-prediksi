<?php

declare(strict_types=1);

namespace App\Filament\Resources\SiteConfigurations\Pages;

use App\Filament\Resources\SiteConfigurations\SiteConfigurationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListSiteConfigurations extends ListRecords
{
    protected static string $resource = SiteConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('Buat konfigurasi')];
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Resources\BrandDomainResource\Pages;

use App\Filament\Resources\BrandDomainResource;
use App\Filament\Widgets\DomainMonitoringStats;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListBrandDomains extends ListRecords
{
    protected static string $resource = BrandDomainResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            DomainMonitoringStats::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

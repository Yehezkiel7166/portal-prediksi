<?php

namespace App\Filament\Resources\ShioPeriods\Pages;

use App\Filament\Resources\ShioPeriods\ShioPeriodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListShioPeriods extends ListRecords
{
    protected static string $resource = ShioPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

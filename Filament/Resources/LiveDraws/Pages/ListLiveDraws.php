<?php

namespace App\Filament\Resources\LiveDraws\Pages;

use App\Filament\Resources\LiveDraws\LiveDrawResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLiveDraws extends ListRecords
{
    protected static string $resource = LiveDrawResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

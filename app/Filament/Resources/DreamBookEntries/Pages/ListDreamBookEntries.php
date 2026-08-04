<?php

namespace App\Filament\Resources\DreamBookEntries\Pages;

use App\Filament\Resources\DreamBookEntries\DreamBookEntryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDreamBookEntries extends ListRecords
{
    protected static string $resource = DreamBookEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}

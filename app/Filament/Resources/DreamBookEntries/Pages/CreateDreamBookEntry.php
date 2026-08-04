<?php

namespace App\Filament\Resources\DreamBookEntries\Pages;

use App\Filament\Resources\DreamBookEntries\DreamBookEntryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDreamBookEntry extends CreateRecord
{
    protected static string $resource = DreamBookEntryResource::class;
}

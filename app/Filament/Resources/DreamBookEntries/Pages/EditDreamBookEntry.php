<?php

namespace App\Filament\Resources\DreamBookEntries\Pages;

use App\Filament\Resources\DreamBookEntries\DreamBookEntryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDreamBookEntry extends EditRecord
{
    protected static string $resource =
        DreamBookEntryResource::class;

    protected function mutateFormDataBeforeSave(
        array $data,
    ): array {
        $description = trim(
            (string) ($data['description'] ?? '')
        );

        $data['title'] = $description;
        $data['interpretation'] = $description;

        $data['keywords'] =
            $this->record->keywords ?? [];

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

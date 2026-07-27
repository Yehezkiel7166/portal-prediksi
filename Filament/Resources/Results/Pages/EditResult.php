<?php

namespace App\Filament\Resources\Results\Pages;

use App\Domains\Result\Actions\UpsertResultAction;
use App\Domains\Result\Models\Result;
use App\Filament\Resources\Results\ResultResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditResult extends EditRecord
{
    protected static string $resource = ResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(
        Model $record,
        array $data,
    ): Model {
        /** @var Result $record */

        return app(UpsertResultAction::class)
            ->execute($record, $data);
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Result berhasil diperbarui';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

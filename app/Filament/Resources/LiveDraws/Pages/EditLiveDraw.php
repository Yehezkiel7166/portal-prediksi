<?php

namespace App\Filament\Resources\LiveDraws\Pages;

use App\Domains\LiveDraw\Actions\UpsertLiveDrawAction;
use App\Domains\LiveDraw\Models\LiveDraw;
use App\Filament\Resources\LiveDraws\LiveDrawResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditLiveDraw extends EditRecord
{
    protected static string $resource = LiveDrawResource::class;

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
        /** @var LiveDraw $record */
        return app(UpsertLiveDrawAction::class)
            ->execute($data, $record);
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Live Draw berhasil diperbarui';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

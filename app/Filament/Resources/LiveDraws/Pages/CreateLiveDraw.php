<?php

namespace App\Filament\Resources\LiveDraws\Pages;

use App\Domains\LiveDraw\Actions\UpsertLiveDrawAction;
use App\Filament\Resources\LiveDraws\LiveDrawResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateLiveDraw extends CreateRecord
{
    protected static string $resource = LiveDrawResource::class;

    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        return app(UpsertLiveDrawAction::class)
            ->execute($data);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Live Draw berhasil dibuat';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

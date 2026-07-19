<?php

namespace App\Filament\Resources\Results\Pages;

use App\Domains\Result\Actions\UpsertResultAction;
use App\Filament\Resources\Results\ResultResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateResult extends CreateRecord
{
    protected static string $resource = ResultResource::class;

    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        return app(UpsertResultAction::class)
            ->execute(null, $data);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Result berhasil dibuat';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

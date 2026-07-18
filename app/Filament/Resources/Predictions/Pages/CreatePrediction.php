<?php

namespace App\Filament\Resources\Predictions\Pages;

use App\Domains\Prediction\Actions\UpsertPredictionAction;
use App\Filament\Resources\Predictions\PredictionResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePrediction extends CreateRecord
{
    protected static string $resource = PredictionResource::class;

    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        return app(UpsertPredictionAction::class)
            ->execute(null, $data);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Prediksi berhasil dibuat';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

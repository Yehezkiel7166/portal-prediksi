<?php

namespace App\Filament\Resources\Predictions\Pages;

use App\Domains\Prediction\Actions\UpsertPredictionAction;
use App\Domains\Prediction\Models\Prediction;
use App\Filament\Resources\Predictions\PredictionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPrediction extends EditRecord
{
    protected static string $resource = PredictionResource::class;

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
        /** @var Prediction $record */
        return app(UpsertPredictionAction::class)
            ->execute($record, $data);
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Prediksi berhasil diperbarui';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

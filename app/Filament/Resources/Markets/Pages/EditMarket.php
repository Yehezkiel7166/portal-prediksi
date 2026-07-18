<?php

namespace App\Filament\Resources\Markets\Pages;

use App\Domains\Market\Actions\UpsertMarketAction;
use App\Domains\Market\Models\Market;
use App\Filament\Resources\Markets\MarketResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditMarket extends EditRecord
{
    protected static string $resource = MarketResource::class;

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
        /** @var Market $record */
        return app(UpsertMarketAction::class)
            ->execute($record, $data);
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Pasaran berhasil diperbarui';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

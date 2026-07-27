<?php

namespace App\Filament\Resources\Markets\Pages;

use App\Domains\Market\Actions\UpsertMarketAction;
use App\Filament\Resources\Markets\MarketResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateMarket extends CreateRecord
{
    protected static string $resource = MarketResource::class;

    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        return app(UpsertMarketAction::class)
            ->execute(null, $data);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Pasaran berhasil dibuat';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

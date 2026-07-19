<?php

namespace App\Filament\Resources\Promotions\Pages;

use App\Domains\Promotion\Actions\UpsertPromotionAction;
use App\Filament\Resources\Promotions\PromotionResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePromotion extends CreateRecord
{
    protected static string $resource = PromotionResource::class;

    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        return app(UpsertPromotionAction::class)
            ->execute(null, $data);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Promotion berhasil dibuat';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

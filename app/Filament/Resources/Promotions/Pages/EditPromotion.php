<?php

namespace App\Filament\Resources\Promotions\Pages;

use App\Domains\Promotion\Actions\UpsertPromotionAction;
use App\Domains\Promotion\Models\Promotion;
use App\Filament\Resources\Promotions\PromotionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPromotion extends EditRecord
{
    protected static string $resource = PromotionResource::class;

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
        /** @var Promotion $record */

        return app(UpsertPromotionAction::class)
            ->execute($record, $data);
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Promotion berhasil diperbarui';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

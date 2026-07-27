<?php

declare(strict_types=1);

namespace App\Filament\Resources\BrandDomainResource\Pages;

use App\Domains\Domain\Actions\SetPrimaryBrandDomain;
use App\Domains\Domain\Actions\UpdateBrandDomainStatus;
use App\Domains\Domain\Models\BrandDomain;
use App\Filament\Resources\BrandDomainResource;
use App\Filament\Resources\BrandDomainResource\Actions\NormalizeBrandDomainFormData;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

final class EditBrandDomain extends EditRecord
{
    protected static string $resource = BrandDomainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->disabled(
                    fn (): bool => (bool) $this->record->is_primary,
                )
                ->tooltip(
                    fn (): ?string => $this->record->is_primary
                        ? 'Domain utama tidak dapat dihapus.'
                        : null,
                ),
        ];
    }

    protected function mutateFormDataBeforeSave(
        array $data,
    ): array {
        return app(NormalizeBrandDomainFormData::class)
            ->execute($data);
    }

    protected function handleRecordUpdate(
        Model $record,
        array $data,
    ): Model {
        /** @var BrandDomain $record */
        $isPrimary = (bool) ($data['is_primary'] ?? false);
        $isActive = (bool) ($data['is_active'] ?? false);

        unset($data['brand_id']);

        $requestedPrimary = $isPrimary;
        $data['is_primary'] = false;

        $record->fill($data)->save();
        $record->refresh();

        if (! $isActive) {
            return app(UpdateBrandDomainStatus::class)
                ->execute($record, false);
        }

        if ($requestedPrimary) {
            return app(SetPrimaryBrandDomain::class)
                ->execute($record);
        }

        return $record;
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Domain berhasil diperbarui';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

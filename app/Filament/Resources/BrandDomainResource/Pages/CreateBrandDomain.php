<?php

declare(strict_types=1);

namespace App\Filament\Resources\BrandDomainResource\Pages;

use App\Domains\Domain\Actions\SetPrimaryBrandDomain;
use App\Filament\Resources\BrandDomainResource;
use App\Filament\Resources\BrandDomainResource\Actions\NormalizeBrandDomainFormData;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

final class CreateBrandDomain extends CreateRecord
{
    protected static string $resource = BrandDomainResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(
        array $data,
    ): array {
        return app(NormalizeBrandDomainFormData::class)
            ->execute($data);
    }

    protected function handleRecordCreation(array $data): Model
    {
        $isPrimary = (bool) ($data['is_primary'] ?? false);

        $data['is_primary'] = false;

        $record = self::getModel()::query()->create($data);

        if ($isPrimary) {
            return app(SetPrimaryBrandDomain::class)
                ->execute($record);
        }

        return $record;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Domain berhasil dibuat';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

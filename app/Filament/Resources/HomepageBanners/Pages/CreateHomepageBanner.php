<?php

declare(strict_types=1);

namespace App\Filament\Resources\HomepageBanners\Pages;

use App\Domains\HomepageBanner\Actions\UpsertHomepageBannerAction;
use App\Filament\Resources\HomepageBanners\HomepageBannerResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

final class CreateHomepageBanner extends CreateRecord
{
    protected static string $resource =
        HomepageBannerResource::class;

    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(
        array $data
    ): Model {
        return app(
            UpsertHomepageBannerAction::class
        )->execute($data);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Banner homepage berhasil dibuat';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

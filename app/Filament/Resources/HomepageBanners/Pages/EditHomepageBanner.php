<?php

declare(strict_types=1);

namespace App\Filament\Resources\HomepageBanners\Pages;

use App\Domains\HomepageBanner\Actions\UpsertHomepageBannerAction;
use App\Domains\HomepageBanner\Models\HomepageBanner;
use App\Filament\Resources\HomepageBanners\HomepageBannerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

final class EditHomepageBanner extends EditRecord
{
    protected static string $resource =
        HomepageBannerResource::class;

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
        /** @var HomepageBanner $record */

        return app(
            UpsertHomepageBannerAction::class
        )->execute($data, $record);
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Banner homepage berhasil diperbarui';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

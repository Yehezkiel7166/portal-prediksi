<?php
namespace App\Filament\Resources\Guides\Pages;
use App\Domains\Guide\Actions\UpsertGuideAction;
use App\Filament\Resources\Guides\GuideResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
class CreateGuide extends CreateRecord
{
    protected static string $resource = GuideResource::class;
    protected static bool $canCreateAnother = false;
    protected function handleRecordCreation(array $data): Model { return app(UpsertGuideAction::class)->execute($data); }
    protected function getCreatedNotificationTitle(): ?string { return 'Panduan berhasil dibuat'; }
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}

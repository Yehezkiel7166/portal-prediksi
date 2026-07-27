<?php
namespace App\Filament\Resources\Guides\Pages;
use App\Domains\Guide\Actions\UpsertGuideAction;
use App\Domains\Guide\Models\Guide;
use App\Filament\Resources\Guides\GuideResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
class EditGuide extends EditRecord
{
    protected static string $resource = GuideResource::class;
    protected function getHeaderActions(): array { return [DeleteAction::make()]; }
    protected function handleRecordUpdate(Model $record, array $data): Model { /** @var Guide $record */ return app(UpsertGuideAction::class)->execute($data, $record); }
    protected function getSavedNotificationTitle(): ?string { return 'Panduan berhasil diperbarui'; }
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}

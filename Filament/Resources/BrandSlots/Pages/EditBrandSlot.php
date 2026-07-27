<?php
namespace App\Filament\Resources\BrandSlots\Pages;
use App\Domains\Rtp\Actions\UpsertBrandSlotAction;
use App\Domains\Rtp\Models\BrandSlot;
use App\Filament\Resources\BrandSlots\BrandSlotResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
class EditBrandSlot extends EditRecord { protected static string $resource=BrandSlotResource::class; protected function getHeaderActions(): array { return [DeleteAction::make()]; } protected function handleRecordUpdate(Model $record,array $data): Model { /** @var BrandSlot $record */ return app(UpsertBrandSlotAction::class)->execute($data,$record); } protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); } }

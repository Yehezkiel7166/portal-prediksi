<?php
namespace App\Filament\Resources\BrandSlots\Pages;
use App\Domains\Rtp\Actions\UpsertBrandSlotAction;
use App\Filament\Resources\BrandSlots\BrandSlotResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
class CreateBrandSlot extends CreateRecord { protected static string $resource=BrandSlotResource::class; protected static bool $canCreateAnother=false; protected function handleRecordCreation(array $data): Model { return app(UpsertBrandSlotAction::class)->execute($data); } protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); } }

<?php
namespace App\Filament\Resources\BrandSlots\Pages;
use App\Filament\Resources\BrandSlots\BrandSlotResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListBrandSlots extends ListRecords { protected static string $resource=BrandSlotResource::class; protected function getHeaderActions(): array { return [CreateAction::make()]; } }

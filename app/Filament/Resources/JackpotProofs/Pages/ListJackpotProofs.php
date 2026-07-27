<?php
namespace App\Filament\Resources\JackpotProofs\Pages;
use App\Filament\Resources\JackpotProofs\JackpotProofResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListJackpotProofs extends ListRecords { protected static string $resource = JackpotProofResource::class; protected function getHeaderActions(): array { return [CreateAction::make()]; } }

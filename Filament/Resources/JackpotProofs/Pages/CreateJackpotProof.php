<?php
namespace App\Filament\Resources\JackpotProofs\Pages;
use App\Domains\JackpotProof\Actions\UpsertJackpotProofAction;
use App\Filament\Resources\JackpotProofs\JackpotProofResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
class CreateJackpotProof extends CreateRecord { protected static string $resource = JackpotProofResource::class; protected static bool $canCreateAnother = false; protected function handleRecordCreation(array $data): Model { return app(UpsertJackpotProofAction::class)->execute($data); } protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); } }

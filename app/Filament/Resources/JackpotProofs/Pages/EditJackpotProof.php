<?php
namespace App\Filament\Resources\JackpotProofs\Pages;
use App\Domains\JackpotProof\Actions\UpsertJackpotProofAction;
use App\Domains\JackpotProof\Models\JackpotProof;
use App\Filament\Resources\JackpotProofs\JackpotProofResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
class EditJackpotProof extends EditRecord { protected static string $resource = JackpotProofResource::class; protected function getHeaderActions(): array { return [DeleteAction::make()]; } protected function handleRecordUpdate(Model $record, array $data): Model { /** @var JackpotProof $record */ return app(UpsertJackpotProofAction::class)->execute($data, $record); } protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); } }

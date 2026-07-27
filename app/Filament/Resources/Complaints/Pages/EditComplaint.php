<?php

namespace App\Filament\Resources\Complaints\Pages;

use App\Domains\Complaint\Actions\UpdateComplaintAction;
use App\Domains\Complaint\Models\Complaint;
use App\Filament\Resources\Complaints\ComplaintResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditComplaint extends EditRecord
{
    protected static string $resource = ComplaintResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /** @var Complaint $record */
        return app(UpdateComplaintAction::class)->execute($data, $record);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

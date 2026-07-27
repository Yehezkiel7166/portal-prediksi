<?php

namespace App\Domains\Complaint\Actions;

use App\Domains\Complaint\Models\Complaint;
use Illuminate\Support\Arr;

class UpdateComplaintAction
{
    public function execute(array $data, Complaint $complaint): Complaint
    {
        $status = (string) ($data['status'] ?? $complaint->status);
        $payload = Arr::only($data, ['status', 'admin_notes']);

        if ($status === Complaint::STATUS_REVIEWED && $complaint->reviewed_at === null) {
            $payload['reviewed_at'] = now();
        }

        if ($status === Complaint::STATUS_RESOLVED && $complaint->resolved_at === null) {
            $payload['resolved_at'] = now();
        }

        if (in_array($status, [Complaint::STATUS_REVIEWED, Complaint::STATUS_RESOLVED, Complaint::STATUS_REJECTED], true)) {
            $payload['handled_by'] = auth()->id();
        }

        $complaint->fill($payload)->save();

        return $complaint->refresh();
    }
}

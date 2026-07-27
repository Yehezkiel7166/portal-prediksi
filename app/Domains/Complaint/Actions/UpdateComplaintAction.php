<?php

namespace App\Domains\Complaint\Actions;

use App\Domains\Complaint\Models\Complaint;
use App\Domains\Complaint\Models\ComplaintStatusHistory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UpdateComplaintAction
{
    /** @var array<string, array<int, string>> */
    private const ALLOWED_TRANSITIONS = [
        Complaint::STATUS_OPEN => [Complaint::STATUS_IN_PROGRESS, Complaint::STATUS_REJECTED],
        Complaint::STATUS_IN_PROGRESS => [Complaint::STATUS_RESOLVED, Complaint::STATUS_REJECTED],
        Complaint::STATUS_RESOLVED => [],
        Complaint::STATUS_REJECTED => [],
    ];

    public function execute(array $data, Complaint $complaint): Complaint
    {
        return DB::transaction(function () use ($data, $complaint): Complaint {
            $fromStatus = $this->normalizeStatus($complaint->status);
            $toStatus = $this->normalizeStatus((string) ($data['status'] ?? $fromStatus));
            $this->validateTransition($fromStatus, $toStatus);

            $payload = Arr::only($data, ['admin_notes', 'admin_response']);
            $payload['status'] = $toStatus;

            if ($toStatus === Complaint::STATUS_IN_PROGRESS && $complaint->reviewed_at === null) {
                $payload['reviewed_at'] = now();
            }

            if ($toStatus === Complaint::STATUS_RESOLVED && $complaint->resolved_at === null) {
                $payload['resolved_at'] = now();
            }

            if (filled($payload['admin_response'] ?? null) && $complaint->responded_at === null) {
                $payload['responded_at'] = now();
            }

            if ($fromStatus !== $toStatus || filled($payload['admin_response'] ?? null) || filled($payload['admin_notes'] ?? null)) {
                $payload['handled_by'] = auth()->id();
            }

            $complaint->fill($payload)->save();

            if ($fromStatus !== $toStatus || filled($payload['admin_response'] ?? null) || filled($payload['admin_notes'] ?? null)) {
                ComplaintStatusHistory::query()->create([
                    'complaint_id' => $complaint->getKey(),
                    'brand_id' => $complaint->brand_id,
                    'from_status' => $fromStatus,
                    'to_status' => $toStatus,
                    'actor_id' => auth()->id(),
                    'admin_response' => $payload['admin_response'] ?? null,
                    'admin_notes' => $payload['admin_notes'] ?? null,
                ]);
            }

            return $complaint->refresh();
        });
    }

    private function normalizeStatus(string $status): string
    {
        return $status === Complaint::STATUS_REVIEWED
            ? Complaint::STATUS_IN_PROGRESS
            : $status;
    }

    private function validateTransition(string $fromStatus, string $toStatus): void
    {
        if ($fromStatus === $toStatus) {
            return;
        }

        if (! in_array($toStatus, self::ALLOWED_TRANSITIONS[$fromStatus] ?? [], true)) {
            throw ValidationException::withMessages([
                'status' => "Perubahan status {$fromStatus} ke {$toStatus} tidak diizinkan.",
            ]);
        }
    }
}

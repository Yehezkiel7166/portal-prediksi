<?php

namespace App\Domains\Complaint\Actions;

use App\Domains\Brand\Support\BrandContext;
use App\Domains\Complaint\Models\Complaint;
use App\Domains\Complaint\Models\ComplaintStatusHistory;
use App\Domains\Complaint\Notifications\NewComplaintSubmitted;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreatePublicComplaintAction
{
    public function __construct(private readonly BrandContext $brandContext) {}

    public function execute(array $data): Complaint
    {
        $brand = $this->brandContext->get();

        if ($brand === null) {
            throw ValidationException::withMessages([
                'brand' => 'Layanan keluhan belum tersedia untuk domain ini.',
            ]);
        }

        $complaint = new Complaint();
        $complaint->brand_id = $brand->getKey();
        $complaint->fill([
            'reference_code' => $this->referenceCode(),
            'name' => trim((string) $data['name']),
            'contact' => trim((string) $data['contact']),
            'subject' => trim((string) $data['subject']),
            'message' => trim((string) $data['message']),
            'status' => Complaint::STATUS_OPEN,
            'source_ip' => $data['source_ip'] ?? null,
            'user_agent' => Str::limit((string) ($data['user_agent'] ?? ''), 1000, ''),
        ]);
        $complaint->save();

        ComplaintStatusHistory::query()->create([
            'complaint_id' => $complaint->getKey(),
            'brand_id' => $complaint->brand_id,
            'from_status' => null,
            'to_status' => Complaint::STATUS_OPEN,
            'actor_id' => null,
        ]);

        $administrators = User::query()->where('is_admin', true)->get();
        Notification::send($administrators, new NewComplaintSubmitted($complaint->load('brand')));

        return $complaint->refresh();
    }

    private function referenceCode(): string
    {
        do {
            $code = 'KLG-'.now()->format('ymd').'-'.Str::upper(Str::random(8));
        } while (Complaint::withoutGlobalScopes()->where('reference_code', $code)->exists());

        return $code;
    }
}

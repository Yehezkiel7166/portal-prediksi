<?php

namespace App\Domains\Rtp\Actions;

use App\Domains\Rtp\Models\BrandSlot;
use App\Domains\Rtp\Models\RtpSnapshot;
use Illuminate\Support\Facades\Validator;

final class CreateRtpSnapshotAction
{
    public function execute(BrandSlot $slot, array $data): RtpSnapshot
    {
        $validated = Validator::make($data, [
            'rtp_value' => ['required', 'numeric', 'between:0,100'],
            'captured_at' => ['required', 'date'],
            'source_label' => ['nullable', 'string', 'max:120'],
        ])->validate();

        $snapshot = new RtpSnapshot($validated);
        $snapshot->brand_id = $slot->brand_id;
        $snapshot->brand_slot_id = $slot->getKey();
        $snapshot->created_at = now();
        $snapshot->save();
        return $snapshot->refresh();
    }
}

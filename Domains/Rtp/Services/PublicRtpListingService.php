<?php

namespace App\Domains\Rtp\Services;

use App\Domains\Rtp\Models\BrandSlot;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class PublicRtpListingService
{
    public function paginate(int $perPage = 24): LengthAwarePaginator
    {
        return BrandSlot::query()
            ->select([
                'id',
                'brand_id',
                'provider_name',
                'game_name',
                'slug',
                'image_url',
                'sort_order',
            ])
            ->publiclyVisible()
            ->with([
                'latestSnapshot' => static fn ($query) => $query->select([
                    'rtp_snapshots.id',
                    'rtp_snapshots.brand_slot_id',
                    'rtp_snapshots.brand_id',
                    'rtp_snapshots.rtp_value',
                    'rtp_snapshots.captured_at',
                    'rtp_snapshots.source_label',
                ]),
            ])
            ->ordered()
            ->paginate($perPage);
    }
}

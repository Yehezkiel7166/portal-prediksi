<?php

namespace App\Domains\Result\Support;

use App\Domains\Market\Models\Market;
use App\Domains\Result\Models\Result;

final class LatestResultResolver
{
    public function forMarket(Market $market): ?Result
    {
        return $this->forMarketId(
            (int) $market->getKey(),
        );
    }

    public function forMarketId(int $marketId): ?Result
    {
        return Result::query()
            ->where('market_id', $marketId)
            ->orderByDesc('result_date')
            ->orderByDesc('id')
            ->first();
    }
}

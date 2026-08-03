<?php

declare(strict_types=1);

namespace App\Domains\Result\Support;

use App\Domains\Market\Models\Market;
use App\Domains\Result\Models\Result;
use Illuminate\Support\Collection;

final class LatestResultResolver
{
    public function forMarket(Market $market): ?Result
    {
        return $this->forMarketId((int) $market->getKey());
    }

    public function forMarketId(int $marketId): ?Result
    {
        return Result::query()
            ->where('market_id', $marketId)
            ->orderByDesc('result_date')
            ->orderByDesc('id')
            ->first();
    }

    /**
     * @param  Collection<int, Market>  $markets
     * @return Collection<int, Market>
     */
    public function attachToMarkets(Collection $markets): Collection
    {
        if ($markets->isEmpty()) {
            return $markets;
        }

        $marketIds = $markets
            ->map(
                static fn (Market $market): int => (int) $market->getKey()
            )
            ->values();

        $latestResults = Result::query()
            ->select([
                'id',
                'brand_id',
                'market_id',
                'result_date',
                'winning_numbers',
                'notes',
                'updated_at',
            ])
            ->whereIn('market_id', $marketIds)
            ->orderByDesc('result_date')
            ->orderByDesc('id')
            ->get()
            ->unique('market_id')
            ->keyBy('market_id');

        return $markets->each(
            static function (
                Market $market
            ) use ($latestResults): void {
                $market->setRelation(
                    'latestResult',
                    $latestResults->get(
                        $market->getKey()
                    ),
                );
            },
        );
    }
}

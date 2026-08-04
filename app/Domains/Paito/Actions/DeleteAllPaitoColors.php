<?php

namespace App\Domains\Paito\Actions;

use App\Domains\Brand\Support\BrandContext;
use App\Domains\Market\Models\Market;
use App\Domains\Paito\Models\PaitoCellColor;
use RuntimeException;

class DeleteAllPaitoColors
{
    public function __construct(
        private readonly BrandContext $brandContext,
    ) {}

    public function execute(Market $market): int
    {
        $brand = $this->brandContext->get();

        if ($brand === null) {
            throw new RuntimeException(
                'Brand context tidak tersedia.'
            );
        }

        if ((int) $market->brand_id !== (int) $brand->getKey()) {
            throw new RuntimeException(
                'Pasaran tidak berada pada brand aktif.'
            );
        }

        return PaitoCellColor::query()
            ->where('market_id', $market->getKey())
            ->delete();
    }
}

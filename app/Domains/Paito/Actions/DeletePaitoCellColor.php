<?php

namespace App\Domains\Paito\Actions;

use App\Domains\Brand\Support\BrandContext;
use App\Domains\Paito\Models\PaitoCellColor;
use App\Domains\Result\Models\Result;
use RuntimeException;

class DeletePaitoCellColor
{
    public function __construct(
        private readonly BrandContext $brandContext,
    ) {}

    public function execute(
        Result $result,
        string $position,
    ): void {
        $brand = $this->brandContext->get();

        if ($brand === null) {
            throw new RuntimeException(
                'Brand context tidak tersedia.'
            );
        }

        PaitoCellColor::query()
            ->where('market_id', $result->market_id)
            ->where('result_id', $result->getKey())
            ->where('position', $position)
            ->delete();
    }
}

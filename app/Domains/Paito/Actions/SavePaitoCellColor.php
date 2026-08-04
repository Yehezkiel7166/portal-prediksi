<?php

namespace App\Domains\Paito\Actions;

use App\Domains\Brand\Support\BrandContext;
use App\Domains\Paito\Models\PaitoCellColor;
use App\Domains\Result\Models\Result;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RuntimeException;

class SavePaitoCellColor
{
    public function __construct(
        private readonly BrandContext $brandContext,
    ) {}

    public function execute(
        Result $result,
        string $position,
        string $color,
    ): PaitoCellColor {
        $brand = $this->brandContext->get();

        if ($brand === null) {
            throw new RuntimeException(
                'Brand context tidak tersedia.'
            );
        }

        if ((int) $result->brand_id !== (int) $brand->getKey()) {
            throw new RuntimeException(
                'Result tidak berada pada brand aktif.'
            );
        }

        $validated = Validator::make(
            [
                'position' => $position,
                'color' => $color,
            ],
            [
                'position' => [
                    'required',
                    Rule::in(PaitoCellColor::POSITIONS),
                ],
                'color' => [
                    'required',
                    Rule::in(PaitoCellColor::COLORS),
                ],
            ],
        )->validate();

        return PaitoCellColor::query()->updateOrCreate(
            [
                'brand_id' => $brand->getKey(),
                'market_id' => $result->market_id,
                'result_id' => $result->getKey(),
                'position' => $validated['position'],
            ],
            [
                'color' => $validated['color'],
            ],
        );
    }
}

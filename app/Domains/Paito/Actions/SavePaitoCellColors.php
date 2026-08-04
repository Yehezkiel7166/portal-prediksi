<?php

namespace App\Domains\Paito\Actions;

use App\Domains\Brand\Support\BrandContext;
use App\Domains\Paito\Models\PaitoCellColor;
use App\Domains\Result\Models\Result;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RuntimeException;

class SavePaitoCellColors
{
    public function __construct(
        private readonly BrandContext $brandContext,
    ) {}

    public function execute(array $cells): int
    {
        $brand = $this->brandContext->get();

        if ($brand === null) {
            throw new RuntimeException(
                'Brand context tidak tersedia.'
            );
        }

        $validated = Validator::make(
            ['cells' => $cells],
            [
                'cells' => [
                    'required',
                    'array',
                    'min:1',
                    'max:900',
                ],
                'cells.*.result_id' => [
                    'required',
                    'integer',
                ],
                'cells.*.position' => [
                    'required',
                    Rule::in(PaitoCellColor::POSITIONS),
                ],
                'cells.*.color' => [
                    'required',
                    Rule::in(PaitoCellColor::COLORS),
                ],
            ],
        )->validate();

        $items = collect($validated['cells'])
            ->unique(
                fn (array $cell): string => $cell['result_id'].'-'.$cell['position']
            )
            ->values();

        $resultIds = $items
            ->pluck('result_id')
            ->map(fn (mixed $id): int => (int) $id)
            ->unique()
            ->values();

        $results = Result::query()
            ->whereKey($resultIds)
            ->get(['id', 'brand_id', 'market_id'])
            ->keyBy('id');

        if ($results->count() !== $resultIds->count()) {
            throw new RuntimeException(
                'Terdapat Result yang tidak tersedia.'
            );
        }

        $now = now();

        $rows = $items->map(
            function (array $cell) use (
                $brand,
                $results,
                $now,
            ): array {
                $result = $results->get(
                    (int) $cell['result_id']
                );

                if (
                    (int) $result->brand_id
                    !== (int) $brand->getKey()
                ) {
                    throw new RuntimeException(
                        'Result bukan milik brand aktif.'
                    );
                }

                return [
                    'brand_id' => $brand->getKey(),
                    'market_id' => $result->market_id,
                    'result_id' => $result->getKey(),
                    'position' => $cell['position'],
                    'color' => $cell['color'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            },
        )->all();

        DB::transaction(
            fn () => PaitoCellColor::query()->upsert(
                $rows,
                [
                    'brand_id',
                    'market_id',
                    'result_id',
                    'position',
                ],
                [
                    'color',
                    'updated_at',
                ],
            ),
        );

        return count($rows);
    }
}

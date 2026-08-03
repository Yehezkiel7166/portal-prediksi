<?php

declare(strict_types=1);

namespace Tests\Feature\Result;

use App\Domains\Brand\Models\Brand;
use App\Domains\Brand\Support\BrandContext;
use App\Domains\Market\Models\Market;
use App\Domains\Result\Actions\BulkImportResultsAction;
use App\Domains\Result\Models\Result;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;
use Tests\TestCase;

class BulkImportResultsActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_imports_csv_for_active_brand(): void
    {
        [$brand, $market] = $this->brandAndMarket();

        $path = $this->csv([
            [
                'market_code',
                'result_date',
                'winning_numbers',
                'notes',
            ],
            [
                'SGP',
                '2026-08-01',
                '1234',
                'Imported CSV',
            ],
        ]);

        $report = app(
            BulkImportResultsAction::class
        )->execute($path);

        $this->assertSame(1, $report['total']);
        $this->assertSame(1, $report['created']);
        $this->assertSame(0, $report['updated']);

        $this->assertDatabaseHas('results', [
            'brand_id' => $brand->id,
            'market_id' => $market->id,
            'result_date' => '2026-08-01 00:00:00',
            'winning_numbers' => '1234',
            'notes' => 'Imported CSV',
        ]);
    }

    public function test_it_updates_existing_result(): void
    {
        [, $market] = $this->brandAndMarket();

        $existing = Result::factory()->create([
            'brand_id' => $market->brand_id,
            'market_id' => $market->id,
            'result_date' => '2026-08-01',
            'winning_numbers' => '0000',
        ]);

        $path = $this->csv([
            [
                'market_code',
                'result_date',
                'winning_numbers',
                'notes',
            ],
            [
                'SGP',
                '2026-08-01',
                '9999',
                'Updated',
            ],
        ]);

        $report = app(
            BulkImportResultsAction::class
        )->execute($path);

        $this->assertSame(0, $report['created']);
        $this->assertSame(1, $report['updated']);

        $this->assertSame(
            '9999',
            $existing->refresh()->winning_numbers,
        );

        $this->assertDatabaseCount('results', 1);
    }

    public function test_it_imports_xlsx(): void
    {
        [, $market] = $this->brandAndMarket();

        $temporary = tempnam(
            sys_get_temp_dir(),
            'results-'
        );

        if ($temporary === false) {
            $this->fail(
                'Temporary file could not be created.'
            );
        }

        $xlsxPath = $temporary.'.xlsx';
        @unlink($temporary);

        $writer = new Writer;
        $writer->openToFile($xlsxPath);

        $writer->addRow(Row::fromValues([
            'market_code',
            'result_date',
            'winning_numbers',
            'notes',
        ]));

        $writer->addRow(Row::fromValues([
            'SGP',
            '02/08/2026',
            '5678',
            'Imported XLSX',
        ]));

        $writer->close();

        try {
            $report = app(
                BulkImportResultsAction::class
            )->execute($xlsxPath);
        } finally {
            @unlink($xlsxPath);
        }

        $this->assertSame(1, $report['created']);

        $this->assertDatabaseHas('results', [
            'market_id' => $market->id,
            'result_date' => '2026-08-02 00:00:00',
            'winning_numbers' => '5678',
        ]);
    }

    public function test_invalid_row_rolls_back_entire_import(): void
    {
        $this->brandAndMarket();

        $path = $this->csv([
            [
                'market_code',
                'result_date',
                'winning_numbers',
                'notes',
            ],
            [
                'SGP',
                '2026-08-01',
                '1234',
                null,
            ],
            [
                'UNKNOWN',
                '2026-08-02',
                '5678',
                null,
            ],
        ]);

        try {
            app(
                BulkImportResultsAction::class
            )->execute($path);

            $this->fail(
                'ValidationException was not thrown.'
            );
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey(
                'file',
                $exception->errors(),
            );
        }

        $this->assertDatabaseCount('results', 0);
    }

    public function test_duplicate_rows_are_rejected(): void
    {
        $this->brandAndMarket();

        $path = $this->csv([
            [
                'market_code',
                'result_date',
                'winning_numbers',
                'notes',
            ],
            [
                'SGP',
                '2026-08-01',
                '1234',
                null,
            ],
            [
                'SGP',
                '2026-08-01',
                '5678',
                null,
            ],
        ]);

        $this->expectException(
            ValidationException::class
        );

        app(
            BulkImportResultsAction::class
        )->execute($path);
    }

    public function test_other_brand_market_is_rejected(): void
    {
        $activeBrand = Brand::factory()->create();
        $otherBrand = Brand::factory()->create();

        app(BrandContext::class)->set(
            $activeBrand
        );

        Market::factory()->create([
            'brand_id' => $otherBrand->id,
            'code' => 'SGP',
        ]);

        $path = $this->csv([
            [
                'market_code',
                'result_date',
                'winning_numbers',
                'notes',
            ],
            [
                'SGP',
                '2026-08-01',
                '1234',
                null,
            ],
        ]);

        $this->expectException(
            ValidationException::class
        );

        app(
            BulkImportResultsAction::class
        )->execute($path);
    }

    public function test_scoped_import_rejects_another_market(): void
    {
        [$brand, $targetMarket] =
            $this->brandAndMarket();

        Market::factory()->create([
            'brand_id' => $brand->id,
            'code' => 'SDY',
            'name' => 'Sydney',
        ]);

        $path = $this->csv([
            [
                'market_code',
                'result_date',
                'winning_numbers',
                'notes',
            ],
            [
                'SDY',
                '2026-08-03',
                '5678',
                null,
            ],
        ]);

        $this->expectException(
            ValidationException::class
        );

        app(BulkImportResultsAction::class)
            ->execute(
                $path,
                $targetMarket,
            );
    }

    /**
     * @return array{Brand, Market}
     */
    private function brandAndMarket(): array
    {
        $brand = Brand::factory()->create();

        app(BrandContext::class)->set($brand);

        $market = Market::factory()->create([
            'brand_id' => $brand->id,
            'code' => 'SGP',
            'name' => 'Singapore',
        ]);

        return [$brand, $market];
    }

    /**
     * @param  list<list<mixed>>  $rows
     */
    private function csv(array $rows): string
    {
        $temporary = tempnam(
            sys_get_temp_dir(),
            'results-'
        );

        if ($temporary === false) {
            $this->fail(
                'Temporary file could not be created.'
            );
        }

        $handle = fopen($temporary, 'wb');

        if ($handle === false) {
            $this->fail(
                'Temporary CSV could not be opened.'
            );
        }

        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        $csvPath = $temporary.'.csv';
        rename($temporary, $csvPath);

        $this->beforeApplicationDestroyed(
            static fn () => @unlink($csvPath)
        );

        return $csvPath;
    }
}

<?php

declare(strict_types=1);

namespace App\Domains\Result\Actions;

use App\Domains\Brand\Support\BrandContext;
use App\Domains\Market\Models\Market;
use App\Domains\Result\Models\Result;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;
use RuntimeException;
use Throwable;

final class BulkImportResultsAction
{
    private const MAX_ROWS = 2000;

    /**
     * @return array{
     *     total:int,
     *     created:int,
     *     updated:int,
     *     skipped:int
     * }
     */
    public function execute(string $path): array
    {
        if (!is_file($path) || !is_readable($path)) {
            throw ValidationException::withMessages([
                'file' =>
                    'File import tidak ditemukan atau tidak dapat dibaca.',
            ]);
        }

        $brand = app(BrandContext::class)->get();

        if ($brand === null) {
            throw ValidationException::withMessages([
                'file' => 'Brand context aktif wajib tersedia.',
            ]);
        }

        $extension = strtolower(
            pathinfo($path, PATHINFO_EXTENSION)
        );

        if (!in_array($extension, ['csv', 'xlsx'], true)) {
            throw ValidationException::withMessages([
                'file' => 'Format file harus CSV atau XLSX.',
            ]);
        }

        $rows = $extension === 'csv'
            ? $this->readCsv($path)
            : $this->readXlsx($path);

        if ($rows === []) {
            throw ValidationException::withMessages([
                'file' => 'File import tidak memiliki baris data.',
            ]);
        }

        if (count($rows) > self::MAX_ROWS) {
            throw ValidationException::withMessages([
                'file' => sprintf(
                    'Maksimal %d baris data per import.',
                    self::MAX_ROWS,
                ),
            ]);
        }

        $prepared = [];
        $rowErrors = [];
        $seen = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            try {
                $normalized = $this->normalizeRow($row);

                $market = Market::query()
                    ->where('brand_id', $brand->getKey())
                    ->whereRaw(
                        'UPPER(code) = ?',
                        [
                            strtoupper(
                                $normalized['market_code']
                            ),
                        ],
                    )
                    ->first();

                if ($market === null) {
                    throw ValidationException::withMessages([
                        'market_code' =>
                            'Kode pasaran tidak ditemukan pada brand aktif.',
                    ]);
                }

                $validated = Validator::make(
                    [
                        'market_id' => $market->getKey(),
                        'result_date' =>
                            $normalized['result_date'],
                        'winning_numbers' =>
                            $normalized['winning_numbers'],
                        'notes' => $normalized['notes'],
                    ],
                    [
                        'market_id' => [
                            'required',
                            'integer',
                        ],
                        'result_date' => [
                            'required',
                            'date_format:Y-m-d',
                        ],
                        'winning_numbers' => [
                            'required',
                            'string',
                            'max:500',
                        ],
                        'notes' => [
                            'nullable',
                            'string',
                            'max:5000',
                        ],
                    ],
                )->validate();

                $key = $market->getKey() .
                    ':' .
                    $validated['result_date'];

                if (isset($seen[$key])) {
                    throw ValidationException::withMessages([
                        'result_date' =>
                            'Pasaran dan tanggal duplikat di dalam file.',
                    ]);
                }

                $seen[$key] = true;

                $prepared[] = [
                    'market' => $market,
                    'data' => $validated,
                ];
            } catch (ValidationException $exception) {
                $rowErrors[] = sprintf(
                    'Baris %d: %s',
                    $rowNumber,
                    $this->firstValidationMessage($exception),
                );
            } catch (Throwable $exception) {
                $rowErrors[] = sprintf(
                    'Baris %d: %s',
                    $rowNumber,
                    $exception->getMessage(),
                );
            }
        }

        if ($rowErrors !== []) {
            throw ValidationException::withMessages([
                'file' => $rowErrors,
            ]);
        }

        return DB::transaction(
            function () use ($prepared): array {
                $created = 0;
                $updated = 0;

                foreach ($prepared as $item) {
                    /** @var Market $market */
                    $market = $item['market'];
                    $data = $item['data'];

                    $existing = Result::query()
                        ->where(
                            'brand_id',
                            $market->brand_id,
                        )
                        ->where(
                            'market_id',
                            $market->getKey(),
                        )
                        ->whereDate(
                            'result_date',
                            $data['result_date'],
                        )
                        ->first();

                    app(UpsertResultAction::class)->execute(
                        $existing,
                        $data,
                    );

                    if ($existing === null) {
                        $created++;
                    } else {
                        $updated++;
                    }
                }

                return [
                    'total' => count($prepared),
                    'created' => $created,
                    'updated' => $updated,
                    'skipped' => 0,
                ];
            }
        );
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function readCsv(string $path): array
    {
        $handle = fopen($path, 'rb');

        if ($handle === false) {
            throw new RuntimeException(
                'File CSV tidak dapat dibuka.'
            );
        }

        try {
            $header = fgetcsv($handle);

            if (!is_array($header)) {
                return [];
            }

            $header = $this->normalizeHeaders($header);
            $this->assertRequiredHeaders($header);

            $rows = [];

            while (($values = fgetcsv($handle)) !== false) {
                if ($this->isEmptyRow($values)) {
                    continue;
                }

                $rows[] = $this->combineRow(
                    $header,
                    $values,
                );
            }

            return $rows;
        } finally {
            fclose($handle);
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function readXlsx(string $path): array
    {
        $reader = new XlsxReader();
        $reader->open($path);

        try {
            $header = null;
            $rows = [];

            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    $values = $row->toArray();

                    if ($header === null) {
                        $header = $this->normalizeHeaders(
                            $values
                        );

                        $this->assertRequiredHeaders(
                            $header
                        );

                        continue;
                    }

                    if ($this->isEmptyRow($values)) {
                        continue;
                    }

                    $rows[] = $this->combineRow(
                        $header,
                        $values,
                    );
                }

                break;
            }

            return $rows;
        } finally {
            $reader->close();
        }
    }

    /**
     * @param array<int, mixed> $headers
     * @return list<string>
     */
    private function normalizeHeaders(
        array $headers
    ): array {
        return array_map(
            static function (mixed $header): string {
                $header = strtolower(
                    trim((string) $header)
                );

                $header = preg_replace(
                    '/[^a-z0-9]+/',
                    '_',
                    $header,
                );

                return trim((string) $header, '_');
            },
            $headers,
        );
    }

    /**
     * @param list<string> $headers
     */
    private function assertRequiredHeaders(
        array $headers
    ): void {
        $required = [
            'market_code',
            'result_date',
            'winning_numbers',
        ];

        $missing = array_values(
            array_diff($required, $headers)
        );

        if ($missing !== []) {
            throw ValidationException::withMessages([
                'file' =>
                    'Header wajib tidak ditemukan: ' .
                    implode(', ', $missing),
            ]);
        }
    }

    /**
     * @param list<string> $headers
     * @param array<int, mixed> $values
     * @return array<string, mixed>
     */
    private function combineRow(
        array $headers,
        array $values,
    ): array {
        $values = array_pad(
            array_slice(
                $values,
                0,
                count($headers),
            ),
            count($headers),
            null,
        );

        $combined = array_combine(
            $headers,
            $values,
        );

        if (!is_array($combined)) {
            throw new RuntimeException(
                'Jumlah kolom file tidak valid.'
            );
        }

        return $combined;
    }

    /**
     * @param array<string, mixed> $row
     * @return array{
     *     market_code:string,
     *     result_date:string,
     *     winning_numbers:string,
     *     notes:?string
     * }
     */
    private function normalizeRow(array $row): array
    {
        $marketCode = strtoupper(
            trim(
                (string) ($row['market_code'] ?? '')
            )
        );

        $winningNumbers = trim(
            (string) (
                $row['winning_numbers'] ?? ''
            )
        );

        $notes = trim(
            (string) ($row['notes'] ?? '')
        );

        $notes = $notes === '' ? null : $notes;

        return [
            'market_code' => $marketCode,
            'result_date' => $this->normalizeDate(
                $row['result_date'] ?? null
            ),
            'winning_numbers' => $winningNumbers,
            'notes' => $notes,
        ];
    }

    private function normalizeDate(mixed $value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return CarbonImmutable::instance($value)
                ->format('Y-m-d');
        }

        $value = trim((string) $value);

        foreach (
            [
                'Y-m-d',
                'd-m-Y',
                'd/m/Y',
                'Y/m/d',
            ] as $format
        ) {
            try {
                $date = CarbonImmutable::createFromFormat(
                    '!' . $format,
                    $value,
                );

                if (
                    $date !== false
                    && $date->format($format) === $value
                ) {
                    return $date->format('Y-m-d');
                }
            } catch (Throwable) {
            }
        }

        throw ValidationException::withMessages([
            'result_date' =>
                'Tanggal harus memakai format YYYY-MM-DD, DD-MM-YYYY, DD/MM/YYYY, atau YYYY/MM/DD.',
        ]);
    }

    /**
     * @param array<int, mixed> $values
     */
    private function isEmptyRow(array $values): bool
    {
        foreach ($values as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function firstValidationMessage(
        ValidationException $exception
    ): string {
        foreach ($exception->errors() as $messages) {
            if (isset($messages[0])) {
                return (string) $messages[0];
            }
        }

        return $exception->getMessage();
    }
}

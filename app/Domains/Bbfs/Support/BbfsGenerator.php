<?php

namespace App\Domains\Bbfs\Support;

use InvalidArgumentException;

final class BbfsGenerator
{
    /**
     * @return array{digits: string, length: int, combinations: list<string>, count: int}
     */
    public function generate(string $input, int $length): array
    {
        $digits = $this->normalize($input);
        $allowedLengths = config('lottery-tools.bbfs.allowed_output_lengths', [2, 3, 4]);
        $minimum = (int) config('lottery-tools.bbfs.minimum_unique_digits', 2);
        $maximum = (int) config('lottery-tools.bbfs.maximum_unique_digits', 7);

        if (! in_array($length, $allowedLengths, true)) {
            throw new InvalidArgumentException('Panjang keluaran BBFS tidak didukung.');
        }

        if (strlen($digits) < $minimum || strlen($digits) > $maximum) {
            throw new InvalidArgumentException("BBFS harus berisi {$minimum} sampai {$maximum} digit unik.");
        }

        if ($length > strlen($digits)) {
            throw new InvalidArgumentException('Panjang keluaran tidak boleh melebihi jumlah digit unik.');
        }

        $combinations = [];
        $this->build(str_split($digits), $length, '', $combinations);

        return [
            'digits' => $digits,
            'length' => $length,
            'combinations' => $combinations,
            'count' => count($combinations),
        ];
    }

    private function normalize(string $input): string
    {
        if (! preg_match('/^\s*[0-9\s,.-]+\s*$/', $input)) {
            throw new InvalidArgumentException('BBFS hanya boleh berisi angka dan pemisah sederhana.');
        }

        $digits = preg_replace('/\D/', '', $input) ?? '';
        $unique = [];

        foreach (str_split($digits) as $digit) {
            $unique[$digit] = true;
        }

        return implode('', array_keys($unique));
    }

    /**
     * @param list<string> $available
     * @param list<string> $results
     */
    private function build(array $available, int $remaining, string $prefix, array &$results): void
    {
        if ($remaining === 0) {
            $results[] = $prefix;

            return;
        }

        foreach ($available as $index => $digit) {
            $next = $available;
            array_splice($next, $index, 1);
            $this->build($next, $remaining - 1, $prefix.$digit, $results);
        }
    }
}

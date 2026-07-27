<?php

namespace App\Domains\Converter\Support;

use InvalidArgumentException;

final class SgpNumberConverter
{
    /**
     * @return array{input: string, four_digit: string, three_digit: string, two_digit: string, as: string, kop: string, kepala: string, ekor: string}
     */
    public function convert(string $input): array
    {
        $number = trim($input);

        if (! preg_match('/^\d{4}$/', $number)) {
            throw new InvalidArgumentException('Masukkan tepat 4 digit angka, termasuk angka nol di depan bila ada.');
        }

        return [
            'input' => $number,
            'four_digit' => $number,
            'three_digit' => substr($number, 1, 3),
            'two_digit' => substr($number, 2, 2),
            'as' => $number[0],
            'kop' => $number[1],
            'kepala' => $number[2],
            'ekor' => $number[3],
        ];
    }
}

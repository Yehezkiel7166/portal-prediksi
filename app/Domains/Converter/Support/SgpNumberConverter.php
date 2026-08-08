<?php

declare(strict_types=1);

namespace App\Domains\Converter\Support;

use InvalidArgumentException;

final class SgpNumberConverter
{
    /**
     * Convert exactly seven SGP winning numbers into a four-digit result.
     *
     * Formula:
     *
     * AS:
     * B2 + B3, take the final digit.
     *
     * KOP:
     * B4 + B5, take the final digit.
     *
     * KEPALA / EKOR:
     * (((B1+B2+B3+B4+B5+B6) * 2) - B1 - B6) + B7,
     * take the final two digits.
     *
     * Reference:
     * [5, 7, 30, 33, 36, 46, 44] => 7907
     *
     * @param  array<int, int|string>  $balls
     */
    public function convert(array $balls): string
    {
        if (count($balls) !== 7) {
            throw new InvalidArgumentException(
                'Konversi SGP membutuhkan tepat 7 angka.',
            );
        }

        $numbers = array_values(array_map(
            static function (int|string $value): int {
                if (
                    is_string($value)
                    && ! preg_match('/^\d+$/', $value)
                ) {
                    throw new InvalidArgumentException(
                        'Semua input harus berupa angka non-negatif.',
                    );
                }

                $number = (int) $value;

                if ($number < 0) {
                    throw new InvalidArgumentException(
                        'Semua input harus berupa angka non-negatif.',
                    );
                }

                return $number;
            },
            $balls,
        ));

        [
            $bola1,
            $bola2,
            $bola3,
            $bola4,
            $bola5,
            $bola6,
            $bola7,
        ] = $numbers;

        /*
         * AS = B2 + B3
         * 7 + 30 = 37
         * digit terakhir = 7
         */
        $asRaw = $bola2 + $bola3;

        /*
         * KOP = B4 + B5
         * 33 + 36 = 69
         * digit terakhir = 9
         */
        $kopRaw = $bola4 + $bola5;

        /*
         * KEPALA / EKOR:
         *
         * (((B1+B2+B3+B4+B5+B6) * 2) - B1 - B6) + B7
         *
         * Reference:
         * (((5+7+30+33+36+46) * 2) - 5 - 46) + 44
         * = 307
         * final two digits = 07
         */
        $kepalaEkorRaw = (
            (
                (
                    $bola1
                    + $bola2
                    + $bola3
                    + $bola4
                    + $bola5
                    + $bola6
                ) * 2
            )
            - $bola1
            - $bola6
        ) + $bola7;

        $as = (string) ($asRaw % 10);
        $kop = (string) ($kopRaw % 10);

        $kepalaEkor = str_pad(
            (string) ($kepalaEkorRaw % 100),
            2,
            '0',
            STR_PAD_LEFT,
        );

        return $as.$kop.$kepalaEkor;
    }
}

<?php

namespace App\Domains\Paito\Support;

final class PaitoColorMapper
{
    private const COLORS = [
        0 => ['name' => 'Merah', 'class' => 'bg-red-600 text-white'],
        1 => ['name' => 'Biru', 'class' => 'bg-blue-600 text-white'],
        2 => ['name' => 'Hijau', 'class' => 'bg-emerald-600 text-white'],
        3 => ['name' => 'Kuning', 'class' => 'bg-yellow-400 text-slate-950'],
        4 => ['name' => 'Ungu', 'class' => 'bg-purple-600 text-white'],
        5 => ['name' => 'Jingga', 'class' => 'bg-orange-500 text-slate-950'],
        6 => ['name' => 'Merah Muda', 'class' => 'bg-pink-500 text-slate-950'],
        7 => ['name' => 'Nila', 'class' => 'bg-indigo-600 text-white'],
        8 => ['name' => 'Teal', 'class' => 'bg-teal-500 text-slate-950'],
        9 => ['name' => 'Abu-abu', 'class' => 'bg-slate-500 text-white'],
    ];

    public function map(string $winningNumbers): array
    {
        $digits = str_split(preg_replace('/\D/', '', $winningNumbers) ?? '');

        return array_map(function (string $digit): array {
            $number = (int) $digit;

            return [
                'digit' => $digit,
                ...self::COLORS[$number],
            ];
        }, $digits);
    }

    public function legend(): array
    {
        return self::COLORS;
    }
}

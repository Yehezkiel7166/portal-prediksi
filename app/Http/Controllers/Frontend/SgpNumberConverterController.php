<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Domains\Converter\Support\SgpNumberConverter;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

final class SgpNumberConverterController extends Controller
{
    public function create(): View
    {
        return view('frontend.tools.sgp-number-converter', [
            'balls' => array_fill(0, 7, ''),
            'result' => null,
        ]);
    }

    public function store(
        Request $request,
        SgpNumberConverter $converter,
    ): View {
        $validated = $request->validate([
            'balls' => [
                'required',
                'array',
                'size:7',
            ],
            'balls.*' => [
                'required',
                'integer',
                'min:0',
            ],
        ], [
            'balls.required' => 'Lengkapi seluruh 7 angka.',
            'balls.array' => 'Format angka tidak valid.',
            'balls.size' => 'Konversi SGP membutuhkan tepat 7 angka.',
            'balls.*.required' => 'Lengkapi seluruh 7 angka.',
            'balls.*.integer' => 'Input hanya boleh berupa angka.',
            'balls.*.min' => 'Input tidak boleh negatif.',
        ]);

        try {
            $result = $converter->convert(
                $validated['balls'],
            );
        } catch (\InvalidArgumentException $exception) {
            throw ValidationException::withMessages([
                'balls' => $exception->getMessage(),
            ]);
        }

        return view('frontend.tools.sgp-number-converter', [
            'balls' => array_map(
                static fn (mixed $value): string => (string) $value,
                $validated['balls'],
            ),
            'result' => $result,
        ]);
    }
}

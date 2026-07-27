<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Converter\Support\SgpNumberConverter;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

final class SgpNumberConverterController extends Controller
{
    public function create(): View
    {
        return view('frontend.tools.sgp-number-converter', [
            'result' => null,
        ]);
    }

    public function store(Request $request, SgpNumberConverter $converter): View
    {
        $validated = $request->validate([
            'number' => ['required', 'string', 'size:4', 'regex:/^\d{4}$/'],
        ]);

        try {
            $result = $converter->convert($validated['number']);
        } catch (InvalidArgumentException $exception) {
            throw ValidationException::withMessages([
                'number' => $exception->getMessage(),
            ]);
        }

        return view('frontend.tools.sgp-number-converter', compact('result'));
    }
}

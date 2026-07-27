<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Bbfs\Support\BbfsGenerator;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

final class BbfsGeneratorController extends Controller
{
    public function create(): View
    {
        return view('frontend.tools.bbfs-generator', [
            'result' => null,
        ]);
    }

    public function store(Request $request, BbfsGenerator $generator): View
    {
        $validated = $request->validate([
            'digits' => ['required', 'string', 'max:64'],
            'length' => ['required', 'integer', 'in:2,3,4'],
        ]);

        try {
            $result = $generator->generate($validated['digits'], (int) $validated['length']);
        } catch (InvalidArgumentException $exception) {
            throw ValidationException::withMessages([
                'digits' => $exception->getMessage(),
            ]);
        }

        return view('frontend.tools.bbfs-generator', compact('result'));
    }
}

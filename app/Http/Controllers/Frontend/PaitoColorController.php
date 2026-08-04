<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Market\Models\Market;
use App\Domains\Paito\Actions\DeleteAllPaitoColors;
use App\Domains\Paito\Actions\DeletePaitoCellColor;
use App\Domains\Paito\Actions\SavePaitoCellColor;
use App\Domains\Result\Models\Result;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class PaitoColorController extends Controller
{
    public function save(
        Request $request,
        Result $result,
        SavePaitoCellColor $action,
    ): JsonResponse {
        $validated = $request->validate([
            'position' => ['required', 'string'],
            'color' => ['required', 'string'],
        ]);

        $color = $action->execute(
            $result,
            $validated['position'],
            $validated['color'],
        );

        return response()->json([
            'saved' => true,
            'color' => $color->color,
        ]);
    }

    public function delete(
        Request $request,
        Result $result,
        DeletePaitoCellColor $action,
    ): JsonResponse {
        $validated = $request->validate([
            'position' => ['required', 'string'],
        ]);

        $action->execute(
            $result,
            $validated['position'],
        );

        return response()->json(['deleted' => true]);
    }

    public function clear(
        Market $market,
        DeleteAllPaitoColors $action,
    ): JsonResponse {
        return response()->json([
            'deleted' => $action->execute($market),
        ]);
    }
}

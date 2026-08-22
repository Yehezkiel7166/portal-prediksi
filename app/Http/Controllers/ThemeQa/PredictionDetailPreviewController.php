<?php

declare(strict_types=1);

namespace App\Http\Controllers\ThemeQa;

use App\Domains\Market\Models\Market;
use App\Domains\Prediction\Models\Prediction;
use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

final class PredictionDetailPreviewController extends Controller
{
    public function __invoke(
        string $marketSlug,
        string $predictionDate,
    ): View {
        /*
        |--------------------------------------------------------------------------
        | Theme-QA synthetic fixture
        |--------------------------------------------------------------------------
        |
        | These Eloquent objects remain intentionally unsaved.
        |
        | This controller is reachable only through the signed Theme-QA route.
        | It renders the production Prediction detail Blade without requiring
        | a published production Prediction fixture.
        |
        */

        $date = CarbonImmutable::createFromFormat(
            '!Y-m-d',
            $predictionDate,
        );

        abort_unless(
            $date !== false,
            404,
        );

        $market = new Market();

        $market->forceFill([
            'name' => Str::headline($marketSlug),
            'slug' => $marketSlug,
            'code' => 'QA',
            'timezone' => 'Asia/Jakarta',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $prediction = new Prediction();

        $prediction->forceFill([
            'prediction_date' => $date,
            'predicted_numbers' => '12 34 56 78',
            'bbfs' => '209184',
            'colok_bebas' => '9-4',
            'prediction_2d' => '18, 91, 82',
            'prediction_3d' => '028, 492',
            'prediction_4d' => '9482, 8491',
            'kembar' => '88, 99',
            'shio' => 'TIKUS',
            'status' => Prediction::STATUS_PUBLISHED,
            'notes' => 'Fixture visual Theme-QA. Data ini tidak disimpan ke database.',
            'published_at' => CarbonImmutable::now()
                ->subMinute(),
        ]);

        $prediction->setRelation(
            'market',
            $market,
        );

        return view(
            'frontend.predictions.show',
            [
                'prediction' => $prediction,
            ],
        );
    }
}

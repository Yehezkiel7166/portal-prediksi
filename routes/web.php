<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PredictionDetailController;
use App\Http\Controllers\Frontend\PredictionsController;
use App\Http\Controllers\Frontend\PromotionDetailController;
use App\Http\Controllers\Frontend\PromotionsController;
use App\Http\Controllers\Frontend\ResultDetailController;
use App\Http\Controllers\Frontend\ResultsController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/prediksi-togel', PredictionsController::class)
    ->name('predictions.index');

Route::get(
    '/prediksi-togel/{marketSlug}/{predictionDate}',
    PredictionDetailController::class,
)
    ->where([
        'marketSlug' => '[a-z0-9]+(?:-[a-z0-9]+)*',
        'predictionDate' => '\d{4}-\d{2}-\d{2}',
    ])
    ->name('predictions.show');

Route::get('/data-result', ResultsController::class)
    ->name('results.index');

Route::get(
    '/data-result/{marketSlug}/{resultDate}',
    ResultDetailController::class,
)
    ->where([
        'marketSlug' => '[a-z0-9]+(?:-[a-z0-9]+)*',
        'resultDate' => '\d{4}-\d{2}-\d{2}',
    ])
    ->name('results.show');

Route::get('/promosi', PromotionsController::class)
    ->name('promotions.index');

Route::get('/promosi/{slug}', PromotionDetailController::class)
    ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')
    ->name('promotions.show');

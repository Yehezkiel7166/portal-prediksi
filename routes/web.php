<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PredictionsController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/prediksi-togel', PredictionsController::class)
    ->name('predictions.index');

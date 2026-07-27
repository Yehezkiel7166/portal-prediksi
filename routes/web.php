<?php

use App\Http\Controllers\Frontend\BbfsGeneratorController;
use App\Http\Controllers\Frontend\BlogDetailController;
use App\Http\Controllers\Frontend\ComplaintController;
use App\Http\Controllers\Frontend\BlogsController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\GuideDetailController;
use App\Http\Controllers\Frontend\GuidesController;
use App\Http\Controllers\Frontend\JackpotProofDetailController;
use App\Http\Controllers\Frontend\JackpotProofsController;
use App\Http\Controllers\Frontend\LiveDrawController;
use App\Http\Controllers\Frontend\LotteryScheduleController;
use App\Http\Controllers\Frontend\DreamBookController;
use App\Http\Controllers\Frontend\PaitoController;
use App\Http\Controllers\Frontend\PredictionDetailController;
use App\Http\Controllers\Frontend\PredictionsController;
use App\Http\Controllers\Frontend\PromotionDetailController;
use App\Http\Controllers\Frontend\PromotionsController;
use App\Http\Controllers\Frontend\ResultDetailController;
use App\Http\Controllers\Frontend\ResultsController;
use App\Http\Controllers\Frontend\SgpNumberConverterController;
use App\Http\Controllers\Frontend\ShioTableController;
use App\Http\Controllers\Frontend\SitemapController;
use App\Http\Controllers\Frontend\SlotGacorController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/live-draw', LiveDrawController::class)
    ->name('live-draw.index');

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

Route::get('/slot-gacor', SlotGacorController::class)
    ->name('slot-gacor.index');

Route::get('/bukti-jackpot', JackpotProofsController::class)->name('jackpot-proofs.index');

Route::get('/bukti-jackpot/{slug}', JackpotProofDetailController::class)
    ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')
    ->name('jackpot-proofs.show');

Route::get('/promosi', PromotionsController::class)
    ->name('promotions.index');

Route::get('/promosi/{slug}', PromotionDetailController::class)
    ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')
    ->name('promotions.show');


Route::get('/keluhan', [ComplaintController::class, 'create'])
    ->name('complaints.create');

Route::post('/keluhan', [ComplaintController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('complaints.store');

Route::get('/panduan', GuidesController::class)->name('guides.index');

Route::get('/panduan/{slug}', GuideDetailController::class)
    ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')
    ->name('guides.show');

Route::get('/blog', BlogsController::class)
    ->name('blog.index');

Route::get('/blog/{slug}', BlogDetailController::class)
    ->where('slug', '[A-Za-z0-9-]+')
    ->name('blog.show');

Route::get('/alat-togel/jadwal-togel', LotteryScheduleController::class)
    ->name('tools.lottery-schedule');

Route::get('/alat-togel/tabel-shio', ShioTableController::class)
    ->name('tools.shio-table');

Route::get('/alat-togel/bbfs-generator', [BbfsGeneratorController::class, 'create'])
    ->name('tools.bbfs.create');

Route::post('/alat-togel/bbfs-generator', [BbfsGeneratorController::class, 'store'])
    ->middleware('throttle:20,1')
    ->name('tools.bbfs.store');

Route::get('/alat-togel/konversi-angka-sgp', [SgpNumberConverterController::class, 'create'])
    ->name('tools.sgp-converter.create');

Route::post('/alat-togel/konversi-angka-sgp', [SgpNumberConverterController::class, 'store'])
    ->middleware('throttle:30,1')
    ->name('tools.sgp-converter.store');

Route::get('/alat-togel/buku-mimpi', [DreamBookController::class, 'index'])
    ->name('tools.dream-book.index');

Route::get('/alat-togel/buku-mimpi/{slug}', [DreamBookController::class, 'show'])
    ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')
    ->name('tools.dream-book.show');

Route::get('/alat-togel/paito-warna', PaitoController::class)
    ->name('tools.paito');

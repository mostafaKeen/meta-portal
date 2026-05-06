<?php

use Illuminate\Support\Facades\Route;
use Modules\ConversionAPI\Http\Controllers\ConversionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where we can register the API routes for the ConversionAPI module.
|
*/

Route::prefix('v1/capi')->group(function () {
    Route::post('/webhook/{token}', [ConversionController::class, 'handle'])->name('capi.webhook');
});

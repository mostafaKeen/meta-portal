<?php

use Illuminate\Support\Facades\Route;
use Modules\ConversionAPI\Http\Controllers\CapiSettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where we can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'verified'])->prefix('settings/capi')->group(function () {
    Route::get('/generate-token', [CapiSettingsController::class, 'generateToken'])->name('capi.token.generate');
});

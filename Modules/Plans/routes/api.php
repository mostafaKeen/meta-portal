<?php

use Illuminate\Support\Facades\Route;
use Modules\Plans\Http\Controllers\PlansController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('plans', PlansController::class)->names('plans');
});

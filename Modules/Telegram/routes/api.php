<?php

use Illuminate\Support\Facades\Route;
use Modules\Telegram\Http\Controllers\TelegramController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('telegrams', TelegramController::class)->names('telegram');
});

// Public Webhook Route
Route::post('telegram/webhook/{token}', [\Modules\Telegram\Http\Controllers\TelegramWebhookController::class, 'handle'])
    ->name('telegram.webhook');

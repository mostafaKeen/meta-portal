<?php

use Illuminate\Support\Facades\Route;
use Modules\WhatsAppQR\Http\Controllers\WhatsAppQRController;
use Modules\WhatsAppQR\Http\Controllers\WhatsAppChatController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('whatsappqrs', WhatsAppQRController::class)->names('whatsappqr');
    Route::post('messages/send', [WhatsAppChatController::class, 'sendMessage'])->name('whatsapp.chat.send');
    Route::post('chats/start', [WhatsAppChatController::class, 'startChat'])->name('whatsapp.chat.start');
});

Route::post('/whatsapp/webhook', [\Modules\WhatsAppQR\Http\Controllers\WhatsAppWebhookController::class, 'handle']);

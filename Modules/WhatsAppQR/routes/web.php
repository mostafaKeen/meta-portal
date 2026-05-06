<?php

use Illuminate\Support\Facades\Route;
use Modules\WhatsAppQR\Http\Controllers\WhatsAppQRController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('whatsappqrs', WhatsAppQRController::class)->names('whatsappqr');
    Route::post('whatsapp/session/retry', [\Modules\WhatsAppQR\Http\Controllers\WhatsAppSessionController::class, 'retry'])->name('whatsapp.session.retry');
});

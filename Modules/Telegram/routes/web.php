<?php

use Illuminate\Support\Facades\Route;
use Modules\Telegram\Http\Controllers\TelegramController;

Route::middleware(['auth', 'verified'])->prefix('telegram')->name('telegram.')->group(function () {
    Route::get('/bots', [TelegramController::class, 'index'])->name('index');
    Route::get('/bots/{bot}/chats', [TelegramController::class, 'chats'])->name('chats');
    Route::get('/bots/{bot}/chats/{chat}', [TelegramController::class, 'showChat'])->name('show');
    Route::post('/bots/{bot}/chats/{chat}/send', [TelegramController::class, 'sendMessage'])->name('send');
});

<?php

use Illuminate\Support\Facades\Route;
use Modules\Company\Http\Controllers\CompanyController;
use Modules\Company\Http\Controllers\CompanyUserController;

/*
|--------------------------------------------------------------------------
| Company Module Web Routes
|--------------------------------------------------------------------------
*/

// Super Admin Routes - Manage all companies
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->group(function () {
    Route::get('dashboard', [\Modules\Company\Http\Controllers\DashboardController::class, 'superAdmin'])->name('admin.dashboard');
    Route::resource('companies', CompanyController::class)->names('company');
});

// Company Admin Routes - Manage users and company settings within their own company
Route::middleware(['auth', 'role:company_admin'])->prefix('company')->group(function () {
    Route::get('dashboard', [\Modules\Company\Http\Controllers\DashboardController::class, 'companyAdmin'])->name('company.dashboard');
    Route::resource('users', CompanyUserController::class)->names('company.users');
    
    // Company Settings
    Route::get('settings', [\Modules\Company\Http\Controllers\CompanySettingsController::class, 'edit'])->name('company.settings.edit');
    Route::put('settings', [\Modules\Company\Http\Controllers\CompanySettingsController::class, 'update'])->name('company.settings.update');

    // WhatsApp Numbers
    Route::resource('whatsapp', \Modules\Company\Http\Controllers\WhatsappNumberController::class)->names('company.whatsapp');

    // WhatsApp Chat Actions (web-based, for Blade UI)
    Route::post('whatsapp/chat/send', [\Modules\WhatsAppQR\Http\Controllers\WhatsAppChatController::class, 'sendMessage'])->name('company.whatsapp.chat.send');
    Route::post('whatsapp/chat/start', [\Modules\WhatsAppQR\Http\Controllers\WhatsAppChatController::class, 'startChat'])->name('company.whatsapp.chat.start');
    Route::get('whatsapp/chat/{chatId}/messages', [\Modules\WhatsAppQR\Http\Controllers\WhatsAppChatController::class, 'getMessages'])->name('company.whatsapp.chat.messages');
    
    // QR polling endpoint (fallback when Echo/Reverb is down)
    Route::get('whatsapp/{id}/qr-status', function ($id) {
        $number = \Modules\Company\Models\WhatsappNumber::findOrFail($id);
        if ($number->company_id !== auth()->user()->company_id) abort(403);
        return response()->json([
            'qr_code' => $number->qr_code,
            'status' => $number->status,
        ]);
    })->name('company.whatsapp.qr-status');

    // Telegram Bots
    Route::resource('telegram', \Modules\Company\Http\Controllers\TelegramBotController::class)->names('company.telegram')->except(['show']);
});

// Agent Routes
Route::middleware(['auth', 'role:agent'])->prefix('agent')->group(function () {
    Route::get('dashboard', [\Modules\Company\Http\Controllers\DashboardController::class, 'agent'])->name('agent.dashboard');
    Route::get('whatsapp', [\Modules\Company\Http\Controllers\AgentWhatsappController::class, 'index'])->name('agent.whatsapp.index');
});

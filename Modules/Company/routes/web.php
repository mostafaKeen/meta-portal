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
    Route::resource('companies', CompanyController::class)->names('company');
});

// Company Admin Routes - Manage users and company settings within their own company
Route::middleware(['auth', 'role:company_admin'])->prefix('company')->group(function () {
    Route::resource('users', CompanyUserController::class)->names('company.users');
    
    // Company Settings
    Route::get('settings', [\Modules\Company\Http\Controllers\CompanySettingsController::class, 'edit'])->name('company.settings.edit');
    Route::put('settings', [\Modules\Company\Http\Controllers\CompanySettingsController::class, 'update'])->name('company.settings.update');

    // WhatsApp Numbers
    Route::resource('whatsapp', \Modules\Company\Http\Controllers\WhatsappNumberController::class)->names('company.whatsapp')->except(['show']);
});

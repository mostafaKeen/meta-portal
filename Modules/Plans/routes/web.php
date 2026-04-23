<?php

use Illuminate\Support\Facades\Route;
use Modules\Plans\Http\Controllers\Admin\PlanController;
use Modules\Plans\Http\Controllers\Admin\SubscriptionController;
use Modules\Plans\Http\Controllers\Admin\SubscriptionRequestController as AdminRequestController;
use Modules\Plans\Http\Controllers\Company\SubscriptionController as CompanySubController;
use Modules\Plans\Http\Controllers\Company\SubscriptionRequestController as CompanyRequestController;
use Modules\Plans\Http\Controllers\PublicPlanController;

// Public Routes
Route::get('plans', [PublicPlanController::class, 'index'])->name('plans.public');

// Admin Routes
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->group(function () {
    Route::resource('plans', PlanController::class)->names('admin.plans');
    Route::resource('subscriptions', SubscriptionController::class)->names('admin.subscriptions');
    
    // Subscription Requests Management
    Route::get('subscription-requests', [AdminRequestController::class, 'index'])->name('admin.requests.index');
    Route::get('subscription-requests/{request}', [AdminRequestController::class, 'show'])->name('admin.requests.show');
    Route::put('subscription-requests/{subRequest}', [AdminRequestController::class, 'update'])->name('admin.requests.update');
});

// Company Admin Routes
Route::middleware(['auth', 'role:company_admin'])->prefix('company')->group(function () {
    Route::get('subscription', [CompanySubController::class, 'index'])->name('company.subscription.index');
    Route::get('subscription/plans', [CompanySubController::class, 'plans'])->name('company.subscription.plans');
    Route::post('subscription/request', [CompanyRequestController::class, 'store'])->name('company.subscription.request');
});

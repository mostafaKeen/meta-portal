<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard', ['verified' => 1]);
        }

        if ($user->isCompanyAdmin()) {
            return redirect()->route('company.dashboard', ['verified' => 1]);
        }

        if ($user->isAgent()) {
            return redirect()->route('agent.dashboard', ['verified' => 1]);
        }

        return redirect('/');
    })->name('dashboard');

    // Notifications
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

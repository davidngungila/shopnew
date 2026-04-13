<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

Route::middleware(['auth', 'api'])->group(function () {
    // Push notification subscription management
    Route::post('/notifications/subscribe', [NotificationController::class, 'subscribe']);
    Route::delete('/notifications/unsubscribe', [NotificationController::class, 'unsubscribe']);
    Route::get('/notifications/subscriptions', [NotificationController::class, 'getSubscriptions']);
    
    // Notification management
    Route::post('/notifications/send', [NotificationController::class, 'sendNotification']);
    Route::post('/notifications/test', [NotificationController::class, 'sendTestNotification']);
    Route::post('/notifications/dismissed', [NotificationController::class, 'markAsDismissed']);
    Route::get('/notifications/history', [NotificationController::class, 'getNotificationHistory']);
    
    // Notification preferences
    Route::get('/notifications/preferences', [NotificationController::class, 'getPreferences']);
    Route::put('/notifications/preferences', [NotificationController::class, 'updatePreferences']);
});

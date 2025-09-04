<?php

use App\Http\Controllers\VillaController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminVillaController;
use App\Http\Controllers\Admin\AdminBookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API routes
Route::prefix('v1')->group(function () {
    // Villa API routes
    Route::get('/villas', [VillaController::class, 'index']);
    Route::get('/villas/{villa}', [VillaController::class, 'show']);
    Route::get('/villas/search', [VillaController::class, 'search']);
    
    // Booking availability check
    Route::post('/bookings/check-availability', [BookingController::class, 'checkAvailability']);
    
    // Payment webhook (no auth required for external services)
    Route::post('/payments/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook'])->name('payments.webhook');
});

// Protected API routes
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Villa management (for owners)
    Route::post('/villas', [VillaController::class, 'store']);
    Route::put('/villas/{villa}', [VillaController::class, 'update']);
    Route::delete('/villas/{villa}', [VillaController::class, 'destroy']);
    
    // Booking management
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);
    Route::put('/bookings/{booking}', [BookingController::class, 'update']);
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy']);
});

// Admin API routes
Route::prefix('admin')->group(function () {
    // Admin authentication
    Route::post('/login', [AdminAuthController::class, 'login']);
    
    // Protected admin routes
    Route::middleware(['auth:sanctum', 'admin.auth'])->group(function () {
        // Auth routes
        Route::post('/logout', [AdminAuthController::class, 'logout']);
        Route::get('/me', [AdminAuthController::class, 'me']);
        
        // Dashboard
        Route::get('/dashboard/stats', [AdminDashboardController::class, 'stats']);
        
        // Villa management
        Route::apiResource('villas', AdminVillaController::class);
        
        // Booking management
        Route::get('/bookings', [AdminBookingController::class, 'index']);
        Route::get('/bookings/{booking}', [AdminBookingController::class, 'show']);
        Route::put('/bookings/{booking}/approve', [AdminBookingController::class, 'approve']);
        Route::put('/bookings/{booking}/cancel', [AdminBookingController::class, 'cancel']);
        Route::get('/bookings/calendar', [AdminBookingController::class, 'calendar']);
    });
});
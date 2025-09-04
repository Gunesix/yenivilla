<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VillaController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminVillaController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminLocationController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Home page - Welcome page with featured villas
Route::get('/', function () {
    $featuredVillas = \App\Models\Villa::with(['images'])
        ->where('is_active', true)
        ->orderBy('created_at', 'desc')
        ->limit(6)
        ->get();
    
    return Inertia::render('Welcome', [
        'featuredVillas' => $featuredVillas
    ]);
})->name('home');

// Villa routes
Route::get('/villas', [VillaController::class, 'index'])->name('villas.index');
Route::get('/villas/{villa}', [VillaController::class, 'show'])->name('villas.show');
Route::get('/villas/search', [VillaController::class, 'search'])->name('villas.search');

// Protected villa routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/villas/create', [VillaController::class, 'create'])->name('villas.create');
    Route::post('/villas', [VillaController::class, 'store'])->name('villas.store');
    Route::get('/villas/{villa}/edit', [VillaController::class, 'edit'])->name('villas.edit');
    Route::put('/villas/{villa}', [VillaController::class, 'update'])->name('villas.update');
    Route::delete('/villas/{villa}', [VillaController::class, 'destroy'])->name('villas.destroy');
});

// Locations page
Route::get('/locations', [LocationController::class, 'index'])->name('locations');
Route::get('/api/locations/villas-map', [LocationController::class, 'getVillasForMap'])->name('locations.villas-map');
Route::get('/api/locations/popular-destinations', [LocationController::class, 'getPopularDestinations'])->name('locations.popular-destinations');
Route::get('/api/locations/nearby-activities', [LocationController::class, 'getNearbyActivities'])->name('locations.nearby-activities');
Route::post('/api/locations/geocode', [LocationController::class, 'geocode'])->name('locations.geocode');
Route::get('/api/locations/search-nearby', [LocationController::class, 'searchNearby'])->name('locations.search-nearby');

// Public booking routes
Route::post('/bookings/check-availability', [BookingController::class, 'checkAvailability'])->name('bookings.check-availability');

// Booking routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::resource('bookings', BookingController::class);
    
    // Payment routes
    Route::get('/payments/create/{booking}', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/refund', [PaymentController::class, 'refund'])->name('payments.refund');
});

// Dashboard for all users (role-based)
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Admin routes
Route::prefix('admin')->group(function () {
    // Admin login page
    Route::get('/login', function () {
        return Inertia::render('Admin/Login');
    })->name('admin.login');
    
    // Admin login API
    Route::post('/login', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = \App\Models\AdminUser::where('email', $request->email)->first();

        if (!$admin || !\Illuminate\Support\Facades\Hash::check($request->password, $admin->password)) {
            return back()->withErrors([
                'email' => 'The provided credentials are incorrect.',
            ]);
        }

        \Illuminate\Support\Facades\Auth::guard('admin')->login($admin, $request->boolean('remember'));

        return redirect()->intended('/admin/dashboard');
    });
    
    // Protected admin routes
    Route::middleware(['auth:admin'])->group(function () {
        // Dashboard
        Route::get('/dashboard', function () {
            // Get dashboard data from API
            $dashboardData = [
                'stats' => [
                    'total_villas' => \App\Models\Villa::count(),
                    'active_bookings' => \App\Models\Booking::whereIn('status', ['confirmed', 'pending'])->count(),
                    'monthly_revenue' => \App\Models\Payment::where('status', 'completed')
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->sum('amount'),
                    'total_users' => \App\Models\User::count(),
                    'pending_bookings' => \App\Models\Booking::where('status', 'pending')->count(),
                ],
                'recent_bookings' => \App\Models\Booking::with(['villa', 'user'])
                    ->latest()
                    ->take(5)
                    ->get()
                    ->map(function ($booking) {
                        return [
                            'id' => $booking->id,
                            'villa_name' => $booking->villa->name,
                            'user_name' => $booking->user->name,
                            'check_in' => $booking->check_in,
                            'check_out' => $booking->check_out,
                            'status' => $booking->status,
                            'total_amount' => $booking->total_amount,
                            'created_at' => $booking->created_at,
                        ];
                    }),
                'monthly_revenue_chart' => collect(range(5, 0))->map(function ($i) {
                    $date = now()->subMonths($i);
                    $revenue = \App\Models\Payment::where('status', 'completed')
                        ->whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->sum('amount');
                    
                    return [
                        'month' => $date->format('M Y'),
                        'revenue' => $revenue,
                    ];
                }),
                'booking_status_distribution' => \App\Models\Booking::select('status', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
                    ->groupBy('status')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->status => $item->count];
                    }),
            ];
            
            return Inertia::render('Admin/Dashboard', [
                'dashboardData' => $dashboardData,
                'auth' => [
                    'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
                ]
            ]);
        })->name('admin.dashboard');
        
        // Villa Management
        Route::get('/villas', [AdminVillaController::class, 'index'])->name('admin.villas.index');
        Route::get('/villas/create', [AdminVillaController::class, 'create'])->name('admin.villas.create');
        Route::post('/villas', [AdminVillaController::class, 'store'])->name('admin.villas.store');
        Route::get('/villas/{villa}', [AdminVillaController::class, 'show'])->name('admin.villas.show');
        Route::get('/villas/{villa}/edit', [AdminVillaController::class, 'edit'])->name('admin.villas.edit');
        Route::put('/villas/{villa}', [AdminVillaController::class, 'update'])->name('admin.villas.update');
        Route::delete('/villas/{villa}', [AdminVillaController::class, 'destroy'])->name('admin.villas.destroy');
        
        // Booking Management
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings.index');
        Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('admin.bookings.show');
        Route::put('/bookings/{booking}/approve', [AdminBookingController::class, 'approve'])->name('admin.bookings.approve');
        Route::put('/bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('admin.bookings.cancel');
        Route::get('/bookings/calendar', [AdminBookingController::class, 'calendar'])->name('admin.bookings.calendar');
        
        // User Management
        Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
        
        // Location Management
        Route::get('/locations', [AdminLocationController::class, 'index'])->name('admin.locations.index');
        Route::get('/locations/create', [AdminLocationController::class, 'create'])->name('admin.locations.create');
        Route::post('/locations', [AdminLocationController::class, 'store'])->name('admin.locations.store');
        Route::get('/locations/{location}/edit', [AdminLocationController::class, 'edit'])->name('admin.locations.edit');
        Route::put('/locations/{location}', [AdminLocationController::class, 'update'])->name('admin.locations.update');
        Route::delete('/locations/{location}', [AdminLocationController::class, 'destroy'])->name('admin.locations.destroy');
        
        // Payment Management
        Route::get('/payments', [AdminPaymentController::class, 'index'])->name('admin.payments.index');
        Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('admin.payments.show');
        Route::post('/payments/{payment}/refund', [AdminPaymentController::class, 'refund'])->name('admin.payments.refund');
        
        // Reports
        Route::get('/reports', [AdminReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/reports/revenue', [AdminReportController::class, 'revenue'])->name('admin.reports.revenue');
        Route::get('/reports/occupancy', [AdminReportController::class, 'occupancy'])->name('admin.reports.occupancy');
        
        // Settings
        Route::get('/settings', [AdminSettingsController::class, 'index'])->name('admin.settings.index');
        Route::put('/settings', [AdminSettingsController::class, 'update'])->name('admin.settings.update');
        Route::put('/settings/general', [AdminSettingsController::class, 'updateGeneral'])->name('admin.settings.general');
        Route::put('/settings/booking', [AdminSettingsController::class, 'updateBooking'])->name('admin.settings.booking');
        Route::put('/settings/payment', [AdminSettingsController::class, 'updatePayment'])->name('admin.settings.payment');
        Route::put('/settings/email', [AdminSettingsController::class, 'updateEmail'])->name('admin.settings.email');
        Route::post('/settings/test-email', [AdminSettingsController::class, 'testEmail'])->name('admin.settings.test-email');
        Route::post('/settings/upload-logo', [AdminSettingsController::class, 'uploadLogo'])->name('admin.settings.upload-logo');
        
        // Logout
        Route::post('/logout', function () {
            \Illuminate\Support\Facades\Auth::guard('admin')->logout();
            return redirect('/admin/login');
        })->name('admin.logout');
    });
});

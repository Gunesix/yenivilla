<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Booking;
use App\Models\Villa;
use App\Models\Payment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the appropriate dashboard based on user role
     */
    public function index()
    {
        $user = Auth::user();
        
        // Redirect based on user role
        switch ($user->role) {
            case 'owner':
            case 'admin':
                return $this->ownerDashboard();
            case 'guest':
            default:
                return $this->guestDashboard();
        }
    }
    
    /**
     * Owner/Admin dashboard with management features
     */
    private function ownerDashboard()
    {
        $user = Auth::user();
        
        // Get user's villas if owner, all villas if admin
        $villasQuery = $user->role === 'admin' ? Villa::query() : $user->villas();
        
        $stats = [
            'total_villas' => $villasQuery->count(),
            'active_bookings' => Booking::whereIn('villa_id', $villasQuery->pluck('id'))
                ->whereIn('status', ['confirmed', 'pending'])
                ->count(),
            'monthly_revenue' => Payment::whereHas('booking', function($query) use ($villasQuery) {
                $query->whereIn('villa_id', $villasQuery->pluck('id'));
            })
            ->where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('amount'),
            'total_customers' => Booking::whereIn('villa_id', $villasQuery->pluck('id'))
                ->distinct('user_id')
                ->count()
        ];
        
        $recentBookings = Booking::with(['villa', 'user'])
            ->whereIn('villa_id', $villasQuery->pluck('id'))
            ->latest()
            ->take(5)
            ->get();
            
        return Inertia::render('OwnerDashboard', [
            'stats' => $stats,
            'recentBookings' => $recentBookings,
            'userRole' => $user->role
        ]);
    }
    
    /**
     * Guest dashboard with booking history and profile
     */
    private function guestDashboard()
    {
        $user = Auth::user();
        
        $userBookings = Booking::with(['villa.images', 'payments'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
            
        $stats = [
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'confirmed_bookings' => Booking::where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->count(),
            'total_spent' => Payment::whereHas('booking', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status', 'completed')
            ->sum('amount'),
            'upcoming_trips' => Booking::where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->where('check_in', '>', Carbon::now())
                ->count()
        ];
        
        return Inertia::render('UserDashboard', [
            'bookings' => $userBookings,
            'stats' => $stats,
            'user' => $user
        ]);
    }
}
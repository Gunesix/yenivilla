<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Villa;
use App\Models\Booking;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function stats(Request $request)
    {
        $totalVillas = Villa::count();
        $activeBookings = Booking::whereIn('status', ['confirmed', 'pending'])->count();
        $totalUsers = User::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        
        // Monthly revenue (current month)
        $monthlyRevenue = Payment::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');

        // Recent bookings
        $recentBookings = Booking::with(['villa', 'user'])
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
            });

        // Monthly revenue chart data (last 6 months)
        $monthlyRevenueChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Payment::where('status', 'completed')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount');
            
            $monthlyRevenueChart[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue,
            ];
        }

        // Booking status distribution
        $bookingStatusDistribution = Booking::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => $item->count];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'total_villas' => $totalVillas,
                    'active_bookings' => $activeBookings,
                    'monthly_revenue' => $monthlyRevenue,
                    'total_users' => $totalUsers,
                    'pending_bookings' => $pendingBookings,
                ],
                'recent_bookings' => $recentBookings,
                'monthly_revenue_chart' => $monthlyRevenueChart,
                'booking_status_distribution' => $bookingStatusDistribution,
            ],
        ]);
    }
}

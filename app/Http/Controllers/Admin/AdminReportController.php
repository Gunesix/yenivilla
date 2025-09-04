<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\Villa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subMonths(6)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        
        // Revenue reports
        $revenueData = $this->getRevenueData($dateFrom, $dateTo);
        
        // Booking reports
        $bookingData = $this->getBookingData($dateFrom, $dateTo);
        
        // Villa performance
        $villaPerformance = $this->getVillaPerformance($dateFrom, $dateTo);
        
        // User statistics
        $userStats = $this->getUserStats($dateFrom, $dateTo);
        
        return Inertia::render('Admin/Reports/Index', [
            'revenueData' => $revenueData,
            'bookingData' => $bookingData,
            'villaPerformance' => $villaPerformance,
            'userStats' => $userStats,
            'filters' => compact('dateFrom', 'dateTo'),
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }
    
    public function revenue(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subYear()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        
        $monthlyRevenue = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(amount) as total_revenue'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'period' => Carbon::create($item->year, $item->month)->format('M Y'),
                    'revenue' => $item->total_revenue,
                    'transactions' => $item->transaction_count,
                ];
            });
            
        $dailyRevenue = Payment::where('status', 'completed')
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as daily_revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        return Inertia::render('Admin/Reports/Revenue', [
            'monthlyRevenue' => $monthlyRevenue,
            'dailyRevenue' => $dailyRevenue,
            'filters' => compact('dateFrom', 'dateTo'),
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }
    
    public function occupancy(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subMonths(6)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        
        $villaOccupancy = Villa::withCount([
            'bookings as total_bookings' => function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('check_in', [$dateFrom, $dateTo])
                      ->where('status', '!=', 'cancelled');
            },
            'bookings as total_nights' => function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('check_in', [$dateFrom, $dateTo])
                      ->where('status', '!=', 'cancelled')
                      ->selectRaw('SUM(DATEDIFF(check_out, check_in))');
            }
        ])
        ->get()
        ->map(function ($villa) use ($dateFrom, $dateTo) {
            $totalDays = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo));
            $occupancyRate = $totalDays > 0 ? ($villa->total_nights / $totalDays) * 100 : 0;
            
            return [
                'villa_name' => $villa->name,
                'total_bookings' => $villa->total_bookings,
                'total_nights' => $villa->total_nights,
                'occupancy_rate' => round($occupancyRate, 2),
            ];
        });
        
        return Inertia::render('Admin/Reports/Occupancy', [
            'villaOccupancy' => $villaOccupancy,
            'filters' => compact('dateFrom', 'dateTo'),
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }
    
    private function getRevenueData($dateFrom, $dateTo)
    {
        return [
            'total_revenue' => Payment::where('status', 'completed')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->sum('amount'),
            'monthly_revenue' => Payment::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
            'average_transaction' => Payment::where('status', 'completed')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->avg('amount'),
        ];
    }
    
    private function getBookingData($dateFrom, $dateTo)
    {
        return [
            'total_bookings' => Booking::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
        ];
    }
    
    private function getVillaPerformance($dateFrom, $dateTo)
    {
        return Villa::withCount([
            'bookings as booking_count' => function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo])
                      ->where('status', '!=', 'cancelled');
            }
        ])
        ->with(['bookings' => function ($query) use ($dateFrom, $dateTo) {
            $query->whereBetween('created_at', [$dateFrom, $dateTo])
                  ->where('status', '!=', 'cancelled');
        }])
        ->get()
        ->map(function ($villa) {
            $totalRevenue = $villa->bookings->sum('total_amount');
            return [
                'villa_name' => $villa->name,
                'booking_count' => $villa->booking_count,
                'total_revenue' => $totalRevenue,
                'average_booking_value' => $villa->booking_count > 0 ? $totalRevenue / $villa->booking_count : 0,
            ];
        })
        ->sortByDesc('total_revenue')
        ->take(10)
        ->values();
    }
    
    private function getUserStats($dateFrom, $dateTo)
    {
        return [
            'new_users' => User::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'active_users' => User::whereHas('bookings', function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            })->count(),
            'repeat_customers' => User::whereHas('bookings', function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            }, '>', 1)->count(),
        ];
    }
}
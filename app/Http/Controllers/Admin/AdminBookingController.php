<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminBookingController extends Controller
{
    /**
     * Display a listing of bookings
     */
    public function index(Request $request)
    {
        $query = Booking::with(['villa', 'user', 'payment']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->where('check_in', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('check_out', '<=', $request->date_to);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('villa', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $bookings = $query->paginate($request->get('per_page', 15));

        // Get stats
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return Inertia::render('Admin/Bookings/Index', [
            'bookings' => $bookings,
            'stats' => $stats,
            'filters' => $request->only(['status', 'date_from', 'date_to', 'search', 'sort_by', 'sort_order']),
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }

    /**
     * Display the specified booking
     */
    public function show(string $id)
    {
        $booking = Booking::with([
            'villa.images', 
            'user', 
            'payment',
            'statusLogs.changedBy'
        ])->findOrFail($id);

        return Inertia::render('Admin/Bookings/Show', [
            'booking' => $booking,
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }

    /**
     * Approve a booking
     */
    public function approve(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);
        $oldStatus = $booking->status;

        if ($booking->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending bookings can be approved',
            ], 400);
        }

        $booking->update(['status' => 'confirmed']);

        // Log status change
        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'old_status' => $oldStatus,
            'new_status' => 'confirmed',
            'changed_by' => Auth::guard('admin')->id(),
            'reason' => $request->get('reason', 'Approved by admin'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking approved successfully',
            'data' => $booking->load(['villa', 'user', 'payment']),
        ]);
    }

    /**
     * Cancel a booking
     */
    public function cancel(Request $request, string $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $booking = Booking::findOrFail($id);
        $oldStatus = $booking->status;

        if (in_array($booking->status, ['cancelled', 'completed'])) {
            return response()->json([
                'success' => false,
                'message' => 'This booking cannot be cancelled',
            ], 400);
        }

        $booking->update(['status' => 'cancelled']);

        // Log status change
        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'old_status' => $oldStatus,
            'new_status' => 'cancelled',
            'changed_by' => Auth::guard('admin')->id(),
            'reason' => $request->reason,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully',
            'data' => $booking->load(['villa', 'user', 'payment']),
        ]);
    }

    /**
     * Get calendar view for bookings
     */
    public function calendar(Request $request)
    {
        // If this is an AJAX request for calendar data, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            $query = Booking::with(['villa', 'user'])
                ->whereIn('status', ['confirmed', 'pending']);

            // Filter by villa if specified
            if ($request->has('villa_id')) {
                $query->where('villa_id', $request->villa_id);
            }

            // Filter by date range
            if ($request->has('start')) {
                $query->where('check_out', '>=', $request->start);
            }
            if ($request->has('end')) {
                $query->where('check_in', '<=', $request->end);
            }

            $bookings = $query->get()->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'title' => $booking->villa->name . ' - ' . $booking->user->name,
                    'start' => $booking->check_in,
                    'end' => $booking->check_out,
                    'color' => $booking->status === 'confirmed' ? '#10b981' : '#f59e0b',
                    'extendedProps' => [
                        'booking_id' => $booking->id,
                        'villa_id' => $booking->villa_id,
                        'villa_name' => $booking->villa->name,
                        'user_name' => $booking->user->name,
                        'status' => $booking->status,
                        'total_amount' => $booking->total_amount,
                    ],
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $bookings,
            ]);
        }

        // For regular page requests, return Inertia view
        $villas = \App\Models\Villa::select('id', 'name')->orderBy('name')->get();

        return Inertia::render('Admin/Bookings/Calendar', [
            'villas' => $villas,
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }
}

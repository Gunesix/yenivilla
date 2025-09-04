<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['booking.villa', 'booking.user']);
        
        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Payment method filter
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        $payments = $query->latest()
            ->paginate(15)
            ->withQueryString();
            
        $stats = [
            'total_payments' => Payment::count(),
            'completed_payments' => Payment::where('status', 'completed')->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'failed_payments' => Payment::where('status', 'failed')->count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'monthly_revenue' => Payment::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
        ];
        
        return Inertia::render('Admin/Payments/Index', [
            'payments' => $payments,
            'stats' => $stats,
            'filters' => $request->only(['date_from', 'date_to', 'status', 'payment_method']),
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }
    
    public function show(Payment $payment)
    {
        $payment->load(['booking.villa', 'booking.user']);
        
        return Inertia::render('Admin/Payments/Show', [
            'payment' => $payment,
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }
    
    public function refund(Request $request, Payment $payment)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0.01|max:' . $payment->amount,
        ]);
        
        if ($payment->status !== 'completed') {
            return back()->withErrors(['error' => 'Only completed payments can be refunded.']);
        }
        
        // Here you would integrate with your payment provider's refund API
        // For now, we'll just update the status
        
        $payment->update([
            'status' => 'refunded',
            'refund_reason' => $request->reason,
            'refund_amount' => $request->amount,
            'refunded_at' => now(),
        ]);
        
        // Update booking status if full refund
        if ($request->amount == $payment->amount) {
            $payment->booking->update(['status' => 'cancelled']);
        }
        
        return back()->with('success', 'Payment refunded successfully.');
    }
}
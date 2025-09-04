<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class PaymentController extends Controller
{
    use AuthorizesRequests;
    // Middleware artık route seviyesinde tanımlanacak

    /**
     * Show payment form for a booking
     */
    public function create(Booking $booking)
    {
        $this->authorize('view', $booking);
        
        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.show', $booking)
                ->withErrors(['payment' => 'Payment can only be made for pending bookings.']);
        }

        return Inertia::render('Payments/Create', [
            'booking' => $booking
        ]);
    }

    /**
     * Process payment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|string|in:stripe,paypal,bank_transfer',
            'stripe_token' => 'required_if:payment_method,stripe|string'
        ]);

        $booking = Booking::findOrFail($validated['booking_id']);
        $this->authorize('view', $booking);

        if ($booking->status !== 'pending') {
            return back()->withErrors(['payment' => 'Payment can only be made for pending bookings.']);
        }

        // Create payment record
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'amount' => $booking->total_amount,
            'payment_method' => $validated['payment_method'],
            'transaction_id' => Str::uuid(),
            'status' => 'pending'
        ]);

        try {
            if ($validated['payment_method'] === 'stripe') {
                $this->processStripePayment($payment, $validated['stripe_token']);
            } elseif ($validated['payment_method'] === 'paypal') {
                $this->processPayPalPayment($payment);
            } else {
                // Bank transfer - manual verification needed
                $payment->update(['status' => 'pending']);
            }

            // Update booking status if payment successful
            if ($payment->status === 'completed') {
                $booking->update(['status' => 'confirmed']);
                $payment->update(['processed_at' => now()]);
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'payment' => $payment,
                    'booking' => $booking->fresh()
                ]);
            }

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Payment processed successfully!');

        } catch (\Exception $e) {
            $payment->update([
                'status' => 'failed',
                'transaction_id' => $e->getMessage()
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment failed: ' . $e->getMessage()
                ], 400);
            }

            return back()->withErrors(['payment' => 'Payment failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Process Stripe payment
     */
    private function processStripePayment(Payment $payment, string $stripeToken)
    {
        // Note: This is a simplified implementation
        // In production, you would use the actual Stripe SDK
        
        // Simulate Stripe API call
        $success = rand(1, 10) > 2; // 80% success rate for demo
        
        if ($success) {
            $payment->update([
                'status' => 'completed',
                'stripe_payment_intent_id' => 'pi_' . Str::random(24),
                'transaction_id' => 'txn_' . Str::random(16)
            ]);
        } else {
            throw new \Exception('Stripe payment failed');
        }
    }

    /**
     * Process PayPal payment
     */
    private function processPayPalPayment(Payment $payment)
    {
        // Note: This is a simplified implementation
        // In production, you would use the actual PayPal SDK
        
        // Simulate PayPal API call
        $success = rand(1, 10) > 3; // 70% success rate for demo
        
        if ($success) {
            $payment->update([
                'status' => 'completed',
                'transaction_id' => 'pp_' . Str::random(16)
            ]);
        } else {
            throw new \Exception('PayPal payment failed');
        }
    }

    /**
     * Show payment details
     */
    public function show(Payment $payment)
    {
        $this->authorize('view', $payment->booking);
        
        $payment->load(['booking.villa', 'booking.user']);

        if (request()->wantsJson()) {
            return response()->json($payment);
        }

        return Inertia::render('Payments/Show', [
            'payment' => $payment
        ]);
    }

    /**
     * Refund payment
     */
    public function refund(Payment $payment)
    {
        $this->authorize('update', $payment->booking);

        if ($payment->status !== 'completed') {
            return back()->withErrors(['refund' => 'Only completed payments can be refunded.']);
        }

        try {
            // Process refund based on payment method
            if ($payment->payment_method === 'stripe') {
                $this->processStripeRefund($payment);
            } elseif ($payment->payment_method === 'paypal') {
                $this->processPayPalRefund($payment);
            }

            $payment->update(['status' => 'refunded']);
            $payment->booking->update(['status' => 'cancelled']);

            if (request()->wantsJson()) {
                return response()->json(['message' => 'Payment refunded successfully']);
            }

            return back()->with('success', 'Payment refunded successfully!');

        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Refund failed: ' . $e->getMessage()], 400);
            }

            return back()->withErrors(['refund' => 'Refund failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Process Stripe refund
     */
    private function processStripeRefund(Payment $payment)
    {
        // Simulate Stripe refund API call
        $success = rand(1, 10) > 1; // 90% success rate for demo
        
        if (!$success) {
            throw new \Exception('Stripe refund failed');
        }
    }

    /**
     * Process PayPal refund
     */
    private function processPayPalRefund(Payment $payment)
    {
        // Simulate PayPal refund API call
        $success = rand(1, 10) > 2; // 80% success rate for demo
        
        if (!$success) {
            throw new \Exception('PayPal refund failed');
        }
    }

    /**
     * Webhook handler for payment notifications
     */
    public function webhook(Request $request)
    {
        // Handle payment provider webhooks
        $provider = $request->header('X-Payment-Provider', 'stripe');
        
        if ($provider === 'stripe') {
            return $this->handleStripeWebhook($request);
        } elseif ($provider === 'paypal') {
            return $this->handlePayPalWebhook($request);
        }

        return response()->json(['message' => 'Webhook processed'], 200);
    }

    /**
     * Handle Stripe webhook
     */
    private function handleStripeWebhook(Request $request)
    {
        // Process Stripe webhook events
        $event = $request->all();
        
        // Handle different event types
        switch ($event['type'] ?? '') {
            case 'payment_intent.succeeded':
                // Update payment status
                break;
            case 'payment_intent.payment_failed':
                // Handle failed payment
                break;
        }

        return response()->json(['received' => true]);
    }

    /**
     * Handle PayPal webhook
     */
    private function handlePayPalWebhook(Request $request)
    {
        // Process PayPal webhook events
        $event = $request->all();
        
        // Handle different event types
        switch ($event['event_type'] ?? '') {
            case 'PAYMENT.CAPTURE.COMPLETED':
                // Update payment status
                break;
            case 'PAYMENT.CAPTURE.DENIED':
                // Handle failed payment
                break;
        }

        return response()->json(['received' => true]);
    }
}

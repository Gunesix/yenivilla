<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Villa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;
use Inertia\Inertia;

class BookingController extends Controller
{
    use AuthorizesRequests;
    // Middleware artık route seviyesinde tanımlanacak

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Kullanıcı sadece kendi rezervasyonlarını görebilir
        $bookings = Booking::with(['villa.images', 'payments'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        if ($request->wantsJson()) {
            return response()->json($bookings);
        }

        return Inertia::render('Bookings/Index', [
            'bookings' => $bookings
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $villa = Villa::with(['images', 'amenities'])->findOrFail($request->villa_id);
        $checkIn = $request->check_in;
        $checkOut = $request->check_out;
        $guests = $request->guests;

        // Debug villa data
        

        // Calculate total amount if dates provided
        $totalAmount = 0;
        if ($checkIn && $checkOut) {
            $nights = Carbon::parse($checkIn)->diffInDays(Carbon::parse($checkOut));
            $totalAmount = $villa->price_per_night * $nights;
        }

        return Inertia::render('Bookings/Create', [
            'villa' => $villa,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'guests' => $guests,
            'totalAmount' => $totalAmount
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'villa_id' => 'required|exists:villas,id',
            'check_in' => 'required|date|after:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'nullable|integer|min:1',
            'total_amount' => 'nullable|numeric|min:0'
        ]);

        // Get guest information from authenticated user
        $validated['guest_name'] = Auth::user()->name;
        $validated['guest_email'] = Auth::user()->email;

        $villa = Villa::findOrFail($validated['villa_id']);

        // Use max_guests as default if no guest count provided
        if (empty($validated['guests'])) {
            $validated['guests'] = $villa->max_guests;
        }

        // Check if villa can accommodate guests
        if ($validated['guests'] > $villa->max_guests) {
            return back()->withErrors(['guests' => 'Villa can only accommodate ' . $villa->max_guests . ' guests.']);
        }

        // Check availability - proper date overlap logic
        $conflictingBooking = Booking::where('villa_id', $villa->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($validated) {
                $query->where(function ($q) use ($validated) {
                    // New booking starts during existing booking
                    $q->where('check_in', '<=', $validated['check_in'])
                      ->where('check_out', '>', $validated['check_in']);
                })->orWhere(function ($q) use ($validated) {
                    // New booking ends during existing booking
                    $q->where('check_in', '<', $validated['check_out'])
                      ->where('check_out', '>=', $validated['check_out']);
                })->orWhere(function ($q) use ($validated) {
                    // New booking completely contains existing booking
                    $q->where('check_in', '>=', $validated['check_in'])
                      ->where('check_out', '<=', $validated['check_out']);
                });
            })
            ->exists();

        if ($conflictingBooking) {
            return back()->withErrors(['check_in' => 'Villa is not available for the selected dates.']);
        }

        // Calculate total amount
        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $nights = $checkIn->diffInDays($checkOut);
        $totalAmount = $villa->price_per_night * $nights;

        $validated['user_id'] = Auth::id();
        $validated['total_amount'] = $totalAmount;
        $validated['status'] = 'pending';

        $booking = Booking::create($validated);

        if ($request->wantsJson()) {
            return response()->json($booking->load(['villa', 'user']), 201);
        }

        // For Inertia requests, return with booking data
        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking created successfully! Please proceed with payment.')
            ->with('booking', $booking);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        
        $booking->load(['villa.images', 'villa.amenities', 'user', 'payments']);

        if (request()->wantsJson()) {
            return response()->json($booking);
        }

        return Inertia::render('Bookings/Show', [
            'booking' => $booking
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        $this->authorize('update', $booking);
        
        // Only allow editing if booking is pending
        if ($booking->status !== 'pending') {
            return back()->withErrors(['status' => 'Only pending bookings can be edited.']);
        }

        // Load villa with images and amenities
        $booking->load(['villa.images', 'villa.amenities', 'user']);

        return Inertia::render('Bookings/Edit', [
            'booking' => $booking
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        if ($booking->status !== 'pending') {
            return back()->withErrors(['status' => 'Only pending bookings can be updated.']);
        }

        $validated = $request->validate([
            'check_in' => 'required|date|after:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'nullable|integer|min:1|max:' . $booking->villa->max_guests,
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'nullable|string|max:20',
            'special_requests' => 'nullable|string|max:1000'
        ]);

        // Use max_guests as default if no guest count provided
        if (empty($validated['guests'])) {
            $validated['guests'] = $booking->villa->max_guests;
        }

        // Recalculate total amount if dates changed
        if ($validated['check_in'] !== $booking->check_in->format('Y-m-d') || 
            $validated['check_out'] !== $booking->check_out->format('Y-m-d')) {
            
            $checkIn = Carbon::parse($validated['check_in']);
            $checkOut = Carbon::parse($validated['check_out']);
            $nights = $checkIn->diffInDays($checkOut);
            $validated['total_amount'] = $booking->villa->price_per_night * $nights;
        }

        $booking->update($validated);

        if ($request->wantsJson()) {
            return response()->json($booking->load(['villa', 'user']));
        }

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        // Only allow cancellation if booking is pending or confirmed
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['status' => 'This booking cannot be cancelled.']);
        }

        $booking->update(['status' => 'cancelled']);

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Booking cancelled successfully']);
        }

        return redirect()->route('bookings.index')
            ->with('success', 'Booking cancelled successfully!');
    }

    /**
     * Check villa availability
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'villa_id' => 'required|exists:villas,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in'
        ]);

        $villa = Villa::with(['images', 'amenities', 'reviews.user'])->findOrFail($request->villa_id);

        $available = !Booking::where('villa_id', $request->villa_id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    // New booking starts during existing booking
                    $q->where('check_in', '<=', $request->check_in)
                      ->where('check_out', '>', $request->check_in);
                })->orWhere(function ($q) use ($request) {
                    // New booking ends during existing booking
                    $q->where('check_in', '<', $request->check_out)
                      ->where('check_out', '>=', $request->check_out);
                })->orWhere(function ($q) use ($request) {
                    // New booking completely contains existing booking
                    $q->where('check_in', '>=', $request->check_in)
                      ->where('check_out', '<=', $request->check_out);
                });
            })
            ->exists();

        $availabilityResult = [
            'available' => $available,
            'message' => $available 
                ? 'Villa seçilen tarihler için müsait!' 
                : 'Villa seçilen tarihler için müsait değil.'
        ];

        // Return to villa show page with availability result
        return redirect()->route('villas.show', $villa)
            ->with('availabilityResult', $availabilityResult);
    }
}

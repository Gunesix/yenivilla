<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Villa;
use App\Models\VillaPricing;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class AdminVillaPricingController extends Controller
{
    /**
     * Display pricing for a specific villa
     */
    public function villaIndex(Villa $villa, Request $request)
    {
        $query = VillaPricing::where('villa_id', $villa->id)
            ->orderBy('start_date', 'desc');

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->where('end_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->where('start_date', '<=', $request->date_to);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }

        $pricings = $query->paginate(15);

        return Inertia::render('Admin/Villas/Pricing', [
            'villa' => $villa,
            'pricings' => $pricings,
            'filters' => $request->only(['date_from', 'date_to', 'status']),
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }

    /**
     * Show the form for creating pricing for a specific villa
     */
    public function villaCreate(Villa $villa)
    {
        return Inertia::render('Admin/Villas/PricingCreate', [
            'villa' => $villa,
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }

    /**
     * Display villa pricing management page
     */
    public function index(Request $request)
    {
        // If this is an API request (for AJAX calls), return JSON
        if ($request->expectsJson() || $request->is('*/api/*')) {
            $query = VillaPricing::with(['villa'])
                ->orderBy('start_date', 'desc');

            // Filter by villa
            if ($request->has('villa_id') && $request->villa_id) {
                $query->where('villa_id', $request->villa_id);
            }

            // Filter by date range
            if ($request->has('date_from') && $request->date_from) {
                $query->where('end_date', '>=', $request->date_from);
            }
            if ($request->has('date_to') && $request->date_to) {
                $query->where('start_date', '<=', $request->date_to);
            }

            // Filter by status
            if ($request->has('status') && $request->status !== '') {
                $query->where('is_active', $request->status === 'active');
            }

            $pricings = $query->get();
            return response()->json($pricings);
        }

        $query = VillaPricing::with(['villa'])
            ->orderBy('start_date', 'desc');

        // Filter by villa
        if ($request->has('villa_id') && $request->villa_id) {
            $query->where('villa_id', $request->villa_id);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->where('end_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->where('start_date', '<=', $request->date_to);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }

        $pricings = $query->paginate(15);
        $villas = Villa::select('id', 'title')->orderBy('title')->get();

        return Inertia::render('Admin/VillaPricing/Index', [
            'pricings' => $pricings,
            'villas' => $villas,
            'filters' => $request->only(['villa_id', 'date_from', 'date_to', 'status']),
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }

    /**
     * Show the form for creating a new pricing
     */
    public function create(Request $request)
    {
        $villas = Villa::select('id', 'title', 'price_per_night')->orderBy('title')->get();
        $selectedVilla = null;
        
        if ($request->has('villa_id')) {
            $selectedVilla = Villa::find($request->villa_id);
        }

        return Inertia::render('Admin/VillaPricing/Create', [
            'villas' => $villas,
            'selectedVilla' => $selectedVilla,
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }

    /**
     * Store a newly created pricing
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'villa_id' => 'required|exists:villas,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'price_per_night' => 'required|numeric|min:0',
            'minimum_stay' => 'required|integer|min:1',
            'season_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ]);

        // Check for overlapping pricing
        $pricing = new VillaPricing($validated);
        if ($pricing->hasOverlap($validated['start_date'], $validated['end_date'])) {
            return back()->withErrors([
                'date_range' => 'Bu tarih aralığında zaten bir fiyatlandırma mevcut.'
            ]);
        }

        VillaPricing::create($validated);

        return redirect()->route('admin.villa-pricing.index')
                        ->with('success', 'Fiyatlandırma başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified pricing
     */
    public function edit(VillaPricing $pricing)
    {
        $pricing->load('villa');
        $villas = Villa::select('id', 'title', 'price_per_night')->orderBy('title')->get();

        return Inertia::render('Admin/VillaPricing/Edit', [
            'pricing' => $pricing,
            'villas' => $villas,
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }

    /**
     * Update the specified pricing
     */
    public function update(Request $request, VillaPricing $pricing)
    {
        $validated = $request->validate([
            'villa_id' => 'required|exists:villas,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price_per_night' => 'required|numeric|min:0',
            'minimum_stay' => 'required|integer|min:1',
            'season_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ]);

        // Check for overlapping pricing (excluding current pricing)
        if ($pricing->hasOverlap($validated['start_date'], $validated['end_date'], $pricing->id)) {
            return back()->withErrors([
                'date_range' => 'Bu tarih aralığında zaten bir fiyatlandırma mevcut.'
            ]);
        }

        $pricing->update($validated);

        return redirect()->route('admin.villa-pricing.index')
                        ->with('success', 'Fiyatlandırma başarıyla güncellendi.');
    }

    /**
     * Remove the specified pricing
     */
    public function destroy(VillaPricing $pricing)
    {
        try {
            $pricing->delete();
            
            // Check if request wants JSON response (for AJAX calls)
            if (request()->wantsJson() || request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Fiyatlandırma başarıyla silindi.'
                ]);
            }
            
            return redirect()->route('admin.villa-pricing.index')
                            ->with('success', 'Fiyatlandırma başarıyla silindi.');
        } catch (\Exception $e) {
            if (request()->wantsJson() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fiyatlandırma silinirken bir hata oluştu.'
                ], 500);
            }
            
            return redirect()->route('admin.villa-pricing.index')
                            ->with('error', 'Fiyatlandırma silinirken bir hata oluştu.');
        }
    }

    /**
     * Get pricing calendar data for a villa
     */
    public function calendar(Request $request)
    {
        $villaId = $request->get('villa_id');
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        if (!$villaId) {
            return response()->json(['error' => 'Villa ID is required'], 400);
        }

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $pricings = VillaPricing::where('villa_id', $villaId)
            ->where('is_active', true)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->get();

        $calendarData = [];
        foreach ($pricings as $pricing) {
            $current = Carbon::parse($pricing->start_date);
            $end = Carbon::parse($pricing->end_date);
            
            while ($current <= $end) {
                if ($current >= $startDate && $current <= $endDate) {
                    $calendarData[$current->format('Y-m-d')] = [
                        'price' => $pricing->price_per_night,
                        'season_name' => $pricing->season_name,
                        'minimum_stay' => $pricing->minimum_stay,
                        'pricing_id' => $pricing->id
                    ];
                }
                $current->addDay();
            }
        }

        return response()->json([
            'success' => true,
            'data' => $calendarData
        ]);
    }
}

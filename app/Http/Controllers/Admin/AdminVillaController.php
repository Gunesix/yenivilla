<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Villa;
use App\Models\VillaAmenity;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminVillaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Villa::with(['images', 'amenities']);
        
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $villas = $query->paginate(10);
        
        return Inertia::render('Admin/Villas/Index', [
            'villas' => $villas,
            'filters' => $request->only(['search', 'status']),
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $amenities = VillaAmenity::all();
        
        return Inertia::render('Admin/Villas/Create', [
            'amenities' => $amenities,
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive'
        ]);
        
        $villa = Villa::create($validated);
        
        return redirect()->route('admin.villas.index')
                        ->with('success', 'Villa başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Villa $villa)
    {
        $villa->load(['images', 'amenities', 'bookings.user']);
        
        return Inertia::render('Admin/Villas/Show', [
            'villa' => $villa,
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Villa $villa)
    {
        $villa->load(['images', 'amenities']);
        $amenities = VillaAmenity::all();
        
        return Inertia::render('Admin/Villas/Edit', [
            'villa' => $villa,
            'amenities' => $amenities,
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Villa $villa)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive'
        ]);
        
        $villa->update($validated);
        
        return redirect()->route('admin.villas.index')
                        ->with('success', 'Villa başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Villa $villa)
    {
        $villa->delete();
        
        return redirect()->route('admin.villas.index')
                        ->with('success', 'Villa başarıyla silindi.');
    }
}

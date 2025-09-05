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
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('city', 'like', '%' . $request->search . '%');
        }
        
        if ($request->status) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        $villas = $query->paginate(10)->through(function ($villa) {
            return [
                'id' => $villa->id,
                'name' => $villa->title,
                'location' => $villa->city,
                'max_guests' => $villa->max_guests,
                'bedrooms' => $villa->bedrooms,
                'bathrooms' => $villa->bathrooms,
                'price_per_night' => $villa->price_per_night,
                'status' => $villa->is_active ? 'active' : 'inactive',
                'main_image' => $villa->primary_image,
                'created_at' => $villa->created_at,
                'updated_at' => $villa->updated_at,
            ];
        });
        
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
        $availableAmenities = [
            ['id' => 'pool', 'name' => 'Havuz', 'icon' => '🏊‍♂️'],
            ['id' => 'wifi', 'name' => 'WiFi', 'icon' => '📶'],
            ['id' => 'parking', 'name' => 'Otopark', 'icon' => '🚗'],
            ['id' => 'air_conditioning', 'name' => 'Klima', 'icon' => '❄️'],
            ['id' => 'kitchen', 'name' => 'Mutfak', 'icon' => '🍳'],
            ['id' => 'sea_view', 'name' => 'Deniz Manzarası', 'icon' => '🌊'],
            ['id' => 'garden', 'name' => 'Bahçe', 'icon' => '🌿'],
            ['id' => 'terrace', 'name' => 'Teras', 'icon' => '🏡'],
            ['id' => 'balcony', 'name' => 'Balkon', 'icon' => '🏠'],
            ['id' => 'fireplace', 'name' => 'Şömine', 'icon' => '🔥'],
            ['id' => 'jacuzzi', 'name' => 'Jakuzi', 'icon' => '🛁'],
            ['id' => 'sauna', 'name' => 'Sauna', 'icon' => '🧖‍♀️'],
            ['id' => 'gym', 'name' => 'Spor Salonu', 'icon' => '💪'],
            ['id' => 'bbq', 'name' => 'Barbekü', 'icon' => '🔥'],
            ['id' => 'washing_machine', 'name' => 'Çamaşır Makinesi', 'icon' => '🧺'],
            ['id' => 'dishwasher', 'name' => 'Bulaşık Makinesi', 'icon' => '🍽️'],
            ['id' => 'tv', 'name' => 'Televizyon', 'icon' => '📺'],
            ['id' => 'sound_system', 'name' => 'Ses Sistemi', 'icon' => '🔊'],
            ['id' => 'game_room', 'name' => 'Oyun Odası', 'icon' => '🎮'],
            ['id' => 'library', 'name' => 'Kütüphane', 'icon' => '📚'],
            ['id' => 'office', 'name' => 'Çalışma Odası', 'icon' => '💻'],
            ['id' => 'pet_friendly', 'name' => 'Evcil Hayvan Dostu', 'icon' => '🐕'],
            ['id' => 'smoking_allowed', 'name' => 'Sigara İçilebilir', 'icon' => '🚬'],
            ['id' => 'private_beach', 'name' => 'Özel Plaj', 'icon' => '🏖️'],
            ['id' => 'boat_dock', 'name' => 'Tekne İskelesi', 'icon' => '⛵'],
            ['id' => 'security', 'name' => 'Güvenlik', 'icon' => '🔒'],
            ['id' => 'concierge', 'name' => 'Concierge', 'icon' => '🛎️'],
            ['id' => 'housekeeping', 'name' => 'Temizlik Servisi', 'icon' => '🧹'],
            ['id' => 'chef', 'name' => 'Şef Hizmeti', 'icon' => '👨‍🍳'],
            ['id' => 'spa', 'name' => 'Spa', 'icon' => '💆‍♀️'],
            ['id' => 'vineyard_view', 'name' => 'Bağ Manzarası', 'icon' => '🍇'],
            ['id' => 'mountain_view', 'name' => 'Dağ Manzarası', 'icon' => '⛰️'],
            ['id' => 'city_view', 'name' => 'Şehir Manzarası', 'icon' => '🏙️']
        ];
        
        return Inertia::render('Admin/Villas/Create', [
            'availableAmenities' => $availableAmenities,
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
            'address' => 'nullable|string|max:500',
            'price_per_night' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'area_sqm' => 'nullable|integer|min:0',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'cleaning_fee' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'amenities' => 'array',
            'amenities.*' => 'string'
        ]);
        
        // Extract amenities from validated data
        $amenities = $validated['amenities'] ?? [];
        unset($validated['amenities']);
        
        // Map form fields to database fields
        $villaData = [
            'title' => $validated['name'],
            'description' => $validated['description'],
            'address' => $validated['address'],
            'city' => $validated['location'],
            'country' => 'Türkiye',
            'price_per_night' => $validated['price_per_night'],
            'max_guests' => $validated['max_guests'],
            'bedrooms' => $validated['bedrooms'],
            'bathrooms' => $validated['bathrooms'],
            'area_sqm' => $validated['area_sqm'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'is_active' => $validated['status'] === 'active',
            'owner_id' => 2 // Default admin user
        ];
        
        $villa = Villa::create($villaData);
        
        // Save amenities
        foreach ($amenities as $amenity) {
            VillaAmenity::create([
                'villa_id' => $villa->id,
                'amenity_type' => $amenity,
                'amenity_value' => $amenity
            ]);
        }
        
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
        
        $availableAmenities = [
            ['id' => 'pool', 'name' => 'Havuz', 'icon' => '🏊‍♂️'],
            ['id' => 'wifi', 'name' => 'WiFi', 'icon' => '📶'],
            ['id' => 'parking', 'name' => 'Otopark', 'icon' => '🚗'],
            ['id' => 'air_conditioning', 'name' => 'Klima', 'icon' => '❄️'],
            ['id' => 'kitchen', 'name' => 'Mutfak', 'icon' => '🍳'],
            ['id' => 'sea_view', 'name' => 'Deniz Manzarası', 'icon' => '🌊'],
            ['id' => 'garden', 'name' => 'Bahçe', 'icon' => '🌿'],
            ['id' => 'terrace', 'name' => 'Teras', 'icon' => '🏡'],
            ['id' => 'balcony', 'name' => 'Balkon', 'icon' => '🏠'],
            ['id' => 'fireplace', 'name' => 'Şömine', 'icon' => '🔥'],
            ['id' => 'jacuzzi', 'name' => 'Jakuzi', 'icon' => '🛁'],
            ['id' => 'sauna', 'name' => 'Sauna', 'icon' => '🧖‍♀️'],
            ['id' => 'gym', 'name' => 'Spor Salonu', 'icon' => '💪'],
            ['id' => 'bbq', 'name' => 'Barbekü', 'icon' => '🔥'],
            ['id' => 'washing_machine', 'name' => 'Çamaşır Makinesi', 'icon' => '🧺'],
            ['id' => 'dishwasher', 'name' => 'Bulaşık Makinesi', 'icon' => '🍽️'],
            ['id' => 'tv', 'name' => 'Televizyon', 'icon' => '📺'],
            ['id' => 'sound_system', 'name' => 'Ses Sistemi', 'icon' => '🔊'],
            ['id' => 'game_room', 'name' => 'Oyun Odası', 'icon' => '🎮'],
            ['id' => 'library', 'name' => 'Kütüphane', 'icon' => '📚'],
            ['id' => 'office', 'name' => 'Çalışma Odası', 'icon' => '💻'],
            ['id' => 'pet_friendly', 'name' => 'Evcil Hayvan Dostu', 'icon' => '🐕'],
            ['id' => 'smoking_allowed', 'name' => 'Sigara İçilebilir', 'icon' => '🚬'],
            ['id' => 'private_beach', 'name' => 'Özel Plaj', 'icon' => '🏖️'],
            ['id' => 'boat_dock', 'name' => 'Tekne İskelesi', 'icon' => '⛵'],
            ['id' => 'security', 'name' => 'Güvenlik', 'icon' => '🔒'],
            ['id' => 'concierge', 'name' => 'Concierge', 'icon' => '🛎️'],
            ['id' => 'housekeeping', 'name' => 'Temizlik Servisi', 'icon' => '🧹'],
            ['id' => 'chef', 'name' => 'Şef Hizmeti', 'icon' => '👨‍🍳'],
            ['id' => 'spa', 'name' => 'Spa', 'icon' => '💆‍♀️'],
            ['id' => 'vineyard_view', 'name' => 'Bağ Manzarası', 'icon' => '🍇'],
            ['id' => 'mountain_view', 'name' => 'Dağ Manzarası', 'icon' => '⛰️'],
            ['id' => 'city_view', 'name' => 'Şehir Manzarası', 'icon' => '🏙️']
        ];
        
        return Inertia::render('Admin/Villas/Edit', [
            'villa' => $villa,
            'availableAmenities' => $availableAmenities,
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
        
        // Map form fields to database fields
        $updateData = [
            'title' => $validated['name'],
            'description' => $validated['description'],
            'city' => $validated['location'],
            'price_per_night' => $validated['price_per_night'],
            'max_guests' => $validated['max_guests'],
            'bedrooms' => $validated['bedrooms'],
            'bathrooms' => $validated['bathrooms'],
            'is_active' => $validated['status'] === 'active'
        ];
        
        $villa->update($updateData);
        
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

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Villa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class LocationController extends Controller
{
    /**
     * Show locations page with map
     */
    public function index()
    {
        $cities = Villa::select('city', 'country')
            ->selectRaw('COUNT(*) as villa_count')
            ->selectRaw('AVG(latitude) as avg_latitude')
            ->selectRaw('AVG(longitude) as avg_longitude')
            ->selectRaw('MIN(price_per_night) as min_price')
            ->selectRaw('MAX(price_per_night) as max_price')
            ->where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->groupBy('city', 'country')
            ->orderBy('villa_count', 'desc')
            ->get();

        return Inertia::render('Locations/Index', [
            'cities' => $cities
        ]);
    }

    /**
     * Get all villas with coordinates for map
     */
    public function getVillasForMap(Request $request)
    {
        $query = Villa::with(['images'])
            ->select('id', 'title', 'city', 'country', 'latitude', 'longitude', 'price_per_night')
            ->where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        // Apply filters
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('min_price')) {
            $query->where('price_per_night', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_night', '<=', $request->max_price);
        }

        if ($request->filled('guests')) {
            $query->where('max_guests', '>=', $request->guests);
        }

        // Bounding box filter for map viewport
        if ($request->filled('bounds')) {
            $bounds = $request->bounds;
            $query->whereBetween('latitude', [$bounds['south'], $bounds['north']])
                  ->whereBetween('longitude', [$bounds['west'], $bounds['east']]);
        }

        $villas = $query->get()->map(function ($villa) {
            return [
                'id' => $villa->id,
                'title' => $villa->title,
                'city' => $villa->city,
                'country' => $villa->country,
                'latitude' => (float) $villa->latitude,
                'longitude' => (float) $villa->longitude,
                'price_per_night' => (float) $villa->price_per_night,
                'primary_image' => $villa->primary_image,
                'url' => route('villas.show', $villa->id)
            ];
        });

        return response()->json($villas);
    }

    /**
     * Get popular destinations
     */
    public function getPopularDestinations()
    {
        $destinations = Villa::select('city', 'country')
            ->selectRaw('COUNT(*) as villa_count')
            ->selectRaw('AVG(latitude) as latitude')
            ->selectRaw('AVG(longitude) as longitude')
            ->selectRaw('AVG(price_per_night) as avg_price')
            ->where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->groupBy('city', 'country')
            ->having('villa_count', '>=', 3)
            ->orderBy('villa_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($destination) {
                return [
                    'city' => $destination->city,
                    'country' => $destination->country,
                    'villa_count' => $destination->villa_count,
                    'latitude' => (float) $destination->latitude,
                    'longitude' => (float) $destination->longitude,
                    'avg_price' => (float) $destination->avg_price,
                    'search_url' => route('villas.index', ['location' => $destination->city])
                ];
            });

        return response()->json($destinations);
    }

    /**
     * Search nearby villas
     */
    public function searchNearby(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:1|max:100' // radius in kilometers
        ]);

        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius ?? 10; // default 10km

        // Using Haversine formula to calculate distance
        $villas = Villa::with(['images'])
            ->select('*')
            ->selectRaw(
                '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance',
                [$latitude, $longitude, $latitude]
            )
            ->where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->limit(20)
            ->get();

        return response()->json($villas);
    }

    /**
     * Get nearby activities and points of interest
     */
    public function getNearbyActivities(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        // This would typically integrate with Google Places API or similar
        // For demo purposes, we'll return mock data
        $activities = [
            [
                'id' => 1,
                'name' => 'Historic City Center',
                'type' => 'attraction',
                'distance' => '2.3 km',
                'rating' => 4.5,
                'description' => 'Beautiful historic architecture and local shops'
            ],
            [
                'id' => 2,
                'name' => 'Seaside Restaurant',
                'type' => 'restaurant',
                'distance' => '1.8 km',
                'rating' => 4.7,
                'description' => 'Fresh seafood with ocean views'
            ],
            [
                'id' => 3,
                'name' => 'Adventure Sports Center',
                'type' => 'activity',
                'distance' => '5.2 km',
                'rating' => 4.3,
                'description' => 'Water sports, hiking, and outdoor adventures'
            ],
            [
                'id' => 4,
                'name' => 'Local Beach',
                'type' => 'beach',
                'distance' => '3.1 km',
                'rating' => 4.6,
                'description' => 'Clean sandy beach with crystal clear water'
            ],
            [
                'id' => 5,
                'name' => 'Shopping District',
                'type' => 'shopping',
                'distance' => '4.0 km',
                'rating' => 4.2,
                'description' => 'Local markets and boutique stores'
            ]
        ];

        return response()->json($activities);
    }

    /**
     * Geocode address to coordinates
     */
    public function geocode(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255'
        ]);

        // This would typically use Google Geocoding API
        // For demo purposes, we'll return mock coordinates
        $mockCoordinates = [
            'bodrum' => ['latitude' => 37.0348, 'longitude' => 27.4305],
            'antalya' => ['latitude' => 36.8969, 'longitude' => 30.7133],
            'istanbul' => ['latitude' => 41.0082, 'longitude' => 28.9784],
            'izmir' => ['latitude' => 38.4192, 'longitude' => 27.1287],
            'kas' => ['latitude' => 36.2021, 'longitude' => 29.6417]
        ];

        $address = strtolower($request->address);
        $coordinates = null;

        foreach ($mockCoordinates as $city => $coords) {
            if (strpos($address, $city) !== false) {
                $coordinates = $coords;
                break;
            }
        }

        if (!$coordinates) {
            // Default to Bodrum if no match found
            $coordinates = $mockCoordinates['bodrum'];
        }

        return response()->json([
            'address' => $request->address,
            'latitude' => $coordinates['latitude'],
            'longitude' => $coordinates['longitude'],
            'formatted_address' => ucfirst($address) . ', Turkey'
        ]);
    }
}

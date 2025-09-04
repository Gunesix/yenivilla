<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Villa;
use App\Models\VillaImage;
use App\Models\VillaAmenity;
use App\Http\Requests\StoreVillaRequest;
use App\Http\Resources\VillaResource;
use App\Services\LoggingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class VillaController extends Controller
{
    
    public function __construct()
    {
        // Middleware is now handled at route level in Laravel 11+
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Debug: Log incoming request
        \Log::info('VillaController@index called', [
            'request_data' => $request->all(),
            'wants_json' => $request->wantsJson()
        ]);

        // Use optimized query with eager loading
        $query = Villa::active()
            ->with(['owner', 'images', 'amenities']);

        // Search filters using optimized scopes
        if ($request->filled('q')) {
            $query->search($request->q);
        }
        
        if ($request->filled('location')) {
            $query->inCity($request->location);
        }

        // Date availability filter using optimized scope
        if ($request->filled('check_in') && $request->filled('check_out')) {
            $query->availableBetween($request->check_in, $request->check_out);
        }

        // Price range filter
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->priceRange($request->min_price, $request->max_price);
        }

        // Guest capacity filter
        if ($request->filled('guests')) {
            $query->forGuests($request->guests);
        }

        // Amenities filter using optimized scope
        if ($request->filled('amenities')) {
            $amenities = is_array($request->amenities) ? $request->amenities : explode(',', $request->amenities);
            $query->withAmenities($amenities);
        }
        
        // Location-based search
        if ($request->filled('latitude') && $request->filled('longitude')) {
            $radius = $request->get('radius', 50); // Default 50km radius
            $query->nearLocation($request->latitude, $request->longitude, $radius);
        }
        
        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price_per_night', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price_per_night', 'desc');
                    break;
                case 'guests':
                    $query->orderBy('max_guests', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Debug: Log the SQL query
        \Log::info('Final SQL Query', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        $villas = $query->paginate(12);

        // Debug: Log result count
        \Log::info('Query results', [
            'villa_count' => $villas->count(),
            'total_results' => $villas->total(),
            'current_page' => $villas->currentPage()
        ]);

        if ($request->wantsJson()) {
            return VillaResource::collection($villas)->additional([
                'meta' => [
                    'total' => $villas->total(),
                    'per_page' => $villas->perPage(),
                    'current_page' => $villas->currentPage(),
                    'last_page' => $villas->lastPage(),
                    'from' => $villas->firstItem(),
                    'to' => $villas->lastItem()
                ]
            ]);
        }

        return Inertia::render('Villas/Index', [
            'villas' => $villas,
            'filters' => $request->only(['location', 'check_in', 'check_out', 'guests', 'min_price', 'max_price', 'amenities', 'sort'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Villa::class);
        return Inertia::render('Villas/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVillaRequest $request)
    {
        $this->authorize('create', Villa::class);

        $validated = $request->validated();
        $villa = Villa::create($validated);

        // Handle amenities
        if ($request->has('amenities')) {
            foreach ($request->amenities as $amenity) {
                VillaAmenity::create([
                    'villa_id' => $villa->id,
                    'amenity_type' => $amenity
                ]);
            }
        }

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('villas/' . $villa->id, 'public');
                VillaImage::create([
                    'villa_id' => $villa->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index
                ]);
            }
        }

        if ($request->wantsJson()) {
            return (new VillaResource($villa->load(['images', 'amenities', 'owner'])))
                ->response()
                ->setStatusCode(201);
        }

        return redirect()->route('villas.show', $villa)->with('success', 'Villa created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Villa $villa)
    {
        $villa->load(['images', 'amenities', 'owner', 'reviews.user']);
        
        if (request()->wantsJson()) {
            return new VillaResource($villa);
        }

        return Inertia::render('Villas/Show', [
            'villa' => $villa
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Villa $villa)
    {
        $this->authorize('update', $villa);
        $villa->load(['images', 'amenities']);
        return Inertia::render('Villas/Edit', [
            'villa' => $villa
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Villa $villa)
    {
        $this->authorize('update', $villa);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'price_per_night' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'area_sqm' => 'nullable|integer|min:1',
            'is_active' => 'boolean'
        ]);

        $villa->update($validated);

        if ($request->wantsJson()) {
            return response()->json($villa->load(['images', 'amenities']));
        }

        return redirect()->route('villas.show', $villa)->with('success', 'Villa updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Villa $villa)
    {
        $this->authorize('delete', $villa);

        // Delete associated images from storage
        foreach ($villa->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $villa->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Villa deleted successfully']);
        }

        return redirect()->route('villas.index')->with('success', 'Villa deleted successfully!');
    }

    /**
     * Search villas with advanced filters
     */
    public function search(Request $request)
    {
        $query = Villa::with(['images', 'amenities'])->active();

        if ($request->filled('location')) {
            $query->where(function ($q) use ($request) {
                $q->where('city', 'like', '%' . $request->location . '%')
                  ->orWhere('address', 'like', '%' . $request->location . '%');
            });
        }

        if ($request->filled('check_in') && $request->filled('check_out')) {
            // Check availability
            $query->whereDoesntHave('bookings', function ($q) use ($request) {
                $q->where('status', '!=', 'cancelled')
                  ->where(function ($q) use ($request) {
                      $q->whereBetween('check_in', [$request->check_in, $request->check_out])
                        ->orWhereBetween('check_out', [$request->check_in, $request->check_out])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('check_in', '<=', $request->check_in)
                              ->where('check_out', '>=', $request->check_out);
                        });
                  });
            });
        }

        if ($request->filled('guests')) {
            $query->where('max_guests', '>=', $request->guests);
        }

        if ($request->filled('min_price')) {
            $query->where('price_per_night', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_night', '<=', $request->max_price);
        }

        $villas = $query->paginate(12);

        return response()->json($villas);
    }
}

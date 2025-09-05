<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Villa;
use App\Models\VillaAvailability;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class AdminVillaAvailabilityController extends Controller
{
    /**
     * Display availability for a specific villa
     */
    public function villaIndex(Villa $villa, Request $request)
    {
        $query = VillaAvailability::where('villa_id', $villa->id)
            ->orderBy('date', 'desc');

        // Tarih filtresi
        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        // Durum filtresi
        if ($request->filled('status')) {
            if ($request->status === 'available') {
                $query->where('is_available', true);
            } elseif ($request->status === 'unavailable') {
                $query->where('is_available', false);
            }
        }

        $availabilities = $query->paginate(20);

        return Inertia::render('Admin/Villas/Availability', [
            'villa' => $villa,
            'availabilities' => $availabilities,
            'filters' => $request->only(['start_date', 'end_date', 'status']),
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }

    /**
     * Show the form for creating availability for a specific villa
     */
    public function villaCreate(Villa $villa)
    {
        return Inertia::render('Admin/Villas/AvailabilityCreate', [
            'villa' => $villa,
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }

    public function index(Request $request)
    {
        // Debug log
        \Log::info('AdminVillaAvailabilityController@index called', [
            'request_data' => $request->all(),
            'expects_json' => $request->expectsJson(),
            'is_api' => $request->is('*/api/*')
        ]);
        
        // If this is an API request (for AJAX calls), return JSON
        if ($request->expectsJson() || $request->is('*/api/*')) {
            $query = VillaAvailability::with('villa')
                ->orderBy('date', 'desc');

            // Villa filtresi
            if ($request->filled('villa_id')) {
                $query->where('villa_id', $request->villa_id);
                \Log::info('Filtering by villa_id', ['villa_id' => $request->villa_id]);
            }

            // Tarih filtresi
            if ($request->filled('start_date')) {
                $query->where('date', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->where('date', '<=', $request->end_date);
            }

            // Durum filtresi
            if ($request->filled('status')) {
                if ($request->status === 'available') {
                    $query->where('is_available', true);
                } elseif ($request->status === 'unavailable') {
                    $query->where('is_available', false);
                }
            }

            $availabilities = $query->get();
            
            \Log::info('Availability query results', [
                'count' => $availabilities->count(),
                'data' => $availabilities->toArray()
            ]);
            
            return response()->json($availabilities);
        }

        $query = VillaAvailability::with('villa')
            ->orderBy('date', 'desc');

        // Villa filtresi
        if ($request->filled('villa_id')) {
            $query->where('villa_id', $request->villa_id);
        }

        // Tarih filtresi
        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        // Durum filtresi
        if ($request->filled('status')) {
            if ($request->status === 'available') {
                $query->where('is_available', true);
            } elseif ($request->status === 'unavailable') {
                $query->where('is_available', false);
            }
        }

        $availabilities = $query->paginate(20);
        $villas = Villa::select('id', 'title')->orderBy('title')->get();

        return Inertia::render('Admin/VillaAvailability/Index', [
            'availabilities' => $availabilities,
            'villas' => $villas,
            'filters' => $request->only(['villa_id', 'start_date', 'end_date', 'status'])
        ]);
    }

    public function create(Request $request)
    {
        $villas = Villa::select('id', 'title')->orderBy('title')->get();
        $selectedVilla = null;

        if ($request->filled('villa_id')) {
            $selectedVilla = Villa::find($request->villa_id);
        }

        return Inertia::render('Admin/VillaAvailability/Create', [
            'villas' => $villas,
            'selectedVilla' => $selectedVilla
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'villa_id' => 'required|exists:villas,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_available' => 'required|boolean',
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000'
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Tarih aralığındaki her gün için uygunluk kaydı oluştur
        $dates = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            // Aynı villa ve tarih için mevcut kayıt var mı kontrol et
            $existingAvailability = VillaAvailability::where('villa_id', $request->villa_id)
                ->where('date', $currentDate->format('Y-m-d'))
                ->first();

            if ($existingAvailability) {
                // Mevcut kaydı güncelle
                $existingAvailability->update([
                    'status' => $request->is_available ? 'available' : 'blocked',
                    'is_available' => $request->is_available,
                    'reason' => $request->reason,
                    'notes' => $request->notes
                ]);
            } else {
                // Yeni kayıt oluştur
                VillaAvailability::create([
                    'villa_id' => $request->villa_id,
                    'date' => $currentDate->format('Y-m-d'),
                    'status' => $request->is_available ? 'available' : 'blocked',
                    'is_available' => $request->is_available,
                    'reason' => $request->reason,
                    'notes' => $request->notes
                ]);
            }

            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Villa uygunluk durumu başarıyla kaydedildi. (' . count($dates) . ' gün)',
                'data' => [
                    'dates_processed' => $dates,
                    'villa_id' => $request->villa_id
                ]
            ]);
        }

        return redirect()->route('admin.villa-availability.index')
            ->with('success', 'Villa uygunluk durumu başarıyla kaydedildi. (' . count($dates) . ' gün)');
    }

    public function edit(VillaAvailability $villaAvailability)
    {
        $villas = Villa::select('id', 'title')->orderBy('title')->get();

        return Inertia::render('Admin/VillaAvailability/Edit', [
            'availability' => $villaAvailability->load('villa'),
            'villas' => $villas
        ]);
    }

    public function update(Request $request, VillaAvailability $villaAvailability)
    {
        $request->validate([
            'villa_id' => 'required|exists:villas,id',
            'date' => 'required|date',
            'is_available' => 'required|boolean',
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000'
        ]);

        $villaAvailability->update([
            'villa_id' => $request->villa_id,
            'date' => $request->date,
            'status' => $request->is_available ? 'available' : 'blocked',
            'is_available' => $request->is_available,
            'reason' => $request->reason,
            'notes' => $request->notes
        ]);

        return redirect()->route('admin.villa-availability.index')
            ->with('success', 'Villa uygunluk durumu başarıyla güncellendi.');
    }

    public function destroy(VillaAvailability $villaAvailability)
    {
        try {
            \Log::info('Attempting to delete availability', [
                'id' => $villaAvailability->id,
                'villa_id' => $villaAvailability->villa_id,
                'date' => $villaAvailability->date
            ]);
            
            $deleted = $villaAvailability->delete();
            
            \Log::info('Delete operation result', [
                'deleted' => $deleted,
                'availability_id' => $villaAvailability->id
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Villa uygunluk kaydı başarıyla silindi.',
                    'data' => [
                        'deleted' => $deleted,
                        'id' => $villaAvailability->id
                    ]
                ]);
            }

            return redirect()->route('admin.villa-availability.index')
                ->with('success', 'Villa uygunluk kaydı başarıyla silindi.');
        } catch (\Exception $e) {
            \Log::error('Error deleting availability', [
                'id' => $villaAvailability->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Uygunluk kaydı silinirken bir hata oluştu: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.villa-availability.index')
                ->with('error', 'Uygunluk kaydı silinirken bir hata oluştu.');
        }
    }

    public function calendar(Request $request)
    {
        $villas = Villa::select('id', 'title')->orderBy('title')->get();
        
        // Varsayılan olarak bu ay
        $month = $request->get('month', now()->format('Y-m'));
        $villaId = $request->get('villa_id');
        $status = $request->get('status');

        // Takvim için uygunluk verilerini çek
        $query = VillaAvailability::with('villa:id,title')
            ->whereYear('date', '=', substr($month, 0, 4))
            ->whereMonth('date', '=', substr($month, 5, 2));

        if ($villaId) {
            $query->where('villa_id', $villaId);
        }

        if ($status === 'available') {
            $query->where('is_available', true);
        } elseif ($status === 'unavailable') {
            $query->where('is_available', false);
        }

        $availabilities = $query->get()->map(function ($availability) {
            return [
                'id' => $availability->id,
                'villa_id' => $availability->villa_id,
                'villa_title' => $availability->villa->title,
                'date' => $availability->date,
                'is_available' => $availability->is_available,
                'reason' => $availability->reason,
                'notes' => $availability->notes
            ];
        });

        return Inertia::render('Admin/VillaAvailability/Calendar', [
            'villas' => $villas,
            'availabilities' => $availabilities,
            'filters' => [
                'villa_id' => $villaId,
                'month' => $month,
                'status' => $status
            ]
        ]);
    }
}

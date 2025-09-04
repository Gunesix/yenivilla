<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class AdminLocationController extends Controller
{
    /**
     * Display a listing of locations
     */
    public function index()
    {
        // Mock location data - in real app this would come from database
        $locations = [
            [
                'id' => 1,
                'name' => 'Bodrum',
                'slug' => 'bodrum',
                'description' => 'Ege\'nin incisi Bodrum, tarihi dokusu ve modern yaşam tarzıyla büyüleyici bir tatil destinasyonu.',
                'villa_count' => 45,
                'image' => 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=bodrum%20turkey%20coastal%20town%20aegean%20sea&image_size=landscape_4_3',
                'created_at' => '2024-01-15',
                'updated_at' => '2024-01-15'
            ],
            [
                'id' => 2,
                'name' => 'Antalya',
                'slug' => 'antalya',
                'description' => 'Akdeniz\'in parlayan yıldızı Antalya, antik şehirlerden modern tatil köylerine kadar her türlü tatil deneyimi sunar.',
                'villa_count' => 38,
                'image' => 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=antalya%20old%20town%20harbor%20mediterranean%20architecture&image_size=landscape_4_3',
                'created_at' => '2024-01-15',
                'updated_at' => '2024-01-15'
            ],
            [
                'id' => 3,
                'name' => 'Kaş',
                'slug' => 'kas',
                'description' => 'Sakin ve otantik bir tatil için ideal olan Kaş, doğal güzellikleri ve butik otelleriyle öne çıkar.',
                'villa_count' => 22,
                'image' => 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=kas%20turkey%20small%20coastal%20town%20turquoise%20sea&image_size=landscape_4_3',
                'created_at' => '2024-01-15',
                'updated_at' => '2024-01-15'
            ],
            [
                'id' => 4,
                'name' => 'Çeşme',
                'slug' => 'cesme',
                'description' => 'Ege\'nin rüzgar ve sörf merkezi',
                'villa_count' => 31,
                'image' => 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=cesme%20turkey%20aegean%20coast%20windsurfing%20beach&image_size=landscape_4_3',
                'created_at' => '2024-01-15',
                'updated_at' => '2024-01-15'
            ],
            [
                'id' => 5,
                'name' => 'Fethiye',
                'slug' => 'fethiye',
                'description' => 'Doğa harikası koylar ve plajlar',
                'villa_count' => 28,
                'image' => 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=fethiye%20turkey%20blue%20lagoon%20paragliding&image_size=landscape_4_3',
                'created_at' => '2024-01-15',
                'updated_at' => '2024-01-15'
            ]
        ];

        return Inertia::render('Admin/Locations/Index', [
            'locations' => $locations,
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Lokasyonlar', 'url' => null]
            ]
        ]);
    }

    /**
     * Show the form for creating a new location
     */
    public function create()
    {
        return Inertia::render('Admin/Locations/Create', [
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Lokasyonlar', 'url' => route('admin.locations.index')],
                ['name' => 'Yeni Lokasyon', 'url' => null]
            ]
        ]);
    }

    /**
     * Store a newly created location
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:locations,slug',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // In real app, this would save to database
        // For now, we'll just redirect back with success message
        
        return redirect()->route('admin.locations.index')
            ->with('success', 'Lokasyon başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing a location
     */
    public function edit($id)
    {
        // Mock location data - in real app this would come from database
        $location = [
            'id' => $id,
            'name' => 'Bodrum',
            'slug' => 'bodrum',
            'description' => 'Ege\'nin incisi Bodrum, tarihi dokusu ve modern yaşam tarzıyla büyüleyici bir tatil destinasyonu.',
            'villa_count' => 45,
            'image' => 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=bodrum%20turkey%20coastal%20town%20aegean%20sea&image_size=landscape_4_3'
        ];

        return Inertia::render('Admin/Locations/Edit', [
            'location' => $location,
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Lokasyonlar', 'url' => route('admin.locations.index')],
                ['name' => 'Düzenle: ' . $location['name'], 'url' => null]
            ]
        ]);
    }

    /**
     * Update a location
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:locations,slug,' . $id,
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // In real app, this would update the database record
        
        return redirect()->route('admin.locations.index')
            ->with('success', 'Lokasyon başarıyla güncellendi.');
    }

    /**
     * Remove a location
     */
    public function destroy($id)
    {
        // In real app, this would delete from database
        
        return redirect()->route('admin.locations.index')
            ->with('success', 'Lokasyon başarıyla silindi.');
    }
}
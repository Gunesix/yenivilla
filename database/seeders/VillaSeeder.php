<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Villa;
use App\Models\VillaImage;
use App\Models\VillaAmenity;
use App\Models\User;

class VillaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Önce bir villa sahibi kullanıcısı oluştur
        $owner = User::firstOrCreate(
            ['email' => 'owner@villarentals.com'],
            [
                'name' => 'Villa Sahibi',
                'password' => bcrypt('password'),
                'role' => 'owner',
                'email_verified_at' => now()
            ]
        );

        // Test kullanıcısı oluştur
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test Kullanıcı',
                'password' => bcrypt('password'),
                'email_verified_at' => now()
            ]
        );

        $villas = [
            [
                'title' => 'Lüks Deniz Manzaralı Villa - Bodrum',
                'description' => 'Muhteşem deniz manzaralı, özel havuzlu ve modern tasarımlı lüks villa. Bodrum\'un en prestijli bölgelerinden birinde yer alan bu villa, unutulmaz bir tatil deneyimi sunar.',
                'address' => 'Yalıkavak Mahallesi, Tilkicik Caddesi No:15',
                'city' => 'Bodrum',
                'country' => 'Türkiye',
                'latitude' => 37.1081,
                'longitude' => 27.2612,
                'price_per_night' => 2500.00,
                'max_guests' => 8,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'area_sqm' => 350,
                'amenities' => ['pool', 'wifi', 'parking', 'sea_view', 'air_conditioning', 'kitchen']
            ],
            [
                'title' => 'Modern Villa - Antalya Kalkan',
                'description' => 'Kalkan\'ın eşsiz manzarasına sahip modern villa. Infinity havuz, geniş teraslar ve lüks iç mekan tasarımı ile mükemmel bir tatil.',
                'address' => 'Kalkan Mahallesi, Yalıboyu Mevkii',
                'city' => 'Antalya',
                'country' => 'Türkiye',
                'latitude' => 36.2667,
                'longitude' => 29.6333,
                'price_per_night' => 3200.00,
                'max_guests' => 10,
                'bedrooms' => 5,
                'bathrooms' => 4,
                'area_sqm' => 450,
                'amenities' => ['pool', 'wifi', 'parking', 'sea_view', 'air_conditioning', 'kitchen', 'garden']
            ],
            [
                'title' => 'Şık Villa - Çeşme Alaçatı',
                'description' => 'Alaçatı\'nın rüzgar ve sörf cennetinde, geleneksel Ege mimarisi ile modern konforu birleştiren şık villa.',
                'address' => 'Alaçatı Mahallesi, Kemalpaşa Caddesi No:42',
                'city' => 'Çeşme',
                'country' => 'Türkiye',
                'latitude' => 38.3000,
                'longitude' => 26.3667,
                'price_per_night' => 1800.00,
                'max_guests' => 6,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area_sqm' => 280,
                'amenities' => ['wifi', 'parking', 'air_conditioning', 'kitchen', 'garden', 'terrace']
            ],
            [
                'title' => 'Butik Villa - Kaş',
                'description' => 'Kaş\'ın sakin atmosferinde, Akdeniz\'in turkuaz sularına nazır butik villa. Doğa ile iç içe huzurlu bir tatil.',
                'address' => 'Kaş Merkez, Hastane Caddesi No:28',
                'city' => 'Kaş',
                'country' => 'Türkiye',
                'latitude' => 36.2020,
                'longitude' => 29.6417,
                'price_per_night' => 2200.00,
                'max_guests' => 7,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'area_sqm' => 320,
                'amenities' => ['pool', 'wifi', 'parking', 'sea_view', 'air_conditioning', 'kitchen']
            ],
            [
                'title' => 'Premium Villa - Fethiye Göcek',
                'description' => 'Göcek\'in eşsiz doğası içinde, yat limanına yakın premium villa. Lüks ve konfor bir arada.',
                'address' => 'Göcek Mahallesi, Marina Caddesi No:8',
                'city' => 'Fethiye',
                'country' => 'Türkiye',
                'latitude' => 36.7500,
                'longitude' => 28.9333,
                'price_per_night' => 2800.00,
                'max_guests' => 9,
                'bedrooms' => 4,
                'bathrooms' => 4,
                'area_sqm' => 400,
                'amenities' => ['pool', 'wifi', 'parking', 'sea_view', 'air_conditioning', 'kitchen', 'garden']
            ],
            [
                'title' => 'Lüks Dağ Evi - Kapadokya',
                'description' => 'Kapadokya\'nın büyülü manzarasında, geleneksel taş mimarisi ile modern lüksü birleştiren eşsiz villa.',
                'address' => 'Göreme Mahallesi, Müze Caddesi No:12',
                'city' => 'Nevşehir',
                'country' => 'Türkiye',
                'latitude' => 38.6431,
                'longitude' => 34.8331,
                'price_per_night' => 2000.00,
                'max_guests' => 6,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area_sqm' => 250,
                'amenities' => ['wifi', 'parking', 'air_conditioning', 'kitchen', 'fireplace', 'terrace']
            ],
            [
                'title' => 'Sahil Villa - Datça',
                'description' => 'Datça\'nın sakin koylarında, denize sıfır konumda özel plajlı villa. Doğa ile baş başa huzur.',
                'address' => 'Datça Merkez, Kargı Koyu Mevkii',
                'city' => 'Datça',
                'country' => 'Türkiye',
                'latitude' => 36.7333,
                'longitude' => 27.6833,
                'price_per_night' => 2600.00,
                'max_guests' => 8,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'area_sqm' => 380,
                'amenities' => ['pool', 'wifi', 'parking', 'sea_view', 'air_conditioning', 'kitchen', 'private_beach']
            ],
            [
                'title' => 'Modern Villa - Marmaris',
                'description' => 'Marmaris\'in canlı atmosferinde, marina manzaralı modern villa. Gece hayatına yakın, lüks konaklama.',
                'address' => 'Marmaris Merkez, Barbaros Caddesi No:55',
                'city' => 'Marmaris',
                'country' => 'Türkiye',
                'latitude' => 36.8500,
                'longitude' => 28.2667,
                'price_per_night' => 2100.00,
                'max_guests' => 7,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'area_sqm' => 300,
                'amenities' => ['pool', 'wifi', 'parking', 'sea_view', 'air_conditioning', 'kitchen']
            ],
            [
                'title' => 'Tarihi Villa - Şirince',
                'description' => 'Şirince\'nin tarihi dokusunda, restore edilmiş geleneksel Rum evi. Bağ manzaralı otantik deneyim.',
                'address' => 'Şirince Köyü, Çamlık Sokak No:7',
                'city' => 'İzmir',
                'country' => 'Türkiye',
                'latitude' => 37.9500,
                'longitude' => 27.4500,
                'price_per_night' => 1500.00,
                'max_guests' => 5,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'area_sqm' => 200,
                'amenities' => ['wifi', 'parking', 'air_conditioning', 'kitchen', 'garden', 'vineyard_view']
            ],
            [
                'title' => 'Lüks Koy Villa - Akyaka',
                'description' => 'Akyaka\'nın sakin koyunda, rüzgar sörfü merkezine yakın lüks villa. Doğa sporları tutkunları için ideal.',
                'address' => 'Akyaka Mahallesi, Azmak Nehri Yanı',
                'city' => 'Muğla',
                'country' => 'Türkiye',
                'latitude' => 37.0500,
                'longitude' => 28.3167,
                'price_per_night' => 1900.00,
                'max_guests' => 6,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area_sqm' => 270,
                'amenities' => ['wifi', 'parking', 'air_conditioning', 'kitchen', 'garden', 'river_view']
            ]
        ];

        foreach ($villas as $villaData) {
            $amenities = $villaData['amenities'];
            unset($villaData['amenities']);
            
            $villaData['owner_id'] = $owner->id;
            $villaData['is_active'] = true;
            
            $villa = Villa::create($villaData);
            
            // Amenities ekle
            foreach ($amenities as $amenity) {
                VillaAmenity::create([
                    'villa_id' => $villa->id,
                    'amenity_type' => $amenity,
                    'amenity_value' => $amenity
                ]);
            }
            
            // Örnek resim ekle
            VillaImage::create([
                'villa_id' => $villa->id,
                'image_path' => 'villas/sample-villa-' . $villa->id . '.jpg',
                'is_primary' => true,
                'sort_order' => 0
            ]);
        }
    }
}

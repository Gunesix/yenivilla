<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Villa extends Model
{
    protected $fillable = [
        'owner_id',
        'title',
        'description',
        'address',
        'city',
        'country',
        'latitude',
        'longitude',
        'price_per_night',
        'max_guests',
        'bedrooms',
        'bathrooms',
        'area_sqm',
        'is_active'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'price_per_night' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(VillaImage::class);
    }

    public function amenities(): HasMany
    {
        return $this->hasMany(VillaAmenity::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function pricings(): HasMany
    {
        return $this->hasMany(VillaPricing::class);
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(VillaAvailability::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInCity($query, $city)
    {
        return $query->where('city', 'like', '%' . $city . '%');
    }

    public function scopePriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price_per_night', [$minPrice, $maxPrice]);
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('title', 'like', '%' . $searchTerm . '%')
              ->orWhere('description', 'like', '%' . $searchTerm . '%')
              ->orWhere('city', 'like', '%' . $searchTerm . '%')
              ->orWhere('address', 'like', '%' . $searchTerm . '%');
        });
    }

    public function scopeForGuests($query, $guestCount)
    {
        return $query->where('max_guests', '>=', $guestCount);
    }

    public function scopeAvailableBetween($query, $checkIn, $checkOut)
    {
        return $query->whereDoesntHave('bookings', function ($q) use ($checkIn, $checkOut) {
            $q->where('status', '!=', 'cancelled')
              ->where(function ($q) use ($checkIn, $checkOut) {
                  $q->whereBetween('check_in', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '<=', $checkIn)
                          ->where('check_out', '>=', $checkOut);
                    });
              });
        });
    }

    public function scopeWithAmenities($query, $amenities)
    {
        return $query->whereHas('amenities', function ($q) use ($amenities) {
            $q->whereIn('amenity_type', $amenities);
        });
    }

    public function scopeNearLocation($query, $latitude, $longitude, $radius = 50)
    {
        return $query->selectRaw(
            '*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance',
            [$latitude, $longitude, $latitude]
        )->having('distance', '<=', $radius)->orderBy('distance');
    }

    // Accessors
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getPrimaryImageAttribute()
    {
        $primaryImage = $this->images()->where('is_primary', true)->first();
        if ($primaryImage) {
            return $primaryImage->image_url;
        }
        
        // If no primary image, get first image
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return $firstImage->image_url;
        }
        
        // Return placeholder if no images
        return "https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=luxury%20villa%20{$this->id}&image_size=landscape_16_9";
    }
}

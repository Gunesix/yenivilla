<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class VillaAvailability extends Model
{
    protected $fillable = [
        'villa_id',
        'date',
        'status',
        'is_available',
        'reason',
        'notes',
        'custom_price',
        'minimum_stay'
    ];

    protected $casts = [
        'date' => 'date',
        'custom_price' => 'decimal:2',
        'minimum_stay' => 'integer'
    ];

    /**
     * Get the villa that owns the availability
     */
    public function villa(): BelongsTo
    {
        return $this->belongsTo(Villa::class);
    }

    /**
     * Scope to get available dates
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope to get blocked dates
     */
    public function scopeBlocked($query)
    {
        return $query->where('status', 'blocked');
    }

    /**
     * Scope to get booked dates
     */
    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }

    /**
     * Scope to get dates in range
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Check if villa is available for date range
     */
    public static function isVillaAvailable($villaId, $startDate, $endDate)
    {
        $unavailableDates = static::where('villa_id', $villaId)
            ->whereIn('status', ['blocked', 'booked'])
            ->whereBetween('date', [$startDate, $endDate])
            ->exists();

        return !$unavailableDates;
    }

    /**
     * Get unavailable dates for a villa in date range
     */
    public static function getUnavailableDates($villaId, $startDate, $endDate)
    {
        return static::where('villa_id', $villaId)
            ->whereIn('status', ['blocked', 'booked'])
            ->whereBetween('date', [$startDate, $endDate])
            ->pluck('date')
            ->map(function ($date) {
                return $date->format('Y-m-d');
            })
            ->toArray();
    }

    /**
     * Block dates for a villa
     */
    public static function blockDates($villaId, $dates, $notes = null)
    {
        foreach ($dates as $date) {
            static::updateOrCreate(
                [
                    'villa_id' => $villaId,
                    'date' => $date
                ],
                [
                    'status' => 'blocked',
                    'notes' => $notes
                ]
            );
        }
    }

    /**
     * Mark dates as booked
     */
    public static function markAsBooked($villaId, $dates)
    {
        foreach ($dates as $date) {
            static::updateOrCreate(
                [
                    'villa_id' => $villaId,
                    'date' => $date
                ],
                [
                    'status' => 'booked'
                ]
            );
        }
    }
}

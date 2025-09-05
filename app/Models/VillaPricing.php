<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class VillaPricing extends Model
{
    protected $fillable = [
        'villa_id',
        'start_date',
        'end_date',
        'price_per_night',
        'minimum_stay',
        'season_name',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price_per_night' => 'decimal:2',
        'minimum_stay' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Get the villa that owns the pricing
     */
    public function villa(): BelongsTo
    {
        return $this->belongsTo(Villa::class);
    }

    /**
     * Scope to get active pricings
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get pricings for a specific date range
     */
    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->where('start_date', '<=', $endDate)
              ->where('end_date', '>=', $startDate);
        });
    }

    /**
     * Get pricing for a specific villa and date
     */
    public static function getPricingForDate($villaId, $date)
    {
        return static::where('villa_id', $villaId)
            ->active()
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first();
    }

    /**
     * Check if dates overlap with existing pricing
     */
    public function hasOverlap($startDate, $endDate, $excludeId = null)
    {
        $query = static::where('villa_id', $this->villa_id)
            ->active()
            ->where(function ($q) use ($startDate, $endDate) {
                $q->where('start_date', '<=', $endDate)
                  ->where('end_date', '>=', $startDate);
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}

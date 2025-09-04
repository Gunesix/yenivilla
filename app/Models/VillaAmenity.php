<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillaAmenity extends Model
{
    protected $fillable = [
        'villa_id',
        'amenity_type',
        'amenity_value'
    ];

    // Relationships
    public function villa(): BelongsTo
    {
        return $this->belongsTo(Villa::class);
    }
}

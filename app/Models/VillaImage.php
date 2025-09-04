<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillaImage extends Model
{
    protected $fillable = [
        'villa_id',
        'image_path',
        'is_primary',
        'sort_order'
    ];

    protected $casts = [
        'is_primary' => 'boolean'
    ];

    // Relationships
    public function villa(): BelongsTo
    {
        return $this->belongsTo(Villa::class);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            // Check if it's already a full URL
            if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
                return $this->image_path;
            }
            // Return storage URL for local files
            return asset('storage/' . $this->image_path);
        }
        
        // Return placeholder image if no image_path
        return "https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=luxury%20villa%20{$this->villa_id}&image_size=landscape_16_9";
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'villa_id',
        'user_id',
        'booking_id',
        'rating',
        'title',
        'comment',
        'is_verified',
        'is_approved'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'integer',
        'is_verified' => 'boolean',
        'is_approved' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the villa that owns the review.
     */
    public function villa(): BelongsTo
    {
        return $this->belongsTo(Villa::class);
    }

    /**
     * Get the user that owns the review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the booking that owns the review.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Scope for approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for verified reviews
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for reviews with specific rating
     */
    public function scopeWithRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope for reviews with rating above
     */
    public function scopeWithRatingAbove($query, $rating)
    {
        return $query->where('rating', '>=', $rating);
    }

    /**
     * Get star rating as string
     */
    public function getStarRatingAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Check if review is positive (4-5 stars)
     */
    public function isPositive(): bool
    {
        return $this->rating >= 4;
    }

    /**
     * Check if review is negative (1-2 stars)
     */
    public function isNegative(): bool
    {
        return $this->rating <= 2;
    }
}
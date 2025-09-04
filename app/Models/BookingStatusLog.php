<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingStatusLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_id',
        'old_status',
        'new_status',
        'changed_by',
        'reason',
    ];

    /**
     * Get the booking that this log belongs to.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the admin user who made the change.
     */
    public function changedBy()
    {
        return $this->belongsTo(AdminUser::class, 'changed_by');
    }
}
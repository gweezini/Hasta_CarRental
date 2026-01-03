<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inspection extends Model
{
    protected $fillable = [
        'booking_id',
        'type',
        'fuel_level',
        'mileage',
        'remarks',
        'photo_front',
        'photo_back',
        'photo_left',
        'photo_right',
        'photo_dashboard',
        'damage_photo',
        'damage_description',
        'created_by',
    ];

    // Removed casts for 'photos' as it's no longer JSON
    // protected $casts = [
    //     'photos' => 'array',
    // ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

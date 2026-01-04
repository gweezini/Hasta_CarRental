<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Claim extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'claim_date_time' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {

        return $this->belongsTo(User::class, 'matric_staff_id', 'matric_staff_id');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $guarded = [];

    /**
     * Cast date/time fields to Carbon instances for view formatting.
     */
    protected $casts = [
        'pickup_date_time' => 'datetime',
        'return_date_time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(Feedback::class);
    }

    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class);
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Calculation Helpers
     */
    public function getTotalFinesAttribute()
    {
        return $this->fines->sum('amount');
    }

    /**
     * Fines that still need to be covered by the deposit or paid manually.
     */
    public function getUnsettledFinesAmountAttribute()
    {
        return $this->fines->where('status', '!=', 'Paid')->sum('amount');
    }

    public function getNetRefundAttribute()
    {
        // For display after return, we show RM 0 if fully consumed, or the net amount.
        // If already returned, we should technically show what was returned, but for now 
        // we use unsettled fines for the calculation phase.
        return max(0, $this->deposit_amount - $this->unsettled_fines_amount);
    }

    public function getOutstandingBalanceAttribute()
    {
        return max(0, $this->unsettled_fines_amount - $this->deposit_amount);
    }
}

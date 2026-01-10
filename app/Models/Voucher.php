<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    // These are the fields we allow to be saved
    protected $fillable = [
        'code',
        'name',
        'type',   // 'percent' or 'fixed'
        'value',  // e.g. 10.00
        'is_active',
        'user_id',
        'single_use',
        'uses_remaining',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'matric_staff_id');
    }

    // Backwards-compatibility accessor: some controllers reference $voucher->amount
    public function getAmountAttribute()
    {
        return $this->attributes['value'] ?? null;
    }

    public function getLabelAttribute()
    {
        if ($this->type === 'percent') return $this->value . '% Off';
        if ($this->type === 'fixed') return 'RM ' . number_format($this->value, 2) . ' Off';
        return $this->value . ' (custom)';
    }
}
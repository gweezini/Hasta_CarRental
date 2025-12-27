<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    protected $fillable = [
    'brand',
    'model',
    'plate_number',
    'year',
    'capacity',
    'price_per_hour',
    'status',
    'type_id',
    'road_tax_expiry',
    'insurance_expiry',
    'is_hasta_owned',
    'vehicle_id_custom',
    'current_fuel_bars', 
    'vehicle_image',     
    'car_owner_id'      
];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function carOwner(): BelongsTo
    {
        return $this->belongsTo(CarOwner::class, 'car_owner_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'type_id', 'code');
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class);
    }
}
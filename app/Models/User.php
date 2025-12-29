<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; 
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        
        // --- ID & Contact ---
        'matric_staff_id',  // Stores Matric ID (Student) or Staff ID (Admin)
        'nric_passport',
        'phone_number',
        
        // --- Role & Status ---
        'role',             // 'customer', 'admin', 'topmanagement'
        'is_blacklisted',   // From ERD (Boolean)

        // --- Student Specific (Nullable for Staff) ---
        'driving_license',  // Renamed from 'license_number' to match ERD 'DrivingLicense'
        'address',          // From ERD 'CustomerAddress'
        'college_id',
        'faculty_id',

        'matric_card_path',
        'driving_license_path',
        'nric_passport_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_blacklisted' => 'boolean', // Ensures this is treated as true/false
        ];
    }

    // --- HELPER FUNCTIONS (Use these in your Controllers/Views) ---

    // Is this a Student?
    public function isCustomer() {
        return $this->role === 'customer';
    }

    // Is this a normal Staff/Worker?
    public function isAdmin() {
        return $this->role === 'admin';
    }

    // Is this the Big Boss (Financial Reports)?
    public function isTopManagement() {
        return $this->role === 'topmanagement';
    }
    
    // Check if user is ANY kind of staff (Admin OR Manager)
    public function isStaff() {
        return in_array($this->role, ['admin', 'topmanagement']);
    }

    // --- RELATIONSHIPS ---

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class, 'college_id');
    }

    public function loyaltyCard(): HasOne
    {
        return $this->hasOne(LoyaltyCard::class);
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }

  // In app/Models/User.php

// 1. Add connection for Vouchers
// Relationship: A User can have many Vouchers (via the 'user_vouchers' wallet table)
   public function vouchers()
    {
        return $this->hasMany(\App\Models\Voucher::class);
    }
}

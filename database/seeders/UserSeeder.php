<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CUSTOMER (Ali Student)
        // Must fill all the ERD fields: Emergency, Address, License, etc.
        User::create([
            'name' => 'Ali Student',
            'email' => 'student@utm.my',
            'password' => Hash::make('12345'),
            'role' => 'customer',
            
            // ID & Contact
            'matric_staff_id' => 'A19EC0001',
            'nric_passport' => '010101-01-1111',
            'phone_number' => '012-3456789',
            
            // Student Specifics
            'driving_license' => 'D-12345678', // Matching your new Model
            'address' => 'Kolej Tun Razak, UTM Skudai',
            'college_id' => 1, // Ensure CollegeSeeder runs before this!
            
            // Emergency Info
            'emergency_name' => 'Abu Father',
            'emergency_contact' => '019-9999999',
            'emergency_relationship' => 'Father',
            
            'is_blacklisted' => false,
        ]);

        // 2. ADMIN (Staff Aiman - Normal Worker)
        // Only needs basic info + Staff ID
        User::create([
            'name' => 'Staff Aiman',
            'email' => 'admin@utm.my',
            'password' => Hash::make('12345'),
            'role' => 'admin',
            
            'matric_staff_id' => 'S001',
            'nric_passport' => '880101-01-2222',
            'phone_number' => '011-1111111',
            
            // We don't need license/emergency/college for staff, so we skip them.
        ]);

        // 3. TOP MANAGEMENT (Madam Boss - Financial Reports)
        User::create([
            'name' => 'Madam Boss',
            'email' => 'boss@utm.my',
            'password' => Hash::make('12345'),
            'role' => 'topmanagement',
            
            'matric_staff_id' => 'M001',
            'nric_passport' => '770101-01-3333',
            'phone_number' => '019-9999999',
        ]);
    }
}
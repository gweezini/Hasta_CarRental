<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. The Admin
        User::firstOrCreate([
            'email' => 'admin@utm.my'
        ], [
            'name' => 'Admin Aiman',
            'matric_staff_id' => 'S12345',
            'nric_passport' => '990101-01-1234',
            'phone_number' => '012-3456789',
            'role' => 'admin',
            'college_id' => 1, 
            'password' => Hash::make('12345'),
            'license_number' => 'D_ADMIN_001', // <--- ADDED THIS
        ]);

        // 2. The Student
        User::firstOrCreate([
            'email' => 'student@utm.my'
        ], [
            'name' => 'Ali Student',
            'matric_staff_id' => 'A21EC0001',
            'nric_passport' => '020202-01-5678',
            'phone_number' => '019-8765432',
            'role' => 'student',
            'college_id' => 2,
            'password' => Hash::make('12344'),
            'license_number' => 'D_STUDENT_001', // <--- ADDED THIS
        ]);
    }
}
<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        $faculties = [
            'FAKULTI ALAM BINA & UKUR',
            'FAKULTI KECERDASAN BUATAN',
            'FAKULTI KEJURUTERAAN AWAM',
            'FAKULTI KEJURUTERAAN ELEKTRIK',
            'FAKULTI KEJURUTERAAN KIMIA',
            'FAKULTI KEJURUTERAAN KIMIA DAN KEJURUTERAAN TENAGA',
            'FAKULTI KEJURUTERAAN MEKANIKAL',
            'FAKULTI KOMPUTERAN',
            'FAKULTI PENGURUSAN',
            'FAKULTI SAINS',
            'FAKULTI SAINS PENDIDIKAN DAN TEKNOLOGI',
            'FAKULTI SAINS SOSIAL DAN KEMANUSIAAN',
        ];

        foreach ($faculties as $facultyName) {
            Faculty::create(['name' => $facultyName]);
        }
    }
}
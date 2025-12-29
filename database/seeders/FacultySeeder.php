<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        $faculties = [
            'Faculty of Built Environment and Surveying',
            'Faculty of Artificial Intelligence',
            'Faculty of Civil Engineering',
            'Faculty of Electrical Engineering',
            'Faculty of Chemical Engineering',
            'Faculty of Chemical and Energy Engineering',
            'Faculty of Mechanical Engineering',
            'Faculty of Computing',
            'Faculty of Management',
            'Faculty of Science',
            'Faculty of Educational Sciences and Technology',
            'Faculty of Social Sciences and Humanities',
        ];

        foreach ($faculties as $facultyName) {
            Faculty::create(['name' => $facultyName]);
        }
    }
}
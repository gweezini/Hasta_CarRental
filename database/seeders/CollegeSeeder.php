<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CollegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colleges = ['KTC', 'KTHO', 'KDOJ', 'KDSE', 'KP', 'KTR', 'KTDI', 'KRP', 'KTF', 'K9K10' ];
        foreach ($colleges as $college){
            \App\Models\College::create(['name'=>$college]);
        }
    }
}

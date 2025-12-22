<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $vehicles = [
        ['V001', 'MCP 6113', 'PERODUA', 'AXIA', 2014, 1, 8, 'Available', 1],
        ['V002', 'JQU 1957', 'PERODUA', 'AXIA', 2015, 1, 9, 'Available', 1],
        ['V003', 'NDD 7803', 'PERODUA', 'AXIA', 2016, 1, 7, 'Available', 1],
        ['V004', 'CEX 5224', 'PERODUA', 'AXIA', 2024, 1, 9, 'Available', 1],
        ['V005', 'UTM 3365', 'PERODUA', 'AXIA', 2024, 1, 8, 'Available', 1],
        ['V006', 'JPN 1416', 'PERODUA', 'MYVI', 2013, 1, 7, 'Available', 3],
        ['V007', 'VC 6522', 'PERODUA', 'MYVI', 2016, 1, 6, 'Available', 3],
        ['V008', 'UTM 3655', 'PERODUA', 'BEZZA', 2023, 1, 9, 'Available', 2],
        ['V009', 'UTM 3057', 'PERODUA', 'BEZZA', 2023, 1, 7, 'Available', 2],
        ['V010', 'QRP 5205', 'HONDA', 'DASH 125', 2021, 1, 5, 'Available', 5],
        ['V011', 'JWD 9496', 'HONDA', 'BEAT 110', 2023, 1, 6, 'Available', 5],
    ];

    foreach ($vehicles as $v) {
        \App\Models\Vehicle::create([
            'vehicle_id_custom' => $v[0],
            'plate_number' => $v[1],
            'brand' => $v[2],
            'model' => $v[3],
            'year' => $v[4],
            'is_hasta_owned' => $v[5],
            'current_fuel_bars' => $v[6],
            'status' => $v[7],
            'type_id' => $v[8],
        ]);
    }
}
}

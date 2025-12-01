<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomsSeeder extends Seeder
{
    public function run(): void
    {
         $rooms = [
            ['room_number' => 'IT101', 'building_name' => 'IT Building', 'capacity' => 40, 'type'=>'Lecture'],
            ['room_number' => 'IT102', 'building_name' => 'IT Building', 'capacity' => 40, 'type'=>'Lecture'],
            ['room_number' => 'IT103', 'building_name' => 'IT Building', 'capacity' => 30, 'type'=>'Lecture'],
            ['room_number' => 'IT104', 'building_name' => 'IT Building', 'capacity' => 30, 'type'=>'Lecture'],
            ['room_number' => 'IT105', 'building_name' => 'IT Building', 'capacity' => 30, 'type'=>'Lecture'],
            ['room_number' => 'IT-L1', 'building_name' => 'IT Building', 'capacity' => 25, 'type'=>'Laboratory'],
            ['room_number' => 'IT-L2', 'building_name' => 'IT Building', 'capacity' => 25, 'type'=>'Laboratory'],

            // Business Building rooms + labs
            ['room_number' => 'BUS201', 'building_name' => 'Business Building', 'capacity' => 50, 'type'=>'Lecture'],
            ['room_number' => 'BUS202', 'building_name' => 'Business Building', 'capacity' => 40, 'type'=>'Lecture'],
            ['room_number' => 'BUS203', 'building_name' => 'Business Building', 'capacity' => 35, 'type'=>'Lecture'],
            ['room_number' => 'BUS204', 'building_name' => 'Business Building', 'capacity' => 35, 'type'=>'Lecture'],
            ['room_number' => 'BUS205', 'building_name' => 'Business Building', 'capacity' => 35, 'type'=>'Lecture'],
            ['room_number' => 'BUS-L1', 'building_name' => 'Business Building', 'capacity' => 20, 'type'=>'Laboratory'],
            ['room_number' => 'BUS-L2', 'building_name' => 'Business Building', 'capacity' => 20, 'type'=>'Laboratory'],

            // Online
            ['room_number' => 'ONLINE-001', 'building_name' => 'Online', 'capacity' => 999, 'type'=>'Online'],
        ];

        foreach ($rooms as $r) {
            DB::table('rooms')->insert(array_merge($r, ['created_at' => now(), 'updated_at' => now()]));
        }
    }
}

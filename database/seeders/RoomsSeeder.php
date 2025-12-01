<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomsSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'room_number' => '101',
                'building_name' => 'Old Building',
                'capacity' => 35,
                'type' => 'Lecture',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => '102',
                'building_name' => 'Old Building',
                'capacity' => 40,
                'type' => 'Lecture',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => '201',
                'building_name' => 'Old Building',
                'capacity' => 25,
                'type' => 'Laboratory',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => '202',
                'building_name' => 'Old Building',
                'capacity' => 30,
                'type' => 'Laboratory',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => '301',
                'building_name' => 'New Building',
                'capacity' => 40,
                'type' => 'Lecture',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => '302',
                'building_name' => 'New Building',
                'capacity' => 30,
                'type' => 'Lecture',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => '303',
                'building_name' => 'New Building',
                'capacity' => 25,
                'type' => 'Laboratory',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => '304',
                'building_name' => 'New Building',
                'capacity' => 30,
                'type' => 'Laboratory',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => '401',
                'building_name' => 'Tech Building',
                'capacity' => 45,
                'type' => 'Lecture',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_number' => '402',
                'building_name' => 'Tech Building',
                'capacity' => 25,
                'type' => 'Laboratory',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('rooms')->insert($rooms);

        $this->command->info('✅ RoomsSeeder: inserted ' . count($rooms) . ' rooms.');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SemesterSeeder extends Seeder
{
    public function run()
    {
        $semesters = [
            [
                'name' => '1st Semester',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '2nd Semester',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Summer',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($semesters as $semester) {
            DB::table('semesters')->updateOrInsert(
                ['name' => $semester['name']],
                $semester
            );
        }
    }
}

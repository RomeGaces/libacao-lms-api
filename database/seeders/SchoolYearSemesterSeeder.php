<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolYearSemesterSeeder extends Seeder
{
    public function run()
    {
        // Create the School Year 2025–2026
        $schoolYearId = DB::table('school_years')->insertGetId([
            'year_start' => 2025,
            'year_end' => 2026,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create Semesters
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

        DB::table('semesters')->insert($semesters);

        // Set school_settings active values
        DB::table('school_settings')->insert([
            'current_school_year_id' => $schoolYearId,
            'current_semester_id' => DB::table('semesters')->where('name', '1st Semester')->first()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

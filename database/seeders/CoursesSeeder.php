<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesSeeder extends Seeder
{
    public function run(): void
    {
        $dce = DB::table('departments')->where('department_code', 'DCE')->first()->id;
        $da = DB::table('departments')->where('department_code', 'DA')->first()->id;

        DB::table('courses')->insert([
            [
                'course_code' => 'BSIT',
                'course_name' => 'Bachelor of Science in Information Technology',
                'description' => 'BSIT program',
                'duration_years' => 4,
                'department_id' => $dce,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'course_code' => 'BSCS',
                'course_name' => 'Bachelor of Science in Computer Science',
                'description' => 'BSCS program',
                'duration_years' => 4,
                'department_id' => $dce,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'course_code' => 'BSA',
                'course_name' => 'Bachelor of Science in Accountancy',
                'description' => 'BSA program',
                'duration_years' => 4,
                'department_id' => $da,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}

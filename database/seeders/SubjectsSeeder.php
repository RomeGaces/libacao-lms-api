<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SubjectsSeeder extends Seeder
{
      public function run(): void
    {
        // Try to find an existing course, or create one if none exist
        $course = DB::table('courses')->where('course_id', 1)->first();

        // Now safely reference its course_id
        $subjects = [
            [
                'course_id' => $course->course_id,
                'subject_code' => 'IT-101',
                'subject_name' => 'Programming Basics',
                'description' => 'Programming Basics',
                'units' => 3,
                'semester' => '1st',
                'year_level' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_id' => $course->course_id,
                'subject_code' => 'MATH17',
                'subject_name' => 'Basic Algebra',
                'description' => 'Basic Algebra',
                'units' => 3,
                'semester' => '1st',
                'year_level' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('subjects')->insert($subjects);

        $this->command->info('✅ SubjectsSeeder: inserted ' . count($subjects) . ' subjects under ' . $course->course_name);
    }
}
 
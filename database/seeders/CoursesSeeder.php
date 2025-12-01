<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'course_code'    => 'BSIT',
                'course_name'    => 'Bachelor of Science in Information Technology',
                'description'    => 'Focuses on software development, networking, and systems administration.',
                'duration_years' => 4,
                'department_id'  => 1, // e.g., College of Computing
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'course_code'    => 'BSCS',
                'course_name'    => 'Bachelor of Science in Computer Science',
                'description'    => 'Emphasizes algorithms, data structures, and computer theory.',
                'duration_years' => 4,
                'department_id'  => 1, // same department as BSIT
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'course_code'    => 'BSA',
                'course_name'    => 'Bachelor of Science in Accountancy',
                'description'    => 'Prepares students for CPA licensure and financial management careers.',
                'duration_years' => 4,
                'department_id'  => 2, // e.g., College of Business
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'course_code'    => 'BSBA',
                'course_name'    => 'Bachelor of Science in Business Administration',
                'description'    => 'Covers entrepreneurship, marketing, and management principles.',
                'duration_years' => 4,
                'department_id'  => 2,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'course_code'    => 'BEED',
                'course_name'    => 'Bachelor of Elementary Education',
                'description'    => 'Trains students to teach at the elementary education level.',
                'duration_years' => 4,
                'department_id'  => 3, // e.g., College of Education
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'course_code'    => 'BSN',
                'course_name'    => 'Bachelor of Science in Nursing',
                'description'    => 'Focuses on healthcare, patient care, and medical science.',
                'duration_years' => 4,
                'department_id'  => 4, // e.g., College of Health Sciences
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ];

        DB::table('courses')->insert($courses);

        $this->command->info('✅ CoursesSeeder: inserted ' . count($courses) . ' courses.');
    }
}

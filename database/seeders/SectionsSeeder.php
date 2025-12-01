<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionsSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            // BSIT (course_id = 1)
            ['section_name' => 'I-IT1', 'course_id' => 1, 'academic_year' => '2025-2026', 'semester' => '1st'],
            ['section_name' => 'I-IT2', 'course_id' => 1, 'academic_year' => '2025-2026', 'semester' => '1st'],
            ['section_name' => 'II-IT1', 'course_id' => 1, 'academic_year' => '2025-2026', 'semester' => '1st'],
            ['section_name' => 'III-IT1', 'course_id' => 1, 'academic_year' => '2025-2026', 'semester' => '1st'],

            // BSCS (course_id = 2)
            ['section_name' => 'I-CS1', 'course_id' => 2, 'academic_year' => '2025-2026', 'semester' => '1st'],
            ['section_name' => 'I-CS2', 'course_id' => 2, 'academic_year' => '2025-2026', 'semester' => '1st'],
            ['section_name' => 'II-CS1', 'course_id' => 2, 'academic_year' => '2025-2026', 'semester' => '1st'],
            ['section_name' => 'III-CS1', 'course_id' => 2, 'academic_year' => '2025-2026', 'semester' => '1st'],

            // BSA (course_id = 3)
            ['section_name' => 'I-BA1', 'course_id' => 3, 'academic_year' => '2025-2026', 'semester' => '1st'],
            ['section_name' => 'II-BA1', 'course_id' => 3, 'academic_year' => '2025-2026', 'semester' => '1st'],
            ['section_name' => 'III-BA1', 'course_id' => 4, 'academic_year' => '2025-2026', 'semester' => '1st'],
            ['section_name' => 'III-BA2', 'course_id' => 4, 'academic_year' => '2025-2026', 'semester' => '1st'],

            // BSECE (course_id = 4)
            ['section_name' => 'I-BEED1', 'course_id' => 5, 'academic_year' => '2025-2026', 'semester' => '1st'],
            ['section_name' => 'II-BEED1', 'course_id' => 5, 'academic_year' => '2025-2026', 'semester' => '1st'],
            ['section_name' => 'III-BSN1', 'course_id' => 6, 'academic_year' => '2025-2026', 'semester' => '1st'],
            ['section_name' => 'IV-BSN1', 'course_id' => 6, 'academic_year' => '2025-2026', 'semester' => '1st'],
        ];

        // Add timestamps automatically
        $sections = array_map(fn($section) => array_merge($section, [
            'created_at' => now(),
            'updated_at' => now(),
        ]), $sections);

        DB::table('class_sections')->insert($sections);
        $this->command->info('✅ SectionsSeeder: inserted ' . count($sections) . ' class sections.');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassSectionsSeeder extends Seeder
{
    public function run()
    {
        $courses = DB::table('courses')->get();

        foreach ($courses as $course) {
            for ($year=1;$year<=4;$year++) {
                $sectionName = match ($course->course_code) {
                    'BSIT' => ($year === 1 ? 'I-IT1' : ($year === 2 ? 'II-IT1' : ($year === 3 ? 'III-IT1' : 'IV-IT1'))),
                    'BSCS' => ($year === 1 ? 'I-CS1' : ($year === 2 ? 'II-CS1' : ($year === 3 ? 'III-CS1' : 'IV-CS1'))),
                    'BSA'  => ($year === 1 ? 'I-BA1' : ($year === 2 ? 'II-BA1' : ($year === 3 ? 'III-BA1' : 'IV-BA1'))),
                    default => $course->course_code . '-Y' . $year
                };

                DB::table('class_sections')->insert([
                    'course_id' => $course->id,
                    'school_year_id' => DB::table('school_years')->orderBy('id','desc')->first()->id ?? 1,
                    'semester_id' => DB::table('semesters')->first()->id ?? 1,
                    'year_level' => $year,
                    'section_name' => $sectionName,
                    'capacity' => 35,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

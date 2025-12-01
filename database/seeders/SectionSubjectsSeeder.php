<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSubjectsSeeder extends Seeder
{
    public function run()
    {
        $sections = DB::table('class_sections')->get();

        foreach ($sections as $sec) {
            // get subjects for that course, year_level and semester (both 1st & 2nd)
            $subjects = DB::table('subjects')
                ->where('course_id', $sec->course_id)
                ->where('year_level', $sec->year_level)
                ->get();

            foreach ($subjects as $s) {
                DB::table('section_subjects')->insert([
                    'section_id' => $sec->id,
                    'subject_id' => $s->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

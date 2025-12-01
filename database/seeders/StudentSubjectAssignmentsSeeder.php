<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSubjectAssignmentsSeeder extends Seeder
{
    public function run()
    {
        $students = DB::table('students')->get();

        foreach ($students as $stu) {
            // get their section (we assigned class_sections by course and year; pick matching one)
            $section = DB::table('class_sections')
                ->where('course_id', $stu->course_id)
                ->first();

            if (!$section) continue;

            $subjects = DB::table('section_subjects')
                ->where('section_id', $section->id)
                ->get();

            foreach ($subjects as $s) {
                DB::table('student_subject_assignments')->insert([
                    'student_id' => $stu->id,
                    'subject_id' => $s->subject_id,
                    'class_section_id' => $section->id,
                    'status' => 'Enrolled',
                    'grade' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

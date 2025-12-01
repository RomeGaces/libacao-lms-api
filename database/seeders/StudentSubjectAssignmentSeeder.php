<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentSubjectAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = DB::table('students')->pluck('student_id');
        $subjects = DB::table('subjects')->pluck('subject_id');
        $sections = DB::table('class_sections')->pluck('class_section_id');

        if ($students->isEmpty() || $subjects->isEmpty() || $sections->isEmpty()) {
            $this->command->warn('⚠️ Skipping StudentSubjectAssignmentSeeder: dependencies missing.');
            return;
        }

        $assignments = [];

        foreach ($students as $studentId) {
            // assign each student 2–3 subjects across random sections
            for ($i = 0; $i < rand(2, 3); $i++) {
                $assignments[] = [
                    'student_id'       => $studentId,
                    'subject_id'       => $subjects->random(),
                    'class_section_id' => $sections->random(),
                    'status'           => 'enrolled',
                    'grade'            => 'N/A',
                    'created_at'       => Carbon::now(),
                    'updated_at'       => Carbon::now(),
                ];
            }
        }

        DB::table('student_subject_assignments')->insert($assignments);

        $this->command->info('✅ StudentSubjectAssignmentSeeder: inserted ' . count($assignments) . ' assignments.');
    }
}

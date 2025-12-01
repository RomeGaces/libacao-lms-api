<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SubjectsSeeder extends Seeder
{
      public function run(): void
    {
        $bsit = DB::table('courses')->where('course_code','BSIT')->first()->id;
        $bscs = DB::table('courses')->where('course_code','BSCS')->first()->id;
        $bsa = DB::table('courses')->where('course_code','BSA')->first()->id;

        $subjects = [
            // BSIT (year_level, semester)
            ['course_id'=>$bsit,'subject_code'=>'IT101','subject_name'=>'Introduction to Computing','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'1st','year_level'=>1],
            ['course_id'=>$bsit,'subject_code'=>'IT102','subject_name'=>'Computer Programming I','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'1st','year_level'=>1],
            ['course_id'=>$bsit,'subject_code'=>'IT103','subject_name'=>'Discrete Math for IT','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'2nd','year_level'=>1],
            ['course_id'=>$bsit,'subject_code'=>'IT201','subject_name'=>'Data Structures','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'1st','year_level'=>2],
            ['course_id'=>$bsit,'subject_code'=>'IT203','subject_name'=>'Object-Oriented Programming','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'2nd','year_level'=>2],
            ['course_id'=>$bsit,'subject_code'=>'IT205','subject_name'=>'Database Management Systems','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'1st','year_level'=>2],
            ['course_id'=>$bsit,'subject_code'=>'IT301','subject_name'=>'Web Systems and Technologies','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'1st','year_level'=>3],
            ['course_id'=>$bsit,'subject_code'=>'IT302','subject_name'=>'Systems Integration & Architecture','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'2nd','year_level'=>3],
            ['course_id'=>$bsit,'subject_code'=>'IT401','subject_name'=>'IT Capstone Project','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'2nd','year_level'=>4],
            ['course_id'=>$bsit,'subject_code'=>'IT303','subject_name'=>'Software Testing and QA','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'2nd','year_level'=>3],

            // BSCS
            ['course_id'=>$bscs,'subject_code'=>'CS101','subject_name'=>'Discrete Structures','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'1st','year_level'=>1],
            ['course_id'=>$bscs,'subject_code'=>'CS102','subject_name'=>'Programming Fundamentals','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'1st','year_level'=>1],
            ['course_id'=>$bscs,'subject_code'=>'CS201','subject_name'=>'Algorithms and Complexity','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'2nd','year_level'=>2],
            ['course_id'=>$bscs,'subject_code'=>'CS202','subject_name'=>'Computer Architecture','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'1st','year_level'=>2],
            ['course_id'=>$bscs,'subject_code'=>'CS301','subject_name'=>'Operating Systems','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'1st','year_level'=>3],
            ['course_id'=>$bscs,'subject_code'=>'CS302','subject_name'=>'Software Engineering','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'2nd','year_level'=>3],
            ['course_id'=>$bscs,'subject_code'=>'CS401','subject_name'=>'CS Capstone Project','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'2nd','year_level'=>4],
            ['course_id'=>$bscs,'subject_code'=>'CS303','subject_name'=>'Theory of Computation','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'2nd','year_level'=>3],

            // BSA (accountancy)
            ['course_id'=>$bsa,'subject_code'=>'ACC101','subject_name'=>'Fundamentals of Accounting','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'1st','year_level'=>1],
            ['course_id'=>$bsa,'subject_code'=>'ACC102','subject_name'=>'Financial Accounting I','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'1st','year_level'=>1],
            ['course_id'=>$bsa,'subject_code'=>'ACC201','subject_name'=>'Cost Accounting','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'1st','year_level'=>2],
            ['course_id'=>$bsa,'subject_code'=>'ACC301','subject_name'=>'Auditing I','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'2nd','year_level'=>3],
            ['course_id'=>$bsa,'subject_code'=>'ACC302','subject_name'=>'Taxation','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'2nd','year_level'=>3],
            ['course_id'=>$bsa,'subject_code'=>'ACC401','subject_name'=>'Accountancy Capstone','units'=>3,'type'=>'Lecture','hours_per_week'=>3,'semester'=>'2nd','year_level'=>4],
        ];

        foreach ($subjects as $s) {
            DB::table('subjects')->insert(array_merge($s,['created_at'=>now(),'updated_at'=>now()]));
        }

        $this->command->info('✅ SubjectsSeeder: inserted ' . count($subjects) );
    }
}
 
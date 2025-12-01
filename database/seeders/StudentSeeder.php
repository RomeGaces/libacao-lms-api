<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Student; // ensure you have a Student model using HasFactory

class StudentSeeder extends Seeder
{
    public function run()
    {
        // Get course IDs
        $bsit = DB::table('courses')->where('course_code', 'BSIT')->value('id');
        $bscs = DB::table('courses')->where('course_code', 'BSCS')->value('id');
        $bsa  = DB::table('courses')->where('course_code', 'BSA')->value('id');

        // Get active school year
        $activeSY = DB::table('school_years')->where('is_active', true)->value('id');

        if (!$activeSY) {
            throw new \Exception("No active school year found. Run SchoolYearSeeder first.");
        }

        // generate 25-35 students per course
        $counts = [
            $bsit => rand(25, 35),
            $bscs => rand(25, 35),
            $bsa  => rand(25, 35),
        ];

        foreach ($counts as $courseId => $count) {
            for ($i = 0; $i < $count; $i++) {

                // Create factory instance as array
                $student = Student::factory()->make([
                    'course_id' => $courseId,
                    'school_year_id' => $activeSY,
                ])->toArray();

                DB::table('students')->insert($student);
            }
        }
    }
}

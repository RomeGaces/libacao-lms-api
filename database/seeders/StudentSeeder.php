<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            [
                'student_number'   => '2025-10199',
                'first_name'       => 'Jose',
                'middle_name'      => 'Baraon',
                'last_name'        => 'Manalo',
                'gender'           => 'Male',
                'birth_date'       => '2015-06-02',
                'email'            => 'jmanalo@libacao.edu',
                'phone_number'     => '+639273182311',
                'address'          => 'Moon',
                'enrollment_date'  => '2025-10-06',
                'status'           => 'Enrolled',
                'course_id'        => 1,
            ],
            [
                'student_number'   => '2025-10200',
                'first_name'       => 'Maria',
                'middle_name'      => 'Reyes',
                'last_name'        => 'Lopez',
                'gender'           => 'Female',
                'birth_date'       => '2014-09-12',
                'email'            => 'mlopez@libacao.edu',
                'phone_number'     => '+639278512312',
                'address'          => 'Sunrise Village',
                'enrollment_date'  => '2025-10-06',
                'status'           => 'Enrolled',
                'course_id'        => 1,
            ],
            [
                'student_number'   => '2025-10201',
                'first_name'       => 'Antonio',
                'middle_name'      => 'Cruz',
                'last_name'        => 'Dela Vega',
                'gender'           => 'Male',
                'birth_date'       => '2014-11-25',
                'email'            => 'adelavega@libacao.edu',
                'phone_number'     => '+639175623842',
                'address'          => 'Brgy. Mabini',
                'enrollment_date'  => '2025-10-06',
                'status'           => 'Enrolled',
                'course_id'        => 2,
            ],
            [
                'student_number'   => '2025-10202',
                'first_name'       => 'Sofia',
                'middle_name'      => 'Lagman',
                'last_name'        => 'Torres',
                'gender'           => 'Female',
                'birth_date'       => '2015-02-18',
                'email'            => 'storres@libacao.edu',
                'phone_number'     => '+639272319991',
                'address'          => 'Sta. Lucia',
                'enrollment_date'  => '2025-10-06',
                'status'           => 'Enrolled',
                'course_id'        => 2,
            ],
            [
                'student_number'   => '2025-10203',
                'first_name'       => 'Rafael',
                'middle_name'      => 'Gomez',
                'last_name'        => 'Santos',
                'gender'           => 'Male',
                'birth_date'       => '2015-07-21',
                'email'            => 'rsantos@libacao.edu',
                'phone_number'     => '+639381234556',
                'address'          => 'Newtown',
                'enrollment_date'  => '2025-10-06',
                'status'           => 'Enrolled',
                'course_id'        => 2,
            ],
            [
                'student_number'   => '2025-10204',
                'first_name'       => 'Angela',
                'middle_name'      => 'Ramos',
                'last_name'        => 'Valdez',
                'gender'           => 'Female',
                'birth_date'       => '2015-03-10',
                'email'            => 'avaldez@libacao.edu',
                'phone_number'     => '+639274112988',
                'address'          => 'Riverside',
                'enrollment_date'  => '2025-10-06',
                'status'           => 'Enrolled',
                'course_id'        => 3,
            ],
            [
                'student_number'   => '2025-10205',
                'first_name'       => 'Daniel',
                'middle_name'      => 'Mendoza',
                'last_name'        => 'Garcia',
                'gender'           => 'Male',
                'birth_date'       => '2014-08-29',
                'email'            => 'dgarcia@libacao.edu',
                'phone_number'     => '+639276623456',
                'address'          => 'Greenfield',
                'enrollment_date'  => '2025-10-06',
                'status'           => 'Enrolled',
                'course_id'        => 1,
            ],
            [
                'student_number'   => '2025-10206',
                'first_name'       => 'Patricia',
                'middle_name'      => 'Dionisio',
                'last_name'        => 'Fernandez',
                'gender'           => 'Female',
                'birth_date'       => '2015-01-30',
                'email'            => 'pfernandez@libacao.edu',
                'phone_number'     => '+639178822311',
                'address'          => 'Northview Subd.',
                'enrollment_date'  => '2025-10-06',
                'status'           => 'Enrolled',
                'course_id'        => 2,
            ],
            [
                'student_number'   => '2025-10207',
                'first_name'       => 'Mark',
                'middle_name'      => 'Aquino',
                'last_name'        => 'Villanueva',
                'gender'           => 'Male',
                'birth_date'       => '2014-10-14',
                'email'            => 'mvillanueva@libacao.edu',
                'phone_number'     => '+639271561922',
                'address'          => 'West Hills',
                'enrollment_date'  => '2025-10-06',
                'status'           => 'Enrolled',
                'course_id'        => 3,
            ],
            [
                'student_number'   => '2025-10208',
                'first_name'       => 'Clarisse',
                'middle_name'      => 'Navarro',
                'last_name'        => 'Castro',
                'gender'           => 'Female',
                'birth_date'       => '2015-05-23',
                'email'            => 'ccastro@libacao.edu',
                'phone_number'     => '+639287734455',
                'address'          => 'Hilltop',
                'enrollment_date'  => '2025-10-06',
                'status'           => 'Enrolled',
                'course_id'        => 2,
            ],
        ];

        foreach ($students as &$student) {
            $student['created_at'] = Carbon::now();
            $student['updated_at'] = Carbon::now();
        }

        DB::table('students')->insert($students);
        $this->command->info('✅ StudentsSeeder: inserted ' . count($students) . ' students.');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;


class ProfessorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dce = DB::table('departments')->where('department_code', 'DCE')->first()->id;
        $da = DB::table('departments')->where('department_code', 'DA')->first()->id;

        $professors = [
            [
                'first_name'     => 'Mark',
                'last_name'      => 'Sarmiento',
                'middle_name'    => 'Antaran',
                'gender'         => 'Male',
                'email'          => 'mark.sarmiento@libacao-university.edu',
                'phone_number'   => '09171234567',
                'hire_date'      => Carbon::parse('2018-06-15'),
                'specialization' => 'Mathematics',
                'status'         => 'active',
                'department_id'  => $dce, // adjust to an existing department_id
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'first_name'     => 'Bigeh',
                'last_name'      => 'Agamin',
                'middle_name'    => 'Altavano',
                'gender'         => 'Male',
                'email'          => 'bigeh.agamin@libacao-university.edu',
                'phone_number'   => '09981234567',
                'hire_date'      => Carbon::parse('2020-01-10'),
                'specialization' => 'Computer Science',
                'status'         => 'active',
                'department_id'  => $dce,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'first_name'     => 'Jerome',
                'last_name'      => 'Gaces',
                'middle_name'    => 'Capili',
                'gender'         => 'Male',
                'email'          => 'jerome.gaces@libacao-university.edu',
                'phone_number'   => '09081234567',
                'hire_date'      => Carbon::parse('2015-09-01'),
                'specialization' => 'Physics',
                'status'         => 'active',
                'department_id'  => $da,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'first_name' => 'Alvin',
                'last_name' => 'Reyes',
                'middle_name' => 'Santos',
                'gender'         => 'Male',
                'email' => 'alvin.reyes@libacao-university.edu',
                'phone_number' => '09171234560',
                'hire_date'      => Carbon::parse('2015-06-01'),
                'specialization' => 'Software Engineering',
                'status'         => 'active',
                'department_id'  => $dce, // adjust to an existing department_id
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'first_name' => 'Celine',
                'last_name' => 'Tolentino',
                'middle_name' => 'Madrigal',
                'gender' => 'Female',
                'email' => 'celine.tolentino@libacao-university.edu',
                'phone_number' => '09171234561',
                'hire_date'      => Carbon::parse('2016-07-15'),
                'specialization' => 'Data Structures',
                'status'         => 'active',
                'department_id'  => $dce, // adjust to an existing department_id
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'first_name' => 'Marie Ann',
                'last_name' => 'Gomez',
                'middle_name' => 'Alvarez',
                'gender' => 'Female',
                'email' => 'marie.gomez@libacao-university.edu',
                'phone_number' => '09171234562',
                'hire_date'      => Carbon::parse('2014-03-10'),
                'specialization' => 'Database Systems',
                'status'         => 'active',
                'department_id'  => $dce, // adjust to an existing department_id
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'first_name' => 'Joseph', 
                'last_name' => 'Bautista', 
                'middle_name' => 'Rodriguez', 
                'gender' => 'Male', 
                'email' => 'joseph.bautista@libacao-university.edu', 
                'phone_number' => '09171234563', 
                'hire_date'      => Carbon::parse('2013-01-20'),
                'specialization' => 'Accounting',
                'status'         => 'active',
                'department_id'  => $da, // adjust to an existing department_id
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
             [
                'first_name' => 'Rafael', 
                'last_name' => 'Aquino', 
                'middle_name' => 'Legarda', 
                'gender' => 'Male', 
                'email' => 'rafael.aquino@libacao-university.edu', 
                'phone_number' => '09171234564',
                'hire_date'      => Carbon::parse('2018-09-01'),
                'specialization' => 'Auditing',
                'status'         => 'active',
                'department_id'  => $da, // adjust to an existing department_id
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
               'first_name' => 'Hannah', 
               'last_name' => 'Lee', 
               'middle_name' => 'Kim', 
               'gender' => 'Female', 
               'email' => 'hannah.lee@libacao-university.edu', 
               'phone_number' => '09171234565',
                'hire_date'      => Carbon::parse('2019-08-01'),
                'specialization' => 'Computer Science',
                'status'         => 'active',
                'department_id'  => $dce, // adjust to an existing department_id
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ];


        DB::table('professors')->insert($professors);
        $this->command->info('✅ ProfessorsSeeder: inserted ' . count($professors) . ' professors.');
    }
}

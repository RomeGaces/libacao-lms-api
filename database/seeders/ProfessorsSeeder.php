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
                'department_id'  => 1, // adjust to an existing department_id
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
                'department_id'  => 2,
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
                'status'         => 'inactive',
                'department_id'  => 3,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ];


        DB::table('professors')->insert($professors);
        $this->command->info('✅ ProfessorsSeeder: inserted ' . count($professors) . ' professors.');
    }
}

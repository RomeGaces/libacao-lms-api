<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            [
                'department_code' => 'DCE',
                'department_name' => 'Department of Computer Education',
                'office_location' => 'IT Building 2F',
                'contact_email' => 'dce@school.edu.ph',
                'contact_number' => '+63 2 8888 0001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_code' => 'DA',
                'department_name' => 'Department of Accountancy',
                'office_location' => 'Business Building 1F',
                'contact_email' => 'da@school.edu.ph',
                'contact_number' => '+63 2 8888 0002',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

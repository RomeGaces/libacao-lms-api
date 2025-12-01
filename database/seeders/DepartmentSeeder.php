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
        $departments = [
            ['department_name' => 'Department of Computer Education', 'department_code' => 'DCE'],
            ['department_name' => 'Department of Accounting Studies', 'department_code' => 'DAS'],
            ['department_name' => 'Department of Teacher Education', 'department_code' => 'DTE'],
            ['department_name' => 'College of Health Sciences', 'department_code' => 'DHS'],
        ];

        DB::table('departments')->insert($departments);

        $this->command->info('✅ DepartmentsSeeder: inserted ' . count($departments) . ' departments.');
    }
}

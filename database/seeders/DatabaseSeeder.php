<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,
            ProfessorsSeeder::class,
            UserSeeder::class,
            CoursesSeeder::class,
            RoomsSeeder::class,
            SubjectsSeeder::class,
            SectionsSeeder::class,
            StudentSeeder::class,
            StudentSubjectAssignmentSeeder::class,
            ClassScheduleSeeder::class
        ]);
    }
}

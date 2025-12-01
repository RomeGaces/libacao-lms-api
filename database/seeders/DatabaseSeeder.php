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
        // Fixed data
        $this->call([
            SchoolYearSemesterSeeder::class,
            DepartmentSeeder::class,
            CoursesSeeder::class,
            RoomsSeeder::class,
            ProfessorsSeeder::class,
            SubjectsSeeder::class,
            UserSeeder::class,
        ]);

        // dynamic/factory-driven
        $this->call([
            // ensure you have at least one school_year and semester seeded manually or via migration seeder
            ClassSectionsSeeder::class,
            SectionSubjectsSeeder::class,
            StudentSeeder::class,
            ClassSchedulesSeeder::class,
            StudentSubjectAssignmentsSeeder::class,
        ]);
    }
}

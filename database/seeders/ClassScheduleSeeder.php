<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClassScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects  = DB::table('subjects')->pluck('subject_id');
        $professors = DB::table('professors')->pluck('professor_id');
        $rooms     = DB::table('rooms')->pluck('room_id');
        $sections  = DB::table('class_sections')->pluck('class_section_id');

        if ($subjects->isEmpty() || $professors->isEmpty() || $rooms->isEmpty() || $sections->isEmpty()) {
            $this->command->warn('⚠️ Skipping ClassScheduleSeeder: dependencies missing.');
            return;
        }

        $schedules = [];
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        foreach ($sections as $sectionId) {
            // create 2–3 schedules per section
            for ($i = 0; $i < rand(2, 3); $i++) {
                $day = $daysOfWeek[array_rand($daysOfWeek)];
                $start = rand(7, 14); // random start hour 7am–2pm
                $end = $start + 2;    // 2-hour class

                $schedules[] = [
                    'subject_id'       => $subjects->random(),
                    'professor_id'     => $professors->random(),
                    'room_id'          => $rooms->random(),
                    'class_section_id' => $sectionId,
                    'day_of_week'      => $day,
                    'start_time'       => sprintf('%02d:00:00', $start),
                    'end_time'         => sprintf('%02d:00:00', $end),
                    'status' =>  'finalized',
                    'created_at'       => Carbon::now(),
                    'updated_at'       => Carbon::now(),
                ];
            }
        }

        DB::table('class_schedules')->insert($schedules);

        $this->command->info('✅ ClassScheduleSeeder: inserted ' . count($schedules) . ' class schedules.');
    }
}

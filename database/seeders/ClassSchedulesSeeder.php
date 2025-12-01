<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class ClassSchedulesSeeder extends Seeder
{
    public function run()
    {
        $timeSlots = [
            ['07:30:00','09:00:00'],
            ['09:15:00','10:45:00'],
            ['11:00:00','12:30:00'],
            ['13:30:00','15:00:00'],
            ['15:15:00','16:45:00'],
        ];

        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday'];

        $professors = DB::table('professors')->pluck('id')->toArray();
        $rooms = DB::table('rooms')->pluck('id')->toArray();

        $sectionSubjects = DB::table('section_subjects')->get();

        // naive scheduler: for each section_subject assign 1 or 2 weekly slots
        foreach ($sectionSubjects as $ss) {
            $subject = DB::table('subjects')->where('id', $ss->subject_id)->first();
            $section = DB::table('class_sections')->where('id', $ss->section_id)->first();

            // decide number of weekly meetings (1 or 2)
            $meetings = rand(1,2);

            $usedSlots = [];

            for ($m=0;$m<$meetings;$m++) {
                // pick day and time slot not used by this section and not conflicting with professor/room
                $attempt = 0;
                do {
                    $attempt++;
                    $day = $days[array_rand($days)];
                    $slot = $timeSlots[array_rand($timeSlots)];
                    $prof = $professors[array_rand($professors)];
                    $room = $rooms[array_rand($rooms)];
                    $start = $slot[0];
                    $end = $slot[1];

                    // check conflicts: same professor, same room, same section on that day/time
                    $conflict = DB::table('class_schedules')
                        ->where('day_of_week', $day)
                        ->where(function($q) use ($prof,$room,$section){
                            $q->where('professor_id',$prof)
                              ->orWhere('room_id',$room)
                              ->orWhere('class_section_id',$section->id);
                        })
                        ->where(function($q) use ($start,$end){
                            $q->whereBetween('start_time', [$start, $end])
                              ->orWhereBetween('end_time', [$start, $end])
                              ->orWhere(function($qq) use ($start,$end){
                                  $qq->where('start_time','<',$start)->where('end_time','>',$start);
                              });
                        })
                        ->exists();

                    if ($attempt > 50) break; // bail out to avoid infinite loop
                } while ($conflict);

                DB::table('class_schedules')->insert([
                    'subject_id' => $ss->subject_id,
                    'professor_id' => $prof,
                    'room_id' => $room,
                    'day_of_week' => $day,
                    'start_time' => $start,
                    'end_time' => $end,
                    'class_section_id' => $ss->section_id,
                    'status' => 'Pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

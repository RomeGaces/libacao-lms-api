<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SchoolYear;
use App\Models\Course;
use App\Models\ClassSection;
use App\Models\Student;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Professor;
use App\Models\Room;
use App\Models\ClassSchedule;
use App\Models\StudentSubjectAssignment;

class AdminController extends Controller
{
    public function summary()
    {
        // 1. Get current active academic year
        $currentAY = SchoolYear::where('is_active', true)->first();

        // 2. Get overall statistics
        $stats = $this->getStats($currentAY?->id);

        // 3. Get breakdown per course
        $courses = $this->getCourseBreakdown($currentAY?->id);

        return response()->json([
            'current_academic_year' => $currentAY,
            'stats' => $stats,
            'courses' => $courses,
        ], 200);
    }

    private function getStats($ayId)
    {
        // If no active AY yet, return zeros
        if (!$ayId) {
            return [
                'total_students' => 0,
                'total_sections' => 0,
                'total_courses' => Course::count(),
            ];
        }

        return [
            'total_students' => Student::where('school_year_id', $ayId)->count(),
            'total_sections' => ClassSection::where('school_year_id', $ayId)->count(),
            'total_courses' => Course::count(),
        ];
    }

    private function getCourseBreakdown($ayId)
    {
        if (!$ayId) {
            // No AY → return empty structure
            return Course::get()->map(function ($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->course_name,
                    'student_count' => 0,
                    'section_count' => 0,
                    'year_levels' => 0,
                ];
            });
        }

        // GROUP BY course
        return Course::get()->map(function ($course) use ($ayId) {

            $studentCount = Student::where('course_id', $course->id)
                ->where('school_year_id', $ayId)
                ->count();

            $sectionCount = ClassSection::where('course_id', $course->id)
                ->where('school_year_id', $ayId)
                ->count();

            $yearLevels = Student::where('course_id', $course->id)
                ->where('school_year_id', $ayId)
                ->distinct()
                ->count('year_level');

            return [
                'id' => $course->id,
                'name' => $course->course_name,
                'student_count' => $studentCount,
                'section_count' => $sectionCount,
                'year_levels' => $yearLevels,
            ];
        });
    }

    public function dryRun(Request $request)
    {
        $validated = $request->validate([
            'academic_year_name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'semester_id' => 'required|integer',
            'dry_run' => 'required|boolean',
            'section_capacity' => 'nullable|integer',
            'advance_academic_year' => 'nullable|boolean',
            // scheduling options
            'fallback_online' => 'nullable|boolean',
            'prof_default_max_load' => 'nullable|integer'
        ]);

        $sectionCapacity = $validated['section_capacity'] ?? config('academic.default_section_capacity', 35);
        $dryRun = (bool) $validated['dry_run'];
        $advanceAY = (bool) ($validated['advance_academic_year'] ?? false);
        $semesterId = $validated['semester_id'];
        $fallbackOnline = (bool) ($validated['fallback_online'] ?? true);
        $profDefaultMaxLoad = (int) ($validated['prof_default_max_load'] ?? config('academic.prof_default_max_load', 10));

        // parse AY name
        [$start, $end] = array_map('trim', explode('-', $validated['academic_year_name']));
        $startYear = intval($start);
        $endYear = intval($end);

        // find current active school year (if any)
        $currentSY = SchoolYear::where('is_active', 1)->first();

        // fetch student counts for upcoming distribution
        $studentCounts = Student::select('course_id', 'year_level', DB::raw('COUNT(*) as total'))
            ->when($currentSY, function ($q) use ($currentSY) {
                $q->where('school_year_id', $currentSY->id);
            })
            ->groupBy('course_id', 'year_level')
            ->get();

        // PREP: timeslots
        $timeslots = $this->getDefaultTimeslots(); // array of timeslot objects with ids

        // Build preview structure and session list
        $preview = [
            'sections' => [],
            'sessions' => [],    // flat list of sessions to be scheduled
            'summary' => [
                'total_courses' => Course::count(),
                'total_students_counted' => $studentCounts->sum('total'),
                'section_capacity' => $sectionCapacity,
                'timeslots_count' => count($timeslots)
            ],
        ];

        // Helper closures
        $getSubjects = function ($courseId, $yearLevel, $semesterId) {
            return Subject::where('course_id', $courseId)
                ->where('year_level', $yearLevel)
                ->where('semester_id', $semesterId)
                ->get();
        };

        $getProfessorsForCourse = function ($course) {
            if (!$course) return collect();
            return Professor::where('department_id', $course->department_id)->get();
        };

        $findRooms = function ($type, $requiredCapacity) {
            return Room::where('type', $type)
                ->where('capacity', '>=', $requiredCapacity)
                ->orderBy('capacity', 'asc')
                ->get();
        };

        // Build sections & sessions preview
        foreach ($studentCounts as $row) {
            $course = Course::find($row->course_id);
            if (!$course) continue;

            $effectiveYear = $row->year_level + ($advanceAY ? 1 : 0);
            if ($effectiveYear < 1) continue;

            $requiredSections = max(1, (int) ceil($row->total / $sectionCapacity));

            for ($s = 0; $s < $requiredSections; $s++) {
                $sectionName = $this->generateSectionName($course, $effectiveYear, $s);

                $subjects = $getSubjects($course->id, $effectiveYear, $semesterId);

                // build subject entries and candidate professors & rooms
                $professorsForCourse = $getProfessorsForCourse($course);
                $subjectEntries = [];
                foreach ($subjects as $sub) {
                    $candidateProfs = $professorsForCourse->map(function ($p) {
                        return [
                            'id' => $p->id,
                            'name' => $p->name,
                            'max_weekly_load' => $p->max_weekly_load ?? null // may be null, default applied later
                        ];
                    })->values()->all();

                    $rooms = $findRooms($sub->room_type ?? 'Lecture', $sectionCapacity)->map(function ($r) {
                        return ['id' => $r->id, 'name' => $r->name, 'capacity' => $r->capacity, 'type' => $r->type];
                    })->values()->all();

                    // keep subject session template
                    $subjectEntries[] = [
                        'subject_id' => $sub->id,
                        'subject_code' => $sub->code ?? $sub->title ?? 'SUB' . $sub->id,
                        'title' => $sub->title ?? '',
                        'room_type' => $sub->room_type ?? 'Lecture',
                        'candidate_professors' => $candidateProfs,
                        'candidate_rooms' => $rooms, // could be empty
                    ];
                }

                // create preview section
                $sectionPreview = [
                    'course_id' => $course->id,
                    'course_code' => $course->course_code,
                    'year_level' => $effectiveYear,
                    'name' => $sectionName,
                    'capacity' => $sectionCapacity,
                    'student_estimate' => (int) ceil($row->total / $requiredSections),
                    'subjects' => $subjectEntries,
                ];

                $preview['sections'][] = $sectionPreview;

                // flatten sessions: one session per subject per section
                foreach ($subjectEntries as $subEntry) {
                    $preview['sessions'][] = [
                        'course_id' => $course->id,
                        'course_code' => $course->course_code,
                        'year_level' => $effectiveYear,
                        'section_name' => $sectionName,
                        'capacity' => $sectionCapacity,
                        'subject_id' => $subEntry['subject_id'],
                        'subject_code' => $subEntry['subject_code'],
                        'room_type' => $subEntry['room_type'],
                        'candidate_professors' => $subEntry['candidate_professors'],
                        'candidate_rooms' => $subEntry['candidate_rooms']
                    ];
                }
            }
        }


        // Now attempt to schedule sessions (dry-run uses the solver to propose an assignment)
        $solverResult = $this->solveScheduling($preview['sessions'], $timeslots, [
            'fallback_online' => $fallbackOnline,
            'prof_default_max_load' => $profDefaultMaxLoad,
        ]);

        $preview['schedule_proposal'] = $solverResult;

        if ($dryRun) {
            return response()->json([
                'preview' => $preview,
                'message' => 'Dry run complete (including schedule suggestion).'
            ]);
        }

        // EXECUTE: create DB records in transaction, but only if solver succeeded fully or we allow partial
        $result = DB::transaction(function () use (
            $startYear,
            $endYear,
            $semesterId,
            $preview,
            $sectionCapacity,
            $currentSY,
            $advanceAY,
            $solverResult
        ) {
            // fail early if solver couldn't schedule all sessions
            if (!$solverResult['success']) {
                // abort transaction by throwing an exception with details
                throw new \Exception('Scheduling conflict: not all sessions could be scheduled. See solver result for details.');
            }

            // deactivate current AY if advance requested
            if ($currentSY && $advanceAY) {
                $currentSY->update(['is_active' => 0]);
            }

            // create or get target SY
            $newSY = SchoolYear::firstOrCreate(
                ['year_start' => $startYear, 'year_end' => $endYear],
                ['is_active' => $advanceAY ? 1 : 0]
            );
            if ($advanceAY && !$newSY->is_active) {
                $newSY->update(['is_active' => 1]);
            }

            // ACTIVATE NEW SEMESTER
            Semester::query()->update(['is_active' => false]);
            Semester::where('id', $semesterId)->update(['is_active' => true]);

            // create sections and persist mapping of section name => model
            $sectionMap = []; // section_name => model
            foreach ($preview['sections'] as $s) {
                $model = ClassSection::create([
                    'section_name' => $s['name'],
                    'course_id' => $s['course_id'],
                    'year_level' => $s['year_level'],
                    'school_year_id' => $newSY->id,
                    'semester_id' => $semesterId,
                    'capacity' => $s['capacity'],
                ]);
                $sectionMap[$s['name']] = $model;
            }

            // promote students if requested and update school_year_id
            if ($advanceAY && $currentSY) {
                Student::where('school_year_id', $currentSY->id)->increment('year_level');
                Student::where('school_year_id', $currentSY->id)->update(['school_year_id' => $newSY->id]);
            } elseif ($currentSY && $currentSY->id !== $newSY->id) {
                Student::where('school_year_id', $currentSY->id)->update(['school_year_id' => $newSY->id]);
            }

            // // assign students to sections evenly
            // // group sections by (course_id, year_level)
            // $sectionsByGroup = [];
            // foreach ($sectionMap as $sname => $model) {
            //     $key = $model->course_id . '|' . $model->year_level;
            //     $sectionsByGroup[$key][] = $model;
            // }
            // foreach ($sectionsByGroup as $key => $sections) {
            //     [$courseId, $yr] = explode('|', $key);
            //     $students = Student::where('course_id', $courseId)
            //         ->where('year_level', $yr)
            //         ->where('school_year_id', $newSY->id)
            //         ->get();
            //     $idx = 0;
            //     foreach ($students as $stu) {
            //         $target = $sections[$idx % count($sections)];
            //         // assumes student has class_section_id column
            //         $stu->class_section_id = $target->id;
            //         $stu->save();
            //         $idx++;
            //     }
            // }

            // Assign students to subjects inside their correct class section
            foreach ($preview['sections'] as $sec) {

                // Find the DB model for this section
                $sectionModel = $sectionMap[$sec['name']];

                // Get all students who belong to this course + year level
                $students = Student::where('course_id', $sec['course_id'])
                    ->where('year_level', $sec['year_level'])
                    ->where('school_year_id', $newSY->id)
                    ->get();

                foreach ($students as $student) {

                    foreach ($sec['subjects'] as $subjectEntry) {

                        StudentSubjectAssignment::create([
                            'student_id'        => $student->id,
                            'subject_id'        => $subjectEntry['subject_id'],
                            'class_section_id'  => $sectionModel->id,
                            'status'            => 'enrolled'
                        ]);
                    }
                }
            }

            // persist schedules using solver assignment
            foreach ($solverResult['assignment'] as $assign) {
                // find target section model by name
                $sectionModel = $sectionMap[$assign['section_name']] ?? null;
                if (!$sectionModel) continue;

                ClassSchedule::create([
                    'class_section_id' => $sectionModel->id,
                    'subject_id' => $assign['subject_id'],
                    'professor_id' => $assign['professor_id'] ?? null,
                    'room_id' => $assign['room_id'] ?? null,
                    'day_of_week' => $assign['timeslot']['day'],
                    'start_time' => $assign['timeslot']['start'],
                    'end_time' => $assign['timeslot']['end'],
                    'is_online' => $assign['is_online'] ?? false,
                ]);
            }

            return [
                'school_year' => $newSY,
                'sections_created_count' => count($sectionMap),
                'schedules_created' => count($solverResult['assignment']),
            ];
        });

        return response()->json([
            'message' => 'Master Setup executed successfully',
            'result' => $result
        ]);
    }

    protected function solveScheduling(array $sessions, array $timeslots, array $options = [])
    {
        $fallbackOnline = $options['fallback_online'] ?? true;
        $profDefaultMaxLoad = $options['prof_default_max_load'] ?? 10;

        $nSessions = count($sessions);
        if ($nSessions === 0) {
            return ['success' => true, 'assignment' => [], 'unschedulable' => []];
        }

        // Build professor pool and initial loads (from DB current schedules for the target semester/week)
        // We'll use simple weekly counts: count current ClassSchedule records for professor in same semester (if you have semester tagging)
        $profLoads = []; // profId => assignedCount (current + assigned)
        $profMaxLoad = []; // profId => max

        // gather all professor ids present in candidate lists
        $allProfessorIds = [];
        foreach ($sessions as $sess) {
            foreach ($sess['candidate_professors'] as $p) {
                if (!in_array($p['id'], $allProfessorIds)) $allProfessorIds[] = $p['id'];
            }
        }

        // initialize counts from DB
        foreach ($allProfessorIds as $pid) {
            $existingCount = ClassSchedule::where('professor_id', $pid)->count(); // approximate weekly load baseline
            $profLoads[$pid] = $existingCount;
            // try to get professor max_load from DB (column 'max_weekly_load') else from candidate or default
            $prof = Professor::find($pid);
            $profMaxLoad[$pid] = $prof->max_weekly_load ?? $profDefaultMaxLoad;
        }

        // room booking map: timeslotIndex => [roomId => true]
        $roomBooked = [];
        // professor booking map: timeslotIndex => [profId => true]
        $profBooked = [];

        // assignment result per session index
        $assignment = array_fill(0, $nSessions, null);
        $unschedulable = [];

        // Precompute candidate data for sessions to speed up solver:
        $sessionCandidates = [];
        for ($i = 0; $i < $nSessions; $i++) {
            $sess = $sessions[$i];
            // candidate prof ids and their max loads
            $candidates = [];
            foreach ($sess['candidate_professors'] as $p) {
                $pid = $p['id'];
                $max = $p['max_weekly_load'] ?? ($profMaxLoad[$pid] ?? $profDefaultMaxLoad);
                $candidates[] = ['id' => $pid, 'max' => $max];
            }
            // candidate rooms list
            $roomCandidates = array_column($sess['candidate_rooms'], null);
            // if no rooms, we can optionally push 'online' as a candidate (room_id=null, is_online true)
            if (empty($roomCandidates) && $fallbackOnline) {
                $roomCandidates[] = null; // null represents online
            }
            $sessionCandidates[$i] = [
                'professors' => $candidates,
                'rooms' => $roomCandidates
            ];
        }

        // order sessions by least-flexible first (heuristic): fewer candidate profs * fewer rooms
        $order = range(0, $nSessions - 1);
        usort($order, function ($a, $b) use ($sessionCandidates) {
            $pa = max(1, count($sessionCandidates[$a]['professors']));
            $ra = max(1, count($sessionCandidates[$a]['rooms']));
            $pb = max(1, count($sessionCandidates[$b]['professors']));
            $rb = max(1, count($sessionCandidates[$b]['rooms']));
            return ($pa * $ra) <=> ($pb * $rb); // smaller domain first
        });

        // Backtracking recursive function
        $timeslotCount = count($timeslots);

        $tryAssign = function ($idx) use (&$tryAssign, $order, $sessions, $sessionCandidates, $timeslotCount, &$roomBooked, &$profBooked, &$profLoads, $profMaxLoad, &$assignment, $timeslots) {
            if ($idx >= count($order)) {
                return true; // all assigned
            }

            $sessionIndex = $order[$idx];
            $sess = $sessions[$sessionIndex];
            $cands = $sessionCandidates[$sessionIndex];

            $subjectLookup = Subject::all()->keyBy('id');
            $professorLookup = Professor::all()->keyBy('id');
            $roomLookup = Room::all()->keyBy('id');

            // try each timeslot
            for ($t = 0; $t < $timeslotCount; $t++) {
                // ensure professor and room available at timeslot t
                // iterate candidate professors
                foreach ($cands['professors'] as $profCand) {
                    $pid = $profCand['id'];
                    $pmax = $profCand['max'];

                    // check if prof is already booked at timeslot
                    if (isset($profBooked[$t][$pid])) continue;
                    // check professor weekly load not exceeded
                    if (($profLoads[$pid] ?? 0) >= $pmax) continue;

                    // iterate candidate rooms
                    foreach ($cands['rooms'] as $roomCand) {
                        $roomId = $roomCand['id'] ?? null; // null => online
                        // check room not booked at timeslot (if physical)
                        if ($roomId !== null && isset($roomBooked[$t][$roomId])) continue;

                        // assign
                        $subject = $subjectLookup[$sess['subject_id']] ?? null;
                        $professor = $professorLookup[$pid] ?? null;
                        $room = $roomId ? ($roomLookup[$roomId] ?? null) : null;

                        $assignment[$sessionIndex] = [
                            'session_index' => $sessionIndex,
                            'section_name' => $sess['section_name'],

                            'subject_id' => $sess['subject_id'],
                            'subject_code' => $subject->subject_code ?? null,
                            'subject_title' => $subject->subject_name ?? null,

                            'professor_id' => $pid,
                            'professor_name' => $professor ? ($professor->last_name . ', ' . $professor->first_name) : null,

                            'room_id' => $roomId,
                            'room_name' => $room ? ($room->building_name . ' - ' . $room->room_number) :  'Online',

                            'timeslot' => $timeslots[$t],
                            'is_online' => $roomId === null
                        ];

                        // mark booked
                        $profBooked[$t][$pid] = true;
                        if ($roomId !== null) $roomBooked[$t][$roomId] = true;
                        $profLoads[$pid] = ($profLoads[$pid] ?? 0) + 1;

                        // recurse
                        if ($tryAssign($idx + 1)) return true;

                        // backtrack
                        unset($profBooked[$t][$pid]);
                        if ($roomId !== null) unset($roomBooked[$t][$roomId]);
                        $profLoads[$pid] = ($profLoads[$pid] ?? 1) - 1;
                        $assignment[$sessionIndex] = null;
                    } // room
                } // prof
            } // timeslot

            // if we reach here, cannot assign this session
            return false;
        };

        $success = $tryAssign(0);

        if (!$success) {
            // find which sessions remain unassigned
            for ($i = 0; $i < $nSessions; $i++) {
                if ($assignment[$i] === null) $unschedulable[] = $i;
            }
            return [
                'success' => false,
                'assignment' => array_values(array_filter($assignment)),
                'unschedulable' => $unschedulable,
                'stats' => [
                    'sessions_total' => $nSessions,
                    'assigned' => array_sum(array_map(function ($a) {
                        return $a ? 1 : 0;
                    }, $assignment)),
                ]
            ];
        }

        // success: return normalized assignment list
        $resultAssign = [];
        foreach ($assignment as $a) {
            if ($a) $resultAssign[] = $a;
        }

        return [
            'success' => true,
            'assignment' => $resultAssign,
            'unschedulable' => [],
            'stats' => [
                'sessions_total' => $nSessions,
                'assigned' => count($resultAssign),
            ]
        ];
    }
    /**
     * Build a simple section name like: BSIT-2A
     */
    protected function generateSectionName($course, $yearLevel, $index)
    {
        $letter = chr(ord('A') + $index);
        $prefix = $course->course_code ?? strtoupper(preg_replace('/\s+/', '', $course->name));
        return "{$prefix}-{$yearLevel}{$letter}";
    }

    /**
     * Default timeslots generator
     * Returns an array of slots: ['day'=>'MWF','start'=>'09:00:00','end'=>'10:00:00']
     * Customize as needed to match your school's actual blocks.
     */
    protected function getDefaultTimeslots()
    {
        return [
            // MWF
            ['day' => 'Monday', 'start' => '08:00:00', 'end' => '09:00:00'],
            ['day' => 'Wednesday', 'start' => '08:00:00', 'end' => '09:00:00'],
            ['day' => 'Friday',  'start' => '08:00:00', 'end' => '09:00:00'],

            ['day' => 'Monday', 'start' => '09:00:00', 'end' => '10:00:00'],
            ['day' => 'Wednesday', 'start' => '09:00:00', 'end' => '10:00:00'],
            ['day' => 'Friday', 'start' => '09:00:00', 'end' => '10:00:00'],

            // TTh
            ['day' => 'Tuesday', 'start' => '13:00:00', 'end' => '15:00:00'],
            ['day' => 'Thursday', 'start' => '13:00:00', 'end' => '15:00:00'],

            ['day' => 'Tuesday', 'start' => '15:00:00', 'end' => '17:00:00'],
            ['day' => 'Thursday', 'start' => '15:00:00', 'end' => '17:00:00'],
        ];
    }

    // =======================
    // GET /school-years
    // =======================
    public function schoolYears()
    {
        $years = SchoolYear::orderBy('year_start', 'desc')->get([
            'id',
            'year_start',
            'year_end',
            'is_active'
        ]);

        return response()->json([
            'data' => $years
        ]);
    }

    // =======================
    // GET /semesters
    // =======================
    public function semesters()
    {
        // If you have a Semester model:
        $semesters = Semester::orderBy('id')->get(['id', 'name', 'is_active']);

        return response()->json([
            'data' => $semesters
        ]);
    }

    public function activeSchoolYear()
    {
        $active = SchoolYear::where('is_active', true)->first([
            'id',
            'year_start',
            'year_end',
            'is_active'
        ]);

        return response()->json([
            'data' => $active
        ]);
    }

    public function activeSemester()
    {
        $active = Semester::where('is_active', true)->first([
            'id',
            'name',
            'is_active'
        ]);

        return response()->json([
            'data' => $active
        ]);
    }
}

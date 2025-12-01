<?php

namespace App\Http\Controllers;

use App\Models\ClassSection;
use App\Models\ClassSchedule;
use App\Models\Student;
use App\Models\StudentSubjectAssignment;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassSectionController extends Controller
{
    /* ============================================================
     * SECTION MANAGEMENT
     * ============================================================
     */

    /** GET /api/sections */
    public function index(Request $request)
    {
        $query = ClassSection::with(['course:course_id,course_code,course_name']);

        if ($search = $request->input('search')) {
            $query->where('section_name', 'like', "%$search%");
        }

        if ($request->course_id) $query->where('course_id', $request->course_id);
        if ($request->semester) $query->where('semester', $request->semester);
        if ($request->academic_year) $query->where('academic_year', $request->academic_year);

        return $query->orderBy('section_name')->paginate($request->per_page ?? 10);
    }

    /** POST /api/sections */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section_name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,course_id',
            'academic_year' => 'required|string',
            'semester' => 'required|string|in:1st,2nd,Summer',
            'year_level' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $section = ClassSection::create($validator->validated());

        return response()->json(['message' => 'Section created', 'data' => $section], 201);
    }

    /** GET /api/sections/{id} */
    public function show($id)
    {
        return ClassSection::with(['course'])->findOrFail($id);
    }

    /** PUT /api/sections/{id} */
    public function update(Request $request, $id)
    {
        $section = ClassSection::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'section_name' => 'sometimes|string|max:255',
            'course_id' => 'sometimes|exists:courses,course_id',
            'academic_year' => 'sometimes|string',
            'semester' => 'sometimes|string|in:1st,2nd,Summer',
            'year_level' => 'sometimes|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $section->update($validator->validated());

        return response()->json(['message' => 'Section updated', 'data' => $section]);
    }

    /** DELETE /api/sections/{id} */
    public function destroy($id)
    {
        $section = ClassSection::findOrFail($id);

        if (
            StudentSubjectAssignment::where('class_section_id', $id)->exists() ||
            ClassSchedule::where('class_section_id', $id)->exists()
        ) {
            return response()->json(['message' => 'Cannot delete section with dependencies'], 409);
        }

        $section->delete();

        return response()->json(['message' => 'Section deleted']);
    }

    /* ============================================================
     * SCHEDULE LISTING & VALIDATION
     * ============================================================
     */

    /** GET /api/sections/{id}/available-students */
    public function availableStudents($id)
    {
        $section = ClassSection::findOrFail($id);

        // students already assigned to this section
        $assignedIds = StudentSubjectAssignment::where('class_section_id', $id)
            ->pluck('student_id')
            ->unique()
            ->toArray();

        // Standard subjects = students must take these
        $standardSubjects = Subject::where([
            ['course_id', $section->course_id],
            ['year_level', $section->year_level],
            ['semester', $section->semester]
        ])->pluck('subject_id')->toArray();

        // Students who belong to the same course but NOT yet assigned
        // OR are retaking subjects matching this section
        $eligible = Student::where('course_id', $section->course_id)
            ->whereNotIn('student_id', $assignedIds)
            ->get();

        return response()->json($eligible);
    }

    /** GET /api/sections/{id}/schedules */
    public function schedules($id)
    {
        return ClassSchedule::with(['subject', 'professor', 'room'])
            ->where('class_section_id', $id)
            ->get();
    }

    /** GET /api/sections/{id}/validate-schedules */
    public function validateSectionSchedule($id)
    {
        $section = ClassSection::findOrFail($id);

        $standardSubjects = Subject::where([
            ['course_id', $section->course_id],
            ['year_level', $section->year_level],
            ['semester', $section->semester]
        ])->get();

        $errors = [];

        foreach ($standardSubjects as $sub) {
            $schedule = ClassSchedule::where('class_section_id', $id)
                ->where('subject_id', $sub->subject_id)
                ->first();

            if (!$schedule) {
                $errors[] = "{$sub->subject_code} has NO schedule assigned";
                continue;
            }

            if (!$schedule->professor_id)
                $errors[] = "{$sub->subject_code} has no professor";

            if (!$schedule->room_id)
                $errors[] = "{$sub->subject_code} has no room";

            if (!$schedule->day_of_week || !$schedule->start_time || !$schedule->end_time)
                $errors[] = "{$sub->subject_code} has incomplete time/day";
        }

        return $errors
            ? response()->json(['status' => 'incomplete', 'errors' => $errors], 422)
            : response()->json(['status' => 'ok']);
    }

    /* ============================================================
     * DETECT SCHEDULE CONFLICTS
     * ============================================================
     */

    /** GET /api/sections/{id}/conflicts */
    public function detectConflicts($id)
    {
        $schedules = ClassSchedule::where('class_section_id', $id)->get();
        $conflicts = [];

        foreach ($schedules as $a) {
            foreach ($schedules as $b) {
                if ($a->class_schedule_id >= $b->class_schedule_id) continue;
                if ($a->day_of_week !== $b->day_of_week) continue;

                $overlap = $a->start_time < $b->end_time && $b->start_time < $a->end_time;

                if ($overlap) {
                    if ($a->professor_id === $b->professor_id)
                        $conflicts[] = "Professor conflict: {$a->subject_id} and {$b->subject_id}";

                    if ($a->room_id === $b->room_id)
                        $conflicts[] = "Room conflict: {$a->subject_id} and {$b->subject_id}";
                }
            }
        }

        return response()->json($conflicts);
    }

    /* ============================================================
     * STUDENT LISTING (REGULAR/IRREGULAR)
     * ============================================================
     */

    /** GET /api/sections/{id}/students */
    public function students($id)
    {
        $section = ClassSection::findOrFail($id);

        $standardSubjects = Subject::where([
            ['course_id', $section->course_id],
            ['year_level', $section->year_level],
            ['semester', $section->semester]
        ])->pluck('subject_id')->toArray();

        $assignments = StudentSubjectAssignment::with(['student', 'subject'])
            ->where('class_section_id', $id)
            ->get()
            ->groupBy('student_id');

        $regular = [];
        $irregular = [];

        foreach ($assignments as $studentId => $group) {
            $student = $group->first()->student;
            $studentSubjects = $group->pluck('subject_id')->toArray();

            $isRegular = !array_diff($standardSubjects, $studentSubjects)
                && !array_diff($studentSubjects, $standardSubjects);

            $data = [
                'student' => $student,
                'subjects' => $group->values(),
            ];

            $isRegular ? $regular[] = $data : $irregular[] = $data;
        }

        return response()->json([
            'regular' => $regular,
            'irregular' => $irregular,
        ]);
    }

    /* ============================================================
     * ASSIGN STUDENTS
     * ============================================================
     */

    /** POST /api/sections/{id}/assign-student */
    public function assignStudent(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,student_id',
            'subject_id' => 'required|exists:subjects,subject_id',
        ]);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 422);

        $count = StudentSubjectAssignment::where('class_section_id', $id)
            ->distinct('student_id')
            ->count();

        if ($count >= 30)
            return response()->json(['message' => 'Section is full (max 30)'], 409);

        return StudentSubjectAssignment::firstOrCreate([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'class_section_id' => $id,
        ]);
    }

    /** POST /api/sections/{id}/auto-assign */
    public function autoAssign($id)
    {
        $section = ClassSection::findOrFail($id);

        $assigned = StudentSubjectAssignment::where('class_section_id', $id)
            ->distinct('student_id')
            ->count();

        if ($assigned >= 30)
            return response()->json(['message' => 'Section already full'], 409);

        $limit = 30 - $assigned;

        $students = Student::where('course_id', $section->course_id)
            ->limit($limit)
            ->get();

        foreach ($students as $student) {
            $this->assignAllStandardSubjectsToStudent($section, $student->student_id);
        }

        return response()->json(['message' => 'Students auto-assigned']);
    }

    private function assignAllStandardSubjectsToStudent($section, $studentId)
    {
        $standardSubjects = Subject::where([
            ['course_id', $section->course_id],
            ['year_level', $section->year_level],
            ['semester', $section->semester]
        ])->get();

        foreach ($standardSubjects as $sub) {
            StudentSubjectAssignment::firstOrCreate([
                'student_id' => $studentId,
                'subject_id' => $sub->subject_id,
                'class_section_id' => $section->class_section_id,
            ]);
        }
    }

    /** DELETE /api/assignments/{id} */
    public function removeAssignment($assignmentId)
    {
        StudentSubjectAssignment::findOrFail($assignmentId)->delete();
        return response()->json(['message' => 'Assignment removed']);
    }

    /* ============================================================
     * LOCK / UNLOCK SECTION
     * ============================================================
     */

    /** POST /api/sections/{id}/lock */
    public function lock($id)
    {
        $section = ClassSection::findOrFail($id);
        $section->update(['locked' => true]);
        return response()->json(['message' => 'Section locked']);
    }

    /** POST /api/sections/{id}/unlock */
    public function unlock($id)
    {
        $section = ClassSection::findOrFail($id);
        $section->update(['locked' => false]);
        return response()->json(['message' => 'Section unlocked']);
    }

    /* ============================================================
     * TIMETABLE EXPORT (Simple structure)
     * ============================================================
     */

    /** GET /api/sections/{id}/timetable */
    public function timetable($id)
    {
        return ClassSchedule::with(['subject', 'professor', 'room'])
            ->where('class_section_id', $id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
    }
}

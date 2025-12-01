<?php

namespace App\Http\Controllers;

use App\Models\ClassSection;
use App\Models\ClassSchedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\StudentSubjectAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassSectionController extends Controller
{
    /** GET /api/sections */
    public function index(Request $request)
    {
        $query = ClassSection::with('course');

        if ($search = $request->input('search')) {
            $query->where('section_name', 'like', "%{$search}%");
        }

        if ($request->course_id) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->school_year_id) {
            $query->where('school_year_id', $request->school_year_id);
        }

        if ($request->semester_id) {
            $query->where('semester_id', $request->semester_id);
        }

        return $query->orderBy('section_name')->paginate($request->per_page ?? 10);
    }

    /** POST /api/sections */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section_name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'school_year_id' => 'required|exists:school_years,id',
            'semester_id' => 'required|exists:semesters,id',
            'year_level' => 'required|integer|min:1|max:5',
            'capacity' => 'nullable|integer|min:1|max:200',
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
        return ClassSection::with('course')->findOrFail($id);
    }

    /** PUT /api/sections/{id} */
    public function update(Request $request, $id)
    {
        $section = ClassSection::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'section_name' => 'sometimes|string|max:255',
            'course_id' => 'sometimes|exists:courses,id',
            'school_year_id' => 'sometimes|exists:school_years,id',
            'semester_id' => 'sometimes|exists:semesters,id',
            'year_level' => 'sometimes|integer|min:1|max:5',
            'capacity' => 'nullable|integer|min:1|max:200',
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
            ['semester_id', $section->semester_id]
        ])->get();

        $errors = [];

        foreach ($standardSubjects as $sub) {
            $schedule = ClassSchedule::where('class_section_id', $id)
                ->where('subject_id', $sub->id)
                ->first();

            if (!$schedule) {
                $errors[] = "{$sub->subject_code} has NO schedule assigned";
                continue;
            }

            if (!$schedule->professor_id) $errors[] = "{$sub->subject_code} has no professor";
            if (!$schedule->room_id) $errors[] = "{$sub->subject_code} has no room";
            if (!$schedule->day_of_week || !$schedule->start_time || !$schedule->end_time)
                $errors[] = "{$sub->subject_code} has incomplete time/day";
        }

        return $errors ? response()->json(['status' => 'incomplete', 'errors' => $errors], 422)
                       : response()->json(['status' => 'ok']);
    }

    /** GET /api/sections/{id}/conflicts */
    public function detectConflicts($id)
    {
        $schedules = ClassSchedule::where('class_section_id', $id)->get();
        $conflicts = [];

        foreach ($schedules as $a) {
            foreach ($schedules as $b) {
                if ($a->id >= $b->id) continue;
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

    /** GET /api/sections/{id}/available-students */
    public function availableStudents($id)
    {
        $section = ClassSection::findOrFail($id);

        $assignedIds = StudentSubjectAssignment::where('class_section_id', $id)
            ->pluck('student_id')->toArray();

        $eligible = Student::where('course_id', $section->course_id)
            ->whereNotIn('id', $assignedIds)
            ->get();

        return response()->json($eligible);
    }

    /** GET /api/sections/{id}/students */
    public function students($id)
    {
        $section = ClassSection::findOrFail($id);

        $standardSubjects = Subject::where([
            ['course_id', $section->course_id],
            ['year_level', $section->year_level],
            ['semester_id', $section->semester_id]
        ])->pluck('id')->toArray();

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

        return response()->json(['regular' => $regular, 'irregular' => $irregular]);
    }

    /** POST /api/sections/{id}/assign-student */
    public function assignStudent(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $count = StudentSubjectAssignment::where('class_section_id', $id)
            ->distinct('student_id')->count();

        if ($count >= 30) return response()->json(['message' => 'Section is full (max 30)'], 409);

        $assignment = StudentSubjectAssignment::firstOrCreate([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'class_section_id' => $id,
        ]);

        return response()->json($assignment);
    }

    /** POST /api/sections/{id}/auto-assign */
    public function autoAssign($id)
    {
        $section = ClassSection::findOrFail($id);

        $assigned = StudentSubjectAssignment::where('class_section_id', $id)
            ->distinct('student_id')->count();

        if ($assigned >= 30) return response()->json(['message' => 'Section already full'], 409);

        $limit = 30 - $assigned;

        $students = Student::where('course_id', $section->course_id)
            ->limit($limit)->get();

        foreach ($students as $student) {
            $this->assignAllStandardSubjectsToStudent($section, $student->id);
        }

        return response()->json(['message' => 'Students auto-assigned']);
    }

    private function assignAllStandardSubjectsToStudent($section, $studentId)
    {
        $standardSubjects = Subject::where([
            ['course_id', $section->course_id],
            ['year_level', $section->year_level],
            ['semester_id', $section->semester_id]
        ])->get();

        foreach ($standardSubjects as $sub) {
            StudentSubjectAssignment::firstOrCreate([
                'student_id' => $studentId,
                'subject_id' => $sub->id,
                'class_section_id' => $section->id,
            ]);
        }
    }

    /** DELETE /api/assignments/{id} (removeAssignment) */
    public function removeAssignment($assignmentId)
    {
        StudentSubjectAssignment::findOrFail($assignmentId)->delete();
        return response()->json(['message' => 'Assignment removed']);
    }

    public function lock($id)
    {
        $section = ClassSection::findOrFail($id);
        $section->update(['locked' => true]);
        return response()->json(['message' => 'Section locked']);
    }

    public function unlock($id)
    {
        $section = ClassSection::findOrFail($id);
        $section->update(['locked' => false]);
        return response()->json(['message' => 'Section unlocked']);
    }

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

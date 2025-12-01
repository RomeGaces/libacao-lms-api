<?php

namespace App\Http\Controllers;

use App\Models\ClassSection;
use App\Models\ClassSchedule;
use App\Models\Student;
use App\Models\StudentSubjectAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassSectionController extends Controller
{
    /**
     * GET /api/sections
     * List sections with search, filtering, pagination.
     */
    public function index(Request $request)
    {
        $query = ClassSection::with(['course:course_id,course_code,course_name']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('section_name', 'like', "%{$search}%")
                    ->orWhere('academic_year', 'like', "%{$search}%")
                    ->orWhereHas(
                        'course',
                        fn($c) =>
                        $c->where('course_name', 'like', "%{$search}%")
                            ->orWhere('course_code', 'like', "%{$search}%")
                    );
            });
        }

        if ($courseId = $request->input('course_id')) {
            $query->where('course_id', $courseId);
        }

        if ($academicYear = $request->input('academic_year')) {
            $query->where('academic_year', $academicYear);
        }

        if ($semester = $request->input('semester')) {
            $query->where('semester', $semester);
        }

        $perPage = (int)$request->input('per_page', 10);
        $sections = $query->orderBy('section_name')->paginate($perPage);

        return response()->json($sections);
    }

    /**
     * POST /api/sections
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section_name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,course_id',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|string|in:1st,2nd,Summer',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $section = ClassSection::create($validator->validated());

        return response()->json(['message' => 'Section created', 'data' => $section], 201);
    }

    /**
     * GET /api/sections/{id}
     * Returns section + minimal relations (no heavy lists).
     */
    public function show($id)
    {
        $section = ClassSection::with(['course:course_id,course_code,course_name'])->findOrFail($id);
        return response()->json($section);
    }

    /**
     * PUT /api/sections/{id}
     */
    public function update(Request $request, $id)
    {
        $section = ClassSection::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'section_name' => 'sometimes|string|max:255',
            'course_id' => 'sometimes|exists:courses,course_id',
            'academic_year' => 'sometimes|string|max:20',
            'semester' => 'sometimes|string|in:1st,2nd,Summer',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $section->update($validator->validated());

        return response()->json(['message' => 'Section updated', 'data' => $section]);
    }

    /**
     * DELETE /api/sections/{id}
     * Prevent deleting sections that have student assignments or schedules.
     */
    public function destroy($id)
    {
        $section = ClassSection::findOrFail($id);

        $hasAssignments = StudentSubjectAssignment::where('class_section_id', $id)->exists();
        $hasSchedules = ClassSchedule::where('class_section_id', $id)->exists();

        if ($hasAssignments || $hasSchedules) {
            return response()->json([
                'message' => 'Cannot delete section with assigned students or schedules.'
            ], 409);
        }

        $section->delete();

        return response()->json(['message' => 'Section deleted']);
    }

    /**
     * GET /api/sections/{id}/schedules
     * Return schedules for the section (flexible: pending/finalized allowed)
     */
    public function schedules($id)
    {
        $section = ClassSection::findOrFail($id);

        $schedules = ClassSchedule::with([
            'subject:subject_id,subject_code,subject_name',
            'professor:professor_id,first_name,last_name',
            'room:room_id,room_number,building_name'
        ])
            ->where('class_section_id', $id)
            ->get();

        // Map results safely
        $formatted = $schedules->map(function ($s) {
            return [
                'class_schedule_id' => $s->class_schedule_id,
                'subject_id' => $s->subject_id,
                'subject' => $s->subject
                    ? ($s->subject->subject_code . ' - ' . $s->subject->subject_name)
                    : null,
                'professor_id' => $s->professor_id,
                'professor' => $s->professor
                    ? ($s->professor->first_name . ' ' . $s->professor->last_name)
                    : null,
                'room_id' => $s->room_id,
                'room' => $s->room
                    ? ($s->room->room_number . ' - ' . $s->room->building_name)
                    : null,
                'day_of_week' => $s->day_of_week,
                // format times safely (cast string or null)
                'start_time' => $s->start_time ? date('H:i', strtotime($s->start_time)) : null,
                'end_time' => $s->end_time ? date('H:i', strtotime($s->end_time)) : null,
                'status' => $s->status,
            ];
        });

        return response()->json($formatted->values());
    }
    /**
     * GET /api/sections/{id}/students
     * Return students assigned to this section (derived from student_subject_assignments).
     * We return one entry per student (distinct), plus counts & assignment details.
     */
    public function students($id)
    {
        $section = ClassSection::findOrFail($id);

        // Get latest assignment per student per subject in this section (or all assignments)
        $assignments = StudentSubjectAssignment::with(['student:student_id,student_number,first_name,middle_name,last_name,email', 'subject:subject_id,subject_code,subject_name'])
            ->where('class_section_id', $id)
            ->get();

        // Aggregate by student to return a single student record with subjects array & counts
        $grouped = $assignments->groupBy('student_id')->map(function ($group) {
            $student = $group->first()->student;
            $subjects = $group->map(fn($a) => [
                'assignment_id' => $a->assignment_id,
                'subject_id' => $a->subject_id,
                'subject_code' => $a->subject->subject_code ?? null,
                'subject_name' => $a->subject->subject_name ?? null,
                'status' => $a->status,
                'grade' => $a->grade,
            ])->values();

            return [
                'student_id' => $student->student_id,
                'student_number' => $student->student_number,
                'first_name' => $student->first_name,
                'middle_name' => $student->middle_name,
                'last_name' => $student->last_name,
                'email' => $student->email,
                'status' => $student->status,
                'subjects' => $subjects,
                'subjects_count' => count($subjects),
            ];
        })->values();

        return response()->json($grouped);
    }

    /**
     * POST /api/sections/{id}/assign-student
     * Assign a student to this section for a specific subject.
     * body: { student_id, subject_id, status (optional), grade (optional) }
     */
    public function assignStudent(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,student_id',
            'subject_id' => 'required|exists:subjects,subject_id',
            'status' => 'nullable|in:enrolled,dropped,completed',
            'grade' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $data['class_section_id'] = $id;

        // Prevent duplicate exact assignment
        $exists = StudentSubjectAssignment::where([
            ['student_id', $data['student_id']],
            ['subject_id', $data['subject_id']],
            ['class_section_id', $id]
        ])->exists();

        if ($exists) {
            return response()->json(['message' => 'Student already assigned to that subject in this section'], 409);
        }

        $assignment = StudentSubjectAssignment::create($data);

        return response()->json(['message' => 'Student assigned', 'data' => $assignment], 201);
    }

    /**
     * DELETE /api/assignments/{assignmentId}
     */
    public function removeAssignment($assignmentId)
    {
        $assignment = StudentSubjectAssignment::findOrFail($assignmentId);
        $assignment->delete();

        return response()->json(['message' => 'Assignment removed']);
    }
}

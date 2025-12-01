<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);

        $query = Course::with('department');

        if ($search) {
            $query->where('course_name', 'like', "%{$search}%")
                ->orWhere('course_code', 'like', "%{$search}%");
        }

        $courses = $query->orderBy('course_name')->paginate($perPage);

        return response()->json([
            'data' => $courses->items(),
            'current_page' => $courses->currentPage(),
            'last_page' => $courses->lastPage(),
            'total' => $courses->total(),
            'per_page' => $courses->perPage(),
        ]);
    }

    public function show($id)
    {
        $course = Course::with(['department', 'subjects'])->findOrFail($id);
        return response()->json($course);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_code' => 'required|string|unique:courses,course_code',
            'course_name' => 'required|string',
            'description' => 'nullable|string',
            'duration_years' => 'nullable|integer|min:1',
            'department_id' => 'nullable|exists:departments,department_id',
        ]);

        $course = Course::create($validated);
        return response()->json($course->load('department'), 201);
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'course_code' => 'required|string|unique:courses,course_code,' . $id . ',course_id',
            'course_name' => 'required|string',
            'description' => 'nullable|string',
            'duration_years' => 'nullable|integer|min:1',
            'department_id' => 'nullable|exists:departments,department_id',
        ]);

        $course->update($validated);
        return response()->json($course->load('department'));
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return response()->json(['message' => 'Course deleted successfully']);
    }

    // -------- SUBJECTS MANAGEMENT --------

    public function addSubject(Request $request, $courseId)
    {
        $validator = Validator::make($request->all(), [
            'subject_code' => 'required|string|unique:subjects,subject_code',
            'subject_name' => 'required|string',
            'units' => 'nullable|integer|min:1',
            'semester' => 'nullable|string',
            'year_level' => 'nullable|integer|min:1|max:4',
        ]);

        if ($validator->fails()) {
            // Return detailed validation errors
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // If validation passes, continue
        $validated = $validator->validated();
        $validated['course_id'] = $courseId;

        $subject = Subject::create($validated);

        return response()->json([
            'message' => 'Subject created successfully',
            'data' => $subject,
        ], 201);
    }

    public function updateSubject(Request $request, $subjectId)
    {
        $subject = Subject::findOrFail($subjectId);

        $validated = $request->validate([
            'subject_code' => 'required|string|unique:subjects,subject_code,' . $subjectId . ',subject_id',
            'subject_name' => 'required|string',
            'units' => 'nullable|integer|min:1',
            'semester' => 'nullable|string',
            'year_level' => 'nullable|integer|min:1|max:4',
        ]);

        $subject->update($validated);
        return response()->json($subject);
    }

    public function deleteSubject($subjectId)
    {
        $subject = Subject::findOrFail($subjectId);
        $subject->delete();

        return response()->json(['message' => 'Subject deleted successfully']);
    }
}

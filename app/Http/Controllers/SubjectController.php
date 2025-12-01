<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    /**
     * GET /api/subjects
     * List with optional search, course_id, pagination
     */
    public function index(Request $request)
    {
        $query = Subject::with('course:course_id,course_code,course_name');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('subject_name', 'like', "%{$search}%")
                  ->orWhere('subject_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($courseId = $request->input('course_id')) {
            $query->where('course_id', $courseId);
        }

        $perPage = (int) $request->input('per_page', 10);
        $subjects = $query->orderBy('subject_code')->paginate($perPage);

        return response()->json($subjects);
    }

    /**
     * GET /api/subjects/{id}
     */
    public function show($id)
    {
        $subject = Subject::with('course:course_id,course_code,course_name')->findOrFail($id);
        return response()->json($subject);
    }

    /**
     * POST /api/subjects
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,course_id',
            'subject_code' => 'required|string|unique:subjects,subject_code',
            'subject_name' => 'required|string',
            'description' => 'nullable|string',
            'units' => 'required|integer|min:1',
            'semester' => 'nullable|string|in:1st,2nd,Summer',
            'year_level' => 'nullable|integer|min:1|max:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $subject = Subject::create($validator->validated());

        return response()->json(['message' => 'Subject created successfully', 'data' => $subject], 201);
    }

    /**
     * PUT /api/subjects/{id}
     */
    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,course_id',
            'subject_code' => 'required|string|unique:subjects,subject_code,' . $id . ',subject_id',
            'subject_name' => 'required|string',
            'description' => 'nullable|string',
            'units' => 'required|integer|min:1',
            'semester' => 'nullable|string|in:1st,2nd,Summer',
            'year_level' => 'nullable|integer|min:1|max:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $subject->update($validator->validated());

        return response()->json(['message' => 'Subject updated successfully', 'data' => $subject->fresh()]);
    }

    /**
     * DELETE /api/subjects/{id}
     */
    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return response()->json(['message' => 'Subject deleted successfully']);
    }

    /**
     * GET /api/courses/{courseId}/subjects
     * Convenience method to list by course (optional)
     */
    public function getByCourse($courseId)
    {
        $subjects = Subject::where('course_id', $courseId)
            ->orderBy('year_level')
            ->orderBy('semester')
            ->orderBy('subject_code')
            ->get();

        return response()->json($subjects);
    }
}

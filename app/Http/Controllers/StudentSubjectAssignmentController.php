<?php

namespace App\Http\Controllers;

use App\Models\StudentSubjectAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentSubjectAssignmentController extends Controller
{
    
    public function index()
    {
        $assignments = StudentSubjectAssignment::with(['student', 'subject', 'section'])
            ->get();
        return response()->json($assignments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_section_id' => 'required|exists:class_sections,id',
            'status' => 'nullable|in:Enrolled,Dropped,Completed,enrolled,dropped,completed',
            'grade' => 'nullable|string',
        ]);

        $assignment = StudentSubjectAssignment::create($validated);
        return response()->json($assignment->load(['student', 'subject', 'section']), 201);
    }

    public function show($id)
    {
        $assignment = StudentSubjectAssignment::with(['student', 'subject', 'section'])->findOrFail($id);
        return response()->json($assignment);
    }

    public function update(Request $request, $id)
    {
        $assignment = StudentSubjectAssignment::findOrFail($id);

        $validated = $request->validate([
            'student_id' => 'sometimes|exists:students,id',
            'subject_id' => 'sometimes|exists:subjects,id',
            'class_section_id' => 'sometimes|exists:class_sections,id',
            'status' => 'nullable|in:Enrolled,Dropped,Completed,enrolled,dropped,completed',
            'grade' => 'nullable|string',
        ]);

        $assignment->update($validated);
        return response()->json($assignment->fresh()->load(['student', 'subject', 'section']));
    }

    public function destroy($id)
    {
        StudentSubjectAssignment::findOrFail($id)->delete();
        return response()->noContent();
    }
}

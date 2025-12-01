<?php

namespace App\Http\Controllers;

use App\Models\StudentSubjectAssignment;
use Illuminate\Http\Request;

class StudentSubjectAssignmentController extends Controller
{
    public function index()
    {
        $assignments = StudentSubjectAssignment::with(['student', 'subject', 'classSchedule'])->get();
        return response()->json($assignments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'subject_id' => 'required|exists:subjects,subject_id',
            'class_schedule_id' => 'required|exists:class_schedules,class_schedule_id',
            'status' => 'nullable|string',
        ]);

        $assignment = StudentSubjectAssignment::create($validated);
        return response()->json($assignment, 201);
    }

    public function show($id)
    {
        $assignment = StudentSubjectAssignment::with(['student', 'subject', 'classSchedule'])->findOrFail($id);
        return response()->json($assignment);
    }

    public function update(Request $request, $id)
    {
        $assignment = StudentSubjectAssignment::findOrFail($id);
        $assignment->update($request->all());
        return response()->json($assignment);
    }

    public function destroy($id)
    {
        StudentSubjectAssignment::findOrFail($id)->delete();
        return response()->noContent();
    }
}
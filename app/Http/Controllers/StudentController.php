<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return response()->json(Student::with('classSection')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_number' => 'required|unique:students',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:students',
            'class_section_id' => 'required|exists:class_sections,class_section_id',
        ]);

        $student = Student::create($validated);
        return response()->json($student, 201);
    }

    public function show($id)
    {
        return response()->json(Student::with('classSection')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->update($request->all());
        return response()->json($student);
    }

    public function destroy($id)
    {
        Student::findOrFail($id)->delete();
        return response()->noContent();
    }
}
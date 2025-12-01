<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\ClassSchedule;
use App\Models\StudentSubjectAssignment;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /** LIST with pagination & search */
    public function index(Request $request)
    {
        $search = $request->search;

        $query = Student::with('course')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('student_number', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            });

        return $query->paginate(10);
    }

    /** STORE student */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_number' => 'required|unique:students',
            'first_name' => 'required',
            'middle_name' => 'nullable|string',
            'last_name' => 'required',
            'gender' => 'nullable',
            'email' => 'nullable|email|unique:students',
            'course_id' => 'required',
            'year_level' => 'required|integer|min:1|max:4',
        ]);

        return Student::create($validated);
    }

    /** UPDATE student */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'student_number' => "required|unique:students,student_number,$id",
            'first_name' => 'required',
            'middle_name' => 'nullable|string',
            'last_name' => 'required',
            'gender' => 'nullable',
            'email' => "nullable|email|unique:students,email,$id",
            'course_id' => 'required',
            'year_level' => 'required|integer|min:1|max:4',
        ]);

        $student->update($validated);

        return $student;
    }

    /** DELETE student */
    public function destroy($id)
    {
        Student::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }

    /** RETURN student schedule (current semester/year) */
    public function schedule($id)
    {
        $student = Student::findOrFail($id);

        $assignments = StudentSubjectAssignment::where('student_id', $id)
            ->where('status', 'Enrolled')
            ->pluck('class_section_id');

        $schedule = ClassSchedule::with(['subject', 'room', 'professor'])
            ->whereIn('class_section_id', $assignments)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return $schedule;
    }

    /** TRANSCRIPT OF RECORDS */
    public function transcript($id)
    {
        $student = Student::with('course')->findOrFail($id);

        $records = StudentSubjectAssignment::with(['subject'])
            ->where('student_id', $id)
            ->where('status', 'Completed')
            ->orderBy('subject_id')
            ->get();

        return [
            'student' => $student,
            'records' => $records,
        ];
    }

    public function checkEmail(Request $request) 
    {
        $email = $request->query('email');

        if (!$email) {
            return response()->json(['available' => false]);
        }

        $exists = Student::where('email', $email)->exists();

        return response()->json([
            'available' => !$exists
        ]);
    }

    public function nextNumber()
    {
        $year = date('Y');

        // Find latest number for this year
        $latest = Student::where('student_number', 'like', "$year-%")
            ->orderBy('student_number', 'desc')
            ->first();

        if (!$latest) {
            return response()->json("$year-00001");
        }

        // Extract the sequential part
        $parts = explode('-', $latest->student_number);
        $seq = intval($parts[1]) + 1;

        // Format to 5 digits
        $newNumber = sprintf("%s-%05d", $year, $seq);

        return response()->json($newNumber);
    }


    /** Get single student with course */
    public function show($id)
    {
        return Student::with('course')->findOrFail($id);
    }
}

<?php
// app/Services/NextSubjectAssignmentService.php

namespace App\Services;

use App\Models\Student;
use App\Models\Subject;
use App\Models\StudentSubjectAssignment;
use Illuminate\Support\Collection;

class NextSubjectAssignmentService
{
    /**
     * Assign next subjects for a single student.
     * Strict upsert: do nothing if assignment exists with status Completed/Enrolled/Pending Enrollment
     */
    public function assignForStudent(Student $student): array
    {
        $created = 0;
        $skipped = 0;

        // Completed subjects
        $completedIds = StudentSubjectAssignment::where('student_id', $student->id)
            ->where('status', 'Completed')
            ->pluck('subject_id')
            ->toArray();

        // Existing assignment subject ids (any status)
        $existingIds = StudentSubjectAssignment::where('student_id', $student->id)
            ->pluck('subject_id')
            ->toArray();

        // All curriculum subjects for student's course
        $subjects = Subject::where('course_id', $student->course_id)->get();

        foreach ($subjects as $subject) {
            // skip if completed or already has any assignment
            if (in_array($subject->id, $completedIds) || in_array($subject->id, $existingIds)) {
                $skipped++;
                continue;
            }

            // check prerequisite
            $prereqId = $subject->subject_prerequisite_id;
            if ($prereqId !== null) {
                if (!in_array($prereqId, $completedIds)) {
                    // prerequisite not satisfied: skip
                    $skipped++;
                    continue;
                }
            }

            // create pending assignment
            StudentSubjectAssignment::create([
                'student_id' => $student->id,
                'subject_id' => $subject->id,
                'class_section_id' => null,
                'status' => 'Pending Enrollment',
                'grade' => null,
            ]);

            $created++;
        }

        return ['created' => $created, 'skipped' => $skipped];
    }

    /**
     * Assign for a list of students (Collection or array of Student models)
     * Returns summary.
     */
    public function assignForStudents($students): array
    {
        $summary = [
            'students_processed' => 0,
            'created' => 0,
            'skipped' => 0,
            'inactive_skipped' => 0
        ];

        foreach ($students as $student) {

            // Skip students who should not be assigned next-sem subjects
            if ($student->status !== 'Active') {
                $summary['inactive_skipped']++;
                continue;
            }

            $res = $this->assignForStudent($student);

            $summary['students_processed']++;
            $summary['created'] += $res['created'];
            $summary['skipped'] += $res['skipped'];
        }

        return $summary;
    }

    /**
     * Convenience: assign for all active students.
     */
    public function assignForAllStudents(): array
    {
        $students = Student::all();
        return $this->assignForStudents($students);
    }
}

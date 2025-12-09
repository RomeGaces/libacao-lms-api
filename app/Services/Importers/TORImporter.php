<?php
// app/Services/Importers/TorImporter.php

namespace App\Services\Importers;

use App\Models\Student;
use App\Models\Subject;
use App\Models\StudentSubjectAssignment;
use App\Services\NextSubjectAssignmentService;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\ClassSection;

use Illuminate\Support\Facades\DB;

class TorImporter
{
    private function resolveSchoolYear($value)
    {
        if (!$value) {
            throw new \Exception("School year is required (format: 2022-2023)");
        }

        // Normalize formats:
        // 2022-2023
        // 2022/2023
        // 2022 to 2023
        // SY 2022-2023
        // 2022 2023
        $clean = str_replace(['/', 'to'], '-', strtolower($value));
        $clean = preg_replace('/[^0-9\-]/', '', $clean); // keep only digits and dash

        $parts = explode('-', $clean);

        if (count($parts) !== 2) {
            throw new \Exception("Invalid school year format '{$value}'. Expected: 2022-2023");
        }

        $start = intval($parts[0]);
        $end = intval($parts[1]);

        if ($start <= 0 || $end <= 0) {
            throw new \Exception("Invalid school year numbers in '{$value}'.");
        }

        // Try to find existing
        $sy = SchoolYear::where('year_start', $start)
            ->where('year_end', $end)
            ->first();

        if ($sy) return $sy->id;

        // CREATE NEW SCHOOL YEAR
        $sy = SchoolYear::create([
            'year_start' => $start,
            'year_end' => $end,
            'is_active' => 0, // or 1 if you prefer
        ]);

        return $sy->id;
    }


    private function resolveSemester($value)
    {
        if (!$value) throw new \Exception("Semester field is required.");

        $semester = Semester::whereRaw("LOWER(name) = ?", [strtolower(trim($value))])->first();

        if (!$semester) {
            throw new \Exception("Semester '{$value}' not found. It must match exactly the Semesters setup page.");
        }

        return $semester->id;
    }

    private function getOrCreateClassSection($student, $data)
    {
        $sectionName = trim($data['class_section'] ?? '');
        $sy = trim($data['school_year'] ?? '');
        $semester = trim($data['semester'] ?? '');
        $yearLevel = intval($data['year_level'] ?? 0);

        if (!$sectionName || !$sy || !$semester || !$yearLevel) {
            throw new \Exception("TOR requires class_section, school_year, semester, and year_level.");
        }

        $schoolYearId = $this->resolveSchoolYear($sy);
        $semesterId = $this->resolveSemester($semester);

        // Find existing
        $section = ClassSection::where('section_name', $sectionName)
            ->where('course_id', $student->course_id)
            ->where('school_year_id', $schoolYearId)
            ->where('semester_id', $semesterId)
            ->where('year_level', $yearLevel)
            ->first();

        if ($section) return $section->id;

        // Create new section
        $section = ClassSection::create([
            'section_name' => $sectionName,
            'course_id' => $student->course_id,
            'year_level' => $yearLevel,
            'school_year_id' => $schoolYearId,
            'semester_id' => $semesterId,
            'capacity' => null,
        ]);

        return $section->id;
    }


    protected $assignmentService;

    public function __construct(NextSubjectAssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    /**
     * CSV expected:
     * student_number,subject_code,grade,remark
     */
    public function import(string $path): array
    {
        $handle = fopen($path, 'r');
        if (!$handle) return ['created' => 0, 'updated' => 0, 'errors' => ['Unable to open file']];

        $header = null;
        $created = 0;
        $updated = 0;
        $errors = [];
        $affectedStudentIds = [];

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 0, ',')) !== false) {
                if (!$header) {
                    $header = array_map('trim', $row);
                    continue;
                }

                $data = array_combine($header, $row);
                $line = ftell($handle);

                try {
                    if (empty($data['student_number']) || empty($data['subject_code'])) {
                        throw new \Exception('Missing student_number or subject_code');
                    }

                    $student = Student::where('student_number', trim($data['student_number']))->first();
                    if (!$student) {
                        throw new \Exception("Student {$data['student_number']} not found.");
                    }

                    $subject = Subject::where('subject_code', trim($data['subject_code']))->first();
                    if (!$subject) {
                        throw new \Exception("Subject {$data['subject_code']} not found.");
                    }

                    $grade = $data['grade'] ?? null;
                    $remark = $data['remark'] ?? null;

                    // Strict upsert logic for Completed TOR
                    $existing = StudentSubjectAssignment::where('student_id', $student->id)
                        ->where('subject_id', $subject->id)
                        ->first();

                    if ($existing) {
                        // If it exists and is Completed, update grade/remark
                        if ($existing->status === 'Completed') {
                            $existing->update([
                                'grade' => $grade,
                                'status' => 'Completed',
                            ]);
                            $updated++;
                        } else {
                            // If existing but not Completed, promote to Completed and update grade
                            $existing->update([
                                'grade' => $grade,
                                'status' => 'Completed',
                            ]);
                            $updated++;
                        }
                    } else {
                        $classSectionId = $this->getOrCreateClassSection($student, $data);

                        StudentSubjectAssignment::create([
                            'student_id' => $student->id,
                            'subject_id' => $subject->id,
                            'class_section_id' => $classSectionId,
                            'status' => 'Completed',
                            'grade' => $grade,
                        ]);
                        $created++;
                    }

                    $affectedStudentIds[$student->id] = $student->id;
                } catch (\Exception $e) {
                    $errors[] = ['line' => $line, 'message' => $e->getMessage(), 'row' => $data];
                }
            }

            fclose($handle);

            // Commit DB changes before running assignment service
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            $errors[] = ['message' => 'Fatal import error: ' . $e->getMessage()];
            return compact('created', 'updated', 'errors');
        }

        // Run the assignment engine for affected students (automatically)
        $students = \App\Models\Student::whereIn('id', array_values($affectedStudentIds))->get();
        $assignmentSummary = $this->assignmentService->assignForStudents($students);

        return [
            'created' => $created,
            'updated' => $updated,
            'errors' => $errors,
            'assignment_summary' => $assignmentSummary,
        ];
    }
}

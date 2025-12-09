<?php
// app/Services/Importers/StudentsImporter.php

namespace App\Services\Importers;

use App\Models\Student;
use App\Models\Course;
use Illuminate\Support\Str;

class StudentsImporter
{
    private function normalizeDate($value)
    {
        if (!$value) return null;

        // Already valid
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        try {
            return \Carbon\Carbon::parse($value)->format("Y-m-d");
        } catch (\Exception $e) {
            return null; // or throw exception instead
        }
    }

    public function import(string $path): array
    {
        $handle = fopen($path, 'r');
        if (!$handle) return ['created' => 0, 'updated' => 0, 'errors' => ['Unable to open file']];

        $header = null;
        $created = 0;
        $updated = 0;
        $errors = [];

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            if (!$header) {
                $header = array_map('trim', $row);
                continue;
            }

            $data = array_combine($header, $row);
            $line = ftell($handle);

            try {
                // Minimal required column checks
                if (empty($data['student_number']) || empty($data['first_name']) || empty($data['last_name']) || empty($data['course_code'])) {
                    throw new \Exception("Missing required fields (student_number, first_name, last_name, course_code).");
                }

                $course = Course::where('course_code', $data['course_code'])->first();
                if (!$course) {
                    throw new \Exception("Course with code {$data['course_code']} not found.");
                }

                $attrs = [
                    'student_number' => $data['student_number'],
                    'first_name' => $data['first_name'],
                    'middle_name' => $data['middle_name'] ?? null,
                    'last_name' => $data['last_name'],
                    'gender' => $data['gender'] ?? null,
                    'birth_date' =>  $this->normalizeDate($data['birth_date'] ?? null),
                    'course_id' => $course->id,
                    'email' => $data['email'] ?? null,
                    'phone_number' => $data['phone_number'] ?? null,
                    'address' => $data['address'] ?? null,
                    'year_level' => isset($data['year_level']) ? intval($data['year_level']) : null,
                ];

                $student = Student::where('student_number', $attrs['student_number'])->first();
                if ($student) {
                    $student->update($attrs);
                    $updated++;
                } else {
                    Student::create($attrs);
                    $created++;
                }
            } catch (\Exception $e) {
                $errors[] = ['line' => $line, 'message' => $e->getMessage(), 'row' => $data];
            }
        }

        fclose($handle);

        return compact('created', 'updated', 'errors');
    }
}

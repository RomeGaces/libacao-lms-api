<?php
// app/Services/Importers/CurriculumImporter.php

namespace App\Services\Importers;

use App\Models\Course;
use App\Models\Subject;
use App\Models\Semester;

class CurriculumImporter
{
    private function resolveSemesterStrict($value)
    {
        if (!$value) {
            throw new \Exception("Semester name is required.");
        }

        $trimmed = trim($value);

        // Exact case-insensitive match
        $semester = Semester::whereRaw("LOWER(name) = ?", [strtolower($trimmed)])->first();

        if ($semester) {
            return $semester->id;
        }

        throw new \Exception("Semester '{$value}' not found. Please ensure the name matches the Semesters setup exactly.");
    }

    /**
     * CSV columns:
     * course_code,subject_code,subject_name,units,year_level,semester_id,subject_prerequisite_code
     */
    public function import(string $path): array
    {
        $handle = fopen($path, 'r');
        if (!$handle) return ['created' => 0, 'updated' => 0, 'errors' => ['Unable to open file']];

        $header = null;
        $created = 0;
        $updated = 0;
        $errors = [];
        $rows = [];

        // collect rows first
        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            if (!$header) {
                $header = array_map('trim', $row);
                continue;
            }
            $data = array_combine($header, $row);
            $rows[] = $data;
        }

        // First pass: create or update subjects without setting prerequisite id
        foreach ($rows as $i => $data) {
            $lineNum = $i + 2;
            try {
                if (empty($data['course_code']) || empty($data['subject_code']) || empty($data['subject_name'])) {
                    throw new \Exception('Missing required course_code, subject_code, or subject_name.');
                }

                $course = Course::where('course_code', $data['course_code'])->first();
                if (!$course) throw new \Exception("Course {$data['course_code']} not found.");

                $attrs = [
                    'subject_code' => $data['subject_code'],
                    'subject_name' => $data['subject_name'],
                    'units' => isset($data['units']) ? intval($data['units']) : null,
                    'semester_id' => $this->resolveSemesterStrict($data['semester'] ?? null),
                    'year_level' => isset($data['year_level']) ? intval($data['year_level']) : null,
                    'course_id' => $course->id,
                    'type' => $data['type'] ?? null,
                    'hours_per_week' => $data['hours_per_week'] ?? null,
                    'description' => $data['description'] ?? null,
                    'subject_prerequisite_id' => null, // resolved later
                ];

                $subject = Subject::where('subject_code', $attrs['subject_code'])->first();
                if ($subject) {
                    $subject->update($attrs);
                    $updated++;
                } else {
                    Subject::create($attrs);
                    $created++;
                }
            } catch (\Exception $e) {
                $errors[] = ['line' => $lineNum, 'message' => $e->getMessage(), 'row' => $data];
            }
        }

        // Second pass: resolve prerequisite codes to IDs
        foreach ($rows as $i => $data) {
            $lineNum = $i + 2;
            if (empty($data['subject_prerequisite_code'])) continue;
            try {
                $subject = Subject::where('subject_code', $data['subject_code'])->first();
                $prereq = Subject::where('subject_code', $data['subject_prerequisite_code'])->first();
                if (!$subject) throw new \Exception("Subject {$data['subject_code']} missing in DB.");
                if (!$prereq) throw new \Exception("Prerequisite {$data['subject_prerequisite_code']} not found.");
                $subject->update(['subject_prerequisite_id' => $prereq->id]);
            } catch (\Exception $e) {
                $errors[] = ['line' => $lineNum, 'message' => $e->getMessage(), 'row' => $data];
            }
        }

        fclose($handle);

        return compact('created', 'updated', 'errors');
    }
}

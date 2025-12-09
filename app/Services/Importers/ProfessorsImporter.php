<?php
// app/Services/Importers/ProfessorsImporter.php

namespace App\Services\Importers;

use App\Models\Professor;
use App\Models\Department;

class ProfessorsImporter
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
                if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email'])) {
                    throw new \Exception("Missing required: first_name, last_name, email");
                }

                $deptId = null;
                if (!empty($data['department_code'])) {
                    $dept = Department::where('department_code', $data['department_code'])->first();
                    if ($dept) $deptId = $dept->id;
                }

                $attrs = [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'middle_name' => $data['middle_name'] ?? null,
                    'gender' => $data['gender'] ?? null,
                    'email' => $data['email'],
                    'phone_number' => $data['phone_number'] ?? null,
                    'hire_date' => $this->normalizeDate($data['hire_date'] ?? null),
                    'specialization' => $data['specialization'] ?? null,
                    'status' => $data['status'] ?? 'active',
                    'department_id' => $deptId,
                ];

                $prof = Professor::where('email', $attrs['email'])->first();
                if ($prof) {
                    $prof->update($attrs);
                    $updated++;
                } else {
                    Professor::create($attrs);
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

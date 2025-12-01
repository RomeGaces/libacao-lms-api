<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SchoolYear;
use App\Models\Course;
use App\Models\ClassSection;
use App\Models\Student;

class AdminController extends Controller
{
    public function summary()
    {
        // 1. Get current active academic year
        $currentAY = SchoolYear::where('is_active', true)->first();

        // 2. Get overall statistics
        $stats = $this->getStats($currentAY?->id);

        // 3. Get breakdown per course
        $courses = $this->getCourseBreakdown($currentAY?->id);

        return response()->json([
            'current_academic_year' => $currentAY,
            'stats' => $stats,
            'courses' => $courses,
        ], 200);
    }

    private function getStats($ayId)
    {
        // If no active AY yet, return zeros
        if (!$ayId) {
            return [
                'total_students' => 0,
                'total_sections' => 0,
                'total_courses' => Course::count(),
            ];
        }

        return [
            'total_students' => Student::where('school_year_id', $ayId)->count(),
            'total_sections' => ClassSection::where('school_year_id', $ayId)->count(),
            'total_courses' => Course::count(),
        ];
    }

    private function getCourseBreakdown($ayId)
    {
        if (!$ayId) {
            // No AY → return empty structure
            return Course::get()->map(function ($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->course_name,
                    'student_count' => 0,
                    'section_count' => 0,
                    'year_levels' => 0,
                ];
            });
        }

        // GROUP BY course
        return Course::get()->map(function ($course) use ($ayId) {

            $studentCount = Student::where('course_id', $course->id)
                ->where('school_year_id', $ayId)
                ->count();

            $sectionCount = ClassSection::where('course_id', $course->id)
                ->where('school_year_id', $ayId)
                ->count();

            $yearLevels = Student::where('course_id', $course->id)
                ->where('school_year_id', $ayId)
                ->distinct()
                ->count('year_level');

            return [
                'id' => $course->id,
                'name' => $course->course_name,
                'student_count' => $studentCount,
                'section_count' => $sectionCount,
                'year_levels' => $yearLevels,
            ];
        });
    }

    public function dryRun(Request $request)
    {
        $validated = $request->validate([
            'academic_year_name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'dry_run' => 'required|boolean'
        ]);

        // Compute next school year ID
        [$start, $end] = explode('-', $validated['academic_year_name']);
        $startYear = intval(trim($start));
        $endYear   = intval(trim($end));

        // Count students per course and year level
        $studentCounts = Student::select('course_id', 'year_level', DB::raw('COUNT(*) as total'))
            ->groupBy('course_id', 'year_level')
            ->get();

        $sectionsPreview = [];

        foreach ($studentCounts as $row) {

            $capacity = 35;
            $requiredSections = ceil($row->total / $capacity);

            for ($i = 1; $i <= $requiredSections; $i++) {
                $sectionsPreview[] = [
                    'course' => Course::find($row->course_id)->course_code,
                    'year_level' => $row->year_level,
                    'name' => Course::find($row->course_id)->course_code . " {$row->year_level}-" . chr(64 + $i),
                    'capacity' => $capacity
                ];
            }
        }

        // If dry-run, return the preview only
        if ($validated['dry_run']) {
            return response()->json([
                'sections_created' => $sectionsPreview,
                'message' => 'Dry run successful.'
            ]);
        }

        // Otherwise, EXECUTE the creation
        DB::transaction(function () use ($startYear, $endYear, $sectionsPreview) {

            // Deactivate current AY
            SchoolYear::where('is_active', 1)->update(['is_active' => 0]);

            // Create new School Year
            $newAY = SchoolYear::create([
                'year_start' => $startYear,
                'year_end' => $endYear,
                'is_active' => 1,
            ]);

            // Create new class sections
            foreach ($sectionsPreview as $section) {
                ClassSection::create([
                    'section_name' => $section['name'],
                    'course_id' => Course::where('course_code', $section['course'])->first()->id,
                    'year_level' => $section['year_level'],
                    'school_year_id' => $newAY->id,
                    'semester_id' => 1, // default
                    'capacity' => $section['capacity'],
                ]);
            }
        });

        return response()->json([
            'message' => 'Master Setup executed successfully!'
        ]);
    }
}

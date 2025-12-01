<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ClassScheduleController extends Controller
{
    /**
     * Build the base query for schedule relationships.
     */
    protected function baseQuery()
    {
        return ClassSchedule::with([
            'subject:subject_id,subject_name,subject_code',
            'professor:professor_id,first_name,last_name',
            'room:room_id,room_number,building_name,capacity',
            'classSection:class_section_id,section_name,academic_year,semester,course_id',
        ]);
    }

    /**
     * Display all class schedules (filter by academic year, semester, course, professor, or room).
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'nullable|string',
            'semester' => 'nullable|string',
            'course_id' => 'nullable|integer',
            'professor_id' => 'nullable|integer',
            'room_id' => 'nullable|integer',
            'status' => 'nullable|string|in:pending,finalized',
        ]);

        $query = $this->baseQuery();

        // Apply filters dynamically
        foreach ($validated as $field => $value) {
            if (!empty($value)) {
                match ($field) {
                    'academic_year' => $query->whereHas('classSection', fn($q) => $q->where('academic_year', $value)),
                    'semester' => $query->whereHas('classSection', fn($q) => $q->where('semester', $value)),
                    'course_id' => $query->whereHas('classSection', fn($q) => $q->where('course_id', $value)),
                    'professor_id' => $query->where('professor_id', $value),
                    'room_id' => $query->where('room_id', $value),
                    'status' => $query->where('status', $value),
                    default => null,
                };
            }
        }

        $schedules = $query->get()->map(function ($s) {
            return [
                'id' => $s->class_schedule_id,
                'title' => $s->subject->subject_code . ' - ' . ($s->subject->subject_name ?? 'Undefined'),
                'professor' => $s->professor
                    ? "{$s->professor->first_name} {$s->professor->last_name}"
                    : 'Unassigned',
                'room' => $s->room
                    ? "{$s->room->room_number} - {$s->room->building_name}"
                    : 'Unassigned',
                'section' => $s->classSection->section_name ?? '-',
                'day_of_week' => $s->day_of_week ?? '-',
                'start_time' => $s->start_time ? Carbon::parse($s->start_time)->format('H:i') : null,
                'end_time' => $s->end_time ? Carbon::parse($s->end_time)->format('H:i') : null,
            ];
        });

        // ✅ Group by day_of_week, start_time, end_time
        $grouped = $schedules
            ->groupBy(function ($s) {
                return "{$s['day_of_week']}|{$s['start_time']}|{$s['end_time']}";
            })
            ->map(function (Collection $group) {
                $first = $group->first();

                return [
                    'day_of_week' => $first['day_of_week'],
                    'start_time'  => $first['start_time'],
                    'end_time'    => $first['end_time'],
                    'count'       => (int) $group->count(),
                    'label'       => $group->count() . ' classes',
                    // return a plain array for JSON consumers (avoids Collection objects)
                    'classes'     => $group->values()->all(),
                ];
            })
            ->values();

        return response()->json($grouped);
    }

    /**
     * Show a single schedule.
     */
    public function show($id)
    {
        $schedule = $this->baseQuery()->findOrFail($id);
        return response()->json($schedule);
    }

    /**
     * Create a new class schedule (supports undefined schedules).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,subject_id',
            'class_section_id' => 'required|exists:class_sections,class_section_id',
            'professor_id' => 'nullable|exists:professors,professor_id',
            'room_id' => 'nullable|exists:rooms,room_id',
            'day_of_week' => 'nullable|string|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'semester' => 'required|string',
            'academic_year' => 'required|string',
            'status' => 'nullable|in:pending,finalized',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        // Additional logic: finalized schedules must be complete
        if (($validated['status'] ?? 'pending') === 'finalized') {
            $requiredFields = ['room_id', 'professor_id', 'day_of_week', 'start_time', 'end_time'];
            foreach ($requiredFields as $f) {
                if (empty($validated[$f])) {
                    return response()->json([
                        'message' => "Finalized schedules must include $f."
                    ], 422);
                }
            }
        }

        // Conflict check only for finalized schedules
        if (($validated['status'] ?? 'pending') === 'finalized' && $this->hasConflict($request)) {
            return response()->json(['error' => 'Schedule conflict detected.'], 409);
        }

        $schedule = ClassSchedule::create($validated);
        return response()->json($schedule->load(['subject', 'professor', 'room']), 201);
    }

    /**
     * Update an existing schedule.
     */
    public function update(Request $request, $id)
    {
        $schedule = ClassSchedule::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,subject_id',
            'class_section_id' => 'required|exists:class_sections,class_section_id',
            'professor_id' => 'nullable|exists:professors,professor_id',
            'room_id' => 'nullable|exists:rooms,room_id',
            'day_of_week' => 'nullable|string|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'semester' => 'required|string',
            'academic_year' => 'required|string',
            'status' => 'nullable|in:pending,finalized',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        // Validate completeness for finalized
        if (($validated['status'] ?? 'pending') === 'finalized') {
            $requiredFields = ['room_id', 'professor_id', 'day_of_week', 'start_time', 'end_time'];
            foreach ($requiredFields as $f) {
                if (empty($validated[$f])) {
                    return response()->json([
                        'message' => "Finalized schedules must include $f."
                    ], 422);
                }
            }

            if ($this->hasConflict($request, $id)) {
                return response()->json(['error' => 'Schedule conflict detected.'], 409);
            }
        }

        $schedule->update($validated);
        return response()->json($schedule->load(['subject', 'professor', 'room']));
    }

    /**
     * Delete a schedule.
     */
    public function destroy($id)
    {
        $schedule = ClassSchedule::findOrFail($id);
        $schedule->delete();

        return response()->json(['message' => 'Schedule deleted successfully']);
    }

    /**
     * Conflict detection logic (room/professor/section).
     */
    private function hasConflict(Request $request, $excludeId = null): bool
    {
        if (!$request->day_of_week || !$request->start_time || !$request->end_time) {
            // Skip conflict check for undefined schedules
            return false;
        }

        return ClassSchedule::where('day_of_week', $request->day_of_week)
            ->where('semester', $request->semester)
            ->where('academic_year', $request->academic_year)
            ->when($excludeId, fn($q) => $q->where('class_schedule_id', '!=', $excludeId))
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q2) use ($request) {
                        $q2->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->where(function ($q) use ($request) {
                $q->where('room_id', $request->room_id)
                    ->orWhere('professor_id', $request->professor_id)
                    ->orWhere('class_section_id', $request->class_section_id);
            })
            ->exists();
    }
}

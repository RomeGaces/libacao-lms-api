<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\ClassSection;
use App\Models\Subject;
use App\Models\Professor;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ClassScheduleController extends Controller
{
    /**
     * Build the base query for schedule relationships.
     */
    protected function baseQuery()
    {
        return ClassSchedule::with([
            'subject:id,subject_code,subject_name',
            'professor:id,first_name,last_name',
            'room:id,room_number,building_name,capacity',
            'classSection:id,section_name,year_level,course_id,school_year_id,semester_id'
        ]);
    }

    /**
     * GET /api/schedules or schedules/query
     * Support filters: academic_year (school_year_id), semester_id, course_id, professor_id, room_id, status
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'school_year_id' => 'nullable|integer|exists:school_years,id',
            'semester_id' => 'nullable|integer|exists:semesters,id',
            'course_id' => 'nullable|integer|exists:courses,id',
            'professor_id' => 'nullable|integer|exists:professors,id',
            'room_id' => 'nullable|integer|exists:rooms,id',
            'status' => 'nullable|string|in:Pending,Finalized,pending,finalized',
        ]);

        $query = $this->baseQuery();

        if (!empty($validated['school_year_id'])) {
            $query->whereHas('classSection', fn($q) => $q->where('school_year_id', $validated['school_year_id']));
        }

        if (!empty($validated['semester_id'])) {
            $query->whereHas('classSection', fn($q) => $q->where('semester_id', $validated['semester_id']));
        }

        if (!empty($validated['course_id'])) {
            $query->whereHas('classSection', fn($q) => $q->where('course_id', $validated['course_id']));
        }

        if (!empty($validated['professor_id'])) {
            $query->where('professor_id', $validated['professor_id']);
        }

        if (!empty($validated['room_id'])) {
            $query->where('room_id', $validated['room_id']);
        }

        if (!empty($validated['status'])) {
            $query->where('status', ucfirst(strtolower($validated['status'])));
        }

        $schedules = $query->get()->map(function ($s) {
            return [
                'id' => $s->id,
                'title' => $s->subject?->subject_code . ' - ' . ($s->subject?->subject_name ?? 'Undefined'),
                'professor' => $s->professor ? "{$s->professor->first_name} {$s->professor->last_name}" : 'Unassigned',
                'room' => $s->room ? "{$s->room->room_number} - {$s->room->building_name}" : 'Unassigned',
                'section' => $s->classSection?->section_name ?? '-',
                'day_of_week' => $s->day_of_week ?? '-',
                'start_time' => $s->start_time ? Carbon::parse($s->start_time)->format('H:i') : null,
                'end_time' => $s->end_time ? Carbon::parse($s->end_time)->format('H:i') : null,
            ];
        });

        // group by day/time to make frontend consumption easier (same behavior as original)
        $grouped = $schedules
            ->groupBy(fn($s) => "{$s['day_of_week']}|{$s['start_time']}|{$s['end_time']}")
            ->map(fn(Collection $group) => [
                'day_of_week' => $group->first()['day_of_week'],
                'start_time' => $group->first()['start_time'],
                'end_time' => $group->first()['end_time'],
                'count' => (int) $group->count(),
                'label' => $group->count() . ' classes',
                'classes' => $group->values()->all(),
            ])
            ->values();

        return response()->json($grouped);
    }

    /**
     * GET /api/schedules/{id}
     */
    public function show($id)
    {
        $schedule = $this->baseQuery()->findOrFail($id);
        return response()->json($schedule);
    }

    /**
     * POST /api/schedules
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id',
            'class_section_id' => 'nullable|exists:class_sections,id',
            'professor_id' => 'nullable|exists:professors,id',
            'room_id' => 'nullable|exists:rooms,id',
            'day_of_week' => 'nullable|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'status' => 'nullable|in:Pending,Finalized,pending,finalized',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // If finalized, ensure required fields set
        if (($data['status'] ?? 'Pending') === 'Finalized' || ($data['status'] ?? 'pending') === 'finalized') {
            $required = ['room_id', 'professor_id', 'day_of_week', 'start_time', 'end_time'];
            foreach ($required as $f) {
                if (empty($data[$f])) {
                    return response()->json(['message' => "Finalized schedules must include {$f}."], 422);
                }
            }

            if ($this->hasConflict($data)) {
                return response()->json(['error' => 'Schedule conflict detected.'], 409);
            }
        }

        $schedule = ClassSchedule::create($data);
        return response()->json($schedule->load(['subject', 'professor', 'room', 'classSection']), 201);
    }

    /**
     * PUT /api/schedules/{id}
     */
    public function update(Request $request, $id)
    {
        $schedule = ClassSchedule::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id',
            'class_section_id' => 'nullable|exists:class_sections,id',
            'professor_id' => 'nullable|exists:professors,id',
            'room_id' => 'nullable|exists:rooms,id',
            'day_of_week' => 'nullable|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'status' => 'nullable|in:Pending,Finalized,pending,finalized',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if (($data['status'] ?? $schedule->status ?? 'Pending') === 'Finalized' || ($data['status'] ?? $schedule->status ?? '') === 'finalized') {
            $required = ['room_id', 'professor_id', 'day_of_week', 'start_time', 'end_time'];
            foreach ($required as $f) {
                if (empty($data[$f]) && empty($schedule->{$f})) {
                    return response()->json(['message' => "Finalized schedules must include {$f}."], 422);
                }
            }

            if ($this->hasConflict($data, $id)) {
                return response()->json(['error' => 'Schedule conflict detected.'], 409);
            }
        }

        $schedule->update($data);
        return response()->json($schedule->fresh()->load(['subject', 'professor', 'room', 'classSection']));
    }

    /**
     * DELETE /api/schedules/{id}
     */
    public function destroy($id)
    {
        $schedule = ClassSchedule::findOrFail($id);
        $schedule->delete();
        return response()->json(['message' => 'Schedule deleted successfully']);
    }

    /**
     * Check if a schedule conflicts (room/professor/section)
     * Accepts array or Request. $excludeId skip a specific schedule (for updates)
     */
    private function hasConflict($data, $excludeId = null): bool
    {
        // Require day & times to meaningfully check
        if (empty($data['day_of_week']) || empty($data['start_time']) || empty($data['end_time'])) {
            return false;
        }

        $query = ClassSchedule::where('day_of_week', $data['day_of_week']);

        if (!empty($data['class_section_id'])) {
            $query->where('class_section_id', $data['class_section_id']);
        }

        if (!empty($data['professor_id'])) {
            $query->where('professor_id', $data['professor_id']);
        }

        if (!empty($data['room_id'])) {
            $query->where('room_id', $data['room_id']);
        }

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        // overlapping logic
        $query->where(function ($q) use ($data) {
            $q->whereBetween('start_time', [$data['start_time'], $data['end_time']])
              ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
              ->orWhere(function ($q2) use ($data) {
                  $q2->where('start_time', '<=', $data['start_time'])
                     ->where('end_time', '>=', $data['end_time']);
              });
        });

        return $query->exists();
    }

    /**
     * POST /api/schedules/check-conflict
     * Validate a schedule payload for conflicts without creating it.
     */
    public function checkConflict(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'professor_id' => 'nullable|exists:professors,id',
            'room_id' => 'nullable|exists:rooms,id',
            'class_section_id' => 'nullable|exists:class_sections,id',
            'day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $conflict = $this->hasConflict($validator->validated());

        return response()->json(['conflict' => $conflict]);
    }

    /**
     * GET /api/schedules/timeslot/{day_of_week}/{start_hour}
     * Returns schedules that begin at a specific hour for a day.
     */
    public function getByTimeslot($day_of_week, $start_hour)
    {
        // normalize day_of_week to allowed enum
        $allowed = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        if (!in_array($day_of_week, $allowed)) {
            return response()->json(['message' => 'Invalid day_of_week'], 422);
        }

        $hour = intval($start_hour);
        $start = sprintf('%02d:00', $hour);

        $schedules = $this->baseQuery()
            ->where('day_of_week', $day_of_week)
            ->whereRaw('HOUR(start_time) = ?', [$hour])
            ->get();

        return response()->json($schedules);
    }

    /**
     * GET /api/schedules/conflicts
     * (Utility) return identified conflicts across the whole schedule set.
     */
    public function checkConflicts()
    {
        // naive conflict sweep: O(n^2) but acceptable for moderate dataset.
        $schedules = ClassSchedule::orderBy('day_of_week')->orderBy('start_time')->get();
        $conflicts = [];

        foreach ($schedules as $a) {
            foreach ($schedules as $b) {
                if ($a->id >= $b->id) continue;
                if ($a->day_of_week !== $b->day_of_week) continue;

                $overlap = $a->start_time < $b->end_time && $b->start_time < $a->end_time;

                if ($overlap) {
                    if ($a->professor_id && $a->professor_id === $b->professor_id) {
                        $conflicts[] = "Professor conflict: schedule {$a->id} and {$b->id} (professor {$a->professor_id})";
                    }

                    if ($a->room_id && $a->room_id === $b->room_id) {
                        $conflicts[] = "Room conflict: schedule {$a->id} and {$b->id} (room {$a->room_id})";
                    }

                    if ($a->class_section_id && $a->class_section_id === $b->class_section_id) {
                        $conflicts[] = "Section conflict: schedule {$a->id} and {$b->id} (section {$a->class_section_id})";
                    }
                }
            }
        }

        return response()->json(array_values(array_unique($conflicts)));
    }
}

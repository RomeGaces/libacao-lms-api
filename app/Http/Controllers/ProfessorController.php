<?php
namespace App\Http\Controllers;

use App\Models\Professor;
use Illuminate\Http\Request;

class ProfessorController extends Controller
{
    public function index(Request $request)
    {
        // Read optional query params
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10); // default 10
        $page = $request->query('page', 1);

        // Build query with eager load
        $query = Professor::with('department');

        // Apply search filter if provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        // Apply pagination (frontend expects pagination object)
        $professors = $query->orderBy('last_name')->paginate($perPage, ['*'], 'page', $page);

        // Return JSON with pagination metadata
        return response()->json([
            'data' => $professors->items(),
            'current_page' => $professors->currentPage(),
            'last_page' => $professors->lastPage(),
            'total' => $professors->total(),
            'per_page' => $professors->perPage(),
        ]);
    }

    public function show($id)
    {
        $professor = Professor::with(['department', 'classSchedules'])->findOrFail($id);
        return response()->json($professor);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'gender' => 'required|string',
            'email' => 'required|email|unique:professors,email',
            'phone_number' => 'nullable|string',
            'hire_date' => 'nullable|date',
            'specialization' => 'nullable|string',
            'status' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,department_id',
        ]);

        $professor = Professor::create($validated);
        return response()->json($professor->load('department'), 201);
    }

    public function update(Request $request, $id)
    {
        $professor = Professor::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'gender' => 'required|string',
            'email' => 'required|email|unique:professors,email,' . $id . ',professor_id',
            'phone_number' => 'nullable|string',
            'hire_date' => 'nullable|date',
            'specialization' => 'nullable|string',
            'status' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,department_id',
        ]);

        $professor->update($validated);
        return response()->json($professor->load('department'));
    }

    public function destroy($id)
    {
        $professor = Professor::findOrFail($id);
        $professor->delete();

        return response()->json(['message' => 'Professor deleted successfully']);
    }
}
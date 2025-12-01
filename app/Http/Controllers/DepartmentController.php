<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);

        $query = Department::query();

        if ($search) {
            $query->where('department_name', 'like', "%{$search}%")
                ->orWhere('department_code', 'like', "%{$search}%")
                ->orWhere('contact_email', 'like', "%{$search}%");
        }

        $departments = $query->orderBy('department_name')->paginate($perPage);

        return response()->json([
            'data' => $departments->items(),
            'current_page' => $departments->currentPage(),
            'last_page' => $departments->lastPage(),
            'total' => $departments->total(),
            'per_page' => $departments->perPage(),
        ]);
    }
    
    public function show($id)
    {
        $department = Department::findOrFail($id);
        return response()->json($department);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_code' => 'required|string|unique:departments,department_code',
            'department_name' => 'required|string',
            'office_location' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_number' => 'nullable|string',
        ]);

        $department = Department::create($validated);

        return response()->json($department, 201);
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'department_code' => 'required|string|unique:departments,department_code,' . $id . ',department_id',
            'department_name' => 'required|string',
            'office_location' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_number' => 'nullable|string',
        ]);

        $department->update($validated);

        return response()->json($department);
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return response()->json(['message' => 'Department deleted successfully']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        return response()->json(Semester::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        // If setting this semester active, deactivate others
        if ($request->is_active) {
            Semester::query()->update(['is_active' => false]);
        }

        $semester = Semester::create([
            'name' => $request->name,
            'is_active' => $request->is_active ?? false,
        ]);

        return response()->json($semester);
    }

    public function update(Request $request, $id)
    {
        $semester = Semester::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
        ]);

        if ($request->is_active) {
            Semester::query()->update(['is_active' => false]);
        }

        $semester->update([
            'name' => $request->name,
            'is_active' => $request->is_active ?? false,
        ]);

        return response()->json($semester);
    }

    public function destroy($id)
    {
        $semester = Semester::findOrFail($id);
        $semester->delete();
        return response()->json(['message' => 'Semester deleted']);
    }
}

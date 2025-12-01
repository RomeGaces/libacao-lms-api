<?php

namespace App\Http\Controllers;

use App\Models\Registrar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistrarController extends Controller
{
    public function index()
    {
        $registrars = Registrar::with('department')->get();
        return response()->json($registrars);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:registrars,email',
            'password' => 'required|string|min:8',
            'employee_number' => 'required|string|unique:registrars,employee_number',
            'department_id' => 'nullable|exists:departments,department_id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $registrar = Registrar::create($validated);
        return response()->json($registrar, 201);
    }

    public function show($id)
    {
        $registrar = Registrar::with('department')->findOrFail($id);
        return response()->json($registrar);
    }

    public function update(Request $request, $id)
    {
        $registrar = Registrar::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:100',
            'last_name' => 'sometimes|string|max:100',
            'email' => "sometimes|email|unique:registrars,email,{$id},registrar_id",
            'password' => 'nullable|string|min:8',
            'employee_number' => "sometimes|string|unique:registrars,employee_number,{$id},registrar_id",
            'department_id' => 'nullable|exists:departments,department_id',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $registrar->update($validated);
        return response()->json($registrar);
    }

    public function destroy($id)
    {
        Registrar::findOrFail($id)->delete();
        return response()->noContent();
    }
}
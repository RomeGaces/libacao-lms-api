<?php
// app/Http/Controllers/ImportController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Importers\StudentsImporter;
use App\Services\Importers\ProfessorsImporter;
use App\Services\Importers\CurriculumImporter;
use App\Services\Importers\TorImporter;

class ImportController extends Controller
{
    public function importStudents(Request $request, StudentsImporter $importer)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt']);
        $result = $importer->import($request->file('file')->getRealPath());
        return response()->json($result);
    }

    public function importProfessors(Request $request, ProfessorsImporter $importer)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt']);
        $result = $importer->import($request->file('file')->getRealPath());
        return response()->json($result);
    }

    public function importCurriculum(Request $request, CurriculumImporter $importer)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt']);
        $result = $importer->import($request->file('file')->getRealPath());
        return response()->json($result);
    }

    public function importTor(Request $request, TorImporter $importer)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt']);
        $result = $importer->import($request->file('file')->getRealPath());
        return response()->json($result);
    }
}

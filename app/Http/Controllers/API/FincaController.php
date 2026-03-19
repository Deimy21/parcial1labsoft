<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

use App\Models\Finca;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FincaController extends Controller
{
    public function index(): JsonResponse
    {
        $fincas = Finca::with(['productor', 'viveros'])->get();
        return response()->json($fincas);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'numero_catastro' => ['required', 'string', 'unique:fincas,numero_catastro'],
            'municipio'       => ['required', 'string', 'max:150'],
            'productor_id'    => ['required', 'exists:productores,id'],
        ]);

        $finca = Finca::create($data);
        $finca->load('productor');
        return response()->json($finca, 201);
    }

    public function show(Finca $finca): JsonResponse
    {
        $finca->load(['productor', 'viveros.labores']);
        return response()->json($finca);
    }

    public function update(Request $request, Finca $finca): JsonResponse
    {
        $data = $request->validate([
            'numero_catastro' => ['sometimes', 'string', Rule::unique('fincas')->ignore($finca->id)],
            'municipio'       => ['sometimes', 'string', 'max:150'],
            'productor_id'    => ['sometimes', 'exists:productores,id'],
        ]);

        $finca->update($data);
        return response()->json($finca);
    }

    public function destroy(Finca $finca): JsonResponse
    {
        $finca->delete();
        return response()->json(['message' => 'Finca eliminada correctamente.']);
    }
}

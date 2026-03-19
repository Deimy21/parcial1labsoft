<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

use App\Models\Vivero;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ViveroController extends Controller
{
    public function index(): JsonResponse
    {
        $viveros = Vivero::with(['finca.productor'])->get();
        return response()->json($viveros);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'codigo'       => [
                'required',
                'string',
                'max:50',
                // El código es único dentro de la misma Finca
                Rule::unique('viveros')->where('finca_id', $request->input('finca_id')),
            ],
            'tipo_cultivo' => ['required', 'string', 'max:150'],
            'finca_id'     => ['required', 'exists:fincas,id'],
        ]);

        $vivero = Vivero::create($data);
        $vivero->load('finca');
        return response()->json($vivero, 201);
    }

    public function show(Vivero $vivero): JsonResponse
    {
        $vivero->load(['finca.productor', 'labores.productoControl']);
        return response()->json($vivero);
    }

    public function update(Request $request, Vivero $vivero): JsonResponse
    {
        $fincaId = $request->input('finca_id', $vivero->finca_id);

        $data = $request->validate([
            'codigo'       => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('viveros')
                    ->where('finca_id', $fincaId)
                    ->ignore($vivero->id),
            ],
            'tipo_cultivo' => ['sometimes', 'string', 'max:150'],
            'finca_id'     => ['sometimes', 'exists:fincas,id'],
        ]);

        $vivero->update($data);
        return response()->json($vivero);
    }

    public function destroy(Vivero $vivero): JsonResponse
    {
        $vivero->delete();
        return response()->json(['message' => 'Vivero eliminado correctamente.']);
    }
}

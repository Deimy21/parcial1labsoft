<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

use App\Models\Labor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LaborController extends Controller
{
    public function index(): JsonResponse
    {
        $labores = Labor::with(['vivero.finca', 'productoControl'])->get();
        return response()->json($labores);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'fecha'               => ['required', 'date'],
            'descripcion'         => ['required', 'string'],
            'vivero_id'           => ['required', 'exists:viveros,id'],
            'producto_control_id' => ['required', 'exists:productos_control,id'],
        ]);

        $labor = Labor::create($data);
        $labor->load(['vivero', 'productoControl']);
        return response()->json($labor, 201);
    }

    public function show(Labor $labor): JsonResponse
    {
        $labor->load(['vivero.finca.productor', 'productoControl']);
        return response()->json($labor);
    }

    public function update(Request $request, Labor $labor): JsonResponse
    {
        $data = $request->validate([
            'fecha'               => ['sometimes', 'date'],
            'descripcion'         => ['sometimes', 'string'],
            'vivero_id'           => ['sometimes', 'exists:viveros,id'],
            'producto_control_id' => ['sometimes', 'exists:productos_control,id'],
        ]);

        $labor->update($data);
        return response()->json($labor->fresh()->load('productoControl'));
    }

    public function destroy(Labor $labor): JsonResponse
    {
        $labor->delete();
        return response()->json(['message' => 'Labor eliminada correctamente.']);
    }
}

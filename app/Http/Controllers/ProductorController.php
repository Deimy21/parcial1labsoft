<?php

namespace App\Http\Controllers;

use App\Models\Productor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductorController extends Controller
{
    public function index(): JsonResponse
    {
        $productores = Productor::with('fincas')->get();
        return response()->json($productores);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'documento_identidad' => ['required', 'string', 'unique:productores,documento_identidad'],
            'nombre'              => ['required', 'string', 'max:100'],
            'apellido'            => ['required', 'string', 'max:100'],
            'telefono'            => ['required', 'string', 'max:20'],
            'correo'              => ['required', 'email', 'unique:productores,correo'],
        ]);

        $productor = Productor::create($data);
        return response()->json($productor, 201);
    }

    public function show(Productor $productor): JsonResponse
    {
        $productor->load('fincas.viveros');
        return response()->json($productor);
    }

    public function update(Request $request, Productor $productor): JsonResponse
    {
        $data = $request->validate([
            'documento_identidad' => ['sometimes', 'string', Rule::unique('productores')->ignore($productor->id)],
            'nombre'              => ['sometimes', 'string', 'max:100'],
            'apellido'            => ['sometimes', 'string', 'max:100'],
            'telefono'            => ['sometimes', 'string', 'max:20'],
            'correo'              => ['sometimes', 'email', Rule::unique('productores')->ignore($productor->id)],
        ]);

        $productor->update($data);
        return response()->json($productor);
    }

    public function destroy(Productor $productor): JsonResponse
    {
        $productor->delete();
        return response()->json(['message' => 'Productor eliminado correctamente.']);
    }
}

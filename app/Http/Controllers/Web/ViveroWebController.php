<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Vivero;
use Illuminate\Http\Request;
use App\Models\Productor;


class ViveroWebController extends Controller
{
    public function index()
    {
        $viveros = Vivero::with('productor')->paginate(10);
        return view('viveros.index', compact('viveros'));
    }

    public function create()
    {
        $productores = Productor::all();

        return view('viveros.create', compact('productores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:255|unique:viveros,codigo',
            'nombre' => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'productor_id' => 'required|exists:productores,id',
        ]);

        Vivero::create($validated);

        return redirect()->route('viveros.index')
            ->with('success', 'Vivero creado exitosamente.');
    }

    public function show(Vivero $vivero)
    {
        return view('viveros.show', compact('vivero'));
    }

    public function edit(Vivero $vivero)
    {
        $productores = Productor::all();
        return view('viveros.edit', compact('vivero', 'productores'));
    }

    public function update(Request $request, Vivero $vivero)
    {
        $validated = $request->validate([
            
            'codigo' => 'required|string|max:255|unique:viveros,codigo,' . $vivero->id,
            'nombre' => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
        ]);

        $vivero->update($validated);

        return redirect()->route('viveros.index')
            ->with('success', 'Vivero actualizado exitosamente.');
    }

    public function destroy(Vivero $vivero)
    {
        $vivero->delete();

        return redirect()->route('viveros.index')
            ->with('success', 'Vivero eliminado exitosamente.');
    }
}
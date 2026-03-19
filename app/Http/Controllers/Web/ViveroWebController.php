<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Finca;
use App\Models\Vivero;
use Illuminate\Http\Request;

class ViveroWebController extends Controller
{
    public function index()
    {
        $viveros = Vivero::with('finca.productor')->paginate(10);
        return view('viveros.index', compact('viveros'));
    }

    public function create()
    {
        $fincas = Finca::with('productor')->get();
        return view('viveros.create', compact('fincas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'finca_id' => 'required|exists:fincas,id',
            'codigo' => 'required|string|max:255|unique:viveros,codigo,NULL,id,finca_id,' . $request->finca_id,
            'tipo_cultivo' => 'required|string|max:255'
        ], [
            'codigo.unique' => 'Ya existe un vivero con este código en la finca seleccionada.'
        ]);

        Vivero::create($validated);

        return redirect()->route('viveros.index')
            ->with('success', 'Vivero creado exitosamente.');
    }

    public function show(Vivero $vivero)
    {
        $vivero->load('finca.productor', 'labores.productoControl');
        return view('viveros.show', compact('vivero'));
    }

    public function edit(Vivero $vivero)
    {
        $fincas = Finca::with('productor')->get();
        return view('viveros.edit', compact('vivero', 'fincas'));
    }

    public function update(Request $request, Vivero $vivero)
    {
        $validated = $request->validate([
            'finca_id' => 'required|exists:fincas,id',
            'codigo' => 'required|string|max:255|unique:viveros,codigo,' . $vivero->id . ',id,finca_id,' . $request->finca_id,
            'tipo_cultivo' => 'required|string|max:255'
        ], [
            'codigo.unique' => 'Ya existe un vivero con este código en la finca seleccionada.'
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

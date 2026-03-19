<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Finca;
use App\Models\Productor;
use Illuminate\Http\Request;

class FincaWebController extends Controller
{
    public function index()
    {
        $fincas = Finca::with('productor', 'viveros')->paginate(10);

        return view('fincas.index', compact('fincas'));
    }
    /**
     * Muestra el form para crear una finca asociada a un productor.
     * Se accede con GET /fincas/create?productor_id=X
     */
    public function create(Request $request)
    {
        $productor = Productor::findOrFail($request->query('productor_id'));
        return view('fincas.create', compact('productor'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'numero_catastro' => ['required', 'string', 'max:100'],
            'municipio'       => ['required', 'string', 'max:150'],
            'productor_id'    => ['required', 'exists:productores,id'],
        ]);

        Finca::create($data);

        return redirect()->route('productores.show', $data['productor_id'])
            ->with('success', 'Finca agregada correctamente.');
    }

    public function edit(Finca $finca)
    {
        $finca->load('productor');
        return view('fincas.edit', compact('finca'));
    }

    public function update(Request $request, Finca $finca)
    {
        $data = $request->validate([
            'numero_catastro' => ['required', 'string', 'max:100'],
            'municipio'       => ['required', 'string', 'max:150'],
        ]);

        $finca->update($data);

        return redirect()->route('productores.show', $finca->productor_id)
            ->with('success', 'Finca actualizada correctamente.');
    }

    public function destroy(Finca $finca)
    {
        $productorId = $finca->productor_id;
        $finca->delete();

        return redirect()->route('productores.show', $productorId)
            ->with('success', 'Finca eliminada correctamente.');
    }
}

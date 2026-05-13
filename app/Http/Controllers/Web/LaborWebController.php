<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Labor;
use App\Models\ProductoControl;
use App\Models\Vivero;
use Illuminate\Http\Request;

class LaborWebController extends Controller
{
    public function index()
    {
        $labores = Labor::with(['vivero', 'productoControl'])
            ->latest('fecha')
            ->paginate(15);

        return view('labores.index', compact('labores'));
    }

    public function create()
    {
        $viveros = Vivero::orderBy('codigo')->get();
        $productosControl = ProductoControl::orderBy('tipo')
            ->orderBy('nombre_producto')
            ->get();

        return view('labores.create', compact('viveros', 'productosControl'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha'               => ['required', 'date'],
            'descripcion'         => ['required', 'string'],
            'vivero_id'           => ['required', 'exists:viveros,id'],
            'producto_control_id' => ['required', 'exists:productos_control,id'],
        ]);

        Labor::create($data);
        return redirect()->route('labores.index')
            ->with('success', 'Labor registrada correctamente.');
    }

    public function edit(Labor $labor)
    {
        $viveros = Vivero::with('productor')->orderBy('codigo')->get();
        $productosControl = ProductoControl::orderBy('tipo')->orderBy('nombre_producto')->get();

        return view('labores.edit', compact('labor', 'viveros', 'productosControl'));
    }

    public function update(Request $request, Labor $labor)
    {
        $data = $request->validate([
            'fecha'               => ['required', 'date'],
            'descripcion'         => ['required', 'string'],
            'vivero_id'           => ['required', 'exists:viveros,id'],
            'producto_control_id' => ['required', 'exists:productos_control,id'],
        ]);

        $labor->update($data);
        return redirect()->route('labores.index')
            ->with('success', 'Labor actualizada correctamente.');
    }

    public function destroy(Labor $labor)
    {
        $labor->delete();
        return redirect()->route('labores.index')
            ->with('success', 'Labor eliminada correctamente.');
    }
}

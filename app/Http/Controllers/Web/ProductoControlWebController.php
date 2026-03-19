<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ProductoControl;
use Illuminate\Http\Request;

class ProductoControlWebController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductoControl::query();

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $productos = $query->orderBy('tipo')->orderBy('nombre_producto')->paginate(15)->withQueryString();

        return view('productos_control.index', compact('productos'));
    }

    public function create()
    {
        return view('productos_control.create');
    }

    public function store(Request $request)
    {
        $tipo = $request->input('tipo');

        $reglas = [
            'tipo'                  => ['required', 'in:hongo,plaga,fertilizante'],
            'registro_ica'          => ['required', 'string', 'max:100'],
            'nombre_producto'       => ['required', 'string', 'max:200'],
            'frecuencia_aplicacion' => ['required', 'integer', 'min:1'],
            'valor_producto'        => ['required', 'numeric', 'min:0'],
        ];

        $reglas += match ($tipo) {
            'hongo'        => [
                'nombre_hongo'     => ['required', 'string', 'max:150'],
                'periodo_carencia_hongo' => ['required', 'integer', 'min:0'],
            ],
            'plaga'        => [
                'periodo_carencia' => ['required', 'integer', 'min:0'],
            ],
            'fertilizante' => [
                'fecha_ultima_aplicacion' => ['required', 'date'],
            ],
            default => [],
        };

        $data = $request->validate($reglas);

        // Normalizar el campo periodo_carencia para hongo
        if ($tipo === 'hongo') {
            $data['periodo_carencia'] = $data['periodo_carencia_hongo'];
            unset($data['periodo_carencia_hongo']);
        }

        ProductoControl::create($data);
        return redirect()->route('productos-control.index')
            ->with('success', 'Producto de control creado correctamente.');
    }

    public function edit(ProductoControl $productoControl)
    {
        return view('productos_control.edit', compact('productoControl'));
    }

    public function update(Request $request, ProductoControl $productoControl)
    {
        $tipo = $productoControl->tipo;

        $reglas = [
            'registro_ica'          => ['required', 'string', 'max:100'],
            'nombre_producto'       => ['required', 'string', 'max:200'],
            'frecuencia_aplicacion' => ['required', 'integer', 'min:1'],
            'valor_producto'        => ['required', 'numeric', 'min:0'],
        ];

        $reglas += match ($tipo) {
            'hongo'        => [
                'nombre_hongo'     => ['required', 'string', 'max:150'],
                'periodo_carencia' => ['required', 'integer', 'min:0'],
            ],
            'plaga'        => [
                'periodo_carencia' => ['required', 'integer', 'min:0'],
            ],
            'fertilizante' => [
                'fecha_ultima_aplicacion' => ['required', 'date'],
            ],
            default => [],
        };

        $data = $request->validate($reglas);
        $productoControl->update($data);

        return redirect()->route('productos-control.index')
            ->with('success', 'Producto de control actualizado correctamente.');
    }

    public function destroy(ProductoControl $productoControl)
    {
        if ($productoControl->labores()->exists()) {
            return back()->with('error', 'No puedes eliminar este producto porque tiene labores asociadas.');
        }

        $productoControl->delete();

        return redirect()->route('productos-control.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}

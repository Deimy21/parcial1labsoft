<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

use App\Models\ProductoControl;
use App\Models\ProductoControlFertilizante;
use App\Models\ProductoControlHongo;
use App\Models\ProductoControlPlaga;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductoControlController extends Controller
{
    /**
     * Reglas de validación base (comunes a todos los subtipos).
     */
    private array $reglasBase = [
        'tipo'                  => ['required', 'in:hongo,plaga,fertilizante'],
        'registro_ica'          => ['required', 'string', 'max:100'],
        'nombre_producto'       => ['required', 'string', 'max:200'],
        'frecuencia_aplicacion' => ['required', 'integer', 'min:1'],
        'valor_producto'        => ['required', 'numeric', 'min:0'],
    ];

    public function index(): JsonResponse
    {
        $productos = ProductoControl::all();
        return response()->json($productos);
    }

    public function store(Request $request): JsonResponse
    {
        $reglas = array_merge($this->reglasBase, $this->reglasPorTipo($request->input('tipo')));
        $data   = $request->validate($reglas);

        // Instanciamos la subclase correcta según el tipo
        $producto = match ($data['tipo']) {
            'hongo'        => ProductoControlHongo::create($data),
            'plaga'        => ProductoControlPlaga::create($data),
            'fertilizante' => ProductoControlFertilizante::create($data),
        };

        return response()->json($producto, 201);
    }

    public function show(ProductoControl $productoControl): JsonResponse
    {
        return response()->json($productoControl->load('labores'));
    }

    public function update(Request $request, ProductoControl $productoControl): JsonResponse
    {
        $tipo  = $request->input('tipo', $productoControl->tipo);
        $reglas = [
            'registro_ica'          => ['sometimes', 'string', 'max:100'],
            'nombre_producto'       => ['sometimes', 'string', 'max:200'],
            'frecuencia_aplicacion' => ['sometimes', 'integer', 'min:1'],
            'valor_producto'        => ['sometimes', 'numeric', 'min:0'],
        ];

        $data = $request->validate(array_merge($reglas, $this->reglasPorTipo($tipo, 'sometimes')));
        $productoControl->update($data);
        return response()->json($productoControl->fresh());
    }

    public function destroy(ProductoControl $productoControl): JsonResponse
    {
        $productoControl->delete();
        return response()->json(['message' => 'Producto de control eliminado correctamente.']);
    }

    /**
     * Retorna las reglas de validación adicionales según el tipo de producto.
     */
    private function reglasPorTipo(?string $tipo, string $presence = 'required'): array
    {
        return match ($tipo) {
            'hongo' => [
                'periodo_carencia' => [$presence, 'integer', 'min:0'],
                'nombre_hongo'     => [$presence, 'string', 'max:150'],
            ],
            'plaga' => [
                'periodo_carencia' => [$presence, 'integer', 'min:0'],
            ],
            'fertilizante' => [
                'fecha_ultima_aplicacion' => [$presence, 'date'],
            ],
            default => [],
        };
    }
}

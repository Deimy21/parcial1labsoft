<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
#use App\Models\Finca;
use App\Models\Labor;
use App\Models\ProductoControl;
use App\Models\Productor;
use App\Models\Vivero;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'productores'       => Productor::count(),
            #'fincas'            => Finca::count(),
            'viveros'           => Vivero::count(),
            'labores'           => Labor::count(),
            'productos_control' => ProductoControl::count(),
        ];

        $ultimasLabores = Labor::with(['vivero', 'productoControl'])
            ->latest('fecha')
            ->take(6)
            ->get();

        $productosPorTipo = ProductoControl::selectRaw('tipo, count(*) as total')
            ->groupBy('tipo')
            ->pluck('total', 'tipo');

        return view('dashboard', compact('stats', 'ultimasLabores', 'productosPorTipo'));
    }
}

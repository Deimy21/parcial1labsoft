<?php

use App\Http\Controllers\API\FincaController;
use App\Http\Controllers\API\LaborController;
use App\Http\Controllers\API\ProductoControlController;
use App\Http\Controllers\API\ProductorController;
use App\Http\Controllers\API\ViveroController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Sistema de Administración de Viveros
|--------------------------------------------------------------------------
|
| Los nombres de ruta llevan el prefijo "api." para evitar colisiones
| con las rutas web (productores.index, labores.index, etc.)
|
*/

Route::prefix('v1')->name('api.')->group(function () {

    // Productores
    Route::apiResource('productores', ProductorController::class);

    // Fincas
    Route::apiResource('fincas', FincaController::class);

    // Viveros
    Route::apiResource('viveros', ViveroController::class);

    // Productos de Control (Hongo, Plaga, Fertilizante)
    Route::apiResource('productos-control', ProductoControlController::class);

    // Labores
    Route::apiResource('labores', LaborController::class);

    // --- Rutas anidadas / de conveniencia ---

    // Fincas de un Productor
    Route::get('productores/{productor}/fincas', function (\App\Models\Productor $productor) {
        return response()->json($productor->fincas()->with('viveros')->get());
    })->name('productores.fincas');

    // Viveros de una Finca
    Route::get('fincas/{finca}/viveros', function (\App\Models\Finca $finca) {
        return response()->json($finca->viveros()->with('labores')->get());
    })->name('fincas.viveros');

    // Labores de un Vivero
    Route::get('viveros/{vivero}/labores', function (\App\Models\Vivero $vivero) {
        return response()->json($vivero->labores()->with('productoControl')->get());
    })->name('viveros.labores');
});
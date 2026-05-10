<?php

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\FincaWebController;
use App\Http\Controllers\Web\LaborWebController;
use App\Http\Controllers\Web\ProductoControlWebController;
use App\Http\Controllers\Web\ProductorWebController;
use App\Http\Controllers\Web\ViveroWebController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes — Sistema de Administración de Viveros
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Rutas protegidas para todos los módulos 
    // (Dashboard, Productores, Fincas, Labores, Productos de Control, Viveros)

     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

     // Productores
     Route::resource('productores', ProductorWebController::class)
          ->parameters(['productores' => 'productor']);

     // Fincas (CRUD manejado desde show del productor)
     Route::get('fincas/create',       [FincaWebController::class, 'create'])->name('fincas.create');
     Route::post('fincas',             [FincaWebController::class, 'store'])->name('fincas.store');
     Route::get('fincas/{finca}/edit', [FincaWebController::class, 'edit'])->name('fincas.edit');
     Route::put('fincas/{finca}',      [FincaWebController::class, 'update'])->name('fincas.update');
     Route::delete('fincas/{finca}',   [FincaWebController::class, 'destroy'])->name('fincas.destroy');
     Route::get('fincas/{finca}',      [FincaWebController::class, 'show'])->name('fincas.show');
     Route::get('fincas',               [FincaWebController::class, 'index'])->name('fincas.index');

     // Labores
     Route::resource('labores', LaborWebController::class)
          ->parameters(['labores' => 'labor'])
          ->except(['show']);

     // Productos de Control
     Route::resource('productos-control', ProductoControlWebController::class)
          ->parameters(['productos-control' => 'productoControl']);

     // Viveros
     Route::resource('viveros', ViveroWebController::class)
          ->parameters(['viveros' => 'vivero']);
});

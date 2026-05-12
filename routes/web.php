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

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/', [AuthController::class, 'showLogin'])
     ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
     ->name('logout');


/*
|--------------------------------------------------------------------------
| RUTAS PARA TODOS LOS USUARIOS AUTENTICADOS
| (Administrador y Empleado)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

     /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

     Route::get('/dashboard', [DashboardController::class, 'index'])
          ->name('dashboard');


     /*
    |--------------------------------------------------------------------------
    | PRODUCTORES (SOLO VER)
    |--------------------------------------------------------------------------
    */

     Route::get('productores', [ProductorWebController::class, 'index'])
          ->name('productores.index');


     /*
//     |--------------------------------------------------------------------------
//     | FINCAS (SOLO VER)
//     |--------------------------------------------------------------------------
//     */

//      Route::get('fincas', [FincaWebController::class, 'index'])
//           ->name('fincas.index');

//      Route::get('fincas/{finca}', [FincaWebController::class, 'show'])
//           ->name('fincas.show');


//      
     /*
    |--------------------------------------------------------------------------
    | VIVEROS (SOLO VER)
    |--------------------------------------------------------------------------
    */

     Route::get('viveros', [ViveroWebController::class, 'index'])
          ->name('viveros.index');


     /*
    |--------------------------------------------------------------------------
    | LABORES (SOLO VER)
    |--------------------------------------------------------------------------
    */

     Route::get('labores', [LaborWebController::class, 'index'])
          ->name('labores.index');


     /*
    |--------------------------------------------------------------------------
    | PRODUCTOS DE CONTROL (SOLO VER)
    |--------------------------------------------------------------------------
    */

     Route::get('productos-control', [ProductoControlWebController::class, 'index'])
          ->name('productos-control.index');
});


/*
|--------------------------------------------------------------------------
| RUTAS SOLO ADMINISTRADOR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:administrador'])->group(function () {

     /*
    |--------------------------------------------------------------------------
    | PRODUCTORES
    |--------------------------------------------------------------------------
    */

     Route::get('productores/create', [ProductorWebController::class, 'create'])
          ->name('productores.create');

     Route::post('productores', [ProductorWebController::class, 'store'])
          ->name('productores.store');

     Route::get('productores/{productor}/edit', [ProductorWebController::class, 'edit'])
          ->name('productores.edit');

     Route::put('productores/{productor}', [ProductorWebController::class, 'update'])
          ->name('productores.update');

     Route::delete('productores/{productor}', [ProductorWebController::class, 'destroy'])
          ->name('productores.destroy');


     /*
    |--------------------------------------------------------------------------
    | FINCAS
    |--------------------------------------------------------------------------
    */

     // Route::get('fincas/create', [FincaWebController::class, 'create'])
     //      ->name('fincas.create');

     // Route::post('fincas', [FincaWebController::class, 'store'])
     //      ->name('fincas.store');

     // Route::get('fincas/{finca}/edit', [FincaWebController::class, 'edit'])
     //      ->name('fincas.edit');

     // Route::put('fincas/{finca}', [FincaWebController::class, 'update'])
     //      ->name('fincas.update');

     // Route::delete('fincas/{finca}', [FincaWebController::class, 'destroy'])
     //      ->name('fincas.destroy');

     Route::get('productores/{productor}', [ProductorWebController::class, 'show'])
          ->name('productores.show');
     /*
    |--------------------------------------------------------------------------
    | VIVEROS
    |--------------------------------------------------------------------------
    */

     Route::get('viveros/create', [ViveroWebController::class, 'create'])
          ->name('viveros.create');

     Route::post('viveros', [ViveroWebController::class, 'store'])
          ->name('viveros.store');

     Route::get('viveros/{vivero}/edit', [ViveroWebController::class, 'edit'])
          ->name('viveros.edit');

     Route::put('viveros/{vivero}', [ViveroWebController::class, 'update'])
          ->name('viveros.update');

     Route::delete('viveros/{vivero}', [ViveroWebController::class, 'destroy'])
          ->name('viveros.destroy');

     Route::get('viveros/{vivero}', [ViveroWebController::class, 'show'])
          ->name('viveros.show');


     /*
    |--------------------------------------------------------------------------
    | LABORES
    |--------------------------------------------------------------------------
    */

     Route::get('labores/create', [LaborWebController::class, 'create'])
          ->name('labores.create');

     Route::post('labores', [LaborWebController::class, 'store'])
          ->name('labores.store');

     Route::get('labores/{labor}/edit', [LaborWebController::class, 'edit'])
          ->name('labores.edit');

     Route::put('labores/{labor}', [LaborWebController::class, 'update'])
          ->name('labores.update');

     Route::delete('labores/{labor}', [LaborWebController::class, 'destroy'])
          ->name('labores.destroy');


     /*
    |--------------------------------------------------------------------------
    | PRODUCTOS DE CONTROL
    |--------------------------------------------------------------------------
    */

     Route::get('productos-control/create', [ProductoControlWebController::class, 'create'])
          ->name('productos-control.create');

     Route::post('productos-control', [ProductoControlWebController::class, 'store'])
          ->name('productos-control.store');

     Route::get('productos-control/{productoControl}/edit', [ProductoControlWebController::class, 'edit'])
          ->name('productos-control.edit');

     Route::put('productos-control/{productoControl}', [ProductoControlWebController::class, 'update'])
          ->name('productos-control.update');

     Route::delete('productos-control/{productoControl}', [ProductoControlWebController::class, 'destroy'])
          ->name('productos-control.destroy');

     Route::get('productos-control/{productoControl}', [ProductoControlWebController::class, 'show'])
          ->name('productos-control.show');
});

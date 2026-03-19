<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

/**
 * RouteServiceProvider — Laravel 10
 *
 * Si usas Laravel 11, este archivo no es necesario.
 * En Laravel 11 las rutas se registran en bootstrap/app.php.
 *
 * Si usas Laravel 10, asegúrate de registrar este provider
 * en config/app.php dentro del array 'providers'.
 */
class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/dashboard';

    public function boot(): void
    {
        $this->routes(function () {
            // Rutas de la API (prefijo /api/v1 ya definido en routes/api.php)
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Rutas web (Blade)
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}

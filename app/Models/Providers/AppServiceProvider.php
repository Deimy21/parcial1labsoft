<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * Activa el estilo Tailwind para los links de paginación generados
     * por $collection->links() en las vistas Blade.
     */
    public function boot(): void
    {
        Paginator::useTailwind();
    }
}

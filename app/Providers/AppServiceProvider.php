<?php

namespace App\Providers;

use App\Models\Insumo;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
     */
    public function boot(): void
    {
        // Fuerza paginación estilo Bootstrap para evitar íconos SVG gigantes.
        Paginator::useBootstrapFive();

        View::composer('*', function ($view): void {
            $totalAlertasStock = 0;

            // Evita error si la tabla aún no existe (por ejemplo en instalación inicial).
            if (Schema::hasTable('insumos')) {
                if (Schema::hasColumn('insumos', 'activo')) {
                    $totalAlertasStock = Insumo::where('activo', true)
                        ->whereColumn('stock_actual', '<=', 'stock_minimo')
                        ->count();
                } else {
                    $totalAlertasStock = Insumo::whereColumn('stock_actual', '<=', 'stock_minimo')
                        ->count();
                }
            }

            $view->with('totalAlertasStock', $totalAlertasStock);
        });
    }
}

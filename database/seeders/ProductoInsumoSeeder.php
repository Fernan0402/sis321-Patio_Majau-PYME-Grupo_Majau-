<?php

namespace Database\Seeders;

use App\Models\Insumo;
use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoInsumoSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('producto_insumo')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $majauBatido = Producto::where('nombre', 'Majau Batido')->first();
        $majauSeco = Producto::where('nombre', 'Majau Seco')->first();
        $charqueHuevo = Producto::where('nombre', 'Charque con Huevo')->first();
        $sopaMani = Producto::where('nombre', 'Sopa de Maní')->first();

        $charque = Insumo::where('nombre', 'Charque')->first();
        $arroz = Insumo::where('nombre', 'Arroz')->first();
        $platano = Insumo::where('nombre', 'Plátano')->first();
        $huevo = Insumo::where('nombre', 'Huevo')->first();
        $mani = Insumo::where('nombre', 'Mani')->first();

        if ($majauBatido && $charque && $arroz && $platano) {
            $majauBatido->insumos()->attach($charque->id, ['cantidad_necesaria' => 1]);
            $majauBatido->insumos()->attach($arroz->id, ['cantidad_necesaria' => 1]);
            $majauBatido->insumos()->attach($platano->id, ['cantidad_necesaria' => 1]);
        }

        if ($majauSeco && $charque && $arroz && $platano) {
            $majauSeco->insumos()->attach($charque->id, ['cantidad_necesaria' => 1]);
            $majauSeco->insumos()->attach($arroz->id, ['cantidad_necesaria' => 1]);
            $majauSeco->insumos()->attach($platano->id, ['cantidad_necesaria' => 2]);
        }

        if ($charqueHuevo && $charque && $huevo) {
            $charqueHuevo->insumos()->attach($charque->id, ['cantidad_necesaria' => 1]);
            $charqueHuevo->insumos()->attach($huevo->id, ['cantidad_necesaria' => 2]);
        }

        if ($sopaMani && $mani) {
            $sopaMani->insumos()->attach($mani->id, ['cantidad_necesaria' => 1]);
        }
    }
}

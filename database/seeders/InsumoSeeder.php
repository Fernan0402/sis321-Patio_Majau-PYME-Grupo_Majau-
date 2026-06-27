<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Insumo;
use Illuminate\Support\Facades\DB;

class InsumoSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Insumo::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $insumos = [
            ['nombre' => 'Charque', 'stock_actual' => 50, 'stock_minimo' => 20, 'unidad_medida' => 'kg'],
            ['nombre' => 'Arroz', 'stock_actual' => 30, 'stock_minimo' => 10, 'unidad_medida' => 'kg'],
            ['nombre' => 'Plátano', 'stock_actual' => 80, 'stock_minimo' => 30, 'unidad_medida' => 'Unidad'],
            ['nombre' => 'Huevo', 'stock_actual' => 120, 'stock_minimo' => 50, 'unidad_medida' => 'Unidad'],
            ['nombre' => 'Mani', 'stock_actual' => 15, 'stock_minimo' => 5, 'unidad_medida' => 'kg'],
        ];

        foreach ($insumos as $insumo) {
            Insumo::create($insumo);
        }

        $this->command->info('✅ Insumos creados exitosamente!');
    }
}
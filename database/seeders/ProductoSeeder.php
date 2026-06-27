<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Producto::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $productos = [
            [
                'nombre' => 'Majau Batido', 
                'descripcion' => 'Plato típico cruceño con charque, arroz y plátano', 
                'precio' => 45.00, 
                'categoria' => 'Plato Principal', 
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Majau Seco', 
                'descripcion' => 'Plato típico cruceño con charque, arroz y plátano frito', 
                'precio' => 50.00, 
                'categoria' => 'Plato Principal', 
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Charque con Huevo', 
                'descripcion' => 'Delicioso charque acompañado de huevos fritos', 
                'precio' => 35.00, 
                'categoria' => 'Desayunos', 
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Sopa de Maní', 
                'descripcion' => 'Sopa tradicional con maní y verduras', 
                'precio' => 25.00, 
                'categoria' => 'Sopas', 
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Refresco de Mocochinchi', 
                'descripcion' => 'Bebida típica de la región', 
                'precio' => 12.00, 
                'categoria' => 'Bebidas', 
                'estado' => 'Activo'
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }

        $this->command->info('✅ Productos creados exitosamente!');
    }
}
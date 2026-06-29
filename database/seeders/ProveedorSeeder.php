<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProveedorSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Proveedor::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $proveedores = [
            [
                'nombre' => 'Distribuidora Oriente',
                'telefono' => '70000001',
                'email' => 'oriente@proveedor.bo',
                'direccion' => 'Av. Alemana, Santa Cruz',
                'activo' => true,
            ],
            [
                'nombre' => 'Abasto Camba',
                'telefono' => '70000002',
                'email' => 'abasto@proveedor.bo',
                'direccion' => 'Zona Abasto, Santa Cruz',
                'activo' => true,
            ],
            [
                'nombre' => 'Insumos del Sur',
                'telefono' => '70000003',
                'email' => 'sur@proveedor.bo',
                'direccion' => 'Doble vía La Guardia',
                'activo' => true,
            ],
        ];

        foreach ($proveedores as $proveedor) {
            Proveedor::create($proveedor);
        }
    }
}

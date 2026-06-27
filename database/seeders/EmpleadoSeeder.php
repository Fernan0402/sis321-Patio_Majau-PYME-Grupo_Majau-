<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empleado;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmpleadoSeeder extends Seeder
{
    public function run(): void
    {
        // En lugar de truncate, eliminamos los registros existentes
        // y reiniciamos el auto-increment
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Empleado::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $empleados = [
            [
                'nombre' => 'Admin',
                'apellido' => 'Sistema',
                'usuario' => 'admin',
                'contrasena' => Hash::make('123456'),
                'rol' => 'Administrador',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Carlos',
                'apellido' => 'Mendez',
                'usuario' => 'mesero1',
                'contrasena' => Hash::make('123456'),
                'rol' => 'Mesero',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Ana',
                'apellido' => 'Garcia',
                'usuario' => 'cajero1',
                'contrasena' => Hash::make('123456'),
                'rol' => 'Cajero',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Jose',
                'apellido' => 'Lopez',
                'usuario' => 'cocinero1',
                'contrasena' => Hash::make('123456'),
                'rol' => 'Cocinero',
                'estado' => 'Activo'
            ]
        ];

        foreach ($empleados as $empleado) {
            Empleado::create($empleado);
        }

        $this->command->info('✅ Empleados creados exitosamente!');
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Ejecutar nuestros seeders en el orden correcto
        $this->call([
            EmpleadoSeeder::class,
            MesaSeeder::class,
            InsumoSeeder::class,
            ProductoSeeder::class,
            ProductoInsumoSeeder::class,
        ]);
    }
}
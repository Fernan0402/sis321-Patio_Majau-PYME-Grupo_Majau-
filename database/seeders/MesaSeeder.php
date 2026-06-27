<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mesa;
use Illuminate\Support\Facades\DB;

class MesaSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Mesa::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        for ($i = 1; $i <= 10; $i++) {
            Mesa::create([
                'numero_mesa' => $i,
                'capacidad' => ($i % 2 == 0) ? 4 : 2,
                'estado' => 'Disponible'
            ]);
        }

        $this->command->info('✅ Mesas creadas exitosamente!');
    }
}
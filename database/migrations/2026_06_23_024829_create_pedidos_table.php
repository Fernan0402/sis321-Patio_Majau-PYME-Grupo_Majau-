<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mesa_id')->constrained();
            $table->foreignId('empleado_id')->constrained();
            $table->enum('tipo_pedido', ['Mesa', 'Delivery'])->default('Mesa');
            $table->enum('estado', ['Pendiente', 'En Preparación', 'Listo', 'Entregado'])->default('Pendiente');
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamp('fecha_hora')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained();
            $table->foreignId('empleado_id')->constrained();
            $table->decimal('monto_total', 10, 2);
            $table->enum('metodo_pago', ['Efectivo', 'Tarjeta Débito', 'Tarjeta Crédito', 'QR'])->default('Efectivo');
            $table->timestamp('fecha_hora')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
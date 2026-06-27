<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained();
            $table->string('numero_factura', 50)->unique();
            $table->string('razon_social_cliente', 150)->nullable();
            $table->string('nit_cliente', 50)->nullable();
            $table->decimal('monto_total', 10, 2);
            $table->timestamp('fecha_emision')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
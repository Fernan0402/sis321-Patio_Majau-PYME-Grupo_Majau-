<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_compra_insumos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compra_insumo_id')->constrained('compra_insumos')->onDelete('cascade');
            $table->foreignId('insumo_id')->constrained('insumos');
            $table->decimal('cantidad', 10, 2);
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_compra_insumos');
    }
};

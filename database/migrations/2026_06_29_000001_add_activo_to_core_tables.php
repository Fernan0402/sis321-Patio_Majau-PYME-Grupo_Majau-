<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->boolean('activo')->default(true)->after('estado');
        });

        Schema::table('productos', function (Blueprint $table) {
            $table->boolean('activo')->default(true)->after('estado');
        });

        Schema::table('mesas', function (Blueprint $table) {
            $table->boolean('activo')->default(true)->after('estado');
        });

        Schema::table('insumos', function (Blueprint $table) {
            $table->boolean('activo')->default(true)->after('unidad_medida');
        });
    }

    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropColumn('activo');
        });

        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('activo');
        });

        Schema::table('mesas', function (Blueprint $table) {
            $table->dropColumn('activo');
        });

        Schema::table('insumos', function (Blueprint $table) {
            $table->dropColumn('activo');
        });
    }
};

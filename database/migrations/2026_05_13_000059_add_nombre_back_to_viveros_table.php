<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Restaura la columna `nombre` en la tabla viveros.
     * Esta columna fue eliminada por error en la migración
     * 2026_05_12_103703_drop_nombre_from_viveros_table.php,
     * pero según el enunciado del proyecto los viveros deben
     * tener nombre, departamento y municipio.
     */
    public function up(): void
    {
        Schema::table('viveros', function (Blueprint $table) {
            $table->string('nombre')->after('codigo');
        });
    }

    public function down(): void
    {
        Schema::table('viveros', function (Blueprint $table) {
            $table->dropColumn('nombre');
        });
    }
};
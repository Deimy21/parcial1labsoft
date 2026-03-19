<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('viveros', function (Blueprint $table) {
            // Agregar columnas si NO existen
            if (!Schema::hasColumn('viveros', 'codigo')) {
                $table->string('codigo')->after('id')->comment('Código asignado por el productor');
            }
            
            if (!Schema::hasColumn('viveros', 'tipo_cultivo')) {
                $table->string('tipo_cultivo')->after('codigo');
            }
            
            if (!Schema::hasColumn('viveros', 'finca_id')) {
                $table->foreignId('finca_id')->after('tipo_cultivo')->constrained()->onDelete('cascade');
            }
            
            // Crear índice único si no existe
            $table->unique(['codigo', 'finca_id'], 'viveros_codigo_finca_unique');
        });
    }

    public function down(): void
    {
        Schema::table('viveros', function (Blueprint $table) {
            // Eliminar el índice único
            $table->dropUnique('viveros_codigo_finca_unique');
            
            // Eliminar columnas (opcional - si quieres poder revertir)
            // $table->dropColumn(['codigo', 'tipo_cultivo', 'finca_id']);
        });
    }
};

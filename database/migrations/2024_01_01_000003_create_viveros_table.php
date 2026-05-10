<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla de Viveros.
     *
     * Según los requisitos:
     *   - Cada Vivero es identificado por un código (asignado por el Productor).
     *   - El Vivero debe poseer un nombre, departamento y municipio.
     *   - Cada Productor puede ser propietario de varios Viveros (relación directa).
     */
    public function up(): void
    {
        Schema::create('viveros', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->comment('Código asignado por el productor');
            $table->string('nombre');
            $table->string('departamento');
            $table->string('municipio');
            $table->foreignId('productor_id')
                  ->constrained('productores')
                  ->cascadeOnDelete();
            $table->timestamps();

            // Un Vivero debe tener un código único por Productor
            $table->unique(['codigo', 'productor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('viveros');
    }
};

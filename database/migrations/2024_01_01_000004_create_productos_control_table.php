<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla única para los tres subtipos de ProductoControl (STI).
     * Los campos exclusivos de cada subtipo pueden ser null cuando no aplican.
     *
     * Columnas compartidas:
     *   tipo, registro_ica, nombre_producto, frecuencia_aplicacion, valor_producto
     *
     * Columnas de subtipo Hongo y Plaga:
     *   periodo_carencia
     *
     * Columnas exclusivas de Hongo:
     *   nombre_hongo
     *
     * Columnas exclusivas de Fertilizante:
     *   fecha_ultima_aplicacion
     */
    public function up(): void
    {
        Schema::create('productos_control', function (Blueprint $table) {
            $table->id();

            // Discriminador STI
            $table->enum('tipo', ['hongo', 'plaga', 'fertilizante']);

            // Atributos comunes (todos obligatorios)
            $table->string('registro_ica');
            $table->string('nombre_producto');
            $table->unsignedInteger('frecuencia_aplicacion')
                  ->comment('Frecuencia de aplicación en días (ej: 15, 30)');
            $table->decimal('valor_producto', 12, 2);

            // Atributos de Hongo y Plaga
            $table->unsignedInteger('periodo_carencia')
                  ->nullable()
                  ->comment('Días entre última aplicación y cosecha (Hongo y Plaga)');

            // Atributo exclusivo de Hongo
            $table->string('nombre_hongo')
                  ->nullable()
                  ->comment('Nombre del hongo que afecta la planta');

            // Atributo exclusivo de Fertilizante
            $table->date('fecha_ultima_aplicacion')
                  ->nullable()
                  ->comment('Fecha de la última aplicación del fertilizante');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos_control');
    }
};

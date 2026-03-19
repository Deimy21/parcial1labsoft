<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('viveros', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->comment('Código asignado por el productor');
            $table->string('tipo_cultivo');
            $table->foreignId('finca_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Un vivero debe tener un código único por finca
            $table->unique(['codigo', 'finca_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('viveros');
    }
};
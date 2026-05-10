<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega el campo `rol` a la tabla `users` para distinguir
     * entre Administrador y Empleado.
     *
     * El módulo de inicio de sesión (correo + contraseña) ya usa los campos
     * estándar `email` y `password` que provee Laravel; este campo
     * únicamente añade el control de acceso por rol.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', ['administrador', 'empleado'])
                  ->default('empleado')
                  ->after('password')
                  ->comment('Rol del usuario en el sistema');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('rol');
        });
    }
};

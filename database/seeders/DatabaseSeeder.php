<?php

namespace Database\Seeders;

use App\Models\Labor;
use App\Models\ProductoControl;
use App\Models\Productor;
use App\Models\User;
use App\Models\Vivero;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Usuarios base (1 administrador + 2 empleados) para el módulo de login.
        User::factory()->administrador()->create([
            'name'  => 'Luis Fernando',
            'email' => 'fer@viveros.test',
            'password' => '12345',
        ]);

        User::factory()->count(2)->empleado()->create();

        
    }
}

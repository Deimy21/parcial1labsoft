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
            'name'  => 'Administrador',
            'email' => 'admin@viveros.test',
        ]);

        User::factory()->count(2)->empleado()->create();

        // 2. Crear 5 Productores, cada uno con 3 Viveros (relación directa).
        Productor::factory(5)
            ->has(Vivero::factory(3), 'viveros')
            ->create();

        // 3. Crear productos de control (2 de cada tipo).
        $productos = collect([
            ...ProductoControl::factory(2)->hongo()->create(),
            ...ProductoControl::factory(2)->plaga()->create(),
            ...ProductoControl::factory(2)->fertilizante()->create(),
        ]);

        // 4. Crear 2 Labores por Vivero, asignando productos aleatorios.
        Vivero::all()->each(function (Vivero $vivero) use ($productos) {
            Labor::factory(2)->create([
                'vivero_id'           => $vivero->id,
                'producto_control_id' => $productos->random()->id,
            ]);
        });
    }
}

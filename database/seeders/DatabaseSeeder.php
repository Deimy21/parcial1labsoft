<?php

namespace Database\Seeders;

use App\Models\Finca;
use App\Models\Labor;
use App\Models\ProductoControl;
use App\Models\Productor;
use App\Models\Vivero;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear 5 Productores, cada uno con 2 Fincas,
        //    cada Finca con 2 Viveros.
        Productor::factory(5)
            ->has(
                Finca::factory(2)
                    ->has(Vivero::factory(2)),
                'fincas'
            )
            ->create();

        // 2. Crear productos de control (2 de cada tipo)
        $productos = collect([
            ...ProductoControl::factory(2)->hongo()->create(),
            ...ProductoControl::factory(2)->plaga()->create(),
            ...ProductoControl::factory(2)->fertilizante()->create(),
        ]);

        // 3. Crear 2 Labores por Vivero, asignando productos aleatorios
        Vivero::all()->each(function (Vivero $vivero) use ($productos) {
            Labor::factory(2)->create([
                'vivero_id'           => $vivero->id,
                'producto_control_id' => $productos->random()->id,
            ]);
        });
    }
}

<?php

namespace Database\Factories;

use App\Models\Productor;
use App\Models\Vivero;
use Illuminate\Database\Eloquent\Factories\Factory;

class ViveroFactory extends Factory
{
    protected $model = Vivero::class;

    public function definition(): array
    {
        return [
            'codigo'       => $this->faker->unique()->bothify('VIV-??###'),
            'nombre'       => $this->faker->words(2, true),
            'departamento' => $this->faker->randomElement([
                'Risaralda', 'Antioquia', 'Cundinamarca', 'Valle del Cauca',
                'Quindío', 'Caldas', 'Tolima', 'Huila', 'Santander', 'Boyacá',
            ]),
            'municipio'    => $this->faker->city(),
            'productor_id' => Productor::factory(),
        ];
    }
}

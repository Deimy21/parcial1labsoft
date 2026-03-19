<?php

namespace Database\Factories;

use App\Models\Finca;
use App\Models\Vivero;
use Illuminate\Database\Eloquent\Factories\Factory;

class ViveroFactory extends Factory
{
    protected $model = Vivero::class;

    public function definition(): array
    {
        return [
            'codigo'       => $this->faker->unique()->bothify('VIV-??###'),
            'tipo_cultivo' => $this->faker->randomElement([
                'Tomate', 'Pimiento', 'Lechuga', 'Fresa', 'Orquídea',
                'Helecho', 'Cactus', 'Albahaca', 'Cilantro', 'Rosas',
            ]),
            'finca_id' => Finca::factory(),
        ];
    }
}

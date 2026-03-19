<?php

namespace Database\Factories;

use App\Models\Finca;
use App\Models\Productor;
use Illuminate\Database\Eloquent\Factories\Factory;

class FincaFactory extends Factory
{
    protected $model = Finca::class;

    public function definition(): array
    {
        return [
            'numero_catastro' => $this->faker->unique()->numerify('CAT-######'),
            'municipio'       => $this->faker->city(),
            'productor_id'    => Productor::factory(),
        ];
    }
}

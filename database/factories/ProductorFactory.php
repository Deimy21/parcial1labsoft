<?php

namespace Database\Factories;

use App\Models\Productor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductorFactory extends Factory
{
    protected $model = Productor::class;

    public function definition(): array
    {
        return [
            'documento_identidad' => $this->faker->unique()->numerify('##########'),
            'nombre'              => $this->faker->firstName(),
            'apellido'            => $this->faker->lastName(),
            'telefono'            => $this->faker->phoneNumber(),
            'correo'              => $this->faker->unique()->safeEmail(),
        ];
    }
}

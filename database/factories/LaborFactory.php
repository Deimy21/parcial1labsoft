<?php

namespace Database\Factories;

use App\Models\Labor;
use App\Models\ProductoControl;
use App\Models\Vivero;
use Illuminate\Database\Eloquent\Factories\Factory;

class LaborFactory extends Factory
{
    protected $model = Labor::class;

    public function definition(): array
    {
        return [
            'fecha'               => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'descripcion'         => $this->faker->sentence(10),
            'vivero_id'           => Vivero::factory(),
            'producto_control_id' => ProductoControl::factory(),
        ];
    }
}

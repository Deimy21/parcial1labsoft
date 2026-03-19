<?php

namespace Database\Factories;

use App\Models\ProductoControl;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoControlFactory extends Factory
{
    protected $model = ProductoControl::class;

    public function definition(): array
    {
        return [
            'tipo'                  => $this->faker->randomElement(['hongo', 'plaga', 'fertilizante']),
            'registro_ica'          => $this->faker->bothify('ICA-######-??'),
            'nombre_producto'       => $this->faker->words(3, true),
            'frecuencia_aplicacion' => $this->faker->randomElement([15, 20, 30, 45, 60]),
            'valor_producto'        => $this->faker->randomFloat(2, 10_000, 500_000),
            // Todos en null por defecto; los estados los sobreescriben
            'periodo_carencia'        => null,
            'nombre_hongo'            => null,
            'fecha_ultima_aplicacion' => null,
        ];
    }

    // -----------------------------------------------------------------------
    // States — uno por cada subtipo
    // -----------------------------------------------------------------------

    /**
     * Producto de control de Hongo.
     * Requiere: periodo_carencia, nombre_hongo.
     */
    public function hongo(): static
    {
        return $this->state(fn () => [
            'tipo'             => 'hongo',
            'periodo_carencia' => $this->faker->numberBetween(7, 60),
            'nombre_hongo'     => $this->faker->randomElement([
                'Botrytis cinerea', 'Fusarium oxysporum', 'Phytophthora infestans',
                'Alternaria solani', 'Oidium spp.',
            ]),
        ]);
    }

    /**
     * Producto de control de Plaga.
     * Requiere: periodo_carencia.
     */
    public function plaga(): static
    {
        return $this->state(fn () => [
            'tipo'             => 'plaga',
            'periodo_carencia' => $this->faker->numberBetween(3, 45),
        ]);
    }

    /**
     * Producto de control Fertilizante.
     * Requiere: fecha_ultima_aplicacion.
     */
    public function fertilizante(): static
    {
        return $this->state(fn () => [
            'tipo'                    => 'fertilizante',
            'fecha_ultima_aplicacion' => $this->faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
        ]);
    }
}

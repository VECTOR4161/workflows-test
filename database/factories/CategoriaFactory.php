<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categoria>
 */
class CategoriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Genera un nombre de producto/categoría aleatorio
            'nombre' => fake()->unique()->words(2, true),
            'descripcion' => fake()->sentence(10),
            'estado' => fake()->boolean(90), // 90% de probabilidad de ser true
        ];
    }

    /**
     * Estado para categorías inactivas
     */
    public function inactivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => false,
        ]);
    }
}

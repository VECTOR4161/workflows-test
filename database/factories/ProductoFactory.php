<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generamos un precio base para que la lógica de negocio tenga sentido
        $precioCompra = fake()->randomFloat(2, 5, 100);

        return [
            // Usamos tu método estático para que el código sea realista
            'codigo' => Producto::generarCodigo(),
            'nombre' => fake()->unique()->words(3, true),
            'descripcion' => fake()->sentence(),

            // Crea una categoría automáticamente si no se asigna una
            'categoria_id' => Categoria::factory(),

            'precio_compra' => $precioCompra,
            // Aseguramos que el precio de venta sea mayor al de compra
            'precio_venta' => $precioCompra + fake()->randomFloat(2, 5, 50),

            'stock' => fake()->numberBetween(0, 200),
            'stock_minimo' => 10,
            'unidad_medida' => fake()->randomElement(['UND', 'KG', 'LT', 'MTS']),
            'imagen' => null,
            'estado' => true,
        ];
    }

    /**
     * Estado para productos con Stock Bajo
     */
    public function stockBajo(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 2,
            'stock_minimo' => 10,
        ]);
    }

    /**
     * Estado para productos inactivos
     */
    public function inactivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => false,
        ]);
    }
}

<?php

namespace Tests\Feature\Api;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        Sanctum::actingAs($user);
    }

    public function test_can_list_productos(): void
    {
        Categoria::factory()->create(['id' => 4]);
        Producto::factory()->count(1)->create();

        $response = $this->getJson('/api/v1/productos');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'codigo', 'nombre', 'precio_venta', 'stock'],
            ],
        ]);
    }

    public function test_can_create_producto(): void
    {
        $categoria = Categoria::factory()->create();

        $data = [
            'codigo' => 'PROD000001',
            'nombre' => 'Producto Test',
            'descripcion' => 'Descripción de prueba',
            'categoria_id' => $categoria->id,
            'precio_compra' => 100.00,
            'precio_venta' => 150.00,
            'stock' => 10,
            'stock_minimo' => 5,
            'unidad_medida' => 'UND',
            'estado' => true,
        ];

        $response = $this->postJson('/api/v1/productos', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('productos', [
            'codigo' => 'PROD000001',
            'nombre' => 'Producto Test',
        ]);
    }

    public function test_can_show_producto(): void
    {
        Categoria::factory()->create(['id' => 1]);
        $producto = Producto::factory()->create();

        $response = $this->getJson("/api/v1/productos/{$producto->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $producto->id,
                'codigo' => $producto->codigo,
            ],
        ]);
    }

    public function test_can_update_producto(): void
    {
        Categoria::factory()->create(['id' => 1]);
        $producto = Producto::factory()->create();

        $data = [
            'nombre' => 'Producto Actualizado',
            'precio_venta' => 200.00,
        ];

        $response = $this->putJson("/api/v1/productos/{$producto->id}", array_merge(
            $producto->toArray(),
            $data
        ));

        $response->assertStatus(200);
        $this->assertDatabaseHas('productos', [
            'id' => $producto->id,
            'nombre' => 'Producto Actualizado',
        ]);
    }

    public function test_can_generate_codigo(): void
    {
        $response = $this->getJson('/api/v1/productos/generar-codigo');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'codigo',
            ]]);
        $this->assertStringStartsWith('PROD', $response->json('data.codigo'));
    }

    public function test_validation_fails_for_required_fields(): void
    {
        $response = $this->postJson('/api/v1/productos', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'codigo',
            'nombre',
            'categoria_id',
            'precio_compra',
            'precio_venta',
        ]);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que el endpoint de health check responde correctamente
     */
    public function test_health_check_endpoint_returns_ok_status(): void
    {
        $response = $this->get('/api/health');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'app' => ['env', 'debug'],
            'db' => ['ok', 'latency_ms', 'driver'],
        ]);
        $response->assertJson([
            'status' => 'ok',
            'db' => ['ok' => true],
        ]);
    }

    /**
     * Test que el health check valida la conexión a la base de datos
     */
    public function test_health_check_validates_database_connection(): void
    {
        $response = $this->get('/api/health');

        $response->assertStatus(200);

        $data = $response->json();

        $this->assertTrue($data['db']['ok']);
        $this->assertIsInt($data['db']['latency_ms']);
        $this->assertGreaterThanOrEqual(0, $data['db']['latency_ms']);
    }
}

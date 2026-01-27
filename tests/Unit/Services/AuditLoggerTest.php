<?php

namespace Tests\Unit\Services;

use App\Services\AuditLogger;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AuditLoggerTest extends TestCase
{
    // Test que el método log registra correctamente

    public function test_log_method_writes_to_log(): void
    {
        Log::shouldReceive('channel')
            ->once()
            ->with('daily')
            ->andReturnSelf();

        Log::shouldReceive('info')
            ->once()
            ->with(\Mockery::type('string'));

        AuditLogger::log('TEST', 'info', 'Test de auditoría');

        $this->assertTrue(true);
    }

    // Test que el método consulta funciona

    public function test_consulta_method_logs_correctly(): void
    {
        Log::shouldReceive('channel')->andReturnSelf();
        Log::shouldReceive('info')->once();

        AuditLogger::consulta('Consulta de prueba');

        $this->assertTrue(true);
    }

    // Test que el método insercion funciona

    public function test_insercion_method_logs_correctly(): void
    {
        Log::shouldReceive('channel')->andReturnSelf();
        Log::shouldReceive('info')->once();

        AuditLogger::insercion('Inserción de prueba', ['id' => 1]);

        $this->assertTrue(true);
    }

    // Test que el método error funciona

    public function test_error_method_logs_correctly(): void
    {
        Log::shouldReceive('channel')->andReturnSelf();
        Log::shouldReceive('info')->once();

        $exception = new \Exception('Test exception');
        AuditLogger::error('Error de prueba', $exception);

        $this->assertTrue(true);
    }
}

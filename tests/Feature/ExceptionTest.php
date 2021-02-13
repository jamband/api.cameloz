<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class ExceptionTest extends TestCase
{
    public function testNotFound(): void
    {
        $response = $this->getJson('/');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(['message' => 'Not Found']);
    }

    public function testMaintenanceMode(): void
    {
        $downFile = $this->app->storagePath().'/framework/down';
        file_put_contents($downFile, json_encode(['retry' => Config::get('retry_after')]));

        $response = $this->getJson('/');
        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE);
        $response->assertHeader('Retry-After', Config::get('retry_after'));
        $response->assertExactJson(['message' => 'Service Unavailable']);

        unlink($downFile);
    }
}

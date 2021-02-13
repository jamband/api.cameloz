<?php

declare(strict_types=1);

namespace Tests\Feature\Project;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetProjectTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function testModelNotFound(): void
    {
        $response = $this->getJson('/projects/123');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(['message' => 'Model Not Found']);
    }

    public function testGetProject(): void
    {
        $response = $this->getJson('/projects/1');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson(['id' => 1, 'name' => 'Foo']);
    }
}

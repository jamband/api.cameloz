<?php

declare(strict_types=1);

namespace Tests\Feature\TaskPriority;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetTaskPrioritiesTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function testGetTaskPrioritiesTest(): void
    {
        $response = $this->getJson('/task_priorities');
        $response->assertStatus(Response::HTTP_OK);

        $response->assertExactJson([
            ['id' => 1, 'name' => 'Low'],
            ['id' => 2, 'name' => 'Medium'],
            ['id' => 3, 'name' => 'High'],
        ]);
    }
}

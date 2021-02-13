<?php

declare(strict_types=1);

namespace Tests\Feature\Task;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class DeleteTaskTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function testNotFound(): void
    {
        $response = $this->deleteJson('/tasks/foo');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(['message' => 'Not Found']);
    }

    public function testModelNotFound(): void
    {
        $response = $this->deleteJson('/tasks/1');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(['message' => 'Model Not Found']);
    }

    public function testDeleteTask(): void
    {
        $task = new Task;
        $task::factory()->create();
        $this->assertDatabaseCount($task->getTable(), 1);

        $response = $this->deleteJson('/tasks/1');
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $response->assertNoContent();
        $this->assertDatabaseCount($task->getTable(), 0);
    }
}

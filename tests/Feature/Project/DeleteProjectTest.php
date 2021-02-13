<?php

declare(strict_types=1);

namespace Tests\Feature\Project;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskPriority;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class DeleteProjectTest extends TestCase
{
    use RefreshDatabase;

    public function testNotFound(): void
    {
        $response = $this->deleteJson('/projects/foo');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(['message' => 'Not Found']);
    }

    public function testModelNotFound(): void
    {
        $response = $this->deleteJson('/projects/1');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(['message' => 'Model Not Found']);
    }

    public function testDeleteTask(): void
    {
        Project::factory()->create();
        Task::factory()->create(['project_id' => 1]);

        $this->assertDatabaseCount((new Task)->getTable(), 1);
        $this->assertDatabaseCount((new TaskPriority)->getTable(), 3);
        $this->assertDatabaseCount((new Project)->getTable(), 1);

        $response = $this->deleteJson('/projects/1');
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $response->assertNoContent();

        $this->assertDatabaseCount((new Task)->getTable(), 0);
        $this->assertDatabaseCount((new TaskPriority)->getTable(), 3);
        $this->assertDatabaseCount((new Project)->getTable(), 0);
    }
}

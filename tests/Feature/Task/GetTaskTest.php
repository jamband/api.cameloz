<?php

declare(strict_types=1);

namespace Tests\Feature\Task;

use App\Models\Task;
use Database\Seeders\ProjectSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetTaskTest extends TestCase
{
    use RefreshDatabase;

    public function testModelNotFound(): void
    {
        $response = $this->getJson('/tasks/1');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(['message' => 'Model Not Found']);
    }

    public function testResource(): void
    {
        $this->seed(ProjectSeeder::class);

        /** @var Task $task */
        $task = Task::factory()->create();

        $response = $this->getJson('/tasks/1');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(4);
        $response->assertJsonCount(2, 'priority');
        $response->assertJsonCount(2, 'project');

        $response->assertExactJson([
            'id' => $task->id,
            'name' => $task->name,
            'priority' => [
                'id' => $task->priority->id,
                'name' => $task->priority->name,
            ],
            'project' => [
                'id' => $task->project->id,
                'name' => $task->project->name,
            ],
        ]);
    }
}

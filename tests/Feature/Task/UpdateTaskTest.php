<?php

declare(strict_types=1);

namespace Tests\Feature\Task;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateTaskTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function testNotFound(): void
    {
        $response = $this->putJson('/tasks/foo');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(['message' => 'Not Found']);
    }

    public function testModelNotFound(): void
    {
        $response = $this->putJson('/tasks/1');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(['message' => 'Model Not Found']);
    }

    public function testFailsValidation(): void
    {
        Task::factory()->create();

        $response = $this->putJson('/tasks/1');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonCount(3, 'errors');

        $response->assertJsonValidationErrors([
            'name',
            'priority_id',
            'project_id',
        ]);
    }

    public function testNameRequiredRuleValidation(): void
    {
        Task::factory()->create();

        $response = $this->putJson('/tasks/1');
        $message = str_replace(':attribute', 'task', __('validation.required'));
        $this->assertSame($message, $response->json('errors.name.0'));
    }

    public function testNameMaxStringRuleValidation(): void
    {
        Task::factory()->create();

        $data['name'] = str_repeat('a', 151);
        $response = $this->putJson('/tasks/1', $data);
        $message = str_replace([':attribute', ':max'], ['task', '150'], __('validation.max.string'));
        $this->assertSame($message, $response->json('errors.name.0'));
    }

    public function testPriorityIdRequiredRuleValidation(): void
    {
        Task::factory()->create();

        $response = $this->putJson('/tasks/1');
        $message = str_replace(':attribute', 'priority', __('validation.required'));
        $this->assertSame($message, $response->json('errors.priority_id.0'));
    }

    public function testPriorityIdInRuleValidation(): void
    {
        Task::factory()->create();

        $data['priority_id'] = 4;
        $response = $this->putJson('/tasks/1', $data);
        $message = str_replace(':attribute', 'priority', __('validation.in'));
        $this->assertSame($message, $response->json('errors.priority_id.0'));
    }

    public function testProjectIdRequiredRuleValidation(): void
    {
        Task::factory()->create();

        $response = $this->putJson('/tasks/1');
        $message = str_replace(':attribute', 'project', __('validation.required'));
        $this->assertSame($message, $response->json('errors.project_id.0'));
    }

    public function testProjectIdRuleValidation(): void
    {
        Task::factory()->create();

        $data['project_id'] = 4;
        $response = $this->putJson('/tasks/1', $data);
        $message = str_replace(':attribute', 'project', __('validation.in'));
        $this->assertSame($message, $response->json('errors.project_id.0'));
    }

    public function testUpdateTask(): void
    {
        Task::factory()->create();
        $this->assertDatabaseCount((new Task)->getTable(), 1);

        $data['name'] = 'updated task';
        $data['priority_id'] = 3;
        $data['project_id'] = 1;
        $response = $this->putJson('/tasks/1', $data);
        $response->assertStatus(Response::HTTP_OK);

        $response->assertExactJson([
            'id' => 1,
            'name' => 'updated task',
            'priority' => [
                'id' => 3,
                'name' => 'High',
            ],
            'project' => [
                'id' => 1,
                'name' => 'Foo',
            ],
        ]);

        /** @var Task $task */
        $task = (new Task)::query()->find(1);
        $this->assertDatabaseCount($task->getTable(), 1);

        $this->assertDatabaseHas($task->getTable(), [
            'id' => 1,
            'name' => 'updated task',
            'priority_id' => $task->priority_id,
            'project_id' => $task->project_id,
            'created_at' => $task->created_at,
            'updated_at' => $task->updated_at,
        ]);
    }
}

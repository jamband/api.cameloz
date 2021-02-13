<?php

declare(strict_types=1);

namespace Tests\Feature\Task;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class CreateTaskTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function testFailsValidation(): void
    {
        $response = $this->postJson('/tasks');
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
        $response = $this->postJson('/tasks');
        $message = str_replace(':attribute', 'task', __('validation.required'));
        $this->assertSame($message, $response->json('errors.name.0'));
    }

    public function testNameMaxStringRuleValidation(): void
    {
        $data['name'] = str_repeat('a', 151);
        $response = $this->postJson('/tasks', $data);
        $message = str_replace([':attribute', ':max'], ['task', '150'], __('validation.max.string'));
        $this->assertSame($message, $response->json('errors.name.0'));
    }

    public function testNameLimitableRuleValidation(): void
    {
        Task::factory()->count(3)->create(['project_id' => 3]);
        Config::set('app.limitable.tasks', 3);

        $data['name'] = 'Foo';
        $data['project_id'] = 3;
        $data['priority_id'] = 1;
        $response = $this->postJson('/tasks', $data);
        $message = str_replace(':attribute', 'task', __('validation.limitable'));
        $this->assertSame($message, $response->json('errors.name.0'));
    }

    public function testPriorityIdRequiredRuleValidation(): void
    {
        $response = $this->postJson('/tasks');
        $message = str_replace(':attribute', 'priority', __('validation.required'));
        $this->assertSame($message, $response->json('errors.priority_id.0'));
    }

    public function testPriorityInRuleValidation(): void
    {
        $data['priority_id'] = 4;
        $response = $this->postJson('/tasks', $data);
        $message = str_replace(':attribute', 'priority', __('validation.in'));
        $this->assertSame($message, $response->json('errors.priority_id.0'));
    }

    public function testProjectIdRequiredRuleValidation(): void
    {
        $response = $this->postJson('/tasks');
        $message = str_replace(':attribute', 'project', __('validation.required'));
        $this->assertSame($message, $response->json('errors.project_id.0'));
    }

    public function testProjectIdRuleValidation(): void
    {
        $data['project_id'] = 4;
        $response = $this->postJson('/tasks', $data);
        $message = str_replace(':attribute', 'project', __('validation.in'));
        $this->assertSame($message, $response->json('errors.project_id.0'));
    }

    public function testCreateTask(): void
    {
        $this->assertDatabaseCount((new Task)->getTable(), 0);

        $data['name'] = 'new task';
        $data['priority_id'] = 2;
        $data['project_id'] = 1;
        $response = $this->postJson('/tasks', $data);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertHeader('Location', 'http://localhost/tasks/1');

        $response->assertExactJson([
            'id' => 1,
            'name' => 'new task',
            'priority' => [
                'id' => 2,
                'name' => 'Medium',
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
            'name' => 'new task',
            'priority_id' => $task->priority_id,
            'project_id' => $task->project_id,
            'created_at' => $task->created_at,
            'updated_at' => $task->updated_at,
        ]);
    }
}

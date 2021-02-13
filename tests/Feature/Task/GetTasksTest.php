<?php

declare(strict_types=1);

namespace Tests\Feature\Task;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetTasksTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function testDataCountAndMeta(): void
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/tasks');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3, 'data');

        $meta = $response->json('meta');
        $this->assertSame(3, $meta['total']);
        $this->assertSame(1, $meta['last_page']);
    }

    public function testResource(): void
    {
        Task::factory()->create();

        /** @var Task $task */
        $task = (new Task)::query()->find(1);

        $response = $this->getJson('/tasks');
        $response->assertStatus(Response::HTTP_OK);

        $resource = $response->json('data.0');
        $this->assertCount(4, $resource);
        $this->assertCount(2, $resource['priority']);
        $this->assertCount(2, $resource['project']);

        $this->assertSame(1, $resource['id']);
        $this->assertSame('task', $resource['name']);
        $this->assertSame($task->priority->id, $resource['priority']['id']);
        $this->assertSame($task->priority->name, $resource['priority']['name']);
        $this->assertSame($task->project->id, $resource['project']['id']);
        $this->assertSame($task->project->name, $resource['project']['name']);
    }

    public function testDataWithInvalidPriorityParameters(): void
    {
        Task::factory()->create();

        $response = $this->getJson('/tasks?priority_id=4');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
    }

    public function testDataWithValidPriorityParameters(): void
    {
        foreach ([3, 1, 3] as $priority_id) {
            Task::factory()->create(['priority_id' => $priority_id]);
        }

        $response = $this->getJson('/tasks?priority_id=3');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(2, 'data');

        $resource = $response->json('data')[0];
        $this->assertCount(4, $resource);
        $this->assertSame(1, $resource['id']);
        $this->assertSame('task', $resource['name']);
        $this->assertSame(3, $resource['priority']['id']);
        $this->assertSame('High', $resource['priority']['name']);
        $this->assertContains($resource['project']['id'], [1, 2, 3]);
        $this->assertContains($resource['project']['name'], ['Foo', 'Bar', 'Baz']);
    }

    public function testDataWithInvalidProjectIdParameters(): void
    {
        Task::factory()->create();

        $response = $this->getJson('/tasks?project_id=4');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
    }

    public function testDataWithValidProjectIdParameters(): void
    {
        foreach ([3, 1, 3] as $project_id) {
            Task::factory()->create(['project_id' => $project_id]);
        }

        $response = $this->getJson('/tasks?project_id=3');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(2, 'data');

        $resource = $response->json('data')[0];
        $this->assertCount(4, $resource);
        $this->assertSame(1, $resource['id']);
        $this->assertSame('task', $resource['name']);
        $this->assertContains($resource['priority']['id'], [1, 2, 3]);
        $this->assertContains($resource['priority']['name'], ['Low', 'Medium', 'High']);
        $this->assertSame(3, $resource['project']['id']);
        $this->assertSame('Baz', $resource['project']['name']);
    }
}

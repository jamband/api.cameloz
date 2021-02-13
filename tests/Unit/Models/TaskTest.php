<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function testInvalidScopeByPriorityId(): void
    {
        Task::factory()->create();
        $this->assertFalse((new Task)->byPriorityId(4)->exists());
    }

    public function testScopeByPriorityId(): void
    {
        $task = new Task;
        $task::factory()->count(5)->create();

        $actual = 0;
        foreach ([1, 2, 3] as $priorityId) {
            $actual += $task->byPriorityId($priorityId)->count();
        }
        $this->assertSame(5, $actual);
    }

    public function testInvalidScopeByProjectId(): void
    {
        Task::factory()->create();
        $this->assertSame(0, (new Task)->byProjectId(4)->count());
    }

    public function testScopeByProjectId(): void
    {
        $task = new Task;
        $task::factory()->count(5)->create();

        $actual = 0;
        foreach ([1, 2, 3] as $project_id) {
            $actual += $task->byProjectId($project_id)->count();
        }

        $this->assertSame(5, $actual);
    }

    public function testTimestamps(): void
    {
        $task = new Task;
        $task->name = 'task';
        $task->priority_id = 1;
        $task->project_id = 1;
        $task->save();

        $this->assertInstanceOf(Carbon::class, $task->created_at);
        $this->assertInstanceOf(Carbon::class, $task->updated_at);
    }
}

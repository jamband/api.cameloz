<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\TaskPriority;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskPriorityTest extends TestCase
{
    use RefreshDatabase;

    public function testGetIds(): void
    {
        $this->assertSame([1, 2, 3], (new TaskPriority)->getIds());
    }
}

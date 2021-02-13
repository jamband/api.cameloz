<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Project;
use Carbon\Carbon;
use Database\Seeders\ProjectSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function testGetIds(): void
    {
        $this->seed(ProjectSeeder::class);
        $this->assertSame([1, 2, 3], (new Project)->getIds());
    }

    public function testTimestamps(): void
    {
        $project = new Project;
        $project->name = 'Foo';
        $project->save();

        $this->assertInstanceOf(Carbon::class, $project->created_at);
        $this->assertInstanceOf(Carbon::class, $project->updated_at);
    }
}

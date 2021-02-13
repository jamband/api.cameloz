<?php

declare(strict_types=1);

namespace Tests\Feature\Project;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetProjectsTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function testGetProjects(): void
    {
        $response = $this->getJson('/projects');
        $response->assertStatus(Response::HTTP_OK);

        $response->assertExactJson([
            ['id' => 1, 'name' => 'Foo'],
            ['id' => 2, 'name' => 'Bar'],
            ['id' => 3, 'name' => 'Baz'],
        ]);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\Project;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateProjectTest extends TestCase
{
    use RefreshDatabase;

    public function testNotFound(): void
    {
        $response = $this->putJson('/projects/foo');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(['message' => 'Not Found']);
    }

    public function testModelNotFound(): void
    {
        $response = $this->putJson('/projects/1');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(['message' => 'Model Not Found']);
    }

    public function testFailsValidation(): void
    {
        Project::factory()->create();

        $response = $this->putJson('/projects/1');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonCount(1, 'errors');

        $response->assertJsonValidationErrors([
            'name',
        ]);
    }

    public function testNameRequiredRuleValidation(): void
    {
        Project::factory()->create();

        $response = $this->putJson('/projects/1');
        $message = str_replace(':attribute', 'project', __('validation.required'));
        $this->assertSame($message, $response->json('errors.name.0'));
    }

    public function testNameMaxRuleValidation(): void
    {
        Project::factory()->create();

        $data['name'] = str_repeat('a', 51);
        $response = $this->putJson('/projects/1', $data);
        $message = str_replace([':attribute', ':max'], ['project', '50'], __('validation.max.string'));
        $this->assertSame($message, $response->json('errors.name.0'));
    }

    public function testCreateProject(): void
    {
        Project::factory()->create();
        $this->assertDatabaseCount((new Project)->getTable(), 1);

        $this->assertDatabaseMissing((new Project)->getTable(), [
            'name' => 'Updated Foo',
        ]);

        $data['name'] = 'Updated Foo';
        $response = $this->putJson('/projects/1', $data);
        $response->assertStatus(Response::HTTP_OK);

        $response->assertExactJson([
            'id' => 1,
            'name' => 'Updated Foo',
        ]);

        $this->assertDatabaseCount((new Project)->getTable(), 1);

        $this->assertDatabaseHas((new Project)->getTable(), [
            'id' => 1,
            'name' => 'Updated Foo',
        ]);
    }
}

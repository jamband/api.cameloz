<?php

declare(strict_types=1);

namespace Tests\Feature\Project;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class CreateProjectTest extends TestCase
{
    use RefreshDatabase;

    public function testFailsValidation(): void
    {
        $response = $this->postJson('/projects');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonCount(1, 'errors');

        $response->assertJsonValidationErrors([
            'name',
        ]);
    }

    public function testNameRequiredRuleValidation(): void
    {
        $response = $this->postJson('/projects');
        $message = str_replace(':attribute', 'project', __('validation.required'));
        $this->assertSame($message, $response->json('errors.name.0'));
    }

    public function testNameMaxRuleValidation(): void
    {
        $data['name'] = str_repeat('a', 51);
        $response = $this->postJson('/projects', $data);
        $message = str_replace([':attribute', ':max'], ['project', '50'], __('validation.max.string'));
        $this->assertSame($message, $response->json('errors.name.0'));
    }

    public function testNameLimitableRuleValidation(): void
    {
        Project::factory()->count(3)->create();
        Config::set('app.limitable.projects', 3);

        $data['name'] = 'New Project';
        $response = $this->postJson('/projects', $data);
        $message = str_replace(':attribute', 'project', __('validation.limitable'));
        $this->assertSame($message, $response->json('errors.name.0'));
    }

    public function testCreateProject(): void
    {
        $this->assertDatabaseCount((new Project)->getTable(), 0);

        $data['name'] = 'Foo';
        $response = $this->postJson('/projects', $data);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertHeader('Location', 'http://localhost/projects/1');

        $response->assertExactJson([
            'id' => 1,
            'name' => 'Foo',
        ]);

        $this->assertDatabaseCount((new Project)->getTable(), 1);

        $this->assertDatabaseHas((new Project)->getTable(), [
            'id' => 1,
            'name' => 'Foo',
        ]);
    }
}

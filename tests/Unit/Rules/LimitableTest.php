<?php

declare(strict_types=1);

namespace Tests\Unit\Rules;

use App\Models\Project;
use App\Rules\Limitable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LimitableTest extends TestCase
{
    use RefreshDatabase;

    public function testFails(): void
    {
        Project::factory()->create();

        $rule = new Limitable((new Project)::query(), 1);
        $this->assertFalse($rule->passes('', null));
    }

    public function testPasses(): void
    {
        Project::factory()->create();

        $rule = new Limitable((new Project)::query(), 2);
        $this->assertTrue($rule->passes('', null));
    }

    public function testMessage(): void
    {
        $rule = new Limitable((new Project)::query(), 0);
        $this->assertSame(__('validation.limitable'), $rule->message());
    }
}

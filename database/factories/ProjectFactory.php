<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Project::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => 'Foo',
            'created_at' => time(),
            'updated_at' => time(),
        ];
    }
}

<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Task::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => 'task',
            'priority_id' => mt_rand(1, 3),
            'project_id' => mt_rand(1, 3),
            'created_at' => time(),
            'updated_at' => time(),
        ];
    }
}

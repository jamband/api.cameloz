<?php

declare(strict_types=1);

namespace App\Http\Requests\Task;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string|null $name
 * @property int|string|null $priority_id
 * @property int|string|null $project_id
 */
class UpdateRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'name' => 'task',
            'project_id' => 'project',
            'priority_id' => 'priority',
        ];
    }

    /**
     * @param TaskPriority $priority
     * @param Project $project
     * @return array
     */
    public function rules(TaskPriority $priority, Project $project): array
    {
        return [
            'name' => [
                'required',
                'max:150',
            ],
            'priority_id' => [
                'required',
                Rule::in($priority->getIds()),
            ],
            'project_id' => [
                'required',
                Rule::in($project->getIds()),
            ],
        ];
    }

    /**
     * @param Task $task
     * @return Task
     */
    public function save(Task $task): Task
    {
        $task->name = $this->name;
        $task->priority_id = $this->priority_id;
        $task->project_id = $this->project_id;
        $task->save();

        return $task;
    }
}

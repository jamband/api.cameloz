<?php

declare(strict_types=1);

namespace App\Http\Requests\Task;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskPriority;
use App\Rules\Limitable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * @property string|null $name
 * @property int|string|null $priority_id
 * @property int|string|null $project_id
 */
class CreateRequest extends FormRequest
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
            'priority_id' => 'priority',
            'project_id' => 'project',
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
            ]
        ];
    }

    /**
     * @param Validator $validator
     * @return void
     * @noinspection PhpUnused
     */
    public function withValidator(Validator $validator): void
    {
        if ($this->filled('project_id')) {
            $validator->addRules([
                'name' => new Limitable(
                    (new Task)->byProjectId((int) $this->project_id),
                    Config::get('app.limitable.tasks')
                )
            ]);
        }
    }

    /**
     * @return Task
     */
    public function save(): Task
    {
        $task = new Task;
        $task->name = $this->name;
        $task->priority_id = $this->priority_id;
        $task->project_id = $this->project_id;
        $task->save();

        return $task;
    }
}

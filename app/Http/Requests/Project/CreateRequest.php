<?php

declare(strict_types=1);

namespace App\Http\Requests\Project;

use App\Models\Project;
use App\Rules\Limitable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

/**
 * @property string|null $name
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
            'name' => 'project',
        ];
    }

    /**
     * @param Project $project
     * @return array
     */
    public function rules(Project $project): array
    {
        return [
            'name' => [
                'required',
                'max:50',
                new Limitable(
                    $project::query(),
                    Config::get('app.limitable.projects')
                ),
            ],
        ];
    }

    /**
     * @return Project
     */
    public function save(): Project
    {
        $data = $this->validated();

        $project = new Project;
        $project->name = $data['name'];
        $project->save();

        return $project;
    }
}

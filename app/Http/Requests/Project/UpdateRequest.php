<?php

declare(strict_types=1);

namespace App\Http\Requests\Project;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string|null $name
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
            'name' => 'project',
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:50',
            ],
        ];
    }

    /**
     * @param Project $project
     * @return Project
     */
    public function save(Project $project): Project
    {
        $project->name = $this->name;
        $project->save();

        return $project;
    }
}

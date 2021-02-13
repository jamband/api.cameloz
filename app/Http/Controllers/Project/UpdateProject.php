<?php

declare(strict_types=1);

namespace App\Http\Controllers\Project;

use App\Http\Requests\Project\UpdateRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;

class UpdateProject extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware([
            'bindings',
        ]);
    }

    /**
     * @param Project $project
     * @param UpdateRequest $request
     * @return ProjectResource
     */
    public function __invoke(
        Project $project,
        UpdateRequest $request
    ): ProjectResource {
        return new ProjectResource($request->save($project));
    }
}

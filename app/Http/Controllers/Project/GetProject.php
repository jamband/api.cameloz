<?php

declare(strict_types=1);

namespace App\Http\Controllers\Project;

use App\Http\Resources\ProjectResource;
use App\Models\Project;

class GetProject extends Controller
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
     * @return ProjectResource
     */
    public function __invoke(Project $project): ProjectResource
    {
        return new ProjectResource($project);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Project;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetProjects extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        return ProjectResource::collection(
            (new Project)::query()->latest()->get()
        );
    }
}

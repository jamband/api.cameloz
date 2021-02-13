<?php

declare(strict_types=1);

namespace App\Http\Controllers\Project;

use App\Http\Requests\Project\CreateRequest;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

class CreateProject extends Controller
{
    /**
     * @param CreateRequest $request
     * @return Response
     */
    public function __invoke(CreateRequest $request): Response
    {
        $project = $request->save();

        return response(new ProjectResource($project), Response::HTTP_CREATED)
            ->header('Location', Url::to('/projects/'.$project->id));
    }
}

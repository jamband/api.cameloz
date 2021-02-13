<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task;

use App\Http\Requests\Task\CreateRequest;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

class CreateTask extends Controller
{
    /**
     * @param CreateRequest $request
     * @return Response
     */
    public function __invoke(CreateRequest $request): Response
    {
        $task = $request->save();

        return response(new TaskResource($task), Response::HTTP_CREATED)
            ->header('Location', URL::to('/tasks/'.$task->id));
    }
}

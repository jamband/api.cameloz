<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task;

use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;

class UpdateTask extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware([
            'bindings',
        ]);
    }

    /**
     * @param Task $task
     * @param UpdateRequest $request
     * @return TaskResource
     */
    public function __invoke(
        Task $task,
        UpdateRequest $request
    ): TaskResource {
        return new TaskResource($request->save($task));
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task;

use App\Http\Resources\TaskResource;
use App\Models\Task;

class GetTask extends Controller
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
     * @return TaskResource
     */
    public function __invoke(Task $task): TaskResource
    {
        return new TaskResource($task);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\TaskPriority;

use App\Http\Resources\ProjectResource;
use App\Models\TaskPriority;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetTaskPriorities extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function __invoke(): AnonymousResourceCollection
    {
        return ProjectResource::collection(
            (new TaskPriority)->inLowestPriorityOrder()->get()
        );
    }
}

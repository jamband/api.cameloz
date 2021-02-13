<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetTasks extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        /** @var Task $query */
        $query = Task::query();

        $priority_id = $request->query('priority_id');
        $project_id = $request->query('project_id');

        if (is_string($priority_id)) {
            $query->byPriorityId((int) $priority_id);
        }

        if (is_string($project_id)) {
            $query->byProjectId((int) $project_id);
        }

        return TaskResource::collection(
            $query->latest()->paginate(self::PER_PAGE)
        );
    }
}

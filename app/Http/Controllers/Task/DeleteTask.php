<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task;

use App\Models\Task;
use Exception;
use Illuminate\Http\Response;

class DeleteTask extends Controller
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
     * @return Response
     * @throws Exception
     */
    public function __invoke(Task $task): Response
    {
        $task->delete();

        return response()->noContent();
    }
}

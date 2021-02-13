<?php

declare(strict_types=1);

namespace App\Http\Controllers\Project;

use App\Models\Project;
use Exception;
use Illuminate\Http\Response;

class DeleteProject extends Controller
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
     * @return Response
     * @throws Exception
     */
    public function __invoke(Project $project): Response
    {
        $project->delete();

        return response()->noContent();
    }
}

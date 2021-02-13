<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->routes(function () {
            $this->createGroups([
                'projects',
                'tasks',
                'task_priorities',
            ]);
        });
    }

    /**
     * @param string[] $groups
     * @return void
     */
    protected function createGroups(array $groups): void
    {
        foreach ($groups as $group) {
            Route::prefix($group)->group(
                base_path('routes/'.$group.'.php')
            );
        }
    }
}

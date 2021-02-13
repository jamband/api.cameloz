<?php

declare(strict_types=1);

use App\Http\Controllers\Project\{
    CreateProject,
    DeleteProject,
    GetProject,
    GetProjects,
    UpdateProject,};
use Illuminate\Support\Facades\Route;

Route::pattern('project', '[\d]+');

Route::get('', GetProjects::class);
Route::get('{project}', GetProject::class);
Route::post('', CreateProject::class);
Route::put('{project}', UpdateProject::class);
Route::delete('{project}', DeleteProject::class);

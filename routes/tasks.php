<?php

declare(strict_types=1);

use App\Http\Controllers\Task\{
    CreateTask,
    DeleteTask,
    GetTask,
    GetTasks,
    UpdateTask,};
use Illuminate\Support\Facades\Route;

Route::pattern('task', '[\d]+');

Route::get('', GetTasks::class);
Route::get('{task}', GetTask::class);
Route::post('', CreateTask::class);
Route::put('{task}', UpdateTask::class);
Route::delete('{task}', DeleteTask::class);

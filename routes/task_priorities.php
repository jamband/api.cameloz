<?php

declare(strict_types=1);

use App\Http\Controllers\TaskPriority\{
    GetTaskPriorities,};
use Illuminate\Support\Facades\Route;

Route::get('', GetTaskPriorities::class);

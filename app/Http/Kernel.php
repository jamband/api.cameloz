<?php

declare(strict_types=1);

namespace App\Http;

use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustHosts;
use App\Http\Middleware\TrustProxies;
use Fruitcake\Cors\HandleCors;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Routing\Middleware\SubstituteBindings;

class Kernel extends HttpKernel
{
    /** @var string[] */
    protected $middleware = [
        ConvertEmptyStringsToNull::class,
        HandleCors::class,
        PreventRequestsDuringMaintenance::class,
        TrimStrings::class,
        TrustHosts::class,
        TrustProxies::class,
        ValidatePostSize::class,
    ];

    /** @var string[] */
    protected $routeMiddleware = [
        'bindings' => SubstituteBindings::class,
    ];

    /** @var string[] */
    protected $middlewarePriority = [
        SubstituteBindings::class,
    ];
}

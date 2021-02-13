<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Fideloper\Proxy\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /** @var string */
    protected $proxies = '*';

    /** @var int */
    protected $headers = Request::HEADER_X_FORWARDED_AWS_ELB;
}

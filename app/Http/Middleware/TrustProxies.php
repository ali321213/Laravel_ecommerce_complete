<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Middleware\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    protected $proxies = '*'; // or specify proxies like ['192.168.1.1']
    protected $headers = Request::HEADER_X_FORWARDED_AWS_ELB;
}

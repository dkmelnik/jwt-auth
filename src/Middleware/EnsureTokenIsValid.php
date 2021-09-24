<?php

namespace CyberLama\JwtAuth\Middleware;

use Closure;
use CyberLama\JwtAuth\JwtService;
use Illuminate\Http\Request;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var  $service JwtService */
        $service = app('JwtService');
        $service->checkToken($request->header("Authorization"));


        return $next($request);
    }
}

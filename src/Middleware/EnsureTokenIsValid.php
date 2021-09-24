<?php

namespace CyberLama\JwtAuth\Middleware;

use App\Exception\EnsureTokenIsValidException;
use Closure;
use CyberLama\JwtAuth\JwtService;
use Illuminate\Http\Request;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws \App\Exceptions\EnsureTokenIsValidException
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var  $service JwtService */
        $service = app('JwtService');
        $service->checkToken($request->header("Authorization"));

        return $next($request);
    }
}

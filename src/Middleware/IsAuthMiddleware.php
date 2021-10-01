<?php

namespace CyberLama\JwtAuth\Middleware;

use Closure;
use CyberLama\JwtAuth\JwtService;
use Illuminate\Http\Request;

class IsAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws \CyberLama\JwtAuth\Exception\TokenNotValid
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var  $service JwtService */
        $service = app(JwtService::class);
        $token = str_replace('Bearer ', '', $request->header("Authorization"));

        if (!$service->checkToken($token)) {
            abort(401);
        }

        return $next($request);
    }
}

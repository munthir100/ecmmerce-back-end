<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class CustomAuthSanctumMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!auth('sanctum')->check()) {
            return new JsonResponse([
                'message' => 'you must login first.',
                'success' => false,
                'statuscode' => 401,
            ], 401);
        }

        return $next($request);
    }
}

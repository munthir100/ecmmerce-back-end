<?php

namespace Modules\Acl\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class ManagementMiddleware
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user->isAdmin && !$user->isSeller) {
            return $this->responseUnAuthorized(message: 'you must be an admin or seller');
        }
        $store = $user->isAdmin ? $user->admin->store : $user->seller->store;


        $request->merge(['authenticated_user' => $user, 'store' => $store]);

        return $next($request);
    }
}

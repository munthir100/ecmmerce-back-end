<?php

namespace Modules\Acl\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class ManagementMiddleware
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
        $user = auth()->user();
        
        if (!$user->isAdmin && !$user->isSeller) {
            throw new AuthorizationException('you must be an admin or seller');
        }
        $store = $user->isAdmin ? $user->admin->store : $user->seller->store;
        
        
        $request->merge(['authenticated_user' => $user, 'store' => $store]);

        return $next($request);
    }
}

<?php

namespace Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdminMiddleware
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
        $user = request()->user();
        if (!$user->isAdmin) {
            abort(response()->json(['message' => 'only admin can perform this action'], 403));
        }
        
        $admin = $user->admin;
        $request->merge(['authenticated_user' => $user, 'admin' => $admin]);
        return $next($request);
    }
}

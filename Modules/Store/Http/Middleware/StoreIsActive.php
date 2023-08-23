<?php

namespace Modules\Store\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StoreIsActive
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
        $store = $request->route('storeLink');

        if ($store && !$store->is_active) {
            return response()->json($store->maintenance_message, 503);
        }

        return $next($request);
    }
}

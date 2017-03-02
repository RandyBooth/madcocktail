<?php

namespace App\Http\Middleware;

use Closure;
use Firewall;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Firewall::isWhitelisted()) {
            abort(404);
        }

        if (!$request->user()) {
            abort(404);
        }

        if (!$request->user()->role) {
            abort(404);
        }

        return $next($request);
    }
}

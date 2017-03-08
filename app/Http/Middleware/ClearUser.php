<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use HobbIoT\Auth\CacheableEloquentUserProvider;
use URL;

class ClearUser
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
        if (Auth::check()) {
            CacheableEloquentUserProvider::clearCache(Auth::user());
        }

        return $next($request);
    }
}

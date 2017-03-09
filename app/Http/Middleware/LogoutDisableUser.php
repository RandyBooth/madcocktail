<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use HobbIoT\Auth\CacheableEloquentUserProvider;

class LogoutDisableUser
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
            $user = Auth::User();

            if (!$user->is_active) {
                Auth::logout();
                CacheableEloquentUserProvider::clearCache($user);
                return redirect()->to('/')->with('danger', 'Your session has expired because your account is deactivated.');
            }
        }

        return $next($request);
    }
}

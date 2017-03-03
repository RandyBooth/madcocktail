<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use Closure;

class UserValid
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
        $message = Helper::user_valid();

        if (!empty($message)) {
            return redirect()->route('user-settings.index.edit')->with('danger', $message);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;

class XSSProtection
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
        if (!in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            return $next($request);
        }

        $input = $request->all();

        array_walk_recursive($input, function (&$input) {
//            $input = trim(preg_replace('/\s+/', ' ', strip_tags($input)));
            $input = trim(strip_tags($input));
        });

        $request->merge($input);
        return $next($request);
    }
}

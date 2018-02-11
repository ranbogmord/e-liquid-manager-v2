<?php

namespace App\Http\Middleware;

use Closure;

class RequiresAdmin
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
        if (auth()->user() && auth()->user()->role === "admin") {
            return $next($request);
        }

        abort(403);
    }
}

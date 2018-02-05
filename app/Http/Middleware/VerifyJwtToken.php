<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;

class VerifyJwtToken
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
        $token = $request->header('X-Authorization');
        if (!$token) {
            return abort(401);
        }

        $token = explode('Bearer ', $token);
        $token = $token[0];
        if (!$token) {
            return abort(401);
        }

        try {
            $decoded = JWT::decode($token, file_get_contents(storage_path('certs/jwt.key')), ['RS256']);
        } catch (\Exception $ex) {
            return abort(401);
        }

        $user = User::find($decoded['user']);
        if (!$user) {
            return abort(401);
        }

        Auth::login($user);
        return $next($request);
    }
}

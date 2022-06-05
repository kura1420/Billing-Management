<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthMikrotik
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $host = $request->header('gw');
        $port = (int) $request->header('pr');
        $username = $request->header('us');
        $password = $request->header('ps');

        if (empty($host) || empty($port) || empty($username) || empty($password)) {
            return response()->json('Unauthorized', 401);     
        } else {
            return $next($request);
        }
    }
}

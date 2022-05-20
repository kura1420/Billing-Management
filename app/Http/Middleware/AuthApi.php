<?php

namespace App\Http\Middleware;

use App\Models\AppProfile;
use Closure;
use Illuminate\Http\Request;

class AuthApi
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
        $username = $request->getUser() ?? NULL;
        $secret = $request->getPassword() ?? NULL;

        $appProfile = AppProfile::where('shortname', $username)
            ->where('secret', $secret)
            ->first();
        
        if ($appProfile) {       
            return $next($request);
        } else {
            return response()->json('Unauthorized', 401);
        }
    }
}

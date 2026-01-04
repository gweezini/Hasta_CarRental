<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBlacklisted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in and blacklisted
        if (Auth::check() && Auth::user()->is_blacklisted) {
            
            // Allow access to the notice page and the logout route to avoid infinite loops
            if (!$request->is('blacklisted') && !$request->is('logout') && !$request->is('logout*')) {
                return redirect()->route('blacklist.notice');
            }
        }

        return $next($request);
    }
}

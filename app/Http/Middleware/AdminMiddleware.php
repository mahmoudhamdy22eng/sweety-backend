<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is logged in
        if (Auth::check()) {
            // Check if the user is an admin
            $user = Auth::user();
            if ($user->user_type === 'admin') {
                console_log($user);
                return $next($request);
            }
        }

        // If not logged in or not an admin, return 403 Forbidden
        return response()->json(['message' => 'Forbidden'], 403);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->roles->name === UserRole::ROLE_ADMIN) {
            return $next($request);
        }

        auth()->logout(); // Log out the user

        return response()->json(['message' => 'Sorry!!! You are not an admin'], 403);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CheckRoleById
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $roles
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $roleIds = explode('|', $roles);
        
        // Convert to integers so Spatie checks IDs instead of names
        $roleIdsInt = array_map('intval', $roleIds);

        if (!Auth::user()->hasAnyRole($roleIdsInt)) {
            throw UnauthorizedException::forRoles($roleIds);
        }

        return $next($request);
    }
}

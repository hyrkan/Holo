<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectByRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only trigger redirection for '/dashboard'
        if ($request->is('dashboard')) {
            // Check Admin/Employee (web guard)
            if (Auth::guard('web')->check()) {
                $user = Auth::guard('web')->user();
                if ($user->hasAnyRole([\App\Models\Role::ADMIN, \App\Models\Role::EMPLOYEE])) {
                    return redirect()->route('admin.dashboard');
                }
            }

            // Check Student (student guard)
            if (Auth::guard('student')->check()) {
                $user = Auth::guard('student')->user();
                if ($user->hasRole(\App\Models\Role::STUDENT)) {
                    return redirect()->route('student.dashboard');
                }
            }
        }

        return $next($request);
    }
}

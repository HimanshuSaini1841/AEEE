<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('admin.login')->with('error', 'Please login to access the admin panel.');
        }

        $user = Auth::guard('web')->user();
        if (!$user->is_active) {
            Auth::guard('web')->logout();
            return redirect()->route('admin.login')->with('error', 'Your administrator account has been deactivated.');
        }

        return $next($request);
    }
}

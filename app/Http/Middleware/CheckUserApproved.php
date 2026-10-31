<?php

namespace App\Http\Middleware;

use App\Models\Enums\UserState;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserApproved
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
        // If user is not logged in, let the auth middleware handle it
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Define protected routes that require approval
        $protectedRoutes = [
            'dashboard*',
            'profile*',
            'flights*',
            'fleet*',
            'pireps*',
            'downloads*',
            'livemap*'
        ];

        // Check if the current request matches any protected route
        $isProtectedRoute = false;
        foreach ($protectedRoutes as $route) {
            if ($request->is($route)) {
                $isProtectedRoute = true;
                break;
            }
        }

        // Handle different user states
        if ($user->state === UserState::PENDING && $isProtectedRoute) {
            return redirect()->route('frontend.index')->with('warning', 'Your account is pending approval. You will be notified when approved.');
        }

        if ($user->state === UserState::REJECTED && $isProtectedRoute) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been rejected. Please contact administration for more information.');
        }

        return $next($request);
    }
}
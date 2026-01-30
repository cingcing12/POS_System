<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // If user is not logged in, send to login
        if (! $request->user()) {
            return redirect('login');
        }

        // "Admin" always gets access to everything
        if ($request->user()->role === 'admin') {
            return $next($request);
        }

        // Check if the user's role is in the allowed list passed from routes
        if (in_array($request->user()->role, $roles)) {
            return $next($request);
        }

        // If not allowed, show 403 Forbidden
        abort(403, 'Unauthorized action.');
    }
}       
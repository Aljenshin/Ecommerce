<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check() || !auth()->user()->is_active) {
            abort(403, 'Unauthorized access');
        }

        if (! empty($roles) && ! auth()->user()->hasRole($roles)) {
            abort(403, 'Insufficient permissions');
        }

        return $next($request);
    }
}



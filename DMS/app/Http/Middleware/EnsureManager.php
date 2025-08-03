<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
  {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Allow access if user is admin OR manager
        if ($user->is_admin || $user->is_manager) {
            return $next($request);
        }

        abort(403, 'Access denied. Only administrators and managers can access this page.');
    }
}

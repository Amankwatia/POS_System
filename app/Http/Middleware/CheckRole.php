<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Cache key for storing validated roles per request.
     */
    private const ROLE_CACHE_KEY = '_middleware_validated_roles';

    /**
     * Handle an incoming request.
     *
     * Optimizations:
     * - Uses Auth facade for slightly faster user retrieval
     * - Caches role validation results within the request lifecycle
     * - Uses hasAnyRole() for single query when checking multiple roles
     * - Early return for unauthenticated users
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Role slugs to check against
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Fast fail for unauthenticated users
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        $user = Auth::user();

        // Generate cache key for this specific role combination
        $cacheKey = self::ROLE_CACHE_KEY . ':' . implode(',', $roles);

        // Check if we've already validated these roles in this request
        if ($request->attributes->has($cacheKey)) {
            return $request->attributes->get($cacheKey)
                ? $next($request)
                : abort(403, 'You do not have permission to access this resource.');
        }

        // Use hasAnyRole for efficient single-pass check when multiple roles allowed
        $hasPermission = count($roles) > 1
            ? $user->hasAnyRole($roles)
            : $user->hasRole($roles[0]);

        // Cache the result for this request (useful if middleware runs multiple times)
        $request->attributes->set($cacheKey, $hasPermission);

        if (!$hasPermission) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }

    /**
     * Terminate the middleware - cleanup if needed.
     */
    public function terminate(Request $request, Response $response): void
    {
        // Clear cached role checks after request completes
        foreach ($request->attributes->all() as $key => $value) {
            if (str_starts_with($key, self::ROLE_CACHE_KEY)) {
                $request->attributes->remove($key);
            }
        }
    }
}

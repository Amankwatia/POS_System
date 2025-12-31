<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogSlowRequests
{
    /**
     * Threshold in milliseconds for logging slow requests.
     */
    private const SLOW_REQUEST_THRESHOLD_MS = 1000;

    /**
     * Handle an incoming request and log if it's slow.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  int  $threshold  Optional threshold in ms (default: 1000)
     */
    public function handle(Request $request, Closure $next, int $threshold = self::SLOW_REQUEST_THRESHOLD_MS): Response
    {
        $startTime = microtime(true);

        $response = $next($request);

        $duration = (microtime(true) - $startTime) * 1000;

        // Log slow requests for performance monitoring
        if ($duration > $threshold) {
            Log::warning('Slow request detected', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'duration_ms' => round($duration, 2),
                'user_id' => $request->user()?->id,
                'ip' => $request->ip(),
                'user_agent' => substr($request->userAgent() ?? '', 0, 100),
            ]);
        }

        // Add timing header for debugging
        if (config('app.debug')) {
            $response->headers->set('X-Response-Time', round($duration, 2) . 'ms');
        }

        return $response;
    }
}

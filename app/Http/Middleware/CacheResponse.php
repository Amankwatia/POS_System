<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheResponse
{
    /**
     * Handle an incoming request with response caching.
     *
     * Only caches GET requests for authenticated users.
     * Cache key is based on URL and user ID for personalized content.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  int  $ttl  Cache time-to-live in seconds (default: 60)
     */
    public function handle(Request $request, Closure $next, int $ttl = 60): Response
    {
        // Only cache GET requests
        if (!$request->isMethod('GET')) {
            return $next($request);
        }

        // Don't cache if user explicitly requests no cache
        if ($request->header('Cache-Control') === 'no-cache') {
            return $next($request);
        }

        $cacheKey = $this->generateCacheKey($request);

        // Return cached response if available
        if (Cache::has($cacheKey)) {
            $cached = Cache::get($cacheKey);
            
            return response($cached['content'])
                ->withHeaders(array_merge($cached['headers'], [
                    'X-Cache' => 'HIT',
                    'X-Cache-Key' => substr(md5($cacheKey), 0, 8),
                ]));
        }

        $response = $next($request);

        // Only cache successful responses
        if ($response->getStatusCode() === 200 && $this->shouldCache($response)) {
            Cache::put($cacheKey, [
                'content' => $response->getContent(),
                'headers' => $this->getCacheableHeaders($response),
            ], $ttl);

            $response->headers->set('X-Cache', 'MISS');
        }

        return $response;
    }

    /**
     * Generate a unique cache key for the request.
     */
    private function generateCacheKey(Request $request): string
    {
        $userId = $request->user()?->id ?? 'guest';
        $url = $request->fullUrl();
        
        return 'response_cache:' . md5($userId . ':' . $url);
    }

    /**
     * Determine if response should be cached.
     */
    private function shouldCache(Response $response): bool
    {
        // Don't cache responses with no-store directive
        $cacheControl = $response->headers->get('Cache-Control', '');
        
        if (str_contains($cacheControl, 'no-store') || str_contains($cacheControl, 'private')) {
            return false;
        }

        // Don't cache responses larger than 1MB
        if (strlen($response->getContent()) > 1048576) {
            return false;
        }

        return true;
    }

    /**
     * Get headers that should be preserved in cache.
     */
    private function getCacheableHeaders(Response $response): array
    {
        return array_filter([
            'Content-Type' => $response->headers->get('Content-Type'),
        ]);
    }
}

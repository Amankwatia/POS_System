<?php

use App\Http\Middleware\CacheResponse;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\LogSlowRequests;
use App\Http\Middleware\SecureHeaders;
use App\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global middleware - runs on every request
        $middleware->append(SecureHeaders::class);
        $middleware->append(TrimStrings::class);

        // Register middleware aliases for route-level use
        $middleware->alias([
            'role' => CheckRole::class,
            'cache.response' => CacheResponse::class,
            'log.slow' => LogSlowRequests::class,
        ]);

        // Priority order for middleware execution
        $middleware->priority([
            SecureHeaders::class,
            TrimStrings::class,
            \Illuminate\Session\Middleware\StartSession::class,
            CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

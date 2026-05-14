<?php

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

        // ── Global middleware ────────────────────────────────────────────────
        $middleware->append(\App\Http\Middleware\SecurityHeadersMiddleware::class);
        $middleware->append(\App\Http\Middleware\SanitizeInputMiddleware::class);
        $middleware->append(\App\Http\Middleware\PreventSqlInjectionMiddleware::class);

        // ── Route middleware aliases ─────────────────────────────────────────
        $middleware->alias([
            'admin'          => \App\Http\Middleware\AdminMiddleware::class,
            'branch_manager' => \App\Http\Middleware\BranchManagerMiddleware::class,
        ]);

        // NOTE: throttleWithRedis() removed — Redis not available in XAMPP
        // Rate limiting is handled manually in AuthController via RateLimiter facade
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

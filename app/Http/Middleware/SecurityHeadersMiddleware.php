<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // ── Prevent XSS attacks ──────────────────────────────────────────────
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // ── Prevent clickjacking ─────────────────────────────────────────────
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // ── Prevent MIME sniffing ────────────────────────────────────────────
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // ── Referrer policy ──────────────────────────────────────────────────
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // ── Content Security Policy ──────────────────────────────────────────
        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; " .
            "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com; " .
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; " .
            "img-src 'self' data: blob:; " .
            "connect-src 'self';"
        );

        // ── Remove server info ───────────────────────────────────────────────
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventSqlInjectionMiddleware
{
    /**
     * Common SQL injection patterns to detect and block.
     * Note: Laravel's Eloquent ORM already uses PDO prepared statements
     * which prevents SQL injection at the query level. This middleware
     * adds an extra layer of protection by detecting obvious attack patterns.
     */
    protected array $sqlPatterns = [
        '/(\bUNION\b.*\bSELECT\b)/i',
        '/(\bSELECT\b.*\bFROM\b)/i',
        '/(\bDROP\b.*\bTABLE\b)/i',
        '/(\bDELETE\b.*\bFROM\b)/i',
        '/(\bINSERT\b.*\bINTO\b)/i',
        '/(\bUPDATE\b.*\bSET\b)/i',
        '/(\bEXEC\b|\bEXECUTE\b)/i',
        '/(--|\#|\/\*|\*\/)/i',
        '/(\bOR\b\s+\d+\s*=\s*\d+)/i',
        '/(\bAND\b\s+\d+\s*=\s*\d+)/i',
        '/(\bOR\b\s+[\'\"]\w+[\'\"]\s*=\s*[\'\"]\w+[\'\"])/i',
        '/(\bSLEEP\b\s*\()/i',
        '/(\bBENCHMARK\b\s*\()/i',
        '/(\bLOAD_FILE\b\s*\()/i',
        '/(\bINTO\b\s+\bOUTFILE\b)/i',
    ];

    protected array $except = [
        'password',
        'password_confirmation',
        '_token',
        'description', // allow rich text in descriptions
    ];

    public function handle(Request $request, Closure $next)
    {
        foreach ($request->all() as $key => $value) {
            if (in_array($key, $this->except)) {
                continue;
            }

            if (is_string($value) && $this->containsSqlInjection($value)) {
                abort(400, 'Invalid input detected.');
            }
        }

        return $next($request);
    }

    private function containsSqlInjection(string $value): bool
    {
        foreach ($this->sqlPatterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }
        return false;
    }
}

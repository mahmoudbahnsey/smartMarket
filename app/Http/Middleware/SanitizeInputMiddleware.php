<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SanitizeInputMiddleware
{
    /**
     * Fields that must NEVER be sanitized.
     * Passwords especially — sanitizing them breaks authentication.
     */
    protected array $except = [
        'password',
        'password_confirmation',
        'current_password',
        '_token',
        '_method',
        'description', // allow product descriptions
        'notes',
        'comment',
        'address',
        'shipping_address',
    ];

    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();
        $this->sanitize($input);
        $request->merge($input);

        return $next($request);
    }

    private function sanitize(array &$data): void
    {
        foreach ($data as $key => &$value) {
            if (in_array($key, $this->except)) {
                continue;
            }

            if (is_array($value)) {
                $this->sanitize($value);
            } elseif (is_string($value)) {
                // Remove HTML tags to prevent XSS
                $value = strip_tags(trim($value));
            }
        }
    }
}

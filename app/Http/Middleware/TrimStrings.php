<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrimStrings
{
    /**
     * The attributes that should not be trimmed.
     */
    protected array $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Handle an incoming request.
     *
     * Trims whitespace from all string inputs except passwords.
     * This helps with data consistency and prevents issues with
     * leading/trailing spaces in search queries and form inputs.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        $request->merge($this->trimRecursive($input));

        return $next($request);
    }

    /**
     * Recursively trim string values in an array.
     */
    private function trimRecursive(array $data): array
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->except, true)) {
                continue;
            }

            if (is_string($value)) {
                $data[$key] = trim($value);
            } elseif (is_array($value)) {
                $data[$key] = $this->trimRecursive($value);
            }
        }

        return $data;
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InputSanitization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip sanitization for admin login to avoid email validation issues
        if ($request->is('admin/login') && $request->isMethod('POST')) {
            return $next($request);
        }

        // Sanitize all input data
        $input = $request->all();
        $sanitized = $this->sanitizeArray($input);
        $request->merge($sanitized);

        return $next($request);
    }

    /**
     * Recursively sanitize array data
     */
    private function sanitizeArray(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->sanitizeArray($value);
            } elseif (is_string($value)) {
                $data[$key] = $this->sanitizeString($value);
            }
        }

        return $data;
    }

    /**
     * Sanitize string input
     */
    private function sanitizeString(string $value): string
    {
        // Remove null bytes
        $value = str_replace("\0", '', $value);
        
        // Trim whitespace
        $value = trim($value);
        
        // Remove potentially dangerous characters for SQL injection (but preserve email-safe characters)
        $value = preg_replace('/[^\w\s\-@.,!?()\'":;\/\\\\+%]/', '', $value);
        
        // Limit length to prevent buffer overflow attacks
        if (strlen($value) > 10000) {
            $value = substr($value, 0, 10000);
        }

        return $value;
    }
}
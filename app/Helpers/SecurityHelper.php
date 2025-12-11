<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SecurityHelper
{
    /**
     * Sanitize string input to prevent XSS and injection attacks
     */
    public static function sanitizeString(string $input, bool $allowHtml = false): string
    {
        // Remove null bytes
        $input = str_replace("\0", '', $input);
        
        // Trim whitespace
        $input = trim($input);
        
        if (!$allowHtml) {
            // Strip all HTML tags
            $input = strip_tags($input);
        } else {
            // Allow only safe HTML tags
            $allowedTags = config('security.validation.allowed_html_tags', '');
            $input = strip_tags($input, $allowedTags);
        }
        
        // Remove potentially dangerous characters
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);
        
        // Limit length
        $maxLength = config('security.validation.max_string_length', 10000);
        if (strlen($input) > $maxLength) {
            $input = substr($input, 0, $maxLength);
        }
        
        return $input;
    }

    /**
     * Check if string contains SQL injection patterns
     */
    public static function containsSqlInjection(string $input): bool
    {
        $patterns = config('security.sql_injection_patterns', []);
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check if string contains XSS patterns
     */
    public static function containsXss(string $input): bool
    {
        $patterns = config('security.xss_patterns', []);
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Validate file upload for security
     */
    public static function validateFileUpload($file): array
    {
        $errors = [];
        
        if (!$file || !$file->isValid()) {
            $errors[] = 'Invalid file upload';
            return $errors;
        }
        
        // Check file size
        $maxSize = config('security.uploads.max_size', 10485760);
        if ($file->getSize() > $maxSize) {
            $errors[] = 'File size exceeds maximum allowed size';
        }
        
        // Check file extension
        $extension = strtolower($file->getClientOriginalExtension());
        $blockedExtensions = config('security.uploads.blocked_extensions', []);
        
        if (in_array($extension, $blockedExtensions)) {
            $errors[] = 'File type not allowed';
        }
        
        // Check MIME type
        $mimeType = $file->getMimeType();
        if (!self::isAllowedMimeType($mimeType)) {
            $errors[] = 'File MIME type not allowed';
        }
        
        // Check for embedded PHP code in files
        if (self::containsPhpCode($file)) {
            $errors[] = 'File contains potentially dangerous code';
        }
        
        return $errors;
    }

    /**
     * Check if MIME type is allowed
     */
    private static function isAllowedMimeType(string $mimeType): bool
    {
        $allowedMimeTypes = [
            // Images
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            // Documents
            'application/pdf', 'application/msword', 
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
            // Audio
            'audio/mpeg', 'audio/wav', 'audio/ogg',
            // Video
            'video/mp4', 'video/webm', 'video/ogg',
        ];
        
        return in_array($mimeType, $allowedMimeTypes);
    }

    /**
     * Check if file contains PHP code
     */
    private static function containsPhpCode($file): bool
    {
        $content = file_get_contents($file->getRealPath());
        
        // Check for PHP opening tags
        $phpPatterns = [
            '/<\?php/i',
            '/<\?=/i',
            '/<\?/i',
            '/<script\s+language\s*=\s*["\']?php["\']?/i',
        ];
        
        foreach ($phpPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Log security event
     */
    public static function logSecurityEvent(string $event, array $data = []): void
    {
        if (!config('security.audit.enabled', true)) {
            return;
        }
        
        $logData = array_merge([
            'event' => $event,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'timestamp' => now()->toISOString(),
        ], $data);
        
        Log::channel('security')->warning($event, $logData);
    }

    /**
     * Check if IP is rate limited
     */
    public static function isRateLimited(string $key, int $maxAttempts = 60, int $decayMinutes = 1): bool
    {
        $attempts = Cache::get($key, 0);
        
        if ($attempts >= $maxAttempts) {
            return true;
        }
        
        Cache::put($key, $attempts + 1, $decayMinutes * 60);
        
        return false;
    }

    /**
     * Generate secure random token
     */
    public static function generateSecureToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Hash sensitive data for storage
     */
    public static function hashSensitiveData(string $data): string
    {
        return hash('sha256', $data . config('app.key'));
    }

    /**
     * Validate password strength
     */
    public static function validatePasswordStrength(string $password): array
    {
        $errors = [];
        $config = config('security.passwords');
        
        if (strlen($password) < $config['min_length']) {
            $errors[] = "Password must be at least {$config['min_length']} characters long";
        }
        
        if ($config['require_uppercase'] && !preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter';
        }
        
        if ($config['require_lowercase'] && !preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain at least one lowercase letter';
        }
        
        if ($config['require_numbers'] && !preg_match('/\d/', $password)) {
            $errors[] = 'Password must contain at least one number';
        }
        
        if ($config['require_symbols'] && !preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = 'Password must contain at least one special character';
        }
        
        return $errors;
    }

    /**
     * Clean and validate URL
     */
    public static function validateUrl(string $url): bool
    {
        // Basic URL validation
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        
        // Check for dangerous protocols
        $dangerousProtocols = ['javascript:', 'data:', 'vbscript:', 'file:'];
        
        foreach ($dangerousProtocols as $protocol) {
            if (stripos($url, $protocol) === 0) {
                return false;
            }
        }
        
        return true;
    }
}
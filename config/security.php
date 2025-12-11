<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains security-related configuration options for the
    | SundayLearn application.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy
    |--------------------------------------------------------------------------
    |
    | Configure Content Security Policy directives
    |
    */
    'csp' => [
        'default-src' => "'self'",
        'script-src' => "'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
        'style-src' => "'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
        'font-src' => "'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com",
        'img-src' => "'self' data: https: http:",
        'connect-src' => "'self'",
        'frame-ancestors' => "'none'",
        'base-uri' => "'self'",
        'form-action' => "'self'",
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configure rate limiting for different endpoints
    |
    */
    'rate_limits' => [
        'api' => [
            'requests' => 60,
            'per_minutes' => 1,
        ],
        'login' => [
            'requests' => 5,
            'per_minutes' => 15,
        ],
        'search' => [
            'requests' => 30,
            'per_minutes' => 1,
        ],
        'download' => [
            'requests' => 10,
            'per_minutes' => 1,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Security
    |--------------------------------------------------------------------------
    |
    | Configure file upload security settings
    |
    */
    'uploads' => [
        'max_size' => 10485760, // 10MB in bytes
        'allowed_extensions' => [
            'images' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'documents' => ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt'],
            'audio' => ['mp3', 'wav', 'ogg'],
            'video' => ['mp4', 'webm', 'ogg'],
        ],
        'blocked_extensions' => [
            'php', 'php3', 'php4', 'php5', 'phtml', 'exe', 'bat', 'cmd', 
            'com', 'pif', 'scr', 'vbs', 'js', 'jar', 'sh', 'asp', 'aspx'
        ],
        'scan_for_malware' => env('SCAN_UPLOADS_FOR_MALWARE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Input Validation
    |--------------------------------------------------------------------------
    |
    | Configure input validation and sanitization
    |
    */
    'validation' => [
        'max_string_length' => 10000,
        'max_array_items' => 100,
        'allowed_html_tags' => '<p><br><strong><em><u><ol><ul><li><h1><h2><h3><h4><h5><h6>',
        'strip_dangerous_attributes' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | SQL Injection Protection
    |--------------------------------------------------------------------------
    |
    | Patterns to detect potential SQL injection attempts
    |
    */
    'sql_injection_patterns' => [
        '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|UNION|SCRIPT)\b)/i',
        '/(\b(OR|AND)\s+\d+\s*=\s*\d+)/i',
        '/(\b(OR|AND)\s+[\'"]?\w+[\'"]?\s*=\s*[\'"]?\w+[\'"]?)/i',
        '/(--|\/\*|\*\/|;)/i',
        '/(\bxp_\w+)/i',
        '/(\bsp_\w+)/i',
    ],

    /*
    |--------------------------------------------------------------------------
    | XSS Protection
    |--------------------------------------------------------------------------
    |
    | Patterns to detect potential XSS attempts
    |
    */
    'xss_patterns' => [
        '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
        '/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/mi',
        '/javascript:/i',
        '/on\w+\s*=/i',
        '/<object\b[^<]*(?:(?!<\/object>)<[^<]*)*<\/object>/mi',
        '/<embed\b[^<]*(?:(?!<\/embed>)<[^<]*)*<\/embed>/mi',
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security
    |--------------------------------------------------------------------------
    |
    | Configure session security settings
    |
    */
    'session' => [
        'timeout_minutes' => 120, // 2 hours
        'regenerate_on_login' => true,
        'invalidate_on_logout' => true,
        'secure_cookies' => env('SESSION_SECURE_COOKIES', false),
        'http_only_cookies' => true,
        'same_site_cookies' => 'strict',
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Security
    |--------------------------------------------------------------------------
    |
    | Configure password security requirements
    |
    */
    'passwords' => [
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => false,
        'max_age_days' => 90,
        'prevent_reuse_count' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Logging
    |--------------------------------------------------------------------------
    |
    | Configure security audit logging
    |
    */
    'audit' => [
        'enabled' => env('SECURITY_AUDIT_ENABLED', true),
        'log_failed_logins' => true,
        'log_admin_actions' => true,
        'log_file_uploads' => true,
        'log_suspicious_activity' => true,
        'retention_days' => 90,
    ],

    /*
    |--------------------------------------------------------------------------
    | IP Filtering
    |--------------------------------------------------------------------------
    |
    | Configure IP-based access control
    |
    */
    'ip_filtering' => [
        'enabled' => env('IP_FILTERING_ENABLED', false),
        'whitelist' => env('IP_WHITELIST', ''),
        'blacklist' => env('IP_BLACKLIST', ''),
        'admin_whitelist' => env('ADMIN_IP_WHITELIST', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTPS Enforcement
    |--------------------------------------------------------------------------
    |
    | Configure HTTPS enforcement settings
    |
    */
    'https' => [
        'enforce' => env('FORCE_HTTPS', false),
        'hsts_max_age' => 31536000, // 1 year
        'include_subdomains' => true,
        'preload' => false,
    ],
];
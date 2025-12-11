<?php return array (
  'app' => 
  array (
    'name' => 'SundayLearn',
    'env' => 'local',
    'debug' => true,
    'url' => 'https://sundaylearn.infinityfree.me',
    'asset_url' => NULL,
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'key' => 'base64:DZnpP/FFx9U17aA/E7qK80B1xfaNqAlY3fTJhRddhKg=',
    'cipher' => 'AES-256-CBC',
    'maintenance' => 
    array (
      'driver' => 'file',
    ),
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Cookie\\CookieServiceProvider',
      6 => 'Illuminate\\Database\\DatabaseServiceProvider',
      7 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      8 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      9 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      10 => 'Illuminate\\Hashing\\HashServiceProvider',
      11 => 'Illuminate\\Mail\\MailServiceProvider',
      12 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      13 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      14 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      15 => 'Illuminate\\Queue\\QueueServiceProvider',
      16 => 'Illuminate\\Redis\\RedisServiceProvider',
      17 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      18 => 'Illuminate\\Session\\SessionServiceProvider',
      19 => 'Illuminate\\Translation\\TranslationServiceProvider',
      20 => 'Illuminate\\Validation\\ValidationServiceProvider',
      21 => 'Illuminate\\View\\ViewServiceProvider',
      22 => 'App\\Providers\\AppServiceProvider',
      23 => 'App\\Providers\\RouteServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Arr' => 'Illuminate\\Support\\Arr',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Broadcast' => 'Illuminate\\Support\\Facades\\Broadcast',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'Date' => 'Illuminate\\Support\\Facades\\Date',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Http' => 'Illuminate\\Support\\Facades\\Http',
      'Js' => 'Illuminate\\Support\\Js',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Notification' => 'Illuminate\\Support\\Facades\\Notification',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'RateLimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'Str' => 'Illuminate\\Support\\Str',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
    ),
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
      'admin' => 
      array (
        'driver' => 'session',
        'provider' => 'admin_users',
      ),
      'sanctum' => 
      array (
        'driver' => 'sanctum',
        'provider' => NULL,
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\Models\\User',
      ),
      'admin_users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\Models\\AdminUser',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'table' => 'password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
      ),
      'admin_users' => 
      array (
        'provider' => 'admin_users',
        'table' => 'admin_password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
      ),
    ),
    'password_timeout' => 10800,
  ),
  'cache' => 
  array (
    'default' => 'file',
    'stores' => 
    array (
      'array' => 
      array (
        'driver' => 'array',
        'serialize' => false,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => 'C:\\xampp\\htdocs\\SundayLearn\\SundayLearn\\SundayLearn\\storage\\framework/cache/data',
      ),
    ),
    'prefix' => 'laravel_cache_',
  ),
  'database' => 
  array (
    'default' => 'mysql',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'url' => NULL,
        'database' => 'sundaylearn',
        'prefix' => '',
        'foreign_key_constraints' => true,
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'sundaylearn',
        'username' => 'root',
        'password' => '',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'sundaylearn',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'search_path' => 'public',
        'sslmode' => 'prefer',
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'sundaylearn',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'client' => 'phpredis',
      'options' => 
      array (
        'cluster' => 'redis',
        'prefix' => 'sundaylearn_database_',
      ),
      'default' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '0',
      ),
      'cache' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '1',
      ),
    ),
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\xampp\\htdocs\\SundayLearn\\SundayLearn\\SundayLearn\\storage\\app',
        'throw' => false,
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\xampp\\htdocs\\SundayLearn\\SundayLearn\\SundayLearn\\storage\\app/public',
        'url' => 'https://sundaylearn.infinityfree.me/storage',
        'visibility' => 'public',
        'throw' => false,
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => NULL,
        'secret' => NULL,
        'region' => NULL,
        'bucket' => NULL,
        'url' => NULL,
        'endpoint' => NULL,
        'use_path_style_endpoint' => false,
        'throw' => false,
      ),
    ),
    'links' => 
    array (
      'C:\\xampp\\htdocs\\SundayLearn\\SundayLearn\\SundayLearn\\public\\storage' => 'C:\\xampp\\htdocs\\SundayLearn\\SundayLearn\\SundayLearn\\storage\\app/public',
    ),
  ),
  'logging' => 
  array (
    'default' => 'stack',
    'deprecations' => NULL,
    'channels' => 
    array (
      'stack' => 
      array (
        'driver' => 'stack',
        'channels' => 
        array (
          0 => 'single',
        ),
        'ignore_exceptions' => false,
      ),
      'single' => 
      array (
        'driver' => 'single',
        'path' => 'C:\\xampp\\htdocs\\SundayLearn\\SundayLearn\\SundayLearn\\storage\\logs/laravel.log',
        'level' => 'error',
      ),
      'null' => 
      array (
        'driver' => 'monolog',
        'handler' => 'Monolog\\Handler\\NullHandler',
      ),
      'security' => 
      array (
        'driver' => 'single',
        'path' => 'C:\\xampp\\htdocs\\SundayLearn\\SundayLearn\\SundayLearn\\storage\\logs/security.log',
        'level' => 'warning',
        'days' => 90,
      ),
    ),
  ),
  'security' => 
  array (
    'csp' => 
    array (
      'default-src' => '\'self\'',
      'script-src' => '\'self\' \'unsafe-inline\' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com',
      'style-src' => '\'self\' \'unsafe-inline\' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com',
      'font-src' => '\'self\' https://fonts.gstatic.com https://cdnjs.cloudflare.com',
      'img-src' => '\'self\' data: https: http:',
      'connect-src' => '\'self\'',
      'frame-ancestors' => '\'none\'',
      'base-uri' => '\'self\'',
      'form-action' => '\'self\'',
    ),
    'rate_limits' => 
    array (
      'api' => 
      array (
        'requests' => 60,
        'per_minutes' => 1,
      ),
      'login' => 
      array (
        'requests' => 5,
        'per_minutes' => 15,
      ),
      'search' => 
      array (
        'requests' => 30,
        'per_minutes' => 1,
      ),
      'download' => 
      array (
        'requests' => 10,
        'per_minutes' => 1,
      ),
    ),
    'uploads' => 
    array (
      'max_size' => 10485760,
      'allowed_extensions' => 
      array (
        'images' => 
        array (
          0 => 'jpg',
          1 => 'jpeg',
          2 => 'png',
          3 => 'gif',
          4 => 'webp',
        ),
        'documents' => 
        array (
          0 => 'pdf',
          1 => 'doc',
          2 => 'docx',
          3 => 'ppt',
          4 => 'pptx',
          5 => 'txt',
        ),
        'audio' => 
        array (
          0 => 'mp3',
          1 => 'wav',
          2 => 'ogg',
        ),
        'video' => 
        array (
          0 => 'mp4',
          1 => 'webm',
          2 => 'ogg',
        ),
      ),
      'blocked_extensions' => 
      array (
        0 => 'php',
        1 => 'php3',
        2 => 'php4',
        3 => 'php5',
        4 => 'phtml',
        5 => 'exe',
        6 => 'bat',
        7 => 'cmd',
        8 => 'com',
        9 => 'pif',
        10 => 'scr',
        11 => 'vbs',
        12 => 'js',
        13 => 'jar',
        14 => 'sh',
        15 => 'asp',
        16 => 'aspx',
      ),
      'scan_for_malware' => false,
    ),
    'validation' => 
    array (
      'max_string_length' => 10000,
      'max_array_items' => 100,
      'allowed_html_tags' => '<p><br><strong><em><u><ol><ul><li><h1><h2><h3><h4><h5><h6>',
      'strip_dangerous_attributes' => true,
    ),
    'sql_injection_patterns' => 
    array (
      0 => '/(\\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|UNION|SCRIPT)\\b)/i',
      1 => '/(\\b(OR|AND)\\s+\\d+\\s*=\\s*\\d+)/i',
      2 => '/(\\b(OR|AND)\\s+[\'"]?\\w+[\'"]?\\s*=\\s*[\'"]?\\w+[\'"]?)/i',
      3 => '/(--|\\/\\*|\\*\\/|;)/i',
      4 => '/(\\bxp_\\w+)/i',
      5 => '/(\\bsp_\\w+)/i',
    ),
    'xss_patterns' => 
    array (
      0 => '/<script\\b[^<]*(?:(?!<\\/script>)<[^<]*)*<\\/script>/mi',
      1 => '/<iframe\\b[^<]*(?:(?!<\\/iframe>)<[^<]*)*<\\/iframe>/mi',
      2 => '/javascript:/i',
      3 => '/on\\w+\\s*=/i',
      4 => '/<object\\b[^<]*(?:(?!<\\/object>)<[^<]*)*<\\/object>/mi',
      5 => '/<embed\\b[^<]*(?:(?!<\\/embed>)<[^<]*)*<\\/embed>/mi',
    ),
    'session' => 
    array (
      'timeout_minutes' => 120,
      'regenerate_on_login' => true,
      'invalidate_on_logout' => true,
      'secure_cookies' => false,
      'http_only_cookies' => true,
      'same_site_cookies' => 'strict',
    ),
    'passwords' => 
    array (
      'min_length' => 8,
      'require_uppercase' => true,
      'require_lowercase' => true,
      'require_numbers' => true,
      'require_symbols' => false,
      'max_age_days' => 90,
      'prevent_reuse_count' => 5,
    ),
    'audit' => 
    array (
      'enabled' => true,
      'log_failed_logins' => true,
      'log_admin_actions' => true,
      'log_file_uploads' => true,
      'log_suspicious_activity' => true,
      'retention_days' => 90,
    ),
    'ip_filtering' => 
    array (
      'enabled' => false,
      'whitelist' => '',
      'blacklist' => '',
      'admin_whitelist' => '',
    ),
    'https' => 
    array (
      'enforce' => false,
      'hsts_max_age' => 31536000,
      'include_subdomains' => true,
      'preload' => false,
    ),
  ),
  'services' => 
  array (
    'mailgun' => 
    array (
      'domain' => NULL,
      'secret' => NULL,
      'endpoint' => 'api.mailgun.net',
      'scheme' => 'https',
    ),
    'postmark' => 
    array (
      'token' => NULL,
    ),
    'ses' => 
    array (
      'key' => NULL,
      'secret' => NULL,
      'region' => 'us-east-1',
    ),
    'supabase' => 
    array (
      'url' => 'https://qlxnyjtochgllymdgzkq.supabase.co',
      'anon_key' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InFseG55anRvY2hnbGx5bWRnemtxIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjUyMjU0NzYsImV4cCI6MjA4MDgwMTQ3Nn0.OwLJPiebxYYSxmG1mTPtKpzpUtOSkVk7YLzzssb0f1c',
      'service_key' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InFseG55anRvY2hnbGx5bWRnemtxIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc2NTIyNTQ3NiwiZXhwIjoyMDgwODAxNDc2fQ.rKycWZX40Psh8XQLsQs-BNh8QCjTnFmQYVKxEqYcooI',
      'bucket' => 'lessons-images',
    ),
    'telegram' => 
    array (
      'bot_token' => '8438858687:AAEe7ep6yRRpvWEjnQAqUGskzldSpM_rO3w',
      'webhook_url' => 'https://sundaylearn.infinityfree.me/api/telegram/webhook',
      'channel_id' => '@friendsinthechildrenministry',
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => '120',
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => 'C:\\xampp\\htdocs\\SundayLearn\\SundayLearn\\SundayLearn\\storage\\framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'laravel_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => NULL,
    'http_only' => true,
    'same_site' => 'lax',
    'partitioned' => false,
  ),
  'supabase' => 
  array (
    'url' => 'https://qlxnyjtochgllymdgzkq.supabase.co',
    'anon_key' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InFseG55anRvY2hnbGx5bWRnemtxIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjUyMjU0NzYsImV4cCI6MjA4MDgwMTQ3Nn0.OwLJPiebxYYSxmG1mTPtKpzpUtOSkVk7YLzzssb0f1c',
    'service_key' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InFseG55anRvY2hnbGx5bWRnemtxIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc2NTIyNTQ3NiwiZXhwIjoyMDgwODAxNDc2fQ.rKycWZX40Psh8XQLsQs-BNh8QCjTnFmQYVKxEqYcooI',
    'buckets' => 
    array (
      'lessons_images' => 'lessons-images',
      'lessons_attachments' => 'lessons-attachments',
      'blog_images' => 'blog-images',
      'telegram_media' => 'telegram-media',
    ),
    'limits' => 
    array (
      'image_max_size' => 5242880,
      'attachment_max_size' => 52428800,
      'total_storage_limit' => 1073741824,
    ),
    'allowed_types' => 
    array (
      'images' => 
      array (
        0 => 'jpg',
        1 => 'jpeg',
        2 => 'png',
        3 => 'gif',
        4 => 'webp',
      ),
      'documents' => 
      array (
        0 => 'pdf',
        1 => 'doc',
        2 => 'docx',
        3 => 'ppt',
        4 => 'pptx',
      ),
      'audio' => 
      array (
        0 => 'mp3',
        1 => 'wav',
        2 => 'ogg',
      ),
      'video' => 
      array (
        0 => 'mp4',
        1 => 'avi',
        2 => 'mov',
        3 => 'wmv',
      ),
    ),
  ),
  'telegram' => 
  array (
    'bot_token' => '8438858687:AAEe7ep6yRRpvWEjnQAqUGskzldSpM_rO3w',
    'webhook_secret' => NULL,
    'channel_id' => '@friendsinthechildrenministry',
    'storage' => 
    array (
      'disk' => 'public',
      'path' => 'telegram',
    ),
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => 'C:\\xampp\\htdocs\\SundayLearn\\SundayLearn\\SundayLearn\\resources\\views',
    ),
    'compiled' => 'C:\\xampp\\htdocs\\SundayLearn\\SundayLearn\\SundayLearn\\storage\\framework\\views',
  ),
  'sanctum' => 
  array (
    'stateful' => 
    array (
      0 => 'localhost',
      1 => 'localhost:3000',
      2 => '127.0.0.1',
      3 => '127.0.0.1:8000',
      4 => '::1',
      5 => 'sundaylearn.infinityfree.me',
    ),
    'guard' => 
    array (
      0 => 'web',
    ),
    'expiration' => NULL,
    'token_prefix' => '',
    'middleware' => 
    array (
      'authenticate_session' => 'Laravel\\Sanctum\\Http\\Middleware\\AuthenticateSession',
      'encrypt_cookies' => 'App\\Http\\Middleware\\EncryptCookies',
      'verify_csrf_token' => 'App\\Http\\Middleware\\VerifyCsrfToken',
    ),
  ),
  'tinker' => 
  array (
    'commands' => 
    array (
    ),
    'alias' => 
    array (
    ),
    'dont_alias' => 
    array (
      0 => 'App\\Nova',
    ),
  ),
);

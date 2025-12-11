<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Supabase Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Supabase database and storage integration
    |
    */

    'url' => env('SUPABASE_URL'),
    'anon_key' => env('SUPABASE_ANON_KEY'),
    'service_key' => env('SUPABASE_SERVICE_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Storage Buckets
    |--------------------------------------------------------------------------
    */

    'buckets' => [
        'lessons_images' => 'lessons-images',
        'lessons_attachments' => 'lessons-attachments',
        'blog_images' => 'blog-images',
        'telegram_media' => 'telegram-media',
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage Limits (in bytes)
    |--------------------------------------------------------------------------
    */

    'limits' => [
        'image_max_size' => 5 * 1024 * 1024, // 5MB
        'attachment_max_size' => 50 * 1024 * 1024, // 50MB
        'total_storage_limit' => 1024 * 1024 * 1024, // 1GB (free tier)
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed File Types
    |--------------------------------------------------------------------------
    */

    'allowed_types' => [
        'images' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'documents' => ['pdf', 'doc', 'docx', 'ppt', 'pptx'],
        'audio' => ['mp3', 'wav', 'ogg'],
        'video' => ['mp4', 'avi', 'mov', 'wmv'],
    ],
];

<?php

return [
    /*
    |--------------------------------------------------------------------------
    | File Upload Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for file uploads in the application.
    | These settings override PHP's default upload limits.
    |
    */

    'max_file_size' => env('UPLOAD_MAX_FILE_SIZE', '100M'),
    'max_post_size' => env('UPLOAD_MAX_POST_SIZE', '100M'),
    'max_execution_time' => env('UPLOAD_MAX_EXECUTION_TIME', 300),
    'max_input_time' => env('UPLOAD_MAX_INPUT_TIME', 300),
    'memory_limit' => env('UPLOAD_MEMORY_LIMIT', '256M'),
    'max_file_uploads' => env('UPLOAD_MAX_FILE_UPLOADS', 20),

    /*
    |--------------------------------------------------------------------------
    | File Type Limits
    |--------------------------------------------------------------------------
    |
    | Specific limits for different file types (in MB)
    |
    */

    'limits' => [
        'video' => 100,
        'audio' => 100,
        'document' => 100,
        'image' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed File Types
    |--------------------------------------------------------------------------
    |
    | Define allowed file extensions for each category
    |
    */

    'allowed_types' => [
        'video' => ['mp4', 'avi', 'mov', 'wmv', 'webm', 'mkv', 'flv'],
        'audio' => ['mp3', 'wav', 'ogg', 'm4a', 'aac', 'flac', 'wma'],
        'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'rar'],
        'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
    ],
];
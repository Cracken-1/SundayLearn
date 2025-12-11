<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Telegram Bot Token
    |--------------------------------------------------------------------------
    |
    | The bot token provided by @BotFather when you create your bot.
    |
    */
    'bot_token' => env('TELEGRAM_BOT_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Webhook Secret Token
    |--------------------------------------------------------------------------
    |
    | A secret token to validate webhook requests from Telegram.
    |
    */
    'webhook_secret' => env('TELEGRAM_WEBHOOK_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Channel ID
    |--------------------------------------------------------------------------
    |
    | The ID of the Telegram channel where lesson content will be posted.
    |
    */
    'channel_id' => env('TELEGRAM_CHANNEL_ID'),

    /*
    |--------------------------------------------------------------------------
    | File Storage
    |--------------------------------------------------------------------------
    |
    | Configuration for storing downloaded Telegram files.
    |
    */
    'storage' => [
        'disk' => env('TELEGRAM_STORAGE_DISK', 'public'),
        'path' => env('TELEGRAM_STORAGE_PATH', 'telegram'),
    ],
];
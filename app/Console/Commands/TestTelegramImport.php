<?php

namespace App\Console\Commands;

use App\Jobs\ProcessTelegramUpdate;
use Illuminate\Console\Command;

class TestTelegramImport extends Command
{
    protected $signature = 'telegram:test-import {--caption=}';
    protected $description = 'Test Telegram import processing with sample data';

    public function handle(): int
    {
        $caption = $this->option('caption') ?: "Topic: Bible Stories\nAge: 5-7\nType: Video\nTitle: David and Goliath\nDescription: Learn about courage and faith through this amazing story.";

        $sampleUpdate = [
            'update_id' => rand(1000, 9999),
            'channel_post' => [
                'message_id' => rand(100, 999),
                'chat' => [
                    'id' => '@friendsinthechildrenministry',
                    'type' => 'channel'
                ],
                'date' => time(),
                'caption' => $caption,
                'photo' => [
                    [
                        'file_id' => 'sample_file_id_' . rand(1000, 9999),
                        'file_unique_id' => 'unique_' . rand(1000, 9999),
                        'width' => 1280,
                        'height' => 720,
                        'file_size' => 150000
                    ]
                ]
            ]
        ];

        $this->info('Processing test Telegram import...');
        $this->info("Caption: {$caption}");

        ProcessTelegramUpdate::dispatch($sampleUpdate);

        $this->info('Test import dispatched successfully!');
        $this->info('Check the admin panel at: http://localhost:8000/admin/telegram-imports');

        return 0;
    }
}